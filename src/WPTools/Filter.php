<?php

namespace TwinDigital\WPTools;

/**
 * Class Filter
 */
class Filter
{

    /**
     * Remove active filter from $wp_filter
     *
     * @param string $hook   Hook on which the original filter was applied.
     * @param string $class  Classname.
     * @param string $method Method.
     *
     * @SuppressWarnings(PHPMD.Superglobals) Don't worry, I know what I'm doing.
     * @return bool
     */
    public static function remove(string $hook, string $class, string $method): bool
    {
        if (array_key_exists($hook, $GLOBALS['wp_filter']) === true) {
            $filters = $GLOBALS['wp_filter'][$hook];
        } else {
            return false;
        }
        $return = false;
        foreach ($filters as $priority => $filter) {
            foreach ($filter as $function) {
                if (self::isFilterFunctionClass($function, $class, $method) === true) {
                    $return = remove_filter(
                        $hook,
                        [
                            $function['function'][0],
                            $method,
                        ],
                        $priority
                    );
                }
            }
        }

        return $return;
    }

    /**
     * Checks whether a filter contains the supplied Class and Function
     *
     * @param array  $function The original filter from $wp_filter.
     * @param string $class    The class in which the method should be called.
     * @param string $method   The method to check for.
     *
     * @return bool
     */
    private static function isFilterFunctionClass(array $function, string $class, string $method): bool
    {
        if (
            is_array($function) === true
            && $function['function'][0] === $class
            && $method === $function['function'][1]
        ) {
            return true;
        }

        return false;
    }
}
