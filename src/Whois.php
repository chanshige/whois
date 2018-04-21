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
        900 => '[900] Whois query failed.',
        901 => '[901] Failed to get whois server.',
        902 => '[902] Failed to find whois server from registrar database.',
        903 => '[903] Failed to find whois server from iana database.',
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

        if (!$socket instanceof SocketInterface) {
            throw new \InvalidArgumentException('Expected a SocketInterface');
        }

        $this->socket = $socket;
    }

    /**
     * Request Whois query.
     *
     * @param string $domain domain name
     * @param string $server whois server
     * @return Whois
     * @throws InvalidWhoisRequestException
     */
    public function query(string $domain, string $server = ''): WhoisInterface
    {
        try {
            $this->result = $this->socket->open($server ?: $this->findServer(tld($domain)))
                ->puts(convertIdnAscii($domain))
                ->read();
            $this->socket->close();
        } catch (\Exception $e) {
            throw new InvalidWhoisRequestException(self::$errorCodes[900], 900);
        }

        if (!$this->recursive && in_array(tld($domain), Config::get('recursive_tld'))) {
            $this->queryRecursive($domain);
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
        $pattern = implode("|", Config::get('no_registration_words'));

        return count(preg_grep("/{$pattern}/mi", $this->result)) === 0;
    }

    /**
     * Is Reserved from Domain Registry.
     *
     * @return bool
     */
    public function isReserved(): bool
    {
        $pattern = implode("|", Config::get('reserved_words'));

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
     * Find whois server name.
     *
     * @param string $tld
     * @return string
     */
    private function findServer($tld): string
    {
        $serverName = Config::serverList($tld);
        if (!is_null($serverName)) {
            return $serverName;
        }

        try {
            $this->query($tld, 'whois.iana.org');
            $key = key(preg_grep('/^whois:/', $this->raw()));
            if (is_null($key)) {
                throw new InvalidWhoisRequestException(self::$errorCodes[903], 903);
            }
            return trim(str_replace('whois:', '', $this->result[$key]));
        } catch (InvalidWhoisRequestException $exception) {
            throw new $exception(self::$errorCodes[901], 901);
        }
    }

    /**
     * Request Whois query recursive.
     *
     * @param string $domain
     */
    private function queryRecursive($domain)
    {
        $this->recursive = true;

        try {
            $key = key(preg_grep('/Whois Server:/mi', $this->result));
            if (is_null($key)) {
                throw new InvalidWhoisRequestException(self::$errorCodes[902], 902);
            }

            $this->query(
                $domain,
                (string)trim(str_replace('Registrar WHOIS Server:', '', $this->result[$key]))
            );
        } catch (InvalidWhoisRequestException $exception) {
            throw new $exception(self::$errorCodes[900], 900);
        }
    }
}
