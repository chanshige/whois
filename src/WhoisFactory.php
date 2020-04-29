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

namespace Chanshige;

use Aura\Di\Container;
use Aura\Di\ContainerBuilder;
use Aura\Di\Exception\SetterMethodNotFound;
use Chanshige\Di\AuraConfig;
use LogicException;

/**
 * Class WhoisFactory
 *
 * @package Chanshige
 */
final class WhoisFactory
{
    /**
     * @return object|WhoisInterface
     */
    public function build()
    {
        return $this->container()->newInstance(Whois::class);
    }

    /**
     * @return Container
     */
    private function container()
    {
        try {
            return (new ContainerBuilder())->newConfiguredInstance(
                [AuraConfig::class],
                ContainerBuilder::AUTO_RESOLVE
            );
        } catch (SetterMethodNotFound $e) {
            throw new LogicException($e->getMessage());
        }
    }
}
