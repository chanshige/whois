<?php
declare(strict_types=1);

namespace Chanshige;

use Chanshige\Exception\InvalidQueryException;
use Chanshige\Exception\SocketExecutionException;
use Chanshige\Handler\Socket;
use Chanshige\Handler\SocketInterface;
use Chanshige\Whois\CcTld;
use Chanshige\Whois\ResponseParser;
use Chanshige\Whois\Server;

/**
 * Class Whois
 *
 * @package Chanshige
 */
final class Whois implements WhoisInterface
{
    /** @var int Socket error retry count. */
    private const MAX_ERROR_RETRY = 3;

    /** @var SocketInterface */
    private $socket;

    /** @var string top level domain. */
    private $tld;

    /** @var ResponseParser */
    private $response;

    /**
     * Whois constructor.
     *
     * @param SocketInterface $socket
     */
    public function __construct(SocketInterface $socket = null)
    {
        if (!$socket instanceof SocketInterface) {
            $socket = new Socket();
        }

        $this->socket = $socket;
    }

    /**
     * Whois query.
     *
     * @param string $domain
     * @param string $servername
     * @return Whois
     */
    public function query(string $domain, string $servername = ''): Whois
    {
        $this->tld = get_tld($domain);
        if (strlen($servername) === 0) {
            $servername = $this->getWhoisServerName($this->tld);
        }
        $this->response = $this->invokeRequest($domain, $servername);
        $responseServername = $this->response->servername();

        if ($this->response->isRegistered() && !CcTld::exists($this->tld) &&
            strlen($responseServername) > 0 && $servername !== $responseServername) {
            return $this->query($domain, $responseServername);
        }

        return $this;
    }

    /**
     * @param string $domain
     * @param string $servername
     * @return Whois
     */
    public function withQuery(string $domain, string $servername = ''): Whois
    {
        $whois = new self($this->socket);

        return $whois->query($domain, $servername);
    }

    /**
     * WhoisInformation results.
     *
     * @return array
     */
    public function results(): array
    {
        return [
            'tld' => $this->tld,
            'registered' => $this->response->isRegistered(),
            'reserved' => $this->response->isReserved(),
            'client_hold' => $this->response->isClientHold(),
            'detail' => (CcTld::exists($this->tld) ? $this->raw() : $this->detail())
        ];
    }

    /**
     * Return result details.
     *
     * @return array
     */
    public function detail(): array
    {
        return [
            'registrant' => $this->response->registrant(),
            'admin' => $this->response->admin(),
            'tech' => $this->response->tech(),
            'billing' => $this->response->billing(),
            'status' => $this->response->status(),
            'date' => $this->response->dates(),
            'name_server' => $this->response->nameserver()
        ];
    }

    /**
     * Return raw data.
     *
     * @return array
     */
    public function raw(): array
    {
        return $this->response->getResponse();
    }

    /**
     * @param string $domain
     * @param string $servername
     * @return ResponseParser
     */
    private function invokeRequest(string $domain, string $servername): ResponseParser
    {
        $response = [];
        $retry = true;
        $cnt = 0;
        do {
            try {
                $response = $this->socket->open($servername)
                    ->puts($domain)
                    ->read();
                $retry = false;
            } catch (SocketExecutionException $exception) {
                $this->pauseOnRetry(++$cnt, $exception);
            } finally {
                $this->socket->close();
            }
        } while ($retry);

        return new ResponseParser($response);
    }

    /**
     * @param string $tld
     * @return string
     */
    private function getWhoisServerName(string $tld): string
    {
        if (Server::has($tld)) {
            return Server::get($tld);
        }

        $servername = $this->invokeRequest($tld, 'whois.iana.org')->servername();
        if (strlen($servername) === 0) {
            throw new InvalidQueryException('Failed to find whois server from iana database.');
        }

        return $servername;
    }

    /**
     * Retry.
     *
     * @param integer    $retries
     * @param \Throwable $throw
     */
    private function pauseOnRetry(int $retries, \Throwable $throw)
    {
        if ($retries <= self::MAX_ERROR_RETRY) {
            sleep(3);
            return;
        }
        throw new InvalidQueryException($throw->getMessage(), $throw->getCode());
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return var_export($this->results(), true);
    }
}
