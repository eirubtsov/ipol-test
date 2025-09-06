<?php
namespace Ipol\Demo\Api\Entity\Request\Part\OrdersMake;

use Ipol\Demo\Api\Entity\AbstractCollection;

/**
 * Class PartnerOrderList
 * @package Ipol\Demo\Api
 * @subpackage Request
 * @method PartnerOrder getFirst
 * @method PartnerOrder getNext
 * @method PartnerOrder getLast
 */
class PartnerOrderList extends AbstractCollection
{
    protected $PartnerOrders;

    public function __construct()
    {
        parent::__construct('PartnerOrders');
    }
}