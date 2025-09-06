<?php
namespace Ipol\Demo\Api\Entity\Request\Part\OrdersMake;

use Ipol\Demo\Api\Entity\AbstractCollection;

/**
 * Class BarcodeList
 * @package Ipol\Demo\Api
 * @subpackage Request
 * @method Barcode getFirst
 * @method Barcode getNext
 * @method Barcode getLast
 */
class BarcodeList extends AbstractCollection
{
    protected $Barcodes;

    public function __construct()
    {
        parent::__construct('Barcodes');
    }
}