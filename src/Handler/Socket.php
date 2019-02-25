<?php
declare(strict_types=1);

namespace Chanshige\Handler;

use Chanshige\Exception\SocketExecutionException;

/**
 * Class Socket
 *
 * @package Chanshige\Handler
 */
final class Socket implements SocketInterface
{
    /** @var resource */
    private $resource;

    /** @var int $port */
    private $port = 43;

    /** @var int $timeout sec */
    private $timeout = 3;

    /** @var int $errno */
    private $errno;

    /** @var string $errStr error message */
    private $errStr;

    private const ERROR_OPEN = 400;
    private const ERROR_PUTS = 405;
    private const ERROR_READ = 403;

    /** @var array $errCodes */
    private static $errCodes = [
        Socket::ERROR_OPEN => 'Failed to open socket connection.',
        Socket::ERROR_PUTS => 'Write to socket failed.',
        Socket::ERROR_READ => 'Read from socket failed.'
    ];

    /**
     * {@inheritdoc}
     */
    public function setPort(int $portNo): void
    {
        $this->port = $portNo;
    }

    /**
     * {@inheritdoc}
     */
    public function setTimeout(int $seconds): void
    {
        $this->timeout = $seconds;
    }

    /**
     * {@inheritdoc}
     * @throws SocketExecutionException
     */
    public function open(string $host): Socket
    {
        $resource = @fsockopen($host, $this->port, $this->errno, $this->errStr, $this->timeout);
        if (!$resource) {
            throw new SocketExecutionException(self::$errCodes[Socket::ERROR_OPEN], Socket::ERROR_OPEN);
        }
        $socket = clone $this;
        $socket->resource = $resource;

        return $socket;
    }

    /**
     * {@inheritdoc}
     * @throws SocketExecutionException
     */
    public function puts(string $value): Socket
    {
        $result = @fwrite($this->resource, $value . PHP_EOL);
        if ($result === false) {
            throw new SocketExecutionException(self::$errCodes[Socket::ERROR_PUTS], Socket::ERROR_PUTS);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws SocketExecutionException
     */
    public function read(): array
    {
        $data = [];
        while (!feof($this->resource)) {
            $data[] = trim(fgets($this->resource));
        }

        return $data;
    }

    /**
     * {@inheritdoc}
     */
    public function close(): bool
    {
        if (!is_resource($this->resource)) {
            return true;
        }

        return fclose($this->resource);
    }
}
