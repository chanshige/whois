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

use Chanshige\Collection\CcTLD;
use Chanshige\Contracts\DomainNormalizerInterface;
use Chanshige\Contracts\ResponseFactoryInterface;
use Chanshige\Contracts\ResponseParserInterface;
use Chanshige\Contracts\ServerResolverInterface;
use Chanshige\Contracts\WhoisClientInterface;
use Chanshige\Contracts\WhoisInterface;
use Chanshige\Contracts\WhoisRequestFormatterInterface;

/**
 * Class Whois
 *
 * @package Chanshige
 */
final class Whois implements WhoisInterface
{
    private const int MAX_REQUESTS = 6;

    private WhoisClientInterface $client;

    private ServerResolverInterface $serverResolver;

    private DomainNormalizerInterface $domainNormalizer;

    private WhoisRequestFormatterInterface $requestFormatter;

    private ResponseParserInterface $response;

    public function __construct(
        WhoisClientInterface $client,
        ServerResolverInterface $serverResolver,
        DomainNormalizerInterface $domainNormalizer,
        WhoisRequestFormatterInterface $requestFormatter,
        ResponseFactoryInterface $responseFactory
    ) {
        $this->client = $client;
        $this->serverResolver = $serverResolver;
        $this->domainNormalizer = $domainNormalizer;
        $this->requestFormatter = $requestFormatter;
        $this->response = $responseFactory->create();
    }

    /**
     * {@inheritdoc}
     */
    public function query(string $domain, ?string $servername = null): WhoisInterface
    {
        $domainName = $this->domainNormalizer->normalize($domain);
        $currentServer = $servername ?: $this->serverResolver->resolve($domainName->tld());
        $visitedServers = [];
        $requestCount = 0;
        $query = $this->requestFormatter->format($domainName);

        while ($requestCount < self::MAX_REQUESTS) {
            $requestCount++;
            $visitedServers[$currentServer] = true;
            $this->response = $this->client->query($currentServer, $query);

            $nextServer = $this->resolveReferralServer($this->response, $domainName->tld(), $visitedServers);
            if ($nextServer === null) {
                return $this;
            }

            $currentServer = $nextServer;
        }

        return $this;
    }

    /**
     * @param ResponseParserInterface $response
     * @param string                  $tld
     * @param array<string, true>     $visitedServers
     */
    private function resolveReferralServer(
        ResponseParserInterface $response,
        string $tld,
        array $visitedServers
    ): ?string {
        if (!$response->isRegistered() || CcTLD::is($tld)) {
            return null;
        }

        $nextServer = $response->servername();
        if ($nextServer === '' || isset($visitedServers[$nextServer])) {
            return null;
        }

        return $nextServer;
    }

    /**
     * {@inheritDoc}
     */
    public function response(): ResponseParserInterface
    {
        return $this->response;
    }
}
