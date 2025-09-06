<?php


namespace Ipol\Demo\Api\Entity\Response;


/**
 * Class JwtGenerate
 * @package Ipol\Demo\Api\Entity\Response
 */
class JwtGenerate extends AbstractResponse
{
    /**
     * @var string
     */
    protected $status;
    /**
     * @var string
     */
    protected $jwt;

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     * @return JwtGenerate
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getJwt()
    {
        return $this->jwt;
    }

    /**
     * @param string $jwt
     * @return JwtGenerate
     */
    public function setJwt($jwt)
    {
        $this->jwt = $jwt;
        return $this;
    }

}