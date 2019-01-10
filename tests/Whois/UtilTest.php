<?php
namespace Chanshige\Whois;

use Chanshige\CommonTestCase;

/**
 * Class UtilTest
 *
 * @package Chanshige\Whois
 */
class UtilTest extends CommonTestCase
{
    public function testExtractWhoisServer()
    {
        $data = [
            'Registrar Whois Server: test.whois.server.host'
        ];
        $this->assertSame('test.whois.server.host', Util::extractWhoisServerName($data));
    }

    public function testExtractWhoisServerFailed()
    {
        $data = [
            ' Server: failed.server.host'
        ];
        $this->assertSame('', Util::extractWhoisServerName($data));
    }

    public function testExtractWhois()
    {
        $data = [
            'whois: iana.whois.server.host'
        ];
        $this->assertSame('iana.whois.server.host', Util::extractWhoisName($data));
    }
}
