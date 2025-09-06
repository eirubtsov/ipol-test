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
 * Class GeneralUrlImplementedMethod
 * @package Ipol\Demo\Api
 * @subpackage Methods
 * @method AbstractResponse|mixed|ErrorResponse getResponse
 */
class GeneralUrlImplementedMethod extends GeneralMethod
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
        string $urlImplement,
        ?EncoderInterface $encoder = null
    ) {
        $this->setUrlImplement($this->encodeFieldToAPI($urlImplement));
        parent::__construct($data, $adapter, $responseClass, $encoder);
    }

}