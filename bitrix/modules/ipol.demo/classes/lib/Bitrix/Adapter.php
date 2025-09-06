<?php
namespace Ipol\Demo\Bitrix;

use \Ipol\Demo\Bitrix\Tools;
use \Ipol\Demo\Bitrix\Controller\LocationLinker;
use \Ipol\Demo\Demo\Handler\Enumerations;
use Ipol\Demo\Bitrix\Adapter\Cargo;
use Ipol\Demo\Bitrix\Entity\DefaultGabarites;
use Ipol\Demo\Bitrix\Entity\Options;
use Ipol\Demo\Bitrix\Handler\PaySystems;
use Ipol\Demo\OrderHandler;
use Ipol\Demo\OrdersTable;

// TODO: make da big cache here from oderds and other continuing stuff

/**
 * Class Adapter
 * @package namespace Ipol\Demo\Bitrix\Adapter
 */
class Adapter
{
    // BITRIX
    // order
    public static function getOrderData($bitrixId,$mode=1)
    {
        if(OrdersTable::getByBitrixId($bitrixId))
            return OrderHandler::loadUploadOrder($bitrixId,$mode);
        elseif($mode === 1)
            return OrderHandler::loadCMSOrder($bitrixId);

        return false;
    }

    public static function getCargo($arItems)
    {
        $obCargo = new Cargo(new DefaultGabarites());

        // Something wrong with basket items CAN_BUY flag or basket was empty at all
        if (empty($arItems))
            $arItems = array(Tools::makeSimpleGood());

        $obCargo->set($arItems);
        return $obCargo;
    }

    public static function getUO()
    {
        return array("RETURN" => Tools::getMessage("LBL_uO_RETURN"),"UTILIZATION" => Tools::getMessage("LBL_uO_UTILIZATION"));
    }

    public static function getPaymentTypes()
    {
        return array("CASH" => Tools::getMessage("LBL_pT_CASH"),"CASHLESS" => Tools::getMessage("LBL_pT_CASHLESS"),'PREPAYMENT' => Tools::getMessage('LBL_pT_PREPAYMENT'));
    }

    public static function convertPaymentTypes($pt,$reverse=false)
    {
        $arCoords = array('Cash'=>'CASH','Card'=>'CASHLESS','Bill'=>'PREPAYMENT');
        if($reverse){
            $arCoords = array_flip($arCoords);
        }
        return array_key_exists($pt,$arCoords) ? $arCoords[$pt] : false;
    }

    public static function getPaymentCorresponds()
    {
        $options = new Options();
        $nal  = $options->fetchPayNal();
        $card = $options->fetchPayCard();
        $arPayments = PaySystems::getAll();

        foreach ($arPayments as $payId => $payName) {
            switch (true) {
                case in_array($payId, $nal):
                    $arPayments[$payId] = 'CASH';
                    break;
                case in_array($payId, $card):
                    $arPayments[$payId] = 'CARD';
                    break;
                default:
                    $arPayments[$payId] = 'BILL';
                    break;
            }
        }

        return $arPayments;
    }

    // Some tools for module tables

    /**
     * Make hash to represent cell dimensions used for delivery point container dimensions check
     *
     * @param int $a this and below are cell dimensions, order does not matter
     * @param int $b
     * @param int $c
     * @return int
     */
    public static function makeDimensionsHash($a, $b, $c)
    {
        $arr = [$a, $b, $c];

        array_walk($arr, function (&$val, $key) {$val = (int)floor($val / 10);});
        sort($arr);

        return ($arr[0] + $arr[1]*1000 + $arr[2]*1000000);
    }

    /**
     * Make sync hash which used for update check
     *
     * @param array $data
     * @return string
     */
    public static function makeSyncHash($data)
    {
        if (!is_array($data))
            $data = array($data);

        return md5('SYNCHASH_'.implode('|', $data));
    }

    // Locations

    /**
     * Try to link CMS location and corresponded API location starting from CMS side
     *
     * @param string $possiblyId Bitrix location Id or Code
     * @return \Ipol\Demo\Bitrix\Controller\LocationLinker object
     */
    public static function locationById($possiblyId)
    {
        $locationLinker = new LocationLinker();
        $locationLinker->tryLinkFromCmsSide($possiblyId);

        return $locationLinker;
    }

    /**
     * Try to link CMS location and corresponded API location starting from API side
     *
     * @param string $guid API location guid
     * @return \Ipol\Demo\Bitrix\Controller\LocationLinker object
     */
    public static function locationByGuid($guid)
    {
        $locationLinker = new LocationLinker();
        $locationLinker->tryLinkFromApiSide($guid);

        return $locationLinker;
    }

    // demo-specific enumerations

    /**
     * Return undeliverableOption variants used in API method createOrder
     *
     * @return array
     */
    public static function getUndeliverableOptionVariants()
    {
        $variants = Enumerations::getUndeliverableOptionVariants();
        $result = array();
        foreach ($variants as $key => $val)
        {
            $result[$val] = Tools::getMessage('VARIANT_UNDELIVERABLEOPTION_'.$val);
        }
        return $result;
    }

    /**
     * Return paymentType variants used in API method createOrder
     *
     * @return array
     */
    public static function getPaymentTypeVariants()
    {
        $variants = Enumerations::getPaymentTypeVariants();
        $result = array();
        foreach ($variants as $key => $val)
        {
            $result[$val] = Tools::getMessage('VARIANT_PAYMENTTYPE_'.$val);
        }
        return $result;
    }

    public static function statusIsSending($status){
        return in_array($status,array('new','rejected'));
    }
    public static function statusIsFinal($status){
        return in_array($status,array('done','canceled'));
    }
    public static function statusIsReady($status){
        return in_array($status,array('ok','sended','valid'));
    }
    public static function statusIsCancelable($status){
        return in_array($status,array('valid','rejected','warehouse','interrupted','lost'));
    }
}