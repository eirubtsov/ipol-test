<?php
namespace Ipol\Demo;

use Ipol\Demo\Admin\Logger;
use Ipol\Demo\Api\Entity\Response\GetOrderStatus;
use Ipol\Demo\Bitrix\Controller\Status;
use Ipol\Demo\Bitrix\Entity\Options;
use Ipol\Demo\Bitrix\Handler\Order;
use Ipol\Demo\Bitrix\Tools;
use Ipol\Demo\Demo\Handler\Enumerations;

IncludeModuleLangFile(__FILE__);

class StatusHandler extends AbstractGeneral
{
    public static function refreshStatusesAjax()
    {
        self::refreshOrderStates();
        echo 'Y';
    }

    public static function checkStatusByBitrixIAjax()
    {
        self::checkStatusByBI($_REQUEST['bitrixId']);
        echo 'Y';
    }

    /**
     * @deprecated
     * BEWARE: usable only for sended orders with BARK_GENERATE_BY_SERVER !== Y
     */
    public static function checkStatusBy5IAjax()
    {
        self::checkStatusBy5I($_REQUEST['demoId']);
        echo 'Y';
    }

    public static function checkStatusByBI($bitrixId)
    {
        $arDBOrder = OrdersTable::getByBitrixId($bitrixId);
        if ($arDBOrder && $arDBOrder['OK'] && ($arDBOrder['DEMO_GUID'] || $arDBOrder['DEMO_ID'])) {
            return self::checkStatus(Order::getOrderNumber($arDBOrder['BITRIX_ID']));
        }
        return false;
    }

    /**
     * @deprecated
     */
    public static function checkStatusBy5I($demoId)
    {
        $arDBOrder = OrdersTable::getByDemoId($demoId);
        if ($arDBOrder && $arDBOrder['OK'] && $arDBOrder['DEMO_ID']) {
            return self::checkStatus(Order::getOrderNumber($arDBOrder['BITRIX_ID']));
        }
        return false;
    }

    /**
     * @param array|string $demoId
     * @return array|bool
     */
    protected static function checkStatus($bitrixNumber)
    {
        if (!empty($bitrixNumber)) {
            $handler = new Status();
            if (is_array($bitrixNumber)) {
                $result = $handler->checkStatuses($bitrixNumber);
            } else {
                $result = $handler->checkStatus($bitrixNumber);
            }

            if ($result->isSuccess()) {
                self::settleStatuses($result->getResponse());
                return true;
            } else {
                Logger::toLog(Tools::getMessage('ERR_NO_STATUS_INFO'), "", 'statuses');
            }
        }
        return false;
    }

    public static function refreshOrderStates()
    {
        $filter = array('OK' => 1, '!DEMO_STATUS' => Enumerations::getFinalStatuses());
        $filter[] = [
            'LOGIC' => 'OR',
            '!DEMO_ID' => false,
            '!DEMO_GUID' => false,
        ];

        /** @var \Bitrix\Main\DB\Result $orders */
        $orders = OrdersTable::getList(array('select' => array('ID', 'BITRIX_ID'), 'filter' => $filter));

        $arOrders = array();
        while($order = $orders->Fetch()) {
            $oN = Order::getOrderNumber($order['BITRIX_ID']);
            if ($oN) {
                $arOrders[] = $oN;
            }
        }

        self::checkStatus($arOrders);
    }

    /**
     * @param GetOrderStatus $obOrderStatuses
     */
    public static function settleStatuses($obOrderStatuses)
    {
        $options  = new Options();
        if ($obOrderStatuses->getOrderStatuses()) {
            $arOrderStatuses = array();
            $obStatuses = $obOrderStatuses->getOrderStatuses();
            $obStatuses->reset();
            while($obStatus = $obStatuses->getNext()) {
                $arExecution = self::splitExecution($obStatus->getExecutionStatus());

                $bxId = Order::getOrderIdFromNumber($obStatus->getSenderOrderId());
                $arOrderStatuses[$bxId] = array(
                    'bitrixNumber' => $obStatus->getSenderOrderId(),
                    'bitrixId'     => Order::getOrderIdFromNumber($obStatus->getSenderOrderId()),
                    'status'       => $obStatus->getStatus(),
                    'execution'    => $arExecution['executionStatus'],
                    'moduleStatus' => self::getStatusLink($obStatus->getStatus(), $arExecution['executionStatus']),
                    'message'      => $arExecution['message']
                );
            }

            if (!empty($arOrderStatuses)) {
                $arErrors = array();
                foreach ($arOrderStatuses as $orderStatus) {
                    // Case: Bitrix order was deleted after sending data to 5P, but 5P order not deleted from LK
                    if (!$orderStatus['bitrixId'])
                        continue;

                    $orderCheck = OrdersTable::getByBitrixId($orderStatus['bitrixId']);

                    // updating BD
                    $updateResult = OrdersTable::update($orderCheck['ID'], array(
                        'STATUS'                    => $orderStatus['moduleStatus'],
                        'DEMO_STATUS'           => $orderStatus['status'],
                        'DEMO_EXECUTION_STATUS' => $orderStatus['execution'],
                        'MESSAGE'                   => $orderStatus['message'],
                        'UPTIME'                    => time(),
                    ));

                    if (!$updateResult->isSuccess()) {
                        $arErrors[$orderStatus['bitrixId']] = Tools::getMessage('ERR_UNADLEUPDATE');
                        foreach(array('STATUS' => 'moduleStatus', 'DEMO_STATUS' => 'status', 'DEMO_EXECUTION_STATUS' => 'execution', 'MESSAGE' => 'message') as $lbl => $code) {
                            $arErrors[$orderStatus['bitrixId']] .= " ".Tools::getMessage('LBL_'.$lbl).": ".$orderStatus[$code].",";
                        }
                    }

                    // updating Bitrix status
                    $statusBitrix = $options::fetchOption('status_'.$orderStatus['moduleStatus']);
                    if ($statusBitrix && $orderStatus['moduleStatus'] !== $orderCheck['STATUS']) {
                        if (!\CSaleOrder::StatusOrder($orderStatus['bitrixId'], $statusBitrix)) {
                            $errMess = Tools::getMessage('ERR_NOUPDATE')." ".Tools::getMessage('LBL_bitrixStatus').": ".$statusBitrix;
                            if (array_key_exists($orderStatus['bitrixId'],$arErrors)) {
                                $arErrors[$orderStatus['bitrixId']] .= $errMess;
                            } else {
                                $arErrors[$orderStatus['bitrixId']] = $errMess;
                            }
                        }
                    }

                    if (
                        $options::fetchOption('markPayed') === 'Y' &&
                        $orderStatus['status'] === 'DONE' &&
                        $orderStatus['status'] !== $orderCheck['DEMO_STATUS'])
                    {
                        order::markPayed($orderCheck['BITRIX_ID']);
                    }
                }

                if (!empty($arErrors)) {
                    $loggerStr = '';
                    foreach ($arErrors as $orderId => $errMess) {
                        $loggerStr .= Tools::getMessage('LBL_ORDER').$arOrderStatuses[$orderId]['bitrixNumber'].' ('.$orderId.'): '.$errMess;
                    }
                    Logger::toLog($loggerStr, "", 'statuses');
                }
            }
        } else {
            Logger::toLog(Tools::getMessage('ERR_NO_STATUS_INFO'), "", 'statuses');
        }
    }

    /**
     * @param $executionStatus
     * @return array of type executionStatus => '', message => ''
     *
     * because of keeping message and es together - lets do some crappy work
     */
    public static function splitExecution($executionStatus) {
        $arSplit = array('executionStatus' => false, 'message' => false);
        if ($executionStatus) {
            $arExecutionStatuses = Enumerations::getExecutionStatuses();
            foreach ($arExecutionStatuses as $status) {
                if (strpos($executionStatus, $status . ':') === 0) {
                    $arSplit['executionStatus'] = substr($executionStatus, 0, strlen($status));
                    $possMess                   = trim(substr($executionStatus, strlen($status) + 1));
                    if ($possMess) {
                        $arSplit['message'] = $possMess;
                    }
                    break;
                }
            }
        }

        if (!$arSplit['executionStatus']) {
            $arSplit['executionStatus'] = $executionStatus;
        }

        return $arSplit;
    }

    /**
     * Get corresponding bitrix status from connection between demo statuses
     * @param string $status
     * @param string $execution
     * @return string
     */
    public static function getStatusLink($status, $execution = false)
    {
        $arDependenses = array(
            array('status' => 'NEW',         'bitrix' => 'new'),
            array('status' => 'APPROVED',    'bitrix' => 'valid'),
            array('status' => 'REJECTED',    'bitrix' => 'rejected'),
            array('status' => 'IN_PROCESS',  'bitrix' => 'warehouse',   '!execution' => 'PLACED_IN_POSTAMAT'),
            array('status' => 'IN_PROCESS',  'bitrix' => 'inpostamat',  'execution'  => 'PLACED_IN_POSTAMAT'),
            array('status' => 'INTERRUPTED', 'bitrix' => 'interrupted', 'execution'  => 'PROBLEM'),
            array('status' => 'INTERRUPTED', 'bitrix' => 'lost',        'execution'  => 'LOST'),
            array('status' => 'UNCLAIMED',   'bitrix' => 'reclaim',     'execution'  => array('READY_FOR_WITHDRAW_FROM_PICKUP_POINT', 'PLACED_IN_POSTAMAT')),
            array('status' => 'UNCLAIMED',   'bitrix' => 'repickup',    'execution'  => array('WAITING_FOR_REPICKUP')),
            array('status' => 'UNCLAIMED',   'bitrix' => 'unclaimed',   '!execution' => array('READY_FOR_WITHDRAW_FROM_PICKUP_POINT', 'PLACED_IN_POSTAMAT', 'WAITING_FOR_REPICKUP')),
            array('status' => 'CANCELLED',   'bitrix' => 'canceled'),
            array('status' => 'DONE',        'bitrix' => 'done'),
        );

        $bitrixStatus = false;
        foreach ($arDependenses as $arStatus) {
            if ($arStatus['status'] === $status) {
                if (!array_key_exists('execution', $arStatus) && !array_key_exists('!execution', $arStatus)) {
                    $bitrixStatus = $arStatus['bitrix'];
                    break;
                }
                if (array_key_exists('execution', $arStatus)) {
                    if (is_array($arStatus['execution'])) {
                        if (in_array($execution, $arStatus['execution'])) {
                            $bitrixStatus = $arStatus['bitrix'];
                            break;
                        }
                    } elseif ($arStatus['execution'] === $execution) {
                        $bitrixStatus = $arStatus['bitrix'];
                        break;
                    }
                }
                if (array_key_exists('!execution', $arStatus)) {
                    if (is_array($arStatus['!execution'])) {
                        if (!in_array($execution, $arStatus['!execution'])) {
                            $bitrixStatus = $arStatus['bitrix'];
                            break;
                        }
                    } elseif($arStatus['!execution'] !== $execution) {
                        $bitrixStatus = $arStatus['bitrix'];
                        break;
                    }
                }
            }
        }

        return $bitrixStatus;
    }

    /**
     * @deprecated
     * @param $demoId
     * @param $status
     * @param $demoStatus
     * @param $execution
     * @param $errorMess
     * @return mixed
     */
    public static function statusOrder($demoId, $status = false, $demoStatus = false, $execution = false, $errorMess = '')
    {
        $result = false;
        $record = OrdersTable::getByDemoId($demoId);
        if ($record) {
            $result = OrdersTable::update($record['ID'], array(
                'STATUS'                    => ($status) ? $status : $record['STATUS'],
                'DEMO_STATUS'           => $demoStatus,
                'DEMO_EXECUTION_STATUS' => $execution,
                'MESSAGE'                   => $errorMess,
                'UPTIME'                    => time()
            ));
        }
        return $result;
    }
}