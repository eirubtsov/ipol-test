<?php


namespace Ipol\Demo\Demo;


use Exception;
use Ipol\Demo\Api\Entity\Response\ErrorResponse;

class ErrorResponseException extends Exception
{
    /**
     * ErrorResponseException constructor.
     */
    public function __construct(ErrorResponse $errorResponse)
    {
        parent::__construct($errorResponse->getMessage() . $errorResponse->getErrorMsg(), $errorResponse->getErrorCode());
    }
}