<?php
namespace Chanshige\Fake;

use Chanshige\Exception\SocketException;
use Chanshige\Foundation\Handler\Socket;
use Chanshige\Foundation\Handler\SocketInterface;
use Generator;

/**
 * Class SocketStub
 *
 * @package Chanshige\Fake
 */
class SocketStub extends Socket
{
    /** @var resource */
    private $resource;

    /** @var string $domain */
    private $domain;

    /**
     * SocketStub constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @param string $host
     * @param string $value
     * @return SocketInterface
     */
    public function __invoke(string $host, string $value)
    {
        return parent::__invoke($host, $value);
    }

    public function open(string $host): SocketInterface
    {
        if (!in_array($host, [
            'whois.iana.org',
            'whois.com.stub',
            'whois.miyazaki.jp.stub'
        ])) {
            throw new SocketException('Failed to open socket connection.', SocketInterface::ERROR_OPEN);
        }
        $this->resource = $host;

        return $this;
    }

    public function puts(string $value): SocketInterface
    {
        if (!ResultSample::has($value) && is_null($this->resource)) {
            throw new SocketException('Write to socket failed.', SocketInterface::ERROR_PUTS);
        }
        $this->domain = $value;

        return $this;
    }

    public function read(): Generator
    {
        foreach (ResultSample::get($this->domain) as $item) {
            yield $item;
        }
    }

    public function close(): bool
    {
        return true;
    }
}
