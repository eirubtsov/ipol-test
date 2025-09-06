<?php


namespace Ipol\Demo\Api\Entity\Response\Part\CreateOrder;


use Ipol\Demo\Api\Entity\AbstractCollection;

class CargoList extends AbstractCollection
{
    protected $Cargoes;

    public function __construct()
    {
        parent::__construct('Cargoes');
        $this->setChildClass(Cargo::class);
    }

    /**
     * @return Cargo
     */
    public function getFirst(){
        return parent::getFirst();
    }

    /**
     * @return Cargo
     */
    public function getNext(){
        return parent::getNext();
    }
}