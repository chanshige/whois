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

use Chanshige\Contracts\WhoisInterface;
use Chanshige\Handler\Socket;

/**
 * Class WhoisFactory
 *
 * @package Chanshige
 */
final class WhoisFactory
{
    /**
     * Return a whois library object.
     *
     * @return WhoisInterface
     */
    public function newInstance()
    {
        return new Whois(new Socket(), new Response());
    }

    /**
     * @return WhoisInterface
     * @deprecated
     */
    public function build()
    {
        return $this->newInstance();
    }
}
