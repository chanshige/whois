<?php
declare(strict_types=1);

namespace Chanshige;

use Chanshige\Exception\InvalidQueryException;
use Chanshige\Exception\SocketExecutionException;
use Chanshige\Handler\Socket;
use Chanshige\Handler\SocketInterface;
use Chanshige\Whois\CcTld;
use Chanshige\Whois\Server;
use Chanshige\Whois\Util;

/**
 * Class Whois
 *
 * @package Chanshige
 */
final class Whois implements WhoisInterface
{
    /** @var string Iana whois server url. */
    private const IANA_SERVER_URL = 'whois.iana.org';

    /** @var SocketInterface */
    private $socket;

    /** @var string top-level-domain. */
    private $tld;

    /** @var array Response data. */
    private $response = [];

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
     * Request Whois query.
     *
     * @param string $domain
     * @param string $servername
     * @return self
     */
    public function query(string $domain, string $servername = ''): WhoisInterface
    {
        $this->tld = get_tld($domain);

        if (strlen($servername) === 0) {
            $servername = $this->getWhoisServerName($this->tld);
        }

        $this->response = $this->invokeRequest($domain, $servername);

        if ($this->isRequestToRegistrarServer()) {
            $registrar = Util::extractWhoisServerName($this->response);
            // not found.
            if (strlen($registrar) === 0) {
                return $this;
            }

            $this->response = $this->invokeRequest($domain, $servername);
        }

        return $this;
    }

    /**
     * @param string $domain
     * @param string $servername
     * @return self
     */
    public function withQuery(string $domain, string $servername = ''): WhoisInterface
    {
        $whois = new self($this->socket);

        return $whois->query($domain, $servername);
    }

    /**
     * Registered domain.
     *
     * @return bool
     */
    public function isRegistered(): bool
    {
        $pattern = implode("|", [
            'No match for',
            'NOT FOUND',
            'No Data Found',
            'has not been registered',
            'does not exist',
            'No match!!',
            'available for registration',
        ]);

        return count(preg_grep("/{$pattern}/mi", $this->response)) === 0;
    }

    /**
     * Reserved domain.
     *
     * @return bool
     */
    public function isReserved(): bool
    {
        $pattern = implode("|", [
            'reserved name',
            'Reserved Domain',
            'registry reserved',
            'has been reserved',
        ]);

        return count(preg_grep("/{$pattern}/mi", $this->response)) > 0;
    }

    /**
     * Is client hold status.
     *
     * @return bool
     */
    public function isClientHold(): bool
    {
        return count(preg_grep('/^(.*)Status(.*)clientHold/mi', $this->response)) > 0;
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
            'registered' => $this->isRegistered(),
            'reserved' => $this->isReserved(),
            'client_hold' => $this->isClientHold(),
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
            'registrant' => preg_grep_values('/^Registrant/', $this->response),
            'admin' => preg_grep_values('/^Admin/', $this->response),
            'tech' => preg_grep_values('/^Tech/', $this->response),
            'billing' => preg_grep_values('/^Billing/', $this->response),
            'status' => preg_grep_values('/^(.*)Status:/', $this->response),
            'date' => preg_grep_values('/^(.*)Date:/', $this->response),
            'name_server' => preg_grep_values('/^Name Server/', $this->response)
        ];
    }

    /**
     * Return raw data.
     *
     * @return array
     */
    public function raw(): array
    {
        return $this->response;
    }

    /**
     * @param string $domain
     * @param string $servername
     * @return array
     * @throws InvalidQueryException
     */
    private function invokeRequest(string $domain, string $servername): array
    {
        try {
            $response = $this->socket->open($servername)
                ->puts($domain)
                ->read();
            return $response;
        } catch (SocketExecutionException $e) {
            throw new InvalidQueryException($e->getMessage(), $e->getCode());
        } finally {
            $this->socket->close();
        }
    }

    /**
     * @param string $tld
     * @return string
     */
    private function getWhoisServerName(string $tld): string
    {
        return Server::has($tld) ? Server::get($tld) : $this->findWhoisServerFromIana($tld);
    }

    /**
     * @param string $tld
     * @return string
     * @throws InvalidQueryException
     */
    private function findWhoisServerFromIana(string $tld): string
    {
        $request = $this->invokeRequest($tld, self::IANA_SERVER_URL);
        $servername = Util::extractWhoisName($request);
        if (strlen($servername) === 0) {
            throw new InvalidQueryException('Failed to find whois server from iana database.');
        }

        return $servername;
    }

    /**
     * @return bool
     */
    private function isRequestToRegistrarServer(): bool
    {
        return $this->isRegistered() && !CcTld::exists($this->tld);
    }
}
