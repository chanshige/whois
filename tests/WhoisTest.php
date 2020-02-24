<?php
namespace Chanshige;

use Chanshige\Fake\SocketStub;
use Chanshige\Foundation\Handler\Socket;
use Chanshige\Foundation\ResponseParser;

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
    protected function setUp()
    {
        parent::setUp();
        $this->whois = new Whois(new SocketStub(), new ResponseParser());
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @throws \Chanshige\Exception\InvalidQueryException
     */
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

    /**
     * @throws \Chanshige\Exception\InvalidQueryException
     */
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

        $info = [
            'domain' => 'chanshige.miyazaki.jp',
            'servername' => 'whois.miyazaki.jp.stub',
            'tld' => 'miyazaki.jp',
        ];
        $this->assertEquals($info, $response->info());
    }

    public function testRequest()
    {
        $whois = new Whois(new Socket(), new ResponseParser());
        $result = $whois->query('tanakashigeki.com')->response();
        $this->assertInstanceOf('Chanshige\Foundation\ResponseParserInterface', $result);
    }

    /**
     * @expectedException \Chanshige\Exception\InvalidQueryException
     * @expectedExceptionMessage Could not find to example whois server from iana database.
     */
    public function testNotFind()
    {
        $whois = new Whois(new Socket(), new ResponseParser());
        $whois->query('example.example');
    }
}
