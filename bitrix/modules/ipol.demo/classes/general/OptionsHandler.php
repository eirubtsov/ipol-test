<?php
namespace Ipol\Demo;

use Ipol\Demo\Admin\Logger;
use Ipol\Demo\Bitrix\Entity\Cache;
use Ipol\Demo\Bitrix\Entity\Encoder;
use Ipol\Demo\Bitrix\Entity\Options;
use Ipol\Demo\Bitrix\Tools;

IncludeModuleLangFile(__FILE__);

/**
 * Class OptionsHandler
 * @package Ipol\Demo\
 * Methods for module option page
 */

class OptionsHandler extends AbstractGeneral
{
    /**
     * Clear module cache
     * @param bool $noFdb is some feedback required (AJAX call) or not
     */
    public static function clearCache($noFdb = false)
    {
        $cacheObj = new Cache();
        $obCache = new \CPHPCache();
        $obCache->CleanDir($cacheObj->getPath());

        // Cool new D7 cache
        $path = '/'.self::getMODULEID().'/';
        $cacheInstance = \Bitrix\Main\Data\Cache::createInstance();
        $cacheInstance->CleanDir($path);

        if (!$noFdb)
            echo "Y";
    }

    /**
     * Clear module log files. @see \Ipol\Demo\Admin\Logger
     * @param $params
     */
    public static function clearLog($params)
    {
        if (array_key_exists('src', $params)) {
            Logger::clearLog($params['src']);
        }
    }

    /**
     * Resets barcode counter
     * @param bool $noFdb is some feedback required (AJAX call) or not
     */
    public static function resetCounter($noFdb = false)
    {
        Option::set('barkCounter', 0);
        if (!$noFdb)
            echo "Y";
    }
}