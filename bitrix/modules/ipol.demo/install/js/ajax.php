<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
$module_id = "ipol.demo";
CModule::IncludeModule($module_id);

\Ipol\Demo\SubscribeHandler::getAjaxAction($_REQUEST[\Ipol\Demo\SubscribeHandler::getMODULELBL().'action']);