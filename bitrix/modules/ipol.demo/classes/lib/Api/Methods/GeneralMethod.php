<?php


namespace Ipol\Demo\Api\Methods;

use Ipol\Demo\Api\Adapter\CurlAdapter;
use Ipol\Demo\Api\ApiLevelException;
use Ipol\Demo\Api\BadResponseException;
use Ipol\Demo\Api\Entity\EncoderInterface;
use Ipol\Demo\Api\Entity\Request\AbstractRequest;
use Ipol\Demo\Api\Entity\Response\AbstractResponse;
use Ipol\Demo\Api\Entity\Response\ErrorResponse;


/**
 * Class GeneralMethod
 * @package Ipol\Demo\Api
 * @subpackage Methods
 * @method AbstractResponse|mixed|ErrorResponse getResponse
 */
class GeneralMethod extends AbstractMethod
{
    /**
     * GetOrderHistory constructor.
     * @param AbstractRequest|mixed|null $data
     * @param CurlAdapter $adapter
     * @param string $responseClass
     * @param EncoderInterface|null $encoder
     * @throws BadResponseException
     */
    public function __construct(
        $data,
        CurlAdapter $adapter,
        string $responseClass,
        ?EncoderInterface $encoder = null
    ) {
        parent::__construct($adapter, $encoder);

        if (!is_null($data)) {
            $this->setData($this->getEntityFields($data));
        }

        try {
            /**@var $response AbstractResponse*/
            $response = new $responseClass($this->request());
            $response->setSuccess(true);
        } catch (ApiLevelException $e) {
            $response = new ErrorResponse($e);
            $response->setSuccess(false);
        }

        $this->setResponse($this->reEncodeResponse($response));

        $this->setFields();
    }

}