<?php

namespace app\core\db\subscription;

use app\core\db\ActiveRecord;
use app\core\helpers\{
    ClassFinder,
    ReflectionHelper
};
use ReflectionClass;
use ReflectionMethod;
use yii\base\Event;

/**
 * Loads entity subscribers from `app\subscribers`.
 */
class SubscriberLoader implements ISubscriberLoader
{
    /**
     * {@inheritdoc}
     */
    public function loadAll(): void
    {
        $subscriberClasses = ClassFinder::getClassesInNamespace('app\subscribers');
        $subscriberClasses = array_filter($subscriberClasses, fn (string $class) => ReflectionHelper::hasClassAttribute(Subscriber::class, $class));

        $this->load($subscriberClasses);
    }

    /**
     * {@inheritdoc}
     */
    public function load(string|array $subscribers): void
    {
        if (is_string($subscribers)) $subscribers = [$subscribers];

        foreach ($subscribers as $subscriberClass) {
            $this->registerSubscriber($subscriberClass);
        }
    }

    /**
     * Registers subcriber events.
     * 
     * @param string $subscriberClass Subscriber class.
     */
    private function registerSubscriber(string $subscriberClass): void
    {
        $subscriber = new ReflectionClass($subscriberClass);
        $entity = ReflectionHelper::getClassAttribute(Subscriber::class, $subscriber)->newInstance()->entity;
        $publicMethods = $subscriber->getMethods(ReflectionMethod::IS_PUBLIC);

        foreach ($publicMethods as $method) {
            $this->registerMethod($subscriber, $method, $entity);
        }
    }

    /**
     * Registers events of a subscriber method.
     * 
     * @param ReflectionClass $subscriber Subscriber reflection.
     * @param ReflectionMethod $method Method reflection.
     * @param string $entity Subscriber entity class.
     */
    private function registerMethod(ReflectionClass $subscriber, ReflectionMethod $method, string $entity): void
    {
        $events = $this->getMethodEvents($method);

        foreach ($events as $eventName) {
            Event::on($entity, $eventName, fn () => $method->invoke($subscriber->newInstance()));
        }
    }

    /**
     * Returns the list of the entity events to be handled by the method.
     * 
     * @param ReflectionMethod $method Method reflection.
     * 
     * @return string[] Event names.
     */
    private function getMethodEvents(ReflectionMethod $method): array
    {
        $events = [];

        if (ReflectionHelper::hasMethodAttribute(AfterInsert::class, $method)) {
            $events[] = ActiveRecord::EVENT_AFTER_INSERT;
        }

        return $events;
    }
}
