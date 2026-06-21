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

namespace Chanshige\Collection;

/**
 * Class AbstractCollection
 *
 * @package Chanshige\Collection
 */
abstract class AbstractCollection
{
    protected static array $data = [];

    /** @var array<class-string, array<int|string, true>> */
    private static array $valueMaps = [];

    /**
     * Get value.
     *
     * @param string|int   $key
     * @param string|array $default
     * @return string|array
     */
    final public static function get(string|int $key, string|array $default = ''): string|array
    {
        return static::$data[$key] ?? $default;
    }

    /**
     * Has key.
     *
     * @param string|int $key
     * @return bool
     */
    final public static function hasKey(string|int $key): bool
    {
        return isset(static::$data[$key]);
    }

    /**
     * Exists value.
     *
     * @param string|int $value
     * @return bool
     */
    final public static function existsValue(string|int $value): bool
    {
        return isset(self::valueMap()[$value]);
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

    /**
     * @return array<int|string, true>
     */
    private static function valueMap(): array
    {
        $class = static::class;
        if (isset(self::$valueMaps[$class])) {
            return self::$valueMaps[$class];
        }

        $map = [];
        foreach (static::$data as $value) {
            if (is_int($value) || is_string($value)) {
                $map[$value] = true;
            }
        }

        return self::$valueMaps[$class] = $map;
    }
}
