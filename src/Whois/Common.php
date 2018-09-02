<?php
declare(strict_types=1);

namespace Chanshige\Whois;

/**
 * Class Common
 *
 * @package Chanshige\Whois
 */
final class Common
{
    /**
     * @return array
     */
    public static function noRegistrationKeyWords(): array
    {
        return [
            'No match for',
            'NOT FOUND',
            'No Data Found',
            'has not been registered',
            'does not exist',
            'No match!!',
            'available for registration',
        ];
    }

    /**
     * @return array
     */
    public static function reservedKeyWords(): array
    {
        return [
            'reserved name',
            'Reserved Domain',
            'registry reserved',
            'has been reserved',
        ];
    }
}
