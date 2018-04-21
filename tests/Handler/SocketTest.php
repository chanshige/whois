<?php
namespace Handler;

use Chanshige\CommonTestCase;
use Exception\SocketExecutionException;

class SocketTest extends CommonTestCase
{
    /** @var Socket */
    private $socket;

    protected function setUp()
    {
        parent::setUp();
        $this->socket = (new Socket())->port(43)->timeout(1);
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testSocket()
    {
        $response = $this->socket
            ->open('whois.verisign-grs.com')
            ->puts('verisign-grs.com')
            ->read();

        $this->assertTrue(is_array($response));
        $this->assertEquals("Domain Name: VERISIGN-GRS.COM", $response[0]);
        $this->assertTrue($this->socket->close());
    }

    public function testOpenFailed()
    {
        try {
            $this->socket->open('localhost');
        } catch (SocketExecutionException $e) {
            $this->assertEquals('Failed to open socket connection.', $e->getMessage());
        }
    }

    public function testPutsFailed()
    {
        try {
            $this->socket->open('whois.verisign-grs.com')->close();
            $this->socket->puts('verisign-grs.com');
        } catch (SocketExecutionException $e) {
            $this->assertEquals('Write to socket failed.', $e->getMessage());
        }
    }

    public function testReadFailed()
    {
        try {
            $this->socket->open('whois.nic.tokyo');
            $this->socket->read();
            $this->socket->close();
        } catch (SocketExecutionException $e) {
            $this->assertEquals('Read from socket failed.', $e->getMessage());
        }
    }
}
