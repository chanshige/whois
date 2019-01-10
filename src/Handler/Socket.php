<?php
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
     * Set port number.
     *
     * @param int $portNo
     * @return void
     */
    public function setPort(int $portNo)
    {
        $this->port = $portNo;
    }

    /**
     * Set timeout.
     *
     * @param int $seconds
     * @return void
     */
    public function setTimeout(int $seconds)
    {
        $this->timeout = $seconds;
    }

    /**
     * Open Internet or Unix domain socket connection.
     *
     * @param string $host
     * @return Socket
     * @throws SocketExecutionException
     */
    public function open(string $host): Socket
    {
        $resource = @fsockopen($host, $this->port, $this->errno, $this->errStr, $this->timeout);
        if (!$resource) {
            throw new SocketExecutionException(self::$errCodes[Socket::ERROR_OPEN], Socket::ERROR_OPEN);
        }
        $this->resource = $resource;

        return $this;
    }

    /**
     * Binary-safe file write.
     *
     * @param string $value
     * @return Socket
     * @throws SocketExecutionException
     */
    public function puts(string $value): Socket
    {
        $res = @fputs($this->resource, "{$value}\r\n");
        if (!$res) {
            throw new SocketExecutionException(self::$errCodes[Socket::ERROR_PUTS], Socket::ERROR_PUTS);
        }

        return $this;
    }

    /**
     * Gets line from file pointer.
     *
     * @return array
     * @throws SocketExecutionException
     */
    public function read(): array
    {
        $data = [];
        while (!feof($this->resource)) {
            $buffer = fgets($this->resource);
            if ($buffer === false) {
                throw new SocketExecutionException(self::$errCodes[Socket::ERROR_READ], Socket::ERROR_READ);
            }
            $data[] = trim($buffer);
        }

        return $data;
    }

    /**
     * Closes an open file pointer.
     *
     * @return bool
     */
    public function close(): bool
    {
        if (!is_resource($this->resource)) {
            return false;
        }

        return fclose($this->resource);
    }
}
