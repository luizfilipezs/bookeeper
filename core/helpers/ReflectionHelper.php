<?php

namespace app\core\helpers;

use ReflectionAttribute;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * Reflection utility to make it easier to handle attributes.
 */
final class ReflectionHelper
{
    /**
     * Checks wether a class has the specified attribute.
     * 
     * @param string $attribute Attribute class.
     * @param ReflectionClass|string Class name or reflection.
     * 
     * @return bool Validation result.
     */
    public static function hasClassAttribute(string $attribute, ReflectionClass|string $class): bool
    {
        $class = self::createReflectionClass($class);
        
        return self::hasAttribute($class, $attribute);
    }

    /**
     * Checks wether a method has the specified attribute.
     * 
     * @param string $attribute Attribute class.
     * @param ReflectionClass Method reflection.
     * 
     * @return bool Validation result.
     */
    public static function hasMethodAttribute(string $attribute, ReflectionMethod $method): bool
    {
        return self::hasAttribute($method, $attribute);
    }

    /**
     * Returns the specified attribute from the given class.
     * 
     * @param string $attribute Attribute class.
     * @param ReflectionClass|string Class name or reflection.
     * 
     * @return ReflectionAttribute|null Attribute or `null` if not found.
     */
    public static function getClassAttribute(string $attribute, ReflectionClass|string $class): ?ReflectionAttribute
    {
        $class = self::createReflectionClass($class);

        return self::getAttribute($class, $attribute);
    }

    /**
     * Returns the specified attribute from the given method.
     * 
     * @param string $attribute Attribute class.
     * @param ReflectionClass Method reflection.
     * 
     * @return ReflectionAttribute|null Attribute or `null` if not found.
     */
    public static function getMethodAttribute(string $attribute, ReflectionMethod $method): ?ReflectionAttribute
    {
        return self::getAttribute($method, $attribute);
    }

    /**
     * Returns a specific attribute from a reflection.
     * 
     * @param ReflectionClass|ReflectionMethod $reflection Reflection class or method.
     * @param string $attribute Attribute class.
     * 
     * @return ReflectionAttribute|null Attribute or `null` if not found.
     */
    private static function getAttribute(ReflectionClass|ReflectionMethod $reflection, string $attribute): ?ReflectionAttribute
    {
        return $reflection->getAttributes($attribute)[0] ?? null;
    }

    /**
     * Checks whether a reflection has an specific attribute.
     * 
     * @param ReflectionClass|ReflectionMethod $reflection Reflection class or method.
     * @param string $attribute Attribute class.
     * 
     * @return bool Validation result.
     */
    private static function hasAttribute(ReflectionClass|ReflectionMethod $reflection, string $attribute): bool
    {
        return self::getAttribute($reflection, $attribute) !== null;
    }
    
    /**
     * Returns an instance of `ReflectionClass` from the given value.
     * 
     * @param ReflectionClass|string $class Class to be reflected. If the value is already
     * an instance of `ReflectionClass`, it will be returned.
     * 
     * @return ReflectionClass New reflection instance.
     * 
     * @throws ReflectionException If the class does not exist.
     */
    private static function createReflectionClass(ReflectionClass|string $class): ReflectionClass
    {
        return $class instanceof ReflectionClass ? $class : new ReflectionClass($class);
    }
}
