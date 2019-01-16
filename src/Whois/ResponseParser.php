<?php
namespace Chanshige\Whois;

/**
 * Class ResponseParser
 *
 * @package Chanshige\Whois
 */
final class ResponseParser implements ResponseParserInterface
{
    private $response;

    /**
     * ResponseParser constructor.
     *
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response;
    }

    /**
     * Whois server name.
     *
     * @return string
     */
    public function servername(): string
    {
        $pattern = ['/^whois:\s+/', '/(.*)Whois Server:\s+/i'];
        $servername = current(preg_filter($pattern, '', (array)$this->response));
        if ($servername === false) {
            return '';
        }

        return $servername;
    }

    /**
     * Get status info.
     *
     * @return array
     */
    public function status(): array
    {
        return preg_grep_values('/^(.*)Status:/', $this->response);
    }

    /**
     * Get registrant info.
     *
     * @return array
     */
    public function registrant(): array
    {
        return preg_grep_values('/^Registrant/', $this->response);
    }

    /**
     * Get admin info.
     *
     * @return array
     */
    public function admin(): array
    {
        return preg_grep_values('/^Admin/', $this->response);
    }

    /**
     * Get billing info.
     *
     * @return array
     */
    public function billing(): array
    {
        return preg_grep_values('/^Billing/', $this->response);
    }

    /**
     * Get tech info.
     *
     * @return array
     */
    public function tech(): array
    {
        return preg_grep_values('/^Tech/', $this->response);
    }

    /**
     * Get date info.
     * (Created, Updated, Expired)
     *
     * @return array
     */
    public function dates(): array
    {
        return preg_grep_values('/^(.*)Date:/', $this->response);
    }

    /**
     * Get name servers (DNS)
     *
     * @return array
     */
    public function nameserver(): array
    {
        return preg_grep_values('/^Name Server/', $this->response);
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
        ]);

        return count(preg_grep("/{$pattern}/mi", $this->response)) === 0;
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

        return count(preg_grep("/{$pattern}/mi", $this->response)) > 0;
    }

    /**
     * Is status client hold.
     *
     * @return bool
     */
    public function isClientHold(): bool
    {
        return count(preg_grep('/^(.*)Status(.*)clientHold/mi', $this->response)) > 0;
    }

    /**
     * @return array
     */
    public function getResponse(): array
    {
        return $this->response;
    }
}
