<?php
namespace Chanshige\Fake;

use Chanshige\Exception\SocketException;
use Chanshige\Handler\Socket;
use Chanshige\Handler\SocketInterface;
use Generator;

/**
 * Class SocketStub
 *
 * @package Chanshige\Fake
 */
class SocketStub extends Socket
{
    private ?string $resource = null;

    private string $domain = '';

    /**
     * @param string $host
     * @param string $value
     * @return SocketInterface
     */
    public function __invoke(string $host, string $value): SocketInterface
    {
        return parent::__invoke($host, $value);
    }

    public function open(string $host): SocketInterface
    {
        if (!in_array($host, [
            'whois.iana.org',
            'whois.com.stub',
            'whois.miyazaki.jp.stub',
        ])) {
            throw new SocketException('Failed to open socket connection.', SocketInterface::ERROR_OPEN);
        }
        $this->resource = $host;

        return $this;
    }

    public function puts(string $value): SocketInterface
    {
        if (!ResultSample::hasKey($value) && $this->resource === null) {
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
