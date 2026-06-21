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
    public function testQueryRaw(): void
    {
        $whois = $this->newWhois(new SocketStub());
        $response = $whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertEquals(
            'Domain Name: chanshige.com.stub',
            $response->response()->raw()[2]
        );
    }

    public function testQueryResults(): void
    {
        $whois = $this->newWhois(new SocketStub());
        $response = $whois->query(
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

    public function testRequestCcTld(): void
    {
        $whois = $this->newWhois(new SocketStub());
        $response = $whois->query('chanshige.miyazaki.jp');
        $this->assertEquals('Domain Name: miyazaki.jp', $response->response()->raw()[0]);
    }

    public function testRequest(): void
    {
        $whois = $this->newWhois(new Socket());
        $result = $whois->query('tanakashigeki.com')->response();
        $this->assertInstanceOf('Chanshige\Contracts\ResponseParserInterface', $result);
    }

    public function testNotFind(): void
    {
        $this->expectExceptionMessage('Could not find the WHOIS server name for the "example" TLD.');
        $this->expectException(InvalidQueryException::class);

        $whois = $this->newWhois(new Socket());
        $whois->query('example.example');
    }

    public function testResponseByStatus(): void
    {
        $whois = $this->newWhois(new SocketStub());
        $whois = $whois->query(
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

    public function testResponseByRegistrant(): void
    {
        $whois = $this->newWhois(new SocketStub());
        $whois = $whois->query(
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

    public function testResponseByAdmin(): void
    {
        $whois = $this->newWhois(new SocketStub());
        $whois = $whois->query(
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

    public function testResponseByBilling(): void
    {
        $whois = $this->newWhois(new SocketStub());
        $whois = $whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertEquals([], $whois->response()->billing());
    }

    public function testResponseByTech(): void
    {
        $whois = $this->newWhois(new SocketStub());
        $whois = $whois->query(
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

    public function testResponseByDates(): void
    {
        $whois = $this->newWhois(new SocketStub());
        $whois = $whois->query(
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

    public function testResponseByNameserver(): void
    {
        $whois = $this->newWhois(new SocketStub());
        $whois = $whois->query(
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

    public function testResponseByIsClientHold(): void
    {
        $whois = $this->newWhois(new SocketStub());
        $whois = $whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertFalse($whois->response()->isClientHold());
    }

    public function testResponseByIsReserved(): void
    {
        $whois = $this->newWhois(new SocketStub());
        $whois = $whois->query(
            'chanshige.com.stub',
            'whois.com.stub'
        );

        $this->assertFalse($whois->response()->isReserved());
    }

    public function testResponseByNotFound(): void
    {
        $whois = $this->newWhois(new SocketStub());
        $whois = $whois->query(
            'notfound.com.stub',
            'whois.com.stub'
        );

        $this->assertFalse($whois->response()->isRegistered());
    }
}
