<?php
namespace Handler;

use Chanshige\Exception\SocketExecutionException;

/**
 * Class Socket
 *
 * @package Handler
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

    private const OPEN_ERROR = 10;
    private const PUTS_ERROR = 11;
    private const READ_ERROR = 12;

    /** @var array $errorCodes */
    private static $errorCodes = [
        Socket::OPEN_ERROR => 'Failed to open socket connection.',
        Socket::PUTS_ERROR => 'Write to socket failed.',
        Socket::READ_ERROR => 'Read from socket failed.'
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
            throw new SocketExecutionException(self::$errorCodes[Socket::OPEN_ERROR], Socket::OPEN_ERROR);
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
            throw new SocketExecutionException(self::$errorCodes[Socket::PUTS_ERROR], Socket::PUTS_ERROR);
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
            if (!$buffer) {
                throw new SocketExecutionException(self::$errorCodes[Socket::READ_ERROR], Socket::READ_ERROR);
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
        return fclose($this->resource);
    }
}
