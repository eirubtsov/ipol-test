<?php


namespace Ipol\Demo\Api\Entity\Response\Part\CreateOrder;

use Ipol\Demo\Api\Entity\AbstractEntity;
use Ipol\Demo\Api\Entity\Response\Part\AbstractResponsePart;


/**
 * Class Cargo
 * @package Ipol\Demo\Api\Entity\Response\Part
 */
class Cargo extends AbstractEntity
{
    use AbstractResponsePart;

    /**
     * @var string (uuid)
     */
    protected $cargoId;
    /**
     * @var string
     */
    protected $senderCargoId;
    /**
     * @var string
     */
    protected $barcode;

    /**
     * @return string
     */
    public function getCargoId()
    {
        return $this->cargoId;
    }

    /**
     * @param string $cargoId
     * @return Cargo
     */
    public function setCargoId($cargoId)
    {
        $this->cargoId = $cargoId;
        return $this;
    }

    /**
     * @return string
     */
    public function getSenderCargoId()
    {
        return $this->senderCargoId;
    }

    /**
     * @param string $senderCargoId
     * @return Cargo
     */
    public function setSenderCargoId($senderCargoId)
    {
        $this->senderCargoId = $senderCargoId;
        return $this;
    }

    /**
     * @return string
     */
    public function getBarcode()
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode
     * @return Cargo
     */
    public function setBarcode($barcode)
    {
        $this->barcode = $barcode;
        return $this;
    }

}