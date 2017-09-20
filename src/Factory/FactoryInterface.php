<?php
namespace Chanshige\Factory;

/**
 * Interface FactoryInterface
 *
 * @package Chanshige\Factory
 */
interface FactoryInterface
{
    /**
     * Create Factory.
     * @return object
     */
    public function create();

    /**
     * @return string
     */
    public function __toString();
}
