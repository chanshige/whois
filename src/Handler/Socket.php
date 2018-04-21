<?php
namespace Handler;

use Exception\SocketExecutionException;

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

    /** @var array $errorCodes */
    private static $errorCodes = [
        10 => 'Failed to open socket connection.',
        11 => 'Write to socket failed.',
        12 => 'Read from socket failed.'
    ];

    /**
     * port
     *
     * @param int $portNo
     * @return SocketInterface
     */
    public function port(int $portNo): SocketInterface
    {
        $this->port = $portNo;

        return $this;
    }

    /**
     * timeout
     *
     * @param int $seconds
     * @return SocketInterface
     */
    public function timeout(int $seconds): SocketInterface
    {
        $this->timeout = $seconds;

        return $this;
    }

    /**
     * Open Internet or Unix domain socket connection.
     *
     * @param string $host
     * @return SocketInterface
     * @throws SocketExecutionException
     */
    public function open(string $host): SocketInterface
    {
        $resource = @fsockopen($host, $this->port, $errNo, $errMsg, $this->timeout);
        if (!$resource) {
            throw new SocketExecutionException("[{$errMsg}] " . self::$errorCodes[10], $errNo);
        }
        $this->resource = $resource;

        return $this;
    }

    /**
     * fwrite.
     *
     * @param string $value
     * @return SocketInterface
     * @throws SocketExecutionException
     */
    public function puts(string $value): SocketInterface
    {
        $res = @fputs($this->resource, "{$value}\r\n");
        if (!$res) {
            throw new SocketExecutionException(self::$errorCodes[11]);
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
        $data = array();
        while (!feof($this->resource)) {
            $data[] = trim($buffer = fgets($this->resource));
            if ($buffer === false) {
                throw new SocketExecutionException(self::$errorCodes[12]);
            }
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
