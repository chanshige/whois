<?php
namespace Chanshige\Foundation;

use Traversable;
use function preg_grep_values;

/**
 * Class ResponseParser
 *
 * @package Chanshige\Foundation
 */
final class ResponseParser implements ResponseParserInterface
{
    /** @var array */
    private $input;

    /**
     * @param iterable $input
     * @return ResponseParser
     */
    public function __invoke(iterable $input): ResponseParserInterface
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
        ]);

        return count(preg_grep("/{$pattern}/mi", $this->input)) === 0;
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
}
