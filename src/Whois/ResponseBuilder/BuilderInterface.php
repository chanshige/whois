<?php
namespace Chanshige\Whois\ResponseBuilder;

/**
 * Interface BuilderInterface
 *
 * @package Chanshige\Whois\ResponseBuilder
 */
interface BuilderInterface
{
    /**
     * @param array $data
     * @return $this
     */
    public function build($data);
}
