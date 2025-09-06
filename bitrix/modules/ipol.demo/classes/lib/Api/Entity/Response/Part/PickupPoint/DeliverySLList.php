<?php


namespace Ipol\Demo\Api\Entity\Response\Part\PickupPoint;


use Ipol\Demo\Api\Entity\AbstractCollection;

class DeliverySLList extends AbstractCollection
{
    protected $DeliverySLs;

    public function __construct()
    {
        parent::__construct('DeliverySLs');
    }

    /**
     * @return DeliverySL
     */
    public function getFirst(){
        return parent::getFirst();
    }

    /**
     * @return DeliverySL
     */
    public function getNext(){
        return parent::getNext();
    }
}