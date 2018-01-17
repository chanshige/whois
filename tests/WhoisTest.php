<?php
namespace Chanshige;

use Chanshige\Factory\Connect;
use Chanshige\Whois\ResponseBuilder\TemplateBuilder;

class WhoisTest extends CommonTestCase
{
    /** @var Whois */
    private $whois;

    protected function setUp()
    {
        parent::setUp();
        $this->whois = new Whois(new Connect(), new TemplateBuilder());
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->whois = null;
    }

    public function testQuery()
    {
        /** @var TemplateBuilder $response */
        $response = $this->whois->query(
            'jprs.jp',
            'whois.jprs.jp'
        );

        $this->assertEquals(
            '[ JPRS database provides information on network administration. Its use is    ]',
            $response()[0]
        );
    }

    public function testFailedQuery()
    {
        try {
            $this->whois->query('aaa.com', 'localhost');
        } catch (\Exception $e) {
            $this->assertEquals('[Connection refused] Connection to localhost failed.', $e->getMessage());
        }
    }
}
