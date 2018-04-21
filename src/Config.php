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
    public static function load($key = '')
    {
        static $values = null;

        if (is_null($values)) {
            $values = Yaml::parseFile(CHANSHIGE_WHOIS_APP_DIR . 'chanshige.yaml');
        }

        return empty($key) ? $values : $values[$key];
    }
}
