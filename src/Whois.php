<?php
namespace Chanshige;

use Exception\InvalidWhoisRequestException;
use Handler\SocketInterface;

/**
 * Class Whois
 *
 * @package Chanshige
 */
final class Whois implements WhoisInterface
{
    /** @var SocketInterface */
    private $socket;

    /** @var bool $recursive */
    private $recursive = false;

    /** @var array */
    private static $errorCodes = [
        900 => 'Whois query failed.',
        902 => 'Failed to find whois server from registrar database.',
        903 => 'Failed to find whois server from iana database.',
    ];

    /** @var string $domain */
    private $domain = '';

    /** @var string $tld */
    private $tld = '';

    /** @var array $result */
    private $result = [];

    /**
     * Whois constructor.
     *
     * @param SocketInterface $socket
     */
    public function __construct(SocketInterface $socket = null)
    {
        if (is_null($socket)) {
            $socket = new \Handler\Socket();
        }

        $this->socket = $socket;
    }

    /**
     * Request Whois query.
     *
     * @param string $domain     domain name
     * @param string $servername whois server
     * @return Whois
     * @throws InvalidWhoisRequestException
     */
    public function query(string $domain, string $servername = ''): WhoisInterface
    {
        try {
            $this->domain = convertIdnAscii($domain);
            $this->tld = tld($domain);

            if (strlen($servername) === 0) {
                $servername = $this->searchFromServerList() ?: $this->findServerNameFromIana();
            }

            $this->result = $this->socket->open($servername)
                ->puts($this->domain)
                ->read();
            $this->socket->close();

            if ($this->isRecursiveQueryDomain()) {
                return $this->queryRecursive();
            }
        } catch (\Exception $e) {
            throw new InvalidWhoisRequestException($e->getMessage(), $e->getCode());
        }

        return $this;
    }

    /**
     * Return an instance with Request Whois query.
     *
     * @param string $domain
     * @param string $servername
     * @return WhoisInterface
     * @throws InvalidWhoisRequestException
     */
    public function withQuery(string $domain, string $servername = ''): WhoisInterface
    {
        $clone = clone $this;
        $clone->recursive = false;
        $clone->domain = '';
        $clone->tld = '';
        $clone->result = [];
        $clone->query($domain, $servername);

        return $clone;
    }

    /**
     * Is Registered Domain.
     *
     * @return bool
     */
    public function isRegistered(): bool
    {
        $pattern = implode("|", Config::load('no_registration_words'));

        return count(preg_grep("/{$pattern}/mi", $this->result)) === 0;
    }

    /**
     * Is Reserved from Domain Registry.
     *
     * @return bool
     */
    public function isReserved(): bool
    {
        $pattern = implode("|", Config::load('reserved_words'));

        return count(preg_grep("/{$pattern}/mi", $this->result)) > 0;
    }

    /**
     * Is Client Hold.
     *
     * @return bool
     */
    public function isClientHold(): bool
    {
        return count(preg_grep('/^(.*)Status(.*)clientHold/mi', $this->result)) > 0;
    }

    /**
     * Only have a Raw Data.
     * 一般的なWhoisフォーマットを持たないTLDを示す
     * (.jp/.be/.uk etc...)
     *
     * @return bool
     */
    public function hasRawOnlyResult(): bool
    {
        return in_array($this->tld, Config::load('raw_only_tld'));
    }

    /**
     * WhoisInformation Detail.
     *
     * @return array
     */
    public function result(): array
    {
        return [
            'domain_name' => $this->domain,
            'tld' => $this->tld,
            'registered' => $this->isRegistered(),
            'reserved' => $this->isReserved(),
            'client_hold' => $this->isClientHold(),
            'detail' => [
                'registrant' => preg_grep_values('/^Registrant/', $this->result),
                'admin' => preg_grep_values('/^Admin/', $this->result),
                'tech' => preg_grep_values('/^Tech/', $this->result),
                'billing' => preg_grep_values('/^Billing/', $this->result),
                'status' => preg_grep_values('/^(.*)Status:/', $this->result),
                'date' => preg_grep_values('/^(.*)Date:/', $this->result),
                'name_server' => preg_grep_values('/^Name Server/', $this->result)
            ]
        ];
    }

    /**
     * Raw ResponseData.
     *
     * @return array
     */
    public function raw(): array
    {
        return $this->result;
    }

    /**
     * @return string
     */
    private function searchFromServerList(): string
    {
        $lists = Config::load('server_list');
        // Null Coalescing Operator
        return $lists[$this->tld] ?? '';
    }

    /**
     * @return string
     * @throws InvalidWhoisRequestException
     */
    private function findServerNameFromIana(): string
    {
        $whois = $this->withQuery($this->tld, 'whois.iana.org');
        $servername = current((array)preg_filter('/^whois:\s+/', '', $whois->raw()));
        if (!$servername) {
            throw new InvalidWhoisRequestException(self::$errorCodes[903], 903);
        }

        return $servername;
    }

    /**
     * @return bool
     */
    private function isRecursiveQueryDomain()
    {
        return !$this->recursive && $this->isRegistered() &&
            in_array($this->tld, Config::load('recursive_tld'), true);
    }

    /**
     * Request Whois query recursive.
     *
     * @return self
     * @throws InvalidWhoisRequestException
     */
    private function queryRecursive()
    {
        $this->recursive = true;

        $servername = current((array)preg_filter('/(.*)Whois Server:\s+/i', '', $this->result));
        if (!$servername) {
            throw new InvalidWhoisRequestException(self::$errorCodes[902], 902);
        }

        return $this->query($this->domain, $servername);
    }
}
