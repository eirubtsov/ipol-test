<?php


namespace Ipol\Demo\Demo\Entity;


use Ipol\Demo\Api\Entity\Response\ErrorResponse;
use Ipol\Demo\Api\Entity\Response\JwtGenerate as ObjResponse;

/**
 * Class RequestJwtResult
 * @package Ipol\Demo\Demo
 * @subpackage Entity
 * @method ObjResponse|ErrorResponse getResponse()
 */
class RequestJwtResult extends AbstractResult
{
    /**
     * @var string
     */
    protected $jwt;

    /**
     * @return string
     */
    public function getJwt(): string
    {
        return $this->jwt;
    }

    /**
     * @return void
     */
    public function parseFields(): void
    {
        $this->jwt = $this->getResponse()->getJwt();
    }
}