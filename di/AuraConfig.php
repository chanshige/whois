<?php

namespace Chanshige\Di;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;
use Chanshige\Foundation\Handler\Socket;
use Chanshige\Foundation\Handler\SocketInterface;
use Chanshige\Foundation\ResponseParser;
use Chanshige\Foundation\ResponseParserInterface;

/**
 * Class Config
 *
 * @package Chanshige\Di
 */
class AuraConfig extends ContainerConfig
{
    /**
     * {@inheritDoc}
     */
    public function define(Container $di): void
    {
        $di->types[SocketInterface::class] = $di->lazyNew(Socket::class);
        $di->types[ResponseParserInterface::class] = $di->lazyNew(ResponseParser::class);
    }
}
