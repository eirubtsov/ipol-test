<?php


namespace Ipol\Demo\Demo\Controller;


use Ipol\Demo\Demo\Entity\CancelOrderByNumberResult;
use Ipol\Demo\Api\Entity\Request\CancelOrderByNumber as CancelRequest;

/**
 * Class CancelOrderByNumber
 * @package Ipol\Demo\Demo
 * @subpackage Controller
 */
class CancelOrderByNumber extends AutomatedCommonRequest
{
    /**
     * CancelOrderByNumber constructor.
     * @param string $number
     */
    public function __construct(string $number)
    {
        $this->setRequestObj(new CancelRequest($number));
        parent::__construct(new CancelOrderByNumberResult());
    }
}