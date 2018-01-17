<?php
namespace Chanshige\Whois\ResponseBuilder;

/**
 * Class TemplateBuilder
 *
 * @method array registrant();
 *
 * @package Chanshige\Whois\ResponseBuilder
 */
class TemplateBuilder implements BuilderInterface
{
    /** @var array */
    private $data = [];

    /**
     * Set Build it ArrayData.
     *
     * @param $data
     * @return $this
     */
    public function build($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Row Data.
     *
     * @return array
     */
    public function __invoke()
    {
        return $this->data;
    }
}
