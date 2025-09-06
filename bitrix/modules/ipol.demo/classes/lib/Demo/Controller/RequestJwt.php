<?php


namespace Ipol\Demo\Demo\Controller;


use Ipol\Demo\Api\Entity\Request\JwtGenerate;
use Ipol\Demo\Demo\Entity\RequestJwtResult;

/**
 * Class RequestJwt
 * @package Ipol\Demo\Demo
 * @subpackage Controller
 */
class RequestJwt extends AutomatedCommonRequest
{
    /**
     * RequestJwt constructor.
     * @param string $apiKey
     */
    public function __construct(string $apiKey)
    {
        parent::__construct(new RequestJwtResult());
        $data = new JwtGenerate();
        $data->setApikey($apiKey);

        $this->setRequestObj($data);
    }

}