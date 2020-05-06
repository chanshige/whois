<?php
/*
 * This file is part of the Chanshige\Whois package.
 *
 * (c) shigeki tanaka <dev@shigeki.tokyo>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace Chanshige\Aura;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;
use Chanshige\Constants\ResponseParserInterface;
use Chanshige\Handler\Socket;
use Chanshige\Handler\SocketInterface;
use Chanshige\Response;

/**
 * Class Config
 *
 * @package Chanshige\Aura
 */
class Config extends ContainerConfig
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
