<?php


namespace Ipol\Demo\Api\Methods;

use Ipol\Demo\Api\Adapter\CurlAdapter;
use Ipol\Demo\Api\ApiLevelException;
use Ipol\Demo\Api\BadResponseException;
use Ipol\Demo\Api\Entity\Response\ErrorResponse;
use Ipol\Demo\Api\Entity\Response\Warehouse as ObjResponse;
use Ipol\Demo\Api\Entity\Request\Warehouse as ObjRequest;


/**
 * Class Warehouse
 * @package Ipol\Demo\Api\Methods
 */
class Warehouse extends AbstractMethod
{
    /**
     * Warehouse constructor.
     * @param ObjRequest $data
     * @param CurlAdapter $adapter
     * @param bool $encoder
     * @throws BadResponseException
     */
    public function __construct(ObjRequest $data, CurlAdapter $adapter, $encoder = false)
    {
        parent::__construct($adapter, $encoder);

        $this->setData($this->getEntityFields($data->getWarehouses()));

        try
        {
            $response = new ObjResponse($this->request());
            $response->setRequestSuccess(true);
        } catch (ApiLevelException $e)
        {
            $response = new ErrorResponse($e->getAnswer());
            $response->setRequestSuccess(false);
        }

        $this->setResponse($this->reEncodeResponse($response));

        $this->setFields();
    }

    /**
     * @return ObjResponse|ErrorResponse
     */
    public function getResponse()
    {
        return parent::getResponse();
    }
}