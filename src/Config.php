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
    /** @var string filename */
    private const FILENAME = 'chanshige.yaml';

    /**
     * @param string $key
     * @return array|string|null
     */
    public static function load(string $key = '')
    {
        static $values = null;

        if (is_null($values)) {
            $values = Yaml::parseFile(CHANSHIGE_WHOIS_APP_DIR . self::FILENAME);
        }

        return empty($key) ? $values : $values[$key];
    }
}
