<?php
namespace Chanshige\Whois;

/**
 * Class Util
 *
 * @package Chanshige\Whois
 */
final class Util
{
    /**
     * @param array $data
     * @return string
     */
    public static function extractWhoisServerName(array $data): string
    {
        $servername = current(preg_filter('/(.*)Whois Server:\s+/i', '', $data));
        if ($servername === false) {
            return '';
        }

        return $servername;
    }

    /**
     * @param array $data
     * @return string
     */
    public static function extractWhoisName(array $data): string
    {
        $servername = current((array)preg_filter('/^whois:\s+/', '', $data));
        if ($servername === false) {
            return '';
        }

        return $servername;
    }
}
