<?php


namespace Ipol\Demo\Api\Entity\Response\Part\PickupPoint;


use Ipol\Demo\Api\Entity\AbstractCollection;

class ContentList extends AbstractCollection
{
    protected $Contents;

    public function __construct()
    {
        parent::__construct('Contents');
    }

    /**
     * @return Content
     */
    public function getFirst(){
        return parent::getFirst();
    }

    /**
     * @return Content
     */
    public function getNext(){
        return parent::getNext();
    }
}