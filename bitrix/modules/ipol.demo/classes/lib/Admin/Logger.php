<?php
namespace Ipol\Demo\Admin;

use Ipol\Demo\Bitrix\Tools;
use Ipol\Demo\Api\Entity\LoggerInterface;
use Ipol\Demo\Option;

/**
 * Class Logger
 * @package Ipol\Demo\
 */
class Logger implements LoggerInterface
{
    /**
     * @param $wat
     * @param $label
     * @param bool $src
     * @param bool $flags
     */
    public static function toLog($wat, $label = "", $src = false, $flags = false){
        if(!$flags){
            $flags = array('ADMIN' => false, 'APPEND' => false);
        }

        if(!$flags['ADMIN'] || Tools::isAdmin()) {
            if ($src) {
                file_put_contents(self::getFileName($src), "\n" . date('H:i:s d.m.Y') . "\n" . $wat."\n", FILE_APPEND);
            } else {
                $arDebugSrc = array('createOrder','warehouse','cancelOrderByNumber','getOrderStatus');
                if(in_array($src,$arDebugSrc)){
                    $flags['APPEND'] = (Option::get('debug_fileMode') === 'a');
                }
                self::toLogFile($wat,$flags);
            }
        }
    }

    /**
     * @param $src
     * @return bool|string
     */
    public static function getLogInfo($src){
        if(
            !self::checkSrc(true) ||
            !file_exists(self::getFileName($src))
        ){
            return '';
        } else {
            return file_get_contents(self::getFileName($src));
        }
    }

    /**
     * @param $src
     */
    public static function clearLog($src){
        if(
            self::checkSrc(true) ||
            file_exists(self::getFileName($src))
        ) {
            unlink(self::getFileName($src));
        }
    }

    /**
     * @param bool $noCreate
     * @return bool
     */
    protected static function checkSrc($noCreate = false){
        $exist = file_exists(self::getRootPath());
        if(!$exist && !$noCreate){
            mkdir(self::getRootPath());
        }
        return $exist;
    }

    /**
     * @param bool $src
     * @return string
     */
    protected static function getFileName($src = false){
        if(!$src){
            return $_SERVER['DOCUMENT_ROOT']."/ipol.demo_log.txt";
        } else {
            self::checkSrc();
            return self::getRootPath()."/".$src.".txt";
        }
    }

    /**
     * @return string
     */
    protected static function getRootPath()
    {
        return $_SERVER['DOCUMENT_ROOT']."/".Tools::getJSPath().'logs';
    }

    // simpleLog

    protected static $fileLink = false;

    /**
     * @param $wat
     * @param array $flags
     */
    protected static function toLogFile($wat, $flags=array('APPEND'=>false)){
        if(!self::$fileLink){
            self::$fileLink = fopen(self::getFileName(),($flags['APPEND']) ? 'a' : 'w');
            fwrite(self::$fileLink,"\n\n".date('H:i:s d.m.Y')."\n");
        }
        fwrite(self::$fileLink,print_r($wat,true)."\n");
    }

    /**
     * @param $src
     * @return string
     */
    public static function toOptions($src)
    {
        $strInfo   = self::getLogInfo($src);
        $strReturn = '';

        if($strInfo){
            $arInfo = explode("\n\n",$strInfo);
            rsort($arInfo);
            foreach ($arInfo as $text){
                if($text){
                    $strReturn .= str_replace("\n","<br>",$text)."<br>";
                }
            }
        }

        return $strReturn;
    }
}