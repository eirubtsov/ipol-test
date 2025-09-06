<?php
namespace Ipol\Demo;

use \Ipol\Demo\Bitrix\Controller\SyncLocations;
use \Ipol\Demo\Bitrix\Handler\LocationsDelivery;

use \Bitrix\Main\Result;
use \Bitrix\Main\Error;

/**
 * Class LocationsHandler
 * @package Ipol\Demo
 */
class LocationsHandler extends AbstractGeneral
{
    /**
     * Refresh location data
     *
     * @return \Bitrix\Main\Result
     */
    public static function refreshLocations()
    {
        $controller = new SyncLocations();

        $result = $controller->refreshLocations();
        return $result;
    }

    /**
     * Refresh locations file
     *
     * @return \Bitrix\Main\Result
     */
    public static function loadLocationsFile()
    {
        $controller = new SyncLocations();

        $result = $controller->loadLocationsFile();
        return $result;
    }

    /**
     * Make statistic info about loaded locations
     *
     * @return \Bitrix\Main\Result
     */
    public static function makeStatistic()
    {
        return SyncLocations::makeStatistic();
    }

    /**
     * @return array
     */
    public static function getCities()
    {
        $arCities = array();

        $obLocations = LocationsTable::getList();
        while($obLocation = $obLocations->Fetch()){
            $req = LocationsDelivery::getByBitrixCode($obLocation['BITRIX_CODE']);

            if($req && !empty($req)){
                $arCities [$req['LOCALITY_CODE']]= array(
                    'NAME'    => $req['NAME'],
                    'COUNTRY' => $req['COUNTRY'],
                    'REGION'  => $req['REGION'],
                    'CODE'    => $req['LOCALITY_CODE'],
                );
            }
        }

        return $arCities;
    }
}