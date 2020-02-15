<?php
/*
 * This file is part of the Chanshige\Whois package.
 *
 * (c) shigeki tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

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
