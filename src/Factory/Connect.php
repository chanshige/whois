<?php
namespace Chanshige\Factory;

use Connect\Socket;

/**
 * Class Connect
 *
 * @package Chanshige\Factory
 */
class Connect implements FactoryInterface
{
    /**
     * @return Socket
     */
    public function create()
    {
        return new Socket();
    }

    /**
     * toString.
     *
     * @return string
     */
    public function __toString()
    {
        return 'This Class is Connect Factory.';
    }
}
