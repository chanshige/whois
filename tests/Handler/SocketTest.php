<?php
namespace Chanshige\Handler;

use Chanshige\CommonTestCase;

class SocketTest extends CommonTestCase
{
    /** @var Socket */
    private $socket;

    protected function setUp()
    {
        parent::setUp();
        $this->socket = new Socket();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @throws \Chanshige\Exception\SocketExecutionException
     */
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

    /**
     * @expectedException \Chanshige\Exception\SocketExecutionException
     * @expectedExceptionMessage Failed to open socket connection.
     */
    public function testOpenFailed()
    {
        $this->socket->open('localhost');
    }

    /**
     * @expectedException \Chanshige\Exception\SocketExecutionException
     * @expectedExceptionMessage Write to socket failed.
     */
    public function testPutsFailed()
    {
        $this->socket->open('whois.verisign-grs.com')->close();
        $this->socket->puts('verisign-grs.com');
    }

    /**
     * @expectedException \Chanshige\Exception\SocketExecutionException
     * @expectedExceptionMessage Read from socket failed.
     */
    public function testReadFailed()
    {
        $this->socket->open('whois.nic.tokyo');
        $this->socket->read();
        $this->socket->close();
    }
}
