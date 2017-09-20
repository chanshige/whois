<?php
namespace Chanshige;

use Chanshige\Factory\Connect;

class WhoisTest extends CommonTestCase
{
    /** @var Whois */
    private $whois;

    protected function setUp()
    {
        parent::setUp();
        $this->whois = new Whois(new Connect());
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->whois = null;
    }

    public function testQuery()
    {
        $response = $this->whois->query(
            'jprs.jp',
            'whois.jprs.jp'
        );

        $this->assertEquals(
            '[ JPRS database provides information on network administration. Its use is    ]',
            $response[0]
        );
    }

    public function testFailedQuery()
    {
        try {
            $this->whois->query('aaa.com', 'localhost');
        } catch (\Exception $e) {
            $this->assertEquals('Invalid input.', $e->getMessage());
        }
    }

    public function testFailedConnection()
    {
        try {
            $this->whois->query('aaa.com', 'whois.icann.org');
        } catch (\Exception $e) {
            $this->assertEquals('[Network is unreachable] Connection to whois.icann.org failed.', $e->getMessage());
        }
    }
}
