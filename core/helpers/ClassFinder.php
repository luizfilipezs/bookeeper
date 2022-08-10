<?php

namespace app\core\helpers;

/**
 * Finds classes by their namespace.
 */
final class ClassFinder
{
    const APP_ROOT = __DIR__ . '/../../../';

    /**
     * Returns the names of all classes in the given namespace.
     * 
     * @param string $namespace The namespace.
     * 
     * @return string[] All classes found.
     */
    public static function getClassesInNamespace(string $namespace): array
    {
        $files = scandir(self::getNamespaceDirectory($namespace));
        $classes = array_map(fn ($file) => $namespace . '\\' . str_replace('.php', '', $file), $files);

        return array_filter($classes, fn ($possibleClass) => class_exists($possibleClass));
    }

    /**
     * Returns all namespaces defined in the `psr-4` section of the application
     * `composer.json` file.
     * 
     * @return string[] Namespaces defined in `composer.json`.
     */
    private static function getDefinedNamespaces(): array
    {
        $composerJsonPath = self::APP_ROOT . 'composer.json';
        $composerConfig = json_decode(file_get_contents($composerJsonPath));

        return (array) $composerConfig->autoload->{'psr-4'};
    }

    /**
     * Returns the directory of the given namespace.
     * 
     * @param string The namespace.
     * 
     * @return string|false Namespace directory or `false` if not found.
     */
    private static function getNamespaceDirectory(string $namespace): string|false
    {
        $composerNamespaces = self::getDefinedNamespaces();
        $namespaceFragments = explode('\\', $namespace);
        $undefinedNamespaceFragments = [];

        while ($namespaceFragments) {
            $possibleNamespace = implode('\\', $namespaceFragments) . '\\';

            if (array_key_exists($possibleNamespace, $composerNamespaces)) {
                return realpath(self::APP_ROOT . $composerNamespaces[$possibleNamespace] . implode('/', $undefinedNamespaceFragments));
            }

            array_unshift($undefinedNamespaceFragments, array_pop($namespaceFragments));
        }

        return false;
    }
}
