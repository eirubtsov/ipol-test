<?php


namespace Ipol\Demo\Demo\Entity;


use Ipol\Demo\Core\Entity\BasicResponse;

/**
 * Class GenerateBarcodeResult
 * @package Ipol\Demo\Demo
 * @subpackage Entity
 * @method BasicResponse getResponse()
 */
class GenerateBarcodeResult extends BasicResponse
{
    /**
     * @var string
     */
    protected $barcode;
    /**
     * @var int
     */
    protected $increment;

    /**
     * @return string
     */
    public function getBarcode(): string
    {
        return $this->barcode;
    }

    /**
     * @param string $barcode
     * @return GenerateBarcodeResult
     */
    public function setBarcode(string $barcode): GenerateBarcodeResult
    {
        $this->barcode = $barcode;
        return $this;
    }

    /**
     * @return int
     */
    public function getIncrement(): int
    {
        return $this->increment;
    }

    /**
     * @param int $increment
     * @return GenerateBarcodeResult
     */
    public function setIncrement(int $increment): GenerateBarcodeResult
    {
        $this->increment = $increment;
        return $this;
    }

}