<?php
namespace Chanshige\Handler;

use Chanshige\Exception\SocketExecutionException;

/**
 * Class SocketStub
 *
 * @package Handler
 */
class SocketStub implements SocketInterface
{
    /** @var bool */
    private $resource;

    private $domain;

    /** @var int $port */
    private $port = 43;

    /** @var int $timeout sec */
    private $timeout = 3;

    /** @var array $errorCodes */
    private static $errorCodes = [
        10 => 'Failed to open socket connection.',
        11 => 'Write to socket failed.',
        12 => 'Read from socket failed.'
    ];

    /**
     * @param int $portNo
     * @return SocketInterface
     */
    public function setPort(int $portNo)
    {
        $this->port = $portNo;

        return $this;
    }

    /**
     * @param int $seconds
     * @return SocketInterface
     */
    public function setTimeout(int $seconds)
    {
        $this->timeout = $seconds;

        return $this;
    }

    /**
     * @param string $host
     * @return SocketInterface
     * @throws SocketExecutionException
     */
    public function open(string $host)
    {
        $resource = in_array($host, $this->allowHostName()) ? $host : false;
        if (!$resource) {
            throw new SocketExecutionException(self::$errorCodes[10], 10);
        }
        $this->resource = $resource;

        return $this;
    }

    /**
     * @param string $value
     * @return SocketInterface
     * @throws SocketExecutionException
     */
    public function puts(string $value): SocketInterface
    {
        $res = !is_null($this->exampleResult($value)) && $this->resource;
        if (!$res) {
            throw new SocketExecutionException(self::$errorCodes[11], 11);
        }
        $this->domain = $value;

        return $this;
    }

    /**
     * @return array
     * @throws SocketExecutionException
     */
    public function read(): array
    {
        $res = !is_null($this->exampleResult($this->domain)) && $this->resource;
        if (!$res) {
            throw new SocketExecutionException(self::$errorCodes[11], 11);
        }

        $data = array();
        foreach ($this->exampleResult($this->domain) as $item) {
            $data[] = $item;
        }

        return $data;
    }

    public function close(): bool
    {
        $this->resource = null;

        return true;
    }

    private function allowHostName()
    {
        return [
            'whois.chanshige.com.stub',
            'whois.chanshige.net.stub',
            'whois.chanshige.jp.stub'
        ];
    }

    private function exampleResult($domain)
    {
        $examples = [
            'chanshige.com.stub' => [
                '',
                '',
                'Domain Name: chanshige.com.stub',
                'Registry Domain ID: VRSN',
                'Registrar WHOIS Server: whois.chanshige.com.stub',
                'Registrar URL: http://networksolutions.com',
                'Updated Date: 2018-03-02T17:00:22Z',
                'Creation Date: 1994-02-07T05:00:00Z',
                'Registrar Registration Expiration Date: 2028-02-08T05:00:00Z',
                'Registrar: NETWORK SOLUTIONS, LLC.',
                'Registrar IANA ID: 2',
                'Domain Status: clientTransferProhibited https://icann.org/epp#clientTransferProhibited',
                'Registry Registrant ID:',
                'Registrant Name: NIC',
                'Registrant Organization: NIC',
                'Registry Admin ID:',
                'Admin Name: Semonche, Douglas',
                'Admin Organization: Network Infiormation Center (NIC), LLC',
                'Registry Tech ID:',
                'Tech Name: Semonche, Douglas',
                'Tech Organization: Network Infiormation Center (NIC), LLC',
                'Name Server: BACKUP.NIC.COM',
                'Name Server: SUE.NIC.COM',
                'DNSSEC: unsigned',
                'URL of the ICANN WHOIS Data Problem Reporting System: http://wdprs.internic.net/',
                '>>> Last update of WHOIS database: 2018-04-23T15:37:52Z <<<',
                '',
            ]
        ];

        return $examples[$domain] ?? null;
    }
}
