<?php
namespace Ipol\Demo\Api\Entity\Response\Part\OrdersMake;

use Ipol\Demo\Api\Entity\AbstractCollection;

/**
 * Class ErrorList
 * @package Ipol\Demo\Api
 * @subpackage Response
 * @method Error getFirst
 * @method Error getNext
 * @method Error getLast
 */
class ErrorList extends AbstractCollection
{
    protected $Errors;

    public function __construct()
    {
        parent::__construct('Errors');
    }
}