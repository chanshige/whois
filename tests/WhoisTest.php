<?php
namespace Chanshige;

use Chanshige\Handler\SocketStub;

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

    /**
     * @throws \Chanshige\Exception\InvalidQueryException
     */
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

    /**
     * @throws \Chanshige\Exception\InvalidQueryException
     */
    public function testQueryResults()
    {
        $response = $this->whois->query(
            'chanshige.com.stub',
            'whois.chanshige.com.stub'
        );

        $expected = [
            'domain' => 'chanshige.com.stub',
            'servername' => 'whois.chanshige.com.stub',
            'tld' => 'com.stub',
            'registered' => true,
            'reserved' => false,
            'client_hold' => false,
            'detail' => [
                'registrant' => [
                    'Registrant Name: NIC',
                    'Registrant Organization: NIC',
                ],
                'admin' => [
                    'Admin Name: Semonche, Douglas',
                    'Admin Organization: Network Infiormation Center (NIC), LLC',
                ],
                'tech' => [
                    'Tech Name: Semonche, Douglas',
                    'Tech Organization: Network Infiormation Center (NIC), LLC',
                ],
                'billing' => [],
                'status' => [
                    'Domain Status: clientTransferProhibited https://icann.org/epp#clientTransferProhibited',
                ],
                'date' => [
                    'Updated Date: 2018-03-02T17:00:22Z',
                    'Creation Date: 1994-02-07T05:00:00Z',
                    'Registrar Registration Expiration Date: 2028-02-08T05:00:00Z',
                ],
                'name_server' => [
                    'Name Server: BACKUP.NIC.COM',
                    'Name Server: SUE.NIC.COM',
                ]
            ]
        ];

        $this->assertEquals($expected, $response->results());
    }

    /**
     * @throws \Chanshige\Exception\InvalidQueryException
     */
    public function testQueryByIana()
    {
        $whois = new Whois();
        $whois->query('nic.app');
        $response = $whois->results();
        $this->assertTrue($response['registered']);
    }

    /**
     * @expectedException \Chanshige\Exception\InvalidQueryException
     * @expectedExceptionMessage Failed to find whois server from iana database.
     */
    public function testFailedQueryByIana()
    {
        $whois = new Whois();
        $whois->query('domains.gmo')->raw();
    }

    /**
     * @expectedException \Chanshige\Exception\InvalidQueryException
     * @expectedExceptionMessage Failed to open socket connection.
     */
    public function testFailedQuery()
    {
        $whois = new Whois();
        $whois->query('aaa.com', 'VERISIGN-GRS.COM');
    }

    /**
     * @throws \Chanshige\Exception\InvalidQueryException
     */
    public function testQueryWithoutStub()
    {
        $whois = new Whois();
        $response = $whois->query('verisign.com');

        $this->assertTrue(is_array($response->raw()));

        $response = $whois->withQuery('afilias.info');
        $this->assertInstanceOf('Chanshige\Whois', $response);

        $response = $whois->withQuery('nic.app');
        $this->assertInstanceOf('Chanshige\Whois', $response);
    }

    public function testToString()
    {
        $whois = new Whois();
        $this->assertIsString((string)$whois->query('verisign.com'));
    }
}
