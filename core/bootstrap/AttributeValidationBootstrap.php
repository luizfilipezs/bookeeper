<?php

namespace app\core\bootstrap;

use app\core\validation\IValidator;
use ReflectionAttribute;
use ReflectionClass;
use yii\base\BootstrapInterface;
use yii\base\Event;
use yii\base\Model;
use yii\helpers\StringHelper;

class AttributeValidationBootstrap implements BootstrapInterface
{
    /**
     * {@inheritdoc}
     */
    public function bootstrap($app): void
    {
        Event::on(Model::class, Model::EVENT_BEFORE_VALIDATE, function (Event $event) {
            $this->runAttributeValidation($event->sender);
        });
    }

    private function runAttributeValidation(Model $model): void
    {
        $reflectionClass = new ReflectionClass($model::class);
        $attributeNames = array_map(fn (ReflectionAttribute $attr) => StringHelper::basename($attr->getName()), $reflectionClass->getAttributes());
        $enableValidation = in_array(StringHelper::basename(EnableValidation::class), $attributeNames);

        if ($enableValidation) {
            $this->validateReflectedModel($model, $reflectionClass);
        }
    }

    private function validateReflectedModel(Model $model, ReflectionClass $reflectionClass): void
    {
        foreach ($reflectionClass->getProperties() as $property) {
            $propertyName = $property->getName();
            $attributes = $property->getAttributes();

            foreach ($attributes as $attribute) {
                $validator = $attribute->newInstance();

                if ($validator instanceof IValidator) {
                    $validator->validateObject($model, $propertyName);
                }
            }
        }
    }
}
