<?php
/*
 * This file is part of the Chanshige\Whois package.
 *
 * (c) shigeki tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Chanshige\Foundation\Collection;

/**
 * Class ArrayList
 *
 * @package Chanshige\Foundation\Collection
 */
abstract class ArrayList
{
    /** @var array */
    protected static $data = [];

    /**
     * Get value.
     *
     * @param mixed $key
     * @param mixed $default
     * @return mixed
     */
    final public static function get($key, $default = '')
    {
        return static::$data[$key] ?? $default;
    }

    /**
     * Has key.
     *
     * @param mixed $key
     * @return bool
     */
    final public static function has($key): bool
    {
        return isset(static::$data[$key]);
    }

    /**
     * Exists value.
     *
     * @param mixed $value
     * @return bool
     */
    final public static function exists($value): bool
    {
        return in_array($value, static::$data, true);
    }

    /**
     * Get all values.
     *
     * @return array
     */
    final public static function all(): array
    {
        return static::$data;
    }
}
