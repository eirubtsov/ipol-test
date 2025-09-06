<?php


namespace Ipol\Demo\Api\Entity\Response\Part\PickupPoint;


use Ipol\Demo\Api\Entity\AbstractCollection;

class RateList extends AbstractCollection
{
    protected $Rates;

    public function __construct()
    {
        parent::__construct('Rates');
    }

    /**
     * @return Rate
     */
    public function getFirst(){
        return parent::getFirst();
    }

    /**
     * @return Rate
     */
    public function getNext(){
        return parent::getNext();
    }
}