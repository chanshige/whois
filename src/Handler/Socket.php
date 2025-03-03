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

namespace Chanshige\Handler;

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

    private ?int $errorCode;

    private ?string $errorMessage;

    public function __construct(
        private int $port = 43,
        private int $timeout = 5,
        private int $retryCount = 3,
    ) {
    }

    /**
     * {@inheritDoc}
     * @throws SocketException
     */
    public function execute(string $host, string $value): SocketInterface
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

        throw new SocketException('request failed.', SocketInterface::ERROR_EXECUTE);
    }

    /**
     * {@inheritdoc}
     * @throws SocketException
     */
    public function open(string $host): SocketInterface
    {
        $ro = @fsockopen(
            $host,
            $this->port,
            $this->errorCode,
            $this->errorMessage,
            $this->timeout,
        );

        if ($ro === false) {
            throw new SocketException('Failed to open socket connection.', SocketInterface::ERROR_OPEN);
        }

        $clone = clone $this;
        $clone->resource = $ro;

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
            throw new SocketException('Write to socket failed.', SocketInterface::ERROR_PUTS);
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
        return !is_resource($this->resource) || fclose($this->resource);
    }

    public function getIterator(): Generator
    {
        return $this->read();
    }

    public function getErrorCode(): int|null
    {
        return $this->errorCode;
    }

    public function getErrorMessage(): string|null
    {
        return $this->errorMessage;
    }

    /**
     * @throws SocketException
     */
    private function pauseOnRetry(int $retries, Throwable $throw): bool
    {
        if ($retries <= $this->retryCount) {
            usleep((int)(pow(4, $retries) * 100000) + 600000);
            return true;
        }
        throw new SocketException($throw->getMessage(), $throw->getCode());
    }
}
