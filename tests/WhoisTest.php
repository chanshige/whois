<?php
namespace Chanshige;

use Chanshige\Handler\SocketStub;

class WhoisTest extends CommonTestCase
{
    /** @var Whois */
    private $whois;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();
        $this->whois = new Whois(new SocketStub());
    }

    /**
     * {@inheritdoc}
     */
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
            ],
            'raw' => [
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
     * @expectedExceptionMessage Could not find to gmo whois server from iana database.
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
        $whois->setRetryCount(3);
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

    public function testJsonEncode()
    {
        $whois = new Whois();
        $this->assertIsString(json_encode($whois->query('verisign.com')));
    }
}
