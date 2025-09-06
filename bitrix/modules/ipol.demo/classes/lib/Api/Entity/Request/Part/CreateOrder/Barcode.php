<?php


namespace Ipol\Demo\Api\Entity\Request\Part\CreateOrder;


use Ipol\Demo\Api\Entity\AbstractEntity;

/**
 * Class Barcode
 * @package Ipol\Demo\Api
 * @subpackage Response
 */
class Barcode extends AbstractEntity
{
    /**
     * @var string
     */
    protected $value;

    /**
     * @param string $value
     */
    public function __construct(string $value)
    {
        parent::__construct();
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
}