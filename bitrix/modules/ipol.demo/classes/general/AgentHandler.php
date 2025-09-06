<?php
namespace Ipol\Demo;

/**
 * Class AgentHandler
 * @package Ipol\Demo
 */
class AgentHandler extends AbstractGeneral
{
    public static function addAgent($agent, $interval = 1800)
    {
        $result = null;
        if (method_exists('Ipol\Demo\AgentHandler', $agent) && $agent !== 'addAgent') {
            $result = \CAgent::AddAgent('\Ipol\Demo\AgentHandler::'.$agent.'();', self::$MODULE_ID, "N", $interval);
        }
        return $result;
    }

    /**
     * Refresh order statuses
     * @return string
     */
    public static function refreshStatuses()
    {
        StatusHandler::refreshOrderStates();
        return '\Ipol\Demo\AgentHandler::refreshStatuses();';
    }

    /**
     * Sync points, rates, locations data
     * @return string
     */
    public static function syncServiceData()
    {
        SyncHandler::syncServiceData();
        return '\Ipol\Demo\AgentHandler::syncServiceData();';
    }

    /**
     * Unmake old uploaded barcode files
     * One run per week recommended
     * @return string
     */
    public static function unmakeOldFiles()
    {
        // Unmake stickers
        BarcodeHandler::unmakeOldFiles('sticker', 604800);
        return '\Ipol\Demo\AgentHandler::unmakeOldFiles();';
    }
}