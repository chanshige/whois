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
use Chanshige\Contracts\WhoisClientInterface;
use Chanshige\Handler\SocketInterface;

final readonly class WhoisClient implements WhoisClientInterface
{
    public function __construct(
        private SocketInterface $socket,
        private ResponseFactoryInterface $responseFactory
    ) {
    }

    public function query(string $servername, string $query): ResponseParserInterface
    {
        $request = ($this->socket)($servername, $query);
        $response = $this->responseFactory->create();

        try {
            return $response($request->read());
        } finally {
            $request->close();
        }
    }
}
