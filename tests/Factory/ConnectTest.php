<?php
namespace Chanshige\Factory;

use Chanshige\CommonTestCase;

class ConnectTest extends CommonTestCase
{
    /** @var Connect */
    private $factory;

    protected function setUp()
    {
        parent::setUp();
        $this->factory = new Connect();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->factory = null;
    }

    public function testCreate()
    {
        $this->assertInstanceOf('Connect\Socket', $this->factory->create());
    }

    public function testToString()
    {
        $this->assertEquals('This Class is Connect Factory.', (string)$this->factory);
    }
}