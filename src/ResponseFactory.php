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

use Chanshige\Contracts\ResponseFactoryInterface;
use Chanshige\Contracts\ResponseParserInterface;

final class ResponseFactory implements ResponseFactoryInterface
{
    public function create(): ResponseParserInterface
    {
        return new Response();
    }
}
