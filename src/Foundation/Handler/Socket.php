<?php
declare(strict_types=1);

namespace Chanshige\Foundation\Handler;

use Chanshige\Exception\SocketException;
use Generator;
use Throwable;

/**
 * Class Socket
 *
 * @package Chanshige\Handler
 */
class Socket implements SocketInterface
{
    /** @var resource */
    private $resource;

    /** @var int $port */
    private $port = 43;

    /** @var int $timeout sec */
    private $timeout = 5;

    /** @var int $retryCount count */
    private $retryCount = 3;

    /** @var int $errno */
    private $errno;

    /** @var string $errStr error message */
    private $errStr;

    /**
     * Socket constructor.
     *
     * {@inheritDoc}
     */
    public function __construct(int $portNo = 43, int $timeout = 5, int $retryCount = 3)
    {
        $this->port = $portNo;
        $this->timeout = $timeout;
        $this->retryCount = $retryCount;
    }

    /**
     * {@inheritDoc}
     * @throws SocketException
     */
    public function __invoke(string $host, string $value)
    {
        $retry = false;
        $cnt = 0;
        do {
            try {
                return $this->open($host)->puts($value);
            } catch (SocketException $e) {
                $retry = $this->pauseOnRetry($cnt++, $e);
            } finally {
                $this->close();
            }
        } while ($retry);

        throw new SocketException('request failed.', Socket::ERROR_REQUEST);
    }

    /**
     * {@inheritdoc}
     * @throws SocketException
     */
    public function open(string $host): SocketInterface
    {
        $resource = @fsockopen($host, $this->port, $this->errno, $this->errStr, $this->timeout);
        if ($resource === false) {
            throw new SocketException('Failed to open socket connection.', Socket::ERROR_OPEN);
        }

        $clone = clone $this;
        $clone->resource = $resource;

        return $clone;
    }

    /**
     * {@inheritdoc}
     * @throws SocketException
     */
    public function puts(string $value): SocketInterface
    {
        $result = @fwrite($this->resource, "{$value}\r\n");
        if ($result === false) {
            throw new SocketException('Write to socket failed.', Socket::ERROR_PUTS);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function read(): Generator
    {
        while (!feof($this->resource)) {
            $buffer = fgets($this->resource);
            if ($buffer === false) {
                break;
            }

            yield trim($buffer);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function close(): bool
    {
        return !is_resource($this->resource) ?: fclose($this->resource);
    }

    /**
     * @param int       $retries
     * @param Throwable $throw
     * @return bool
     * @throws SocketException
     */
    protected function pauseOnRetry(int $retries, Throwable $throw)
    {
        if ($retries <= $this->retryCount) {
            usleep((int)(pow(4, $retries) * 100000) + 600000);
            return true;
        }
        throw new SocketException($throw->getMessage(), $throw->getCode());
    }
}
