<?php
namespace Ipol\Demo;

use \Bitrix\Main\Loader;

define('IPOL_DEMO', 'ipol.demo'); // Use this if module code needed
define('IPOL_DEMO_LBL', 'IPOL_DEMO_');

IncludeModuleLangFile(__FILE__);

Loader::includeModule('sale'); // Because no delivery without sales

// Module classes autoloader
spl_autoload_register(function($className){
    if (strpos($className, __NAMESPACE__) === 0) {
        $classPath = implode(DIRECTORY_SEPARATOR, explode('\\', substr($className, 10)));

        $filename = __DIR__.DIRECTORY_SEPARATOR."classes".DIRECTORY_SEPARATOR."lib".DIRECTORY_SEPARATOR.$classPath.".php";

        if (is_readable($filename) && file_exists($filename))
            require_once $filename;
    }
});

// Main module classes
Loader::registerAutoLoadClasses(IPOL_DEMO, array(
    // General
    '\Ipol\Demo\AbstractGeneral'   => '/classes/general/AbstractGeneral.php',
    '\Ipol\Demo\BarcodeHandler'    => '/classes/general/BarcodeHandler.php',
    '\Ipol\Demo\SubscribeHandler'  => '/classes/general/SubscribeHandler.php',
    '\Ipol\Demo\Option'            => '/classes/general/Option.php',
    '\Ipol\Demo\OptionsHandler'    => '/classes/general/OptionsHandler.php',
    '\Ipol\Demo\OrderHandler'      => '/classes/general/OrderHandler.php',
    '\Ipol\Demo\OrderPropsHandler' => '/classes/general/OrderPropsHandler.php',
    '\Ipol\Demo\AgentHandler'      => '/classes/general/AgentHandler.php',
    '\Ipol\Demo\AuthHandler'       => '/classes/general/AuthHandler.php',
    '\Ipol\Demo\Warhouses'         => '/classes/general/Warhouses.php',
    '\Ipol\Demo\StatusHandler'     => '/classes/general/StatusHandler.php',

    '\Ipol\Demo\CargoHandler' => '/classes/general/CargoHandler.php',
    '\Ipol\Demo\ProfileHandler'    => '/classes/general/ProfileHandler.php',
    '\Ipol\Demo\PvzWidgetHandler'  => '/classes/general/PvzWidgetHandler.php',

    '\Ipol\Demo\PointsHandler'     => '/classes/general/PointsHandler.php',
    '\Ipol\Demo\LocationsHandler'  => '/classes/general/LocationsHandler.php',
    '\Ipol\Demo\SyncHandler'       => '/classes/general/SyncHandler.php',

    // DB
    '\Ipol\Demo\OrdersTable'       => '/classes/db/OrdersTable.php',
    '\Ipol\Demo\PointsTable'       => '/classes/db/PointsTable.php',
    '\Ipol\Demo\RatesTable'        => '/classes/db/RatesTable.php',
    '\Ipol\Demo\LocationsTable'    => '/classes/db/LocationsTable.php',
));