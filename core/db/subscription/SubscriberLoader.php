<?php

namespace app\core\db\subscription;

use app\core\helpers\{
    ClassFinder,
    ReflectionHelper
};
use ReflectionClass;
use ReflectionMethod;
use Yii;
use yii\base\Event;
use yii\helpers\StringHelper;

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
        $subscriberClasses = ClassFinder::getClassesInDirectory('subscribers');
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
        $entity = $this->getSubscriberEntity($subscriber);
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
            Event::on($entity, $eventName, fn (Event $event) => $method->invoke(
                Yii::createObject($subscriber->getName()),
                $event->sender
            ));
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
        $eventNames = [];
        $attributes = $method->getAttributes();

        foreach ($attributes as $attribute) {
            $attributeInstance = $attribute->newInstance();

            if ($eventName = $attributeInstance->eventName ?? false) {
                $eventNames[] = $eventName;
            }
        }

        return $eventNames;
    }

    /**
     * Returns the entity class the subscriber is subscribed to.
     * 
     * @param ReflectionClass $subscriber Subscriber reflection.
     * 
     * @return string Entity class name.
     */
    private function getSubscriberEntity(ReflectionClass $subscriber): string
    {
        $subscriberAttribute = ReflectionHelper::getClassAttribute(Subscriber::class, $subscriber)->newInstance();
        
        if ($subscriberAttribute->entity !== null) {
            return $subscriberAttribute->entity;
        }

        $subscriberName = StringHelper::basename($subscriber->getName());
        $entityName = str_replace('Subscriber', '', $subscriberName);
        
        return 'app\entities\\' . $entityName;
    }
}
