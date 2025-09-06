<?php


namespace Ipol\Demo\Api\Entity\Response;


use Ipol\Demo\Api\Entity\Response\Part\GetOrderHistory\HistoryElementList;
use Ipol\Demo\Api\Tools;

/**
 * Class GetOrderHistory
 * @package Ipol\Demo\Api\Entity\Response
 */
class GetOrderHistory extends AbstractResponse
{
    /**
     * @var HistoryElementList
     */
    protected $history;

    /**
     * @return HistoryElementList
     */
    public function getHistory()
    {
        return $this->history;
    }

    /**
     * @param array $array
     * @return GetOrderHistory
     * @throws \Exception
     */
    public function setHistory($array)
    {
        if(Tools::isSeqArr($array))
        {
            $collection = new HistoryElementList();
            $this->history = $collection->fillFromArray($array);
            return $this;
        }
        else
        {
            throw new \Exception(__FUNCTION__.' requires parameter to be SEQUENTIAL array. '. gettype($array). ' given.');
        }
    }

    public function setDecoded($decoded)
    {
        if(Tools::isSeqArr($decoded))
        {
            parent::setDecoded(['history' => $decoded]);
        }
        else{
            parent::setDecoded($decoded);
        }

        return $this;
    }

}