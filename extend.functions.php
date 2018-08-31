<?php
declare(strict_types=1);
/*
 * This file is part of the Chanshige\Whois package.
 *
 * (c) shigeki tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Get Tld From DomainName.
 *
 * @param string $domain
 * @return string
 */
function get_tld($domain): string
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
function convert_idn_ascii(string $value): string
{
    return idn_to_ascii($value, 0, INTL_IDNA_VARIANT_UTS46);
}

/**
 * Convert domain name from IDNA ASCII to Unicode.
 *
 * @param string $value
 * @return string
 */
function convert_Idn_unicode(string $value): string
{
    return idn_to_utf8($value, 0, INTL_IDNA_VARIANT_UTS46);
}

/**
 * Return array entries that match the pattern
 *
 * @param string $pattern Pattern to search for, as a string.
 * @param array  $input
 * @param int    $flags
 * @return array
 */
function preg_grep_values(string $pattern, array $input, int $flags = 0): array
{
    return array_values(preg_grep($pattern, $input, $flags));
}
