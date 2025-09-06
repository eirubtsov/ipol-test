<?php
namespace Ipol\Demo\Bitrix\Adapter;

use \Ipol\Demo\Bitrix\Tools;
use \Ipol\Demo\Bitrix\Entity\DefaultGabarites;
use \Ipol\Demo\Bitrix\Entity\Options;
use \Ipol\Demo\Core\Delivery\CargoItem;
use \Ipol\Demo\Core\Delivery\Cargo as BaseCargo;
use \Ipol\Demo\Core\Entity\Money;
use \Ipol\Demo\Core\Entity\Packing\MebiysDimMerger;

/**
 * Class Cargo
 * Generates Core Cargo from Bitrix items
 * @package namespace Ipol\Demo\Bitrix\Adapter
 */
class Cargo
{
    /**
     * @var
     * array of items formed in Bitrix
     */
    protected $items;

    /**
     * @var \Ipol\Demo\Core\Delivery\Cargo
     * The base cargo object
     */
    protected $cargo;

    protected $defaultGabarites;

    public function __construct(DefaultGabarites $defaultGabarites = null)
    {
        if (!is_null($defaultGabarites))
            $this->defaultGabarites = $defaultGabarites;
    }

    public function set($arItems)
    {
        $this->setItems($arItems);
        $this->formCargo();
        return $this;
    }

    /**
     * Generates Base Cargo from given Bitrix items
     * @throws \Exception
     */
    protected function formCargo()
    {
        if (empty($this->items))
        {
            throw new \Exception('No items to convert in '.get_class());
        }

        $options     = new Options;
        $this->cargo = new BaseCargo();

        $arItems = $this->reformItems();

        foreach ($arItems as $item)
        {
            $obCargoItem = new CargoItem();
            $this->cargo->add(
                $obCargoItem
                    ->setGabs(
                        (int)$item['DIMENSIONS']['LENGTH'],
                        (int)$item['DIMENSIONS']['WIDTH'],
                        (int)$item['DIMENSIONS']['HEIGHT']
                    )
                    ->setWeight((int)$item['WEIGHT'])
                    ->setQuantity(((int)$item['QUANTITY']) ?: 1)
                    // Set cargo item cost to 0 if insurance not required
                    ->setCost(new Money(($options->fetchNoInsurance() === 'Y') ? 0 : $item['PRICE']))
                    ->setPrice(new Money($item['PRICE']))
            );
        }
    }

    protected function reformItems()
    {
        if (!empty($this->defaultGabarites))
        {
            $_arItems = $this->getItems();
            if ($this->defaultGabarites->getMode() === 'O') // Default gabarites for all order
            {
                // Array for \Ipol\Demo\Core\Entity\Packing::getSumDimensions()
                $arDimensions = array();
                $weight       = 0;
                $hasEmpty     = false;
                $ttlPrice     = 0;

                foreach ($_arItems as $key => $item)
                {
                    // Non-pieces measure types crutch
                    $quantity = ((int)$item['QUANTITY']) ?: 1;

                    if((int)$item['WEIGHT'] > 0)
                        $weight += $item['WEIGHT'] * $quantity;
                    else
                        $hasEmpty = true;

                    if(
                        $item['DIMENSIONS']['LENGTH'] && (int)$item['DIMENSIONS']['LENGTH'] > 0 &&
                        $item['DIMENSIONS']['WIDTH'] && (int)$item['DIMENSIONS']['WIDTH'] > 0 &&
                        $item['DIMENSIONS']['HEIGHT'] && (int)$item['DIMENSIONS']['HEIGHT'] > 0
                    )
                        $arDimensions []= array($item['DIMENSIONS']['LENGTH'],$item['DIMENSIONS']['WIDTH'],$item['DIMENSIONS']['HEIGHT'],$quantity);
                    else
                        $hasEmpty = true;

                    $ttlPrice += $item['PRICE'] * $item['QUANTITY'];
                }

                if ($hasEmpty)
                {
                    $packer = new MebiysDimMerger();
                    $arDimensions = $packer::getSumDimensions($arDimensions);

                    $_arItems = array(
                        Tools::makeSimpleGood(array(
                            'WEIGHT'  => max($weight,$this->defaultGabarites->getWeight()),
                            'LENGTH'  => max($this->defaultGabarites->getLength(),$arDimensions['L']),
                            'WIDTH'   => max($this->defaultGabarites->getWidth(), $arDimensions['W']),
                            'HEIGHT'  => max($this->defaultGabarites->getHeight(),$arDimensions['H']),
                            'PRICE'   => $ttlPrice,
                        ))
                    );
                }
            }
            else // Default gabarites for each good
            {
                foreach($_arItems as $key => $item)
                {
                    if(!(int)$item['WEIGHT'])
                        $_arItems[$key]['WEIGHT'] = $this->defaultGabarites->getWeight();
                    if(!(int)$item['DIMENSIONS']['LENGTH'])
                        $_arItems[$key]['DIMENSIONS']['LENGTH'] = $this->defaultGabarites->getLength();
                    if(!(int)$item['DIMENSIONS']['WIDTH'])
                        $_arItems[$key]['DIMENSIONS']['WIDTH'] = $this->defaultGabarites->getWidth();
                    if(!(int)$item['DIMENSIONS']['HEIGHT'])
                        $_arItems[$key]['DIMENSIONS']['HEIGHT'] = $this->defaultGabarites->getHeight();
                }
            }
            $arItems = $_arItems;
        }
        else
            $arItems = $this->getItems();

        return $arItems;
    }

    /**
     * @return mixed
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param mixed $items
     * @return $this
     */
    public function setItems($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * @return \Ipol\Demo\Core\Delivery\Cargo
     */
    public function getCargo()
    {
        if (empty($this->cargo))
            $this->formCargo();

        return $this->cargo;
    }

    /**
     * @param mixed $cargo
     */
    protected function setCargo($cargo)
    {
        $this->cargo = $cargo;
    }
}