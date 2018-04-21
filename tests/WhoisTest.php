<?php
namespace Chanshige;

class WhoisTest extends CommonTestCase
{
    /** @var Whois */
    private $whois;

    protected function setUp()
    {
        parent::setUp();
        $this->whois = new Whois();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->whois = null;
    }

    public function testQueryJp()
    {
        $response = $this->whois->query(
            'jprs.jp',
            'whois.jprs.jp'
        );

        $this->assertEquals(
            '[ JPRS database provides information on network administration. Its use is    ]',
            $response->raw()[0]
        );
    }

    public function testQueryCom()
    {
        $expected = [
            'registered' => true,
            'reserved' => false,
            'client_hold' => false,
            'detail' => [
                'registrant' => [
                    16 => 'Registrant Name: NIC',
                    17 => 'Registrant Organization: NIC',
                    18 => 'Registrant Street: 637 WYCKOFF AVE # 294',
                    19 => 'Registrant City: WYCKOFF',
                    20 => 'Registrant State/Province: NJ',
                    21 => 'Registrant Postal Code: 07481-1438',
                    22 => 'Registrant Country: US',
                    23 => 'Registrant Phone: +1.2019341556',
                    24 => 'Registrant Phone Ext:',
                    25 => 'Registrant Fax: +1.2019341445',
                    26 => 'Registrant Fax Ext:',
                    27 => 'Registrant Email: dcs@NIC.COM'
                ],
                'admin' => [
                    29 => 'Admin Name: Semonche, Douglas',
                    30 => 'Admin Organization: Network Infiormation Center (NIC), LLC',
                    31 => 'Admin Street: 637 Wyckoff Avnue # 294',
                    32 => 'Admin City: Wyckoff',
                    33 => 'Admin State/Province: NJ',
                    34 => 'Admin Postal Code: 07481',
                    35 => 'Admin Country: US',
                    36 => 'Admin Phone: +1.2019341445',
                    37 => 'Admin Phone Ext:',
                    38 => 'Admin Fax: +1.2019341556',
                    39 => 'Admin Fax Ext:',
                    40 => 'Admin Email: dcs@NIC.COM',
                ],
                'tech' => [
                    42 => 'Tech Name: Semonche, Douglas',
                    43 => 'Tech Organization: Network Infiormation Center (NIC), LLC',
                    44 => 'Tech Street: 637 Wyckoff Avnue # 294',
                    45 => 'Tech City: Wyckoff',
                    46 => 'Tech State/Province: NJ',
                    47 => 'Tech Postal Code: 07481',
                    48 => 'Tech Country: US',
                    49 => 'Tech Phone: +1.2019341445',
                    50 => 'Tech Phone Ext:',
                    51 => 'Tech Fax: +1.2019341556',
                    52 => 'Tech Fax Ext:',
                    53 => 'Tech Email: dcs@NIC.COM',
                ],
                'billing' => [],
                'status' => [
                    14 => 'Domain Status: clientTransferProhibited https://icann.org/epp#clientTransferProhibited'
                ],
                'date' => [
                    6 => 'Updated Date: 2018-03-02T17:00:22Z',
                    7 => 'Creation Date: 1994-02-07T05:00:00Z',
                    8 => 'Registrar Registration Expiration Date: 2028-02-08T05:00:00Z',
                ],
                'name_server' => [
                    54 => 'Name Server: BACKUP.NIC.COM',
                    55 => 'Name Server: SUE.NIC.COM'
                ]
            ]
        ];

        $this->assertEquals(
            $expected,
            $this->whois->query('nic.com', 'whois.internic.net')->result()
        );
    }

    public function testQueryFun()
    {
        $expected = [
            'registered' => true,
            'reserved' => true,
            'client_hold' => false,
            'detail' => [
                'registrant' => [],
                'admin' => [],
                'tech' => [],
                'billing' => [],
                'status' => [],
                'date' => [],
                'name_server' => []
            ]
        ];

        $this->assertEquals($expected, $this->whois->query('domains.fun')->result());
    }

    public function testFailedQueryByIana()
    {
        try {
            $this->whois->query('domains.gmo')->raw();
        } catch (\Exception $e) {
            $this->assertEquals('Failed to find whois server from iana database.', $e->getMessage());
        }
    }

    public function testFailedQueryByRegistrar()
    {
        try {
            $this->whois->query('nic.kyoto')->raw();
        } catch (\Exception $e) {
            $this->assertEquals('Failed to find whois server from registrar database.', $e->getMessage());
        }
    }

    public function testFailedQuery()
    {
        try {
            $this->whois->query('aaa.com', 'VERISIGN-GRS.COM');
        } catch (\Exception $e) {
            $this->assertEquals('Failed to open socket connection.', $e->getMessage());
        }
    }
}
