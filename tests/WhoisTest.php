<?php

namespace Chanshige;

use Chanshige\Exception\InvalidQueryException;
use Chanshige\Fake\SocketStub;
use Chanshige\Handler\Socket;

/**
 * Class WhoisTest
 *
 * @package Chanshige
 */
class WhoisTest extends CommonTestCase
{
    /** @var Whois */
    private $whois;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->whois = new Whois(new SocketStub(), new Response());
    }

    public function testQueryRaw()
    {
        $response = $this->whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertEquals(
            'Domain Name: chanshige.com.stub',
            $response->response()->raw()[2]
        );
    }

    public function testQueryResults()
    {
        $response = $this->whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $expected = [
            '',
            '',
            'Domain Name: chanshige.com.stub',
            'Registry Domain ID: VRSN',
            'Registrar WHOIS Server: whois.com.stub',
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
        ];

        $this->assertEquals($expected, $response->response()->raw());
    }

    public function testRequestCcTld()
    {
        $response = $this->whois->query('chanshige.miyazaki.jp');
        $this->assertEquals('Domain Name: miyazaki.jp', $response->response()->raw()[0]);
    }

    public function testRequest()
    {
        $whois = new Whois(new Socket(), new Response());
        $result = $whois->query('tanakashigeki.com')->response();
        $this->assertInstanceOf('Chanshige\Contracts\ResponseParserInterface', $result);
    }

    public function testNotFind()
    {
        $this->expectExceptionMessage("Could not find to example whois server from iana database.");
        $this->expectException(InvalidQueryException::class);

        $whois = new Whois(new Socket(), new Response());
        $whois->query('example.example');
    }

    public function testResponseByStatus()
    {
        $whois = $this->whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertEquals(
            [
                'Domain Status: clientTransferProhibited https://icann.org/epp#clientTransferProhibited',
            ],
            $whois->response()->status()
        );
    }

    public function testResponseByRegistrant()
    {
        $whois = $this->whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertEquals(
            [
                'Registrant Name: NIC',
                'Registrant Organization: NIC',
            ],
            $whois->response()->registrant()
        );
    }

    public function testResponseByAdmin()
    {
        $whois = $this->whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertEquals(
            [
                'Admin Name: Semonche, Douglas',
                'Admin Organization: Network Infiormation Center (NIC), LLC',
            ],
            $whois->response()->admin()
        );
    }

    public function testResponseByBilling()
    {
        $whois = $this->whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertEquals([], $whois->response()->billing());
    }

    public function testResponseByTech()
    {
        $whois = $this->whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertEquals(
            [
                'Tech Name: Semonche, Douglas',
                'Tech Organization: Network Infiormation Center (NIC), LLC',
            ],
            $whois->response()->tech()
        );
    }

    public function testResponseByDates()
    {
        $whois = $this->whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertEquals(
            [
                'Updated Date: 2018-03-02T17:00:22Z',
                'Creation Date: 1994-02-07T05:00:00Z',
                'Registrar Registration Expiration Date: 2028-02-08T05:00:00Z',
            ],
            $whois->response()->dates()
        );
    }

    public function testResponseByNameserver()
    {
        $whois = $this->whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertEquals(
            [
                'Name Server: BACKUP.NIC.COM',
                'Name Server: SUE.NIC.COM',
            ],
            $whois->response()->nameserver()
        );
    }

    public function testResponseByIsClientHold()
    {
        $whois = $this->whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertFalse($whois->response()->isClientHold());
    }

    public function testResponseByIsReserved()
    {
        $whois = $this->whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertFalse($whois->response()->isReserved());
    }

    public function testResponseByNotFound()
    {
        $whois = $this->whois->query(
            'notfound.com.stub',
            'whois.com.stub'
        );

        $this->assertFalse($whois->response()->isRegistered());
    }
}
