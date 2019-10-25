<?php
namespace Chanshige\Foundation;

use Chanshige\CommonTestCase;
use Chanshige\Fake\ResultSample;

/**
 * Class ResponseParserTest
 *
 * @package Chanshige\Foundation
 */
class ResponseParserTest extends CommonTestCase
{
    private $response;

    public function setUp()
    {
        parent::setUp();
        $this->response = (new ResponseParser)(ResultSample::get('chanshige.com.stub'));
    }

    public function testParser()
    {
        $this->assertTrue($this->response->isRegistered());
        $this->assertFalse($this->response->isReserved());
        $this->assertFalse($this->response->isClientHold());
        $this->assertEquals('whois.com.stub', $this->response->servername());
        $this->assertEquals(
            [
                'Registrant Name: NIC',
                'Registrant Organization: NIC',
            ],
            $this->response->registrant()
        );
        $this->assertEquals(
            [
                'Admin Name: Semonche, Douglas',
                'Admin Organization: Network Infiormation Center (NIC), LLC',
            ],
            $this->response->admin()
        );
        $this->assertEquals(
            [
                'Tech Name: Semonche, Douglas',
                'Tech Organization: Network Infiormation Center (NIC), LLC',
            ],
            $this->response->tech()
        );
        $this->assertEquals(
            [],
            $this->response->billing()
        );
        $this->assertEquals(
            [
                'Domain Status: clientTransferProhibited https://icann.org/epp#clientTransferProhibited',
            ],
            $this->response->status()
        );
        $this->assertEquals(
            [
                'Updated Date: 2018-03-02T17:00:22Z',
                'Creation Date: 1994-02-07T05:00:00Z',
                'Registrar Registration Expiration Date: 2028-02-08T05:00:00Z',
            ],
            $this->response->dates()
        );
        $this->assertEquals(
            [
                'Name Server: BACKUP.NIC.COM',
                'Name Server: SUE.NIC.COM',
            ],
            $this->response->nameserver()
        );
    }
}
