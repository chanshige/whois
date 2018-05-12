<?php
/*
 * This file is part of the Chanshige\Whois package.
 *
 * (c) shigeki tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/** @const BASE_DIR */
const CHANSHIGE_WHOIS_APP_DIR = __DIR__ . '/';

/**
 * Extract Tld From DomainName.
 *
 * @param string $domain
 * @return string
 */
function tld($domain): string
{
    $res = explode('.', $domain, 2);

    return (string)array_pop($res);
}

/**
 *  Convert domain name to IDNA ASCII form.
 * (Internationalized Domain Name)
 *
 * @param string $value
 * @return string
 */
function convertIdnAscii(string $value): string
{
    return idn_to_ascii($value, 0, INTL_IDNA_VARIANT_UTS46);
}

/**
 * Convert domain name from IDNA ASCII to Unicode.
 *
 * @param string $value
 * @return string
 */
function convertIdnUnicode(string $value): string
{
    return idn_to_utf8($value, 0, INTL_IDNA_VARIANT_UTS46);
}

/**
 * Return array entries that match the pattern
 *
 * @param string $pattern
 * @param array  $input
 * @param int    $flags
 * @return array
 */
function preg_grep_values($pattern, array $input, $flags = 0)
{
    return array_values(preg_grep($pattern, $input, $flags));
}
