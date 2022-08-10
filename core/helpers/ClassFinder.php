<?php

namespace app\core\helpers;

/**
 * Finds classes by their namespace.
 */
final class ClassFinder
{
    /**
     * Returns the names of all classes in the given directory.
     * 
     * @param string $relativePath Directory relative path.
     * 
     * @return string[] All classes found.
     */
    public static function getClassesInDirectory(string $relativePath): array
    {
        $files = scandir(FileManager::rootDirectory() . $relativePath);
        $classes = array_map(fn ($file) => 'app' . '\\' . $relativePath . '\\' . str_replace('.php', '', $file), $files);

        return array_filter($classes, fn ($possibleClass) => class_exists($possibleClass));
    }
}
