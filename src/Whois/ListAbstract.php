<?php
declare(strict_types=1);

namespace Chanshige\Whois;

/**
 * Class ListAbstract
 *
 * @package Chanshige\Whois
 */
abstract class ListAbstract
{
    /**
     * @var array
     */
    protected static $lists = [];

    /**
     * Has key.
     *
     * @param string $key
     * @return bool
     */
    final public static function has($key): bool
    {
        return isset(static::$lists[$key]);
    }

    /**
     * Exist value.
     *
     * @param int|string $value
     * @return bool
     */
    final public static function exists($value): bool
    {
        return in_array($value, static::$lists, true);
    }

    /**
     * Get one.
     *
     * @param int|string $key
     * @return string
     */
    final public static function get($key): string
    {
        // Null Coalescing Operator
        return static::$lists[$key] ?? '';
    }

    /**
     * Get all.
     *
     * @return array
     */
    final public static function getAll(): array
    {
        return static::$lists;
    }
}
