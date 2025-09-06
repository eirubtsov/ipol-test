<?php


namespace Ipol\Demo\Api\Entity\Response;


/**
 * Class CancelOrderById
 * @package Ipol\Demo\Api\Entity\Response
 */
class CancelOrderById extends AbstractResponse
{
    /**
     * @var string
     */
    protected $error;

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     * @return CancelOrderById
     */
    public function setError($error)
    {
        $this->error = $error;
        return $this;
    }

}