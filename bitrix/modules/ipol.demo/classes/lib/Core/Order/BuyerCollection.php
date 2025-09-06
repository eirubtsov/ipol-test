<?php


namespace Ipol\Demo\Core\Order;


use Ipol\Demo\Core\Entity\Collection;

/**
 * Class BuyerCollection
 * @package Ipol\Demo\Core
 * @subpackage Order
 * @method false|Buyer getFirst
 * @method false|Buyer getNext
 * @method false|Buyer getLast
 */
class BuyerCollection extends Collection
{
    /**
     * @var array
     */
    protected $receivers;

    /**
     * BuyerCollection constructor.
     */
    public function __construct()
    {
        parent::__construct('buyers');
    }

}