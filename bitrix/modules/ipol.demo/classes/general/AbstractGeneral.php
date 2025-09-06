<?php
namespace Ipol\Demo;

/**
 * Class AbstractGeneral
 * @package Ipol\Demo\
 * Parent for all general classes
 */
class AbstractGeneral
{
    protected static $MODULE_LBL = IPOL_DEMO_LBL;
    protected static $MODULE_ID  = IPOL_DEMO;

    /**
     * @return string
     */
    public static function getMODULELBL()
    {
        return self::$MODULE_LBL;
    }

    /**
     * @return string
     */
    public static function getMODULEID()
    {
        return self::$MODULE_ID;
    }
}