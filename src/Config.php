<?php
namespace Chanshige;

use Symfony\Component\Yaml\Yaml;

/**
 * Class Config
 *
 * @package Chanshige
 */
final class Config
{
    /**
     * @param string $key
     * @return array|string|null
     */
    public static function get($key = '')
    {
        static $values = null;

        if (is_null($values)) {
            $values = Yaml::parseFile(CHANSHIGE_WHOIS_APP_VAR_DIR . 'common.yaml');
        }

        return empty($key) ? $values : $values[$key];
    }

    /**
     * @param string $key
     * @return array|string|null
     */
    public static function serverList($key = '')
    {
        static $values = null;

        if (is_null($values)) {
            $values = Yaml::parseFile(CHANSHIGE_WHOIS_APP_VAR_DIR . 'server_list.yaml');
        }

        return empty($key) ? $values : $values[$key];
    }
}
