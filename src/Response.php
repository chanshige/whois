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
    /** @var array */
    private $input = [];

    /**
     * @param Traversable $input
     * @return ResponseParserInterface
     */
    public function __invoke(Traversable $input): ResponseParserInterface
    {
        $this->input = $input instanceof Traversable ?
            iterator_to_array($input) : $input;

        return $this;
    }

    /**
     * Whois server name.
     *
     * @return string
     */
    public function servername(): string
    {
        $pattern = ['/^whois:\s+/', '/(.*)Whois Server:\s+/i'];
        $servername = current(preg_filter($pattern, '', $this->input));
        if ($servername === false) {
            return '';
        }

        return strtolower(trim($servername));
    }

    /**
     * Get status info.
     *
     * @return array
     */
    public function status(): array
    {
        return preg_grep_values('/^(.*)Status:/', $this->input);
    }

    /**
     * Get registrant info.
     *
     * @return array
     */
    public function registrant(): array
    {
        return preg_grep_values('/^Registrant/', $this->input);
    }

    /**
     * Get admin info.
     *
     * @return array
     */
    public function admin(): array
    {
        return preg_grep_values('/^Admin/', $this->input);
    }

    /**
     * Get billing info.
     *
     * @return array
     */
    public function billing(): array
    {
        return preg_grep_values('/^Billing/', $this->input);
    }

    /**
     * Get tech info.
     *
     * @return array
     */
    public function tech(): array
    {
        return preg_grep_values('/^Tech/', $this->input);
    }

    /**
     * Get date info.
     * (Created, Updated, Expired)
     *
     * @return array
     */
    public function dates(): array
    {
        return preg_grep_values('/^(.*)Date:/', $this->input);
    }

    /**
     * Get name servers (DNS)
     *
     * @return array
     */
    public function nameserver(): array
    {
        return preg_grep_values('/^Name Server/', $this->input);
    }

    /**
     * Is registered.
     *
     * @return bool
     */
    public function isRegistered(): bool
    {
        $pattern = implode("|", [
            'No match for',
            'NOT FOUND',
            'No Data Found',
            'has not been registered',
            'does not exist',
            'No match!!',
            'available for registration',
            'No entries found',
            '^(.*?)Status(:)?(\s+?)free',
            '^(.*?)Status(:)?(\s+?)AVAILABLE',
        ]);

        return count(preg_grep("/{$pattern}/i", $this->input)) === 0;
    }

    /**
     * Is reserved.
     *
     * @return bool
     */
    public function isReserved(): bool
    {
        $pattern = implode("|", [
            'reserved name',
            'Reserved Domain',
            'registry reserved',
            'has been reserved',
        ]);

        return count(preg_grep("/{$pattern}/mi", $this->input)) > 0;
    }

    /**
     * Is status client hold.
     *
     * @return bool
     */
    public function isClientHold(): bool
    {
        return count(preg_grep('/^(.*)Status(.*)clientHold/mi', $this->input)) > 0;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        return $this->input;
    }

    /**
     * {@inheritDoc}
     */
    public function __clone()
    {
        // initialize
        $this->input = [];
    }
}
