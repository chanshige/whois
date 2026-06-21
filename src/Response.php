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

use Chanshige\Contracts\ResponseParserInterface;
use Traversable;

/**
 * Class Response
 *
 * @package Chanshige
 */
class Response implements ResponseParserInterface
{
    private const string STATUS_PATTERN = '/^(.*)Status:/';
    private const string REGISTRANT_PATTERN = '/^Registrant/';
    private const string ADMIN_PATTERN = '/^Admin/';
    private const string BILLING_PATTERN = '/^Billing/';
    private const string TECH_PATTERN = '/^Tech/';
    private const string DATES_PATTERN = '/^(.*)Date:/';
    private const string NAMESERVER_PATTERN = '/^Name Server/';
    private const string CLIENT_HOLD_PATTERN = '/^(.*)?Status(:)?(\s+?)clientHold/i';
    private const string REGISTERED_PATTERN = '/No match for|NOT FOUND|No Data Found|has not been registered|does not exist|No match!!|available for registration|No entries found|^(.*?)Status(:)?(\s+?)free|^(.*?)Status(:)?(\s+?)AVAILABLE/i';
    private const string RESERVED_PATTERN = '/reserved name|Reserved Domain|registry reserved|has been reserved/i';

    private array $input = [];

    /** @var array<string, array> */
    private array $matches = [];

    private ?bool $registered = null;

    private ?bool $reserved = null;

    private ?bool $clientHold = null;

    private ?string $servername = null;

    /**
     * @param Traversable $input
     * @return ResponseParserInterface
     */
    public function __invoke(Traversable $input): ResponseParserInterface
    {
        $this->input = iterator_to_array($input);
        $this->resetCache();

        return $this;
    }

    /**
     * Whois server name.
     *
     * @return string
     */
    public function servername(): string
    {
        if ($this->servername !== null) {
            return $this->servername;
        }

        foreach ($this->input as $line) {
            if (preg_match('/^whois:\s*(\S+)/i', $line, $matches) === 1) {
                return $this->servername = strtolower($matches[1]);
            }

            if (preg_match('/Whois Server:\s*(\S+)/i', $line, $matches) === 1) {
                return $this->servername = strtolower($matches[1]);
            }
        }

        return $this->servername = '';
    }

    /**
     * Get status info.
     *
     * @return array
     */
    public function status(): array
    {
        return $this->grep(self::STATUS_PATTERN);
    }

    /**
     * Get registrant info.
     *
     * @return array
     */
    public function registrant(): array
    {
        return $this->grep(self::REGISTRANT_PATTERN);
    }

    /**
     * Get admin info.
     *
     * @return array
     */
    public function admin(): array
    {
        return $this->grep(self::ADMIN_PATTERN);
    }

    /**
     * Get billing info.
     *
     * @return array
     */
    public function billing(): array
    {
        return $this->grep(self::BILLING_PATTERN);
    }

    /**
     * Get tech info.
     *
     * @return array
     */
    public function tech(): array
    {
        return $this->grep(self::TECH_PATTERN);
    }

    /**
     * Get date info.
     * (Created, Updated, Expired)
     *
     * @return array
     */
    public function dates(): array
    {
        return $this->grep(self::DATES_PATTERN);
    }

    /**
     * Get name servers (DNS)
     *
     * @return array
     */
    public function nameserver(): array
    {
        return $this->grep(self::NAMESERVER_PATTERN);
    }

    /**
     * Is registered.
     *
     * @return bool
     */
    public function isRegistered(): bool
    {
        return $this->registered ??= count($this->grep(self::REGISTERED_PATTERN)) === 0;
    }

    /**
     * Is reserved.
     *
     * @return bool
     */
    public function isReserved(): bool
    {
        return $this->reserved ??= count($this->grep(self::RESERVED_PATTERN)) > 0;
    }

    /**
     * Is status client hold.
     *
     * @return bool
     */
    public function isClientHold(): bool
    {
        return $this->clientHold ??= count($this->grep(self::CLIENT_HOLD_PATTERN)) > 0;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->input;
    }

    private function grep(string $pattern): array
    {
        return $this->matches[$pattern] ??= preg_grep_values($pattern, $this->input);
    }

    private function resetCache(): void
    {
        $this->matches = [];
        $this->registered = null;
        $this->reserved = null;
        $this->clientHold = null;
        $this->servername = null;
    }
}
