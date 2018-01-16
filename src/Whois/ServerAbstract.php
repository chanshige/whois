<?php
namespace Chanshige\Whois;

/**
 * Class ServerAbstract
 *
 * @package Chanshige\Whois
 */
abstract class ServerAbstract
{
    /** @var array */
    static $servers;

    /**
     * @param $tld
     * @return string
     */
    public static function get($tld)
    {
        return self::exists($tld) ? static::$servers[$tld] : '';
    }

    /**
     * @return array
     */
    public static function getAll()
    {
        return static::$servers;
    }

    /**
     * @param $tld
     * @return bool
     */
    public static function exists($tld)
    {
        return isset(static::$servers[$tld]);
    }
}
