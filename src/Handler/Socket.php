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
    /** @var resource */
    private $resource;

    /** @var int $errno */
    private $errno;

    /** @var string $errStr error message */
    private $errStr;

    /** @var array */
    private $config = [
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
        $ro = @fsockopen($host, $this->config['port'], $this->errno, $this->errStr, $this->config['timeout']);

        if ($ro === false) {
            throw new SocketException('Failed to open socket connection.', Socket::ERROR_OPEN);
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
     * Retrieve an external iterator.
     *
     * @return Traversable An instance of an object.
     */
    public function getIterator()
    {
        return $this->read();
    }

    /**
     * @param int       $retries
     * @param Throwable $throw
     * @return bool
     * @throws SocketException
     */
    private function pauseOnRetry(int $retries, Throwable $throw)
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
    private function applyConfig(array $config)
    {
        foreach ($config as $key => $value) {
            if (!array_key_exists($key, $this->config)) {
                throw new SocketException($key . ' is either not part of the configuration key name.');
            }
            $this->config[$key] = $value;
        }
    }
}
