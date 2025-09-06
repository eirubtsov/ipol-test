<?php


namespace Ipol\Demo\Demo\Controller;


use Ipol\Demo\Api\Entity\Request\CancelOrderById;
use Ipol\Demo\Demo\Entity\CancelOrderByUuidResult;

/**
 * Class CancelOrderByUuid
 * @package Ipol\Demo\Demo
 * @subpackage Controller
 */
class CancelOrderByUuid extends AutomatedCommonRequest
{
    /**
     * CancelOrderByUuid constructor.
     * @param string $uuid
     */
    public function __construct(string $uuid)
    {
        parent::__construct(new CancelOrderByUuidResult());
        $this->setRequestObj(new CancelOrderById($uuid));

    }
}