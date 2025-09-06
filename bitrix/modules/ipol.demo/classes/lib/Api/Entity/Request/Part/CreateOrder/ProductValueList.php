<?php


namespace Ipol\Demo\Api\Entity\Request\Part\CreateOrder;


use Ipol\Demo\Api\Entity\AbstractCollection;

class ProductValueList extends AbstractCollection
{
    protected $ProductValues;

    public function __construct()
    {
        parent::__construct('ProductValues');
    }

    /**
     * @return ProductValue
     */
    public function getFirst(){
        return parent::getFirst();
    }

    /**
     * @return ProductValue
     */
    public function getNext(){
        return parent::getNext();
    }
}