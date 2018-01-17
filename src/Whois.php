<?php
namespace Chanshige;

use Chanshige\Factory\FactoryInterface;
use Chanshige\Whois\ResponseBuilder\BuilderInterface;
use Connect\Socket;
use Exception\InvalidWhoisRequestException;

/**
 * Class Whois
 *
 * @package Chanshige
 */
class Whois
{
    /** @var Socket */
    private $socket;
    /** @var BuilderInterface */
    private $responseBuilder;

    public function __construct(FactoryInterface $factory, BuilderInterface $builder)
    {
        $this->socket = $factory->create();
        $this->responseBuilder = $builder;
    }

    /**
     * @param string $domain domain name
     * @param string $server whois server
     * @return BuilderInterface
     * @throws InvalidWhoisRequestException
     */
    public function query($domain, $server)
    {
        try {
            $response = $this->socket->open($server)
                ->puts($domain)
                ->read();
            $this->socket->close();
        } catch (\Exception $e) {
            throw new InvalidWhoisRequestException($e->getMessage(), $e->getCode());
        }

        return $this->responseBuilder->build($response);
    }
}
