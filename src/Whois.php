<?php
declare(strict_types=1);

namespace Chanshige;

use Chanshige\Exception\InvalidQueryException;
use Chanshige\Handler\Socket;
use Chanshige\Handler\SocketInterface;

/**
 * Class Whois
 *
 * @package Chanshige
 */
final class Whois implements WhoisInterface
{
    private $socket;

    private $isRequested = false;

    private $tld;

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
     * @return Whois
     */
    public function query(string $domain, string $servername = '')
    {
        $this->tld = get_tld($domain);

        if (strlen($servername) === 0) {
            $servername = get_whois_servername($this->tld) ?: $this->findWhoisServerFromIana();
        }

        try {
            $this->response = $this->socket->open($servername)
                ->puts($domain)
                ->read();
            $this->socket->close();

            // Registrarへの問い合わせを行わない
            if (!$this->hasRequestedRegistrarServer()) {
                return $this;
            }

            return $this->queryRegistrarWhoisServer($domain);
        } catch (\Exception $e) {
            throw new InvalidQueryException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @param string $domain
     * @param string $servername
     * @return Whois
     */
    public function withQuery(string $domain, string $servername = '')
    {
        $clone = clone $this;
        $clone->socket = clone $this->socket;
        $clone->tld = '';
        $clone->response = [];
        $clone->isRequested = false;

        return $clone->query($domain, $servername);
    }

    /**
     * Registered domain.
     *
     * @return bool
     */
    public function isRegistered(): bool
    {
        $pattern = implode("|", whois_no_registration_words());
        return count(preg_grep("/{$pattern}/mi", $this->response)) === 0;
    }

    /**
     * Reserved domain.
     *
     * @return bool
     */
    public function isReserved(): bool
    {
        $pattern = implode("|", whois_reserved_words());
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
            'detail' => ($this->isCcTld() ? $this->raw() : $this->detail())
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
     * @return string
     */
    private function findWhoisServerFromIana(): string
    {
        $res = $this->withQuery($this->tld, 'whois.iana.org');
        $servername = current((array)preg_filter('/^whois:\s+/', '', $res->response));
        if (!$servername) {
            throw new InvalidQueryException('Failed to find whois server from iana database.');
        }

        return $servername;
    }

    /**
     * @return string
     */
    private function extractRegistrarWhoisServer(): string
    {
        $servername = current((array)preg_filter('/(.*)Whois Server:\s+/i', '', $this->response));
        if (!$servername) {
            return '';
        }

        return $servername;
    }

    /**
     * @param string $domain
     * @return Whois
     */
    private function queryRegistrarWhoisServer(string $domain)
    {
        $this->isRequested = true;
        $registrar = $this->extractRegistrarWhoisServer();
        if (strlen($registrar) === 0) {
            return $this;
        }

        return $this->query($domain, $registrar);
    }

    /**
     * @return bool
     */
    private function hasRequestedRegistrarServer()
    {
        return $this->isRegistered() && !$this->isRequested && !$this->isCcTld();
    }

    /**
     * @return bool
     */
    private function isCcTld()
    {
        return in_array($this->tld, get_cctld_list(), true);
    }
}
