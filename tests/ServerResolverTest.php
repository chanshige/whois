<?php

namespace Chanshige;

use ArrayIterator;
use Chanshige\Contracts\ResponseParserInterface;
use Chanshige\Contracts\WhoisClientInterface;
use Chanshige\Exception\InvalidQueryException;

class ServerResolverTest extends CommonTestCase
{
    public function testResolveKnownServerWithoutIanaRequest(): void
    {
        $client = new class implements WhoisClientInterface {
            public int $calls = 0;

            public function query(string $servername, string $query): ResponseParserInterface
            {
                $this->calls++;

                return (new Response())(new ArrayIterator([]));
            }
        };

        $resolver = new ServerResolver($client);

        $this->assertSame('whois.verisign-grs.com', $resolver->resolve('com'));
        $this->assertSame(0, $client->calls);
    }

    public function testResolveUnknownServerThroughIanaOnce(): void
    {
        $client = new class implements WhoisClientInterface {
            public int $calls = 0;

            public function query(string $servername, string $query): ResponseParserInterface
            {
                $this->calls++;

                return (new Response())(new ArrayIterator(['whois:        whois.com.stub']));
            }
        };

        $resolver = new ServerResolver($client);

        $this->assertSame('whois.com.stub', $resolver->resolve('stub'));
        $this->assertSame('whois.com.stub', $resolver->resolve('stub'));
        $this->assertSame(1, $client->calls);
    }

    public function testResolveEmptyStaticServerThroughIana(): void
    {
        $client = new class implements WhoisClientInterface {
            public function query(string $servername, string $query): ResponseParserInterface
            {
                return (new Response())(new ArrayIterator(['whois:        whois.abbvie.stub']));
            }
        };

        $resolver = new ServerResolver($client);

        $this->assertSame('whois.abbvie.stub', $resolver->resolve('abbvie'));
    }

    public function testThrowWhenServerCannotBeResolved(): void
    {
        $this->expectException(InvalidQueryException::class);

        $client = new class implements WhoisClientInterface {
            public function query(string $servername, string $query): ResponseParserInterface
            {
                return (new Response())(new ArrayIterator([]));
            }
        };

        (new ServerResolver($client))->resolve('unknown');
    }
}
