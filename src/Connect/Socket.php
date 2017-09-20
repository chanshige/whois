<?php
namespace Connect;

use Exception\SocketExecutionException;

/**
 * Class Socket
 *
 * @package Connect
 */
final class Socket implements SocketInterface
{
    /** @var resource */
    private $resource;

    /** @var int $port */
    private $port = 43;
    /** @var int $timeout sec */
    private $timeout = 3;

    /**
     * port(set).
     *
     * @param int $portNo
     * @return object
     */
    public function port($portNo)
    {
        $this->port = $portNo;

        return $this;
    }

    /**
     * timeout(set).
     *
     * @param int $seconds
     * @return object
     */
    public function timeout($seconds)
    {
        $this->timeout = $seconds;

        return $this;
    }

    /**
     * Open Internet or Unix domain socket connection.
     *
     * @param string $host
     * @return self
     * @throws SocketExecutionException
     */
    public function open($host)
    {
        $resource = @fsockopen($host, $this->port, $errNo, $errMsg, $this->timeout);
        if (!$resource) {
            throw new SocketExecutionException("[{$errMsg}] Connection to {$host} failed.", $errNo);
        }
        $this->resource = $resource;

        return $this;
    }

    /**
     * fwrite.
     *
     * @param string $value
     * @return self
     * @throws SocketExecutionException
     */
    public function puts($value)
    {
        $res = @fputs($this->resource, "{$value}\r\n");
        if (!$res) {
            throw new SocketExecutionException("Cannot write to {$value}.");
        }

        return $this;
    }

    /**
     * Gets line from file pointer.
     *
     * @return array
     */
    public function read()
    {
        $data = [];
        while (!feof($this->resource)) {
            $data[] = rtrim(fgets($this->resource));
        }

        return $data;
    }

    /**
     * Closes an open file pointer.
     *
     * @return bool
     */
    public function close()
    {
        return fclose($this->resource);
    }
}
