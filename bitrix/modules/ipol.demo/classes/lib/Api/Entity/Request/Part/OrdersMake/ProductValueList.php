<?php
namespace Ipol\Demo\Api\Entity\Request\Part\OrdersMake;

use Ipol\Demo\Api\Entity\AbstractCollection;

/**
 * Class ProductValueList
 * @package Ipol\Demo\Api
 * @subpackage Request
 * @method ProductValue getFirst
 * @method ProductValue getNext
 * @method ProductValue getLast
 */
class ProductValueList extends AbstractCollection
{
    protected $ProductValues;

    public function __construct()
    {
        parent::__construct('ProductValues');
    }
}