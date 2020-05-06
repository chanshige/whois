<?php
/*
 * This file is part of the Chanshige\Whois package.
 *
 * (c) shigeki tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Chanshige\Handler;

use Generator;

/**
 * Interface SocketInterface
 *
 * @package Chanshige\Handler
 */
interface SocketInterface
{
    public const ERROR_OPEN = 10;
    public const ERROR_PUTS = 11;
    public const ERROR_REQUEST = 12;

    /**
     * SocketInterface constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = []);

    /**
     * Invoke socket connection and write.
     *
     * @param string $host
     * @param string $value
     * @return SocketInterface
     */
    public function __invoke(string $host, string $value);

    /**
     * Open Internet or Unix domain socket connection.
     *
     * @param string $host
     * @return SocketInterface
     */
    public function open(string $host);

    /**
     * Binary-safe file write.
     *
     * @param string $value
     * @return SocketInterface
     */
    public function puts(string $value);

    /**
     * Gets line from file pointer.
     *
     * @return Generator
     */
    public function read(): Generator;

    /**
     * Closes an open file pointer.
     *
     * @return bool
     */
    public function close(): bool;
}
