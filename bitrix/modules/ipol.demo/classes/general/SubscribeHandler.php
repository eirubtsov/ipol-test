<?php
namespace Ipol\Demo;

IncludeModuleLangFile(__FILE__);

/**
 * Class SubscribeHandler
 * @package Ipol\Demo\
 */
class SubscribeHandler extends AbstractGeneral
{
    public static $link = true;

    /**
     * Called from /js/ajax.php
     * @param $action
     */
    public static function getAjaxAction($action)
    {
        if(method_exists('\Ipol\Demo\AgentHandler',$action))
            \Ipol\Demo\AgentHandler::$action($_POST);
        elseif(method_exists('\Ipol\Demo\OptionsHandler',$action))
            \Ipol\Demo\OptionsHandler::$action($_POST);
        elseif(method_exists('\Ipol\Demo\AuthHandler',$action))
            \Ipol\Demo\AuthHandler::$action($_POST);
        elseif(method_exists('\Ipol\Demo\Warhouses',$action))
            \Ipol\Demo\Warhouses::$action($_POST);
        elseif(method_exists('\Ipol\Demo\PvzWidgetHandler',$action))
            \Ipol\Demo\PvzWidgetHandler::$action($_POST);
        elseif(method_exists('\Ipol\Demo\OrderHandler',$action))
            \Ipol\Demo\OrderHandler::$action($_POST);
        elseif(method_exists('\Ipol\Demo\BarcodeHandler',$action))
            \Ipol\Demo\BarcodeHandler::$action($_POST);
        elseif(method_exists('\Ipol\Demo\StatusHandler',$action))
            \Ipol\Demo\StatusHandler::$action($_POST);
    }

    /**
     * Returns module dependencies
     * @return array
     */
    protected static function getDependences()
    {
        return array(
            array('main', 'OnEpilog', self::$MODULE_ID, 'Ipol\Demo\SubscribeHandler', 'onEpilog'),
            array('sale', 'OnSaleOrderBeforeSaved',self::$MODULE_ID,'\Ipol\Demo\SubscribeHandler','onBeforeOrderCreate'),
            array("sale", "OnSaleComponentOrderOneStepComplete", self::$MODULE_ID, 'Ipol\Demo\SubscribeHandler','onOrderCreate'),
            array("sale", "OnSaleComponentOrderUserResult", self::$MODULE_ID, 'Ipol\Demo\SubscribeHandler','getOrderCreatePaysystem'),

            // Add module delivery handler classes
            array('sale', 'onSaleDeliveryHandlersClassNamesBuildList', self::$MODULE_ID, 'Ipol\Demo\SubscribeHandler', 'onSaleDeliveryHandlersClassNamesBuildList'),

            // PVZ
            array("sale", "OnSaleComponentOrderOneStepProcess",  self::$MODULE_ID, 'Ipol\Demo\SubscribeHandler', "loadWidjet",900),
            array("main", "OnEndBufferContent", self::$MODULE_ID, 'Ipol\Demo\SubscribeHandler', "addWidjetData"),
            array("sale", "OnSaleComponentOrderOneStepDelivery", self::$MODULE_ID, 'Ipol\Demo\SubscribeHandler', "prepareData",900),

        );
    }

    /**
     * Register module dependencies
     */
    public static function register()
    {
        foreach (self::getDependences() as $regArray) {
            RegisterModuleDependences($regArray[0], $regArray[1], $regArray[2], $regArray[3], $regArray[4], (isset($regArray[5]) ? $regArray[5] : false));
        }
    }

    /**
     * Unregister module dependencies (uninstall or logout)
     */
    public static function unRegister(){
        foreach(self::getDependences() as $regArray) {
            UnRegisterModuleDependences($regArray[0], $regArray[1], $regArray[2], $regArray[3], $regArray[4]);
        }
    }

    // Events
    public static function onEpilog()
    {
         Admin\OrderSender::init();
    }

    // Widget
    public static function loadWidjet()
    {
        PvzWidgetHandler::loadWidjet();
    }

    public static function addWidjetData(&$content)
    {
        PvzWidgetHandler::addWidjetData($content);
    }

    public static function prepareData($arResult,$arUserResult)
    {
        PvzWidgetHandler::prepareData($arResult,$arUserResult);
    }

    // Register module delivery handler classes
    public static function onSaleDeliveryHandlersClassNamesBuildList()
    {
        $result = new \Bitrix\Main\EventResult(
            \Bitrix\Main\EventResult::SUCCESS,
            array(
                // Delivery service
                '\Ipol\Demo\Bitrix\Handler\DeliveryHandler'       => '/bitrix/modules/'.self::$MODULE_ID.'/classes/lib/Bitrix/Handler/DeliveryHandler.php',
                // Delivery service profiles (only pickup for now)
                '\Ipol\Demo\Bitrix\Handler\DeliveryHandlerPickup' => '/bitrix/modules/'.self::$MODULE_ID.'/classes/lib/Bitrix/Handler/DeliveryHandlerPickup.php',
            )
        );

        return $result;
    }

    public static function getOrderCreatePaysystem($arUserResult, $obOrder, $arParams)
    {
        CargoHandler::getOrderCreatePaysystem($arUserResult, $obOrder, $arParams);
    }

    // orderPVZPropHandling
    public static function onBeforeOrderCreate($entity, $values)
    {
        $pvz = PvzWidgetHandler::checkPVZProp($entity, $values);
        return ($pvz === true) ? PvzWidgetHandler::checkPVZPaysys($entity, $values) : $pvz;
    }

    public static function onOrderCreate($oId, $arFields)
    {
        OrderPropsHandler::onOrderCreate($oId, $arFields);
    }
}