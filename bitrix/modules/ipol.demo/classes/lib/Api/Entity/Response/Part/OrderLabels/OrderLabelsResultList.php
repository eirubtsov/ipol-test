<?php
namespace Ipol\Demo\Api\Entity\Response\Part\OrderLabels;

use Ipol\Demo\Api\Entity\AbstractCollection;

/**
 * Class OrderLabelsResultList
 * @package Ipol\Demo\Api
 * @subpackage Entity\Response
 * @method OrderLabelsResult getFirst
 * @method OrderLabelsResult getNext
 * @method OrderLabelsResult getLast
 */
class OrderLabelsResultList extends AbstractCollection
{
    protected $OrderLabelsResults;

    public function __construct()
    {
        parent::__construct('OrderLabelsResults');
    }
}