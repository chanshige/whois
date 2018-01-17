<?php
namespace Chanshige\Whois\ResponseBuilder;

use Chanshige\CommonTestCase;

class TemplateBuilderTest extends CommonTestCase
{
    /** @var TemplateBuilder */
    private $responseBuilder;

    public function setUp()
    {
        parent::setUp();
        $this->responseBuilder = new TemplateBuilder();
    }

    public function tearDown()
    {
        parent::tearDown();
    }

    public function testTemplateBuild()
    {
        $array = ['Test Array Test'];
        $objBuilder = $this->responseBuilder->build($array);

        $this->assertInstanceOf('Chanshige\Whois\ResponseBuilder\TemplateBuilder', $objBuilder);
        $this->assertEquals($array, $objBuilder());
    }
}
