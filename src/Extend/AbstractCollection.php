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

use Chanshige\Contracts\CollectionInterface;

abstract class AbstractCollection implements CollectionInterface
{
    /** @var array<string|int, mixed> */
    protected static array $data = [];

    /**
     * 指定されたキーに対応する値を取得する
     *
     * @param string|int $key キー
     * @param mixed $default 見つからない場合のデフォルト値
     * @return mixed 対応する値
     */
    final public static function get(string|int $key, mixed $default = ''): mixed
    {
        return static::$data[$key] ?? $default;
    }

    final public static function has(string|int $key): bool
    {
        return isset(static::$data[$key]);
    }

    /** @return array<string|int, mixed> */
    final public static function all(): array
    {
        return static::$data;
    }

    final public static function count(): int
    {
        return count(static::$data);
    }
}
