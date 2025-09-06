<?php
namespace Ipol\Demo\Demo\Handler;

use Exception;
use Ipol\Demo\Demo\Entity\OptionsInterface;
use Ipol\Demo\Demo\Entity\GenerateBarcodeResult;

/**
 * Class Tools
 * @package Ipol\Demo\Demo\Handler
 */
class Tools
{
    /**
     * @param int $uniqueOmniId
     * @param int $uniqueItemCode
     * @return string always 14 unit long can be converted to int
     * @throws Exception
     */
    public static function barcodeGlueSubLogic(int $uniqueOmniId, int $uniqueItemCode)
    {
        if($uniqueOmniId < 0)
            throw new Exception('barcodeGenerate failed! Invalid uniqueOmniId: '. $uniqueOmniId);
        if((strlen($uniqueOmniId) > 4))
            throw new Exception('barcodeGenerate failed! Unacceptable uniqueOmniId (longer then 4 units): '. $uniqueOmniId);
        if($uniqueOmniId < 0)
            throw new Exception('barcodeGenerate failed! Invalid uniqueItemCode: '. $uniqueItemCode);
        if((strlen($uniqueItemCode) > 9))
            throw new Exception('barcodeGenerate failed! Unacceptable uniqueItemCode (longer then 10 units) : '. $uniqueItemCode);

        return str_pad($uniqueOmniId, 4, "0", STR_PAD_LEFT) . str_pad($uniqueItemCode, 10, "0", STR_PAD_LEFT);
    }

    /**
     * @param OptionsInterface $options
     * @param int|null $uniqueItemCode
     * @return GenerateBarcodeResult
     * @throws Exception
     */
    public static function barcodeGenerate(OptionsInterface $options, $uniqueItemCode = null): GenerateBarcodeResult
    {
        $counter = isset($uniqueItemCode) ? (int)$uniqueItemCode : (int)$options::fetchOption('barkCounter');
        $objBarcodeResult = new GenerateBarcodeResult();
        $objBarcodeResult->setBarcode(self::barcodeGlueSubLogic((int)$options::fetchOption('barkID'), $counter))
        ->setIncrement((strlen($counter) > 10)? 0 : ++$counter)
        ->setSuccess(true);

        return $objBarcodeResult;
    }
}