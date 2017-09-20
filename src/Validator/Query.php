<?php
namespace Chanshige\Validator;

use Respect\Validation\Validator as v;

/**
 * Class Query
 *
 * @package Chanshige\Validator
 */
class Query
{
    /**
     * @param string $domain domain name
     * @param string $server whois server
     * @return bool
     */
    public static function validate($domain, $server)
    {
        return !v::alnum('.-')->noWhitespace()->validate($domain) ||
            !v::domain()->validate($server);
    }
}
