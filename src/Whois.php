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
            $tld = tld($domain);

            if (strlen($servername) === 0) {
                $servername = $this->searchFromServerList($tld) ?: $this->findServerNameFromIana($tld);
            }

            $this->result = $this->socket->open($servername)
                ->puts(convertIdnAscii($domain))
                ->read();

            $this->socket->close();

            if (!$this->recursive && in_array($tld, Config::load('recursive_tld'), true)) {
                $this->queryRecursive($domain);
            }
        } catch (\Exception $e) {
            throw new InvalidWhoisRequestException($e->getMessage(), $e->getCode());
        }

        return $this;
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
     * WhoisInformation Detail.
     *
     * @return array
     */
    public function result(): array
    {
        return [
            'registered' => $this->isRegistered(),
            'reserved' => $this->isReserved(),
            'client_hold' => $this->isClientHold(),
            'detail' => [
                'registrant' => preg_grep('/^Registrant/', $this->result),
                'admin' => preg_grep('/^Admin/', $this->result),
                'tech' => preg_grep('/^Tech/', $this->result),
                'billing' => preg_grep('/^Billing/', $this->result),
                'status' => preg_grep('/^(.*)Status:/', $this->result),
                'date' => preg_grep('/^(.*)Date:/', $this->result),
                'name_server' => preg_grep('/^Name Server/', $this->result)
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
     * @param string $tld
     * @return string
     */
    private function searchFromServerList(string $tld): string
    {
        $lists = Config::load('server_list');
        // Null Coalescing Operator
        return $lists[$tld] ?? '';
    }

    /**
     * @param string $tld
     * @return string
     * @throws InvalidWhoisRequestException
     */
    private function findServerNameFromIana(string $tld): string
    {
        $this->query($tld, 'whois.iana.org');
        $servername = current((array)preg_filter('/^whois:\s+/', '', $this->result));
        if (!$servername) {
            throw new InvalidWhoisRequestException(self::$errorCodes[903], 903);
        }

        return $servername;
    }

    /**
     * Request Whois query recursive.
     *
     * @param string $domain
     * @throws InvalidWhoisRequestException
     */
    private function queryRecursive(string $domain)
    {
        $this->recursive = true;

        $servername = current((array)preg_filter('/(.*)Whois Server:\s+/i', '', $this->result));
        if (!$servername) {
            throw new InvalidWhoisRequestException(self::$errorCodes[902], 902);
        }

        $this->query($domain, $servername);
    }
}
