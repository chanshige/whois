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
