<?php

namespace Chanshige\Di;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;
use Chanshige\Constants\ResponseParserInterface;
use Chanshige\Handler\Socket;
use Chanshige\Handler\SocketInterface;
use Chanshige\Response;

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
        $di->types[ResponseParserInterface::class] = $di->lazyNew(Response::class);
    }
}
