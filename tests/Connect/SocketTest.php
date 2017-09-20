<?php
namespace Connect;

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
            ->open('whois.nic.tokyo')
            ->puts('nic.tokyo')
            ->read();

        $this->assertTrue(is_array($response));
        $this->assertEquals("Domain Name: NIC.TOKYO", $response[0]);
        $this->assertTrue($this->socket->close());
    }

    public function testOpenFailed()
    {
        try {
            $this->socket->open('localhost');
        } catch (SocketExecutionException $e) {
            $this->assertEquals('[Connection refused] Connection to localhost failed.', $e->getMessage());
        }
    }

    public function testPutsFailed()
    {
        try {
            $this->socket->open('whois.nic.tokyo')->close();
            $this->socket->puts('nic.tokyo');
        } catch (SocketExecutionException $e) {
            $this->assertEquals('Cannot write to nic.tokyo.', $e->getMessage());
        }
    }
}
