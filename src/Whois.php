<?php
namespace Chanshige;

use Chanshige\Factory\FactoryInterface;
use Chanshige\Validator\Query;
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

    /**
     * Whois constructor.
     *
     * @param FactoryInterface $factory
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->socket = $factory->create();
    }

    /**
     * @param string $domain domain name
     * @param string $server whois server
     * @return array
     * @throws InvalidWhoisRequestException
     */
    public function query($domain, $server)
    {
        if (Query::validate($domain, $server)) {
            throw new InvalidWhoisRequestException('Invalid input.');
        }

        try {
            $response = $this->socket->open($server)
                ->puts($domain)
                ->read();
            $this->socket->close();
        } catch (\Exception $e) {
            throw new InvalidWhoisRequestException($e->getMessage(), $e->getCode());
        }

        return $response;
    }
}
