<?php
namespace Chanshige;

use Handler\SocketStub;

class WhoisTest extends CommonTestCase
{
    /** @var Whois */
    private $whois;

    protected function setUp()
    {
        parent::setUp();
        $this->whois = new Whois(new SocketStub());
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->whois = null;
    }

    public function testQueryRaw()
    {
        $response = $this->whois->query(
            'chanshige.com.stub',
            'whois.chanshige.com.stub'
        );

        $this->assertEquals(
            'Domain Name: chanshige.com.stub',
            $response->raw()[2]
        );
    }

    public function testQueryResult()
    {
        $response = $this->whois->query(
            'chanshige.com.stub',
            'whois.chanshige.com.stub'
        );

        $expected = [
            'registered' => true,
            'reserved' => false,
            'client_hold' => false,
            'detail' => [
                'registrant' => [
                    13 => 'Registrant Name: NIC',
                    14 => 'Registrant Organization: NIC',
                ],
                'admin' => [
                    16 => 'Admin Name: Semonche, Douglas',
                    17 => 'Admin Organization: Network Infiormation Center (NIC), LLC',
                ],
                'tech' => [
                    19 => 'Tech Name: Semonche, Douglas',
                    20 => 'Tech Organization: Network Infiormation Center (NIC), LLC',
                ],
                'billing' => [],
                'status' => [
                    11 => 'Domain Status: clientTransferProhibited https://icann.org/epp#clientTransferProhibited',
                ],
                'date' => [
                    6 => 'Updated Date: 2018-03-02T17:00:22Z',
                    7 => 'Creation Date: 1994-02-07T05:00:00Z',
                    8 => 'Registrar Registration Expiration Date: 2028-02-08T05:00:00Z',
                ],
                'name_server' => [
                    21 => 'Name Server: BACKUP.NIC.COM',
                    22 => 'Name Server: SUE.NIC.COM',
                ]
            ]
        ];

        $this->assertEquals($expected, $response->result());
    }

    public function testFailedQueryByIana()
    {
        $whois = new Whois();
        try {
            $whois->query('domains.gmo')->raw();
        } catch (\Exception $e) {
            $this->assertEquals('Failed to find whois server from iana database.', $e->getMessage());
        }
    }

    public function testFailedQuery()
    {
        $whois = new Whois();
        try {
            $whois->query('aaa.com', 'VERISIGN-GRS.COM');
        } catch (\Exception $e) {
            $this->assertEquals('Failed to open socket connection.', $e->getMessage());
        }
    }

    public function testQueryWithOutStub()
    {
        $whois = new Whois();
        $response = $whois->query('verisign.com');

        $this->assertTrue(is_array($response->raw()));
        $this->assertTrue($response->isRegistered());
        $this->assertFalse($response->isReserved());
        $this->assertFalse($response->isClientHold());
    }
}
