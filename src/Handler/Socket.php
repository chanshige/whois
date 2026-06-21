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
use Traversable;

/**
 * Class Socket
 *
 * @package Chanshige\Handler
 */
class Socket implements SocketInterface
{
    /** @var resource|null */
    private $resource = null;

    private int $errno = 0;

    private string $errStr = '';

    private array $config = [
        'port' => 43,
        'timeout' => 5,
        'retry_count' => 3
    ];

    /**
     * Socket constructor.
     * {@inheritDoc}
     */
    public function __construct(array $config = [])
    {
        $this->applyConfig($config);
    }

    /**
     * {@inheritDoc}
     * @throws SocketException
     */
    public function __invoke(string $host, string $value): SocketInterface
    {
        $retry = false;
        $cnt = 0;
        do {
            try {
                return $this->open($host)->puts($value);
            } catch (SocketException $e) {
                $this->close();
                $retry = $this->pauseOnRetry($cnt++, $e);
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
        $ro = @fsockopen($host, $this->config['port'], $this->errno, $this->errStr, $this->config['timeout']);

        if ($ro === false) {
            throw new SocketException('Failed to open socket connection.', Socket::ERROR_OPEN);
        }

        $this->resource = $ro;

        return $this;
    }

    /**
     * {@inheritdoc}
     * @throws SocketException
     */
    public function puts(string $value): SocketInterface
    {
        if (!is_resource($this->resource)) {
            throw new SocketException('Socket connection has not been opened.', Socket::ERROR_PUTS);
        }

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
        if (!is_resource($this->resource)) {
            return;
        }

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
        if (!is_resource($this->resource)) {
            return true;
        }

        $result = fclose($this->resource);
        $this->resource = null;

        return $result;
    }

    /**
     * Retrieve an external iterator.
     *
     * @return Traversable An instance of an object.
     */
    public function getIterator(): Traversable
    {
        return $this->read();
    }

    /**
     * @param int       $retries
     * @param Throwable $throw
     * @return bool
     * @throws SocketException
     */
    private function pauseOnRetry(int $retries, Throwable $throw): bool
    {
        if ($retries <= $this->config['retry_count']) {
            usleep((int)(pow(4, $retries) * 100000) + 600000);
            return true;
        }
        throw new SocketException($throw->getMessage(), $throw->getCode());
    }

    /**
     * Apply configuration key value.
     *
     * @param array $config
     */
    private function applyConfig(array $config): void
    {
        foreach ($config as $key => $value) {
            if (!array_key_exists($key, $this->config)) {
                throw new SocketException($key . ' is either not part of the configuration key name.');
            }
            $this->config[$key] = $value;
        }
    }
}
