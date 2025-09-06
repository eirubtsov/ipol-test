<?php
use \Ipol\Demo\Bitrix\Tools;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

Loader::includeModule('ipol.demo');

if ($GLOBALS['APPLICATION']->GetGroupRight(IPOL_DEMO) > 'D') // checking rights
{
    // Main menu block
    $aMenu = array(
        'parent_menu' => 'global_menu_store', // IM menu block
        'section' => 'demo',
        'sort' => 110,
        'text' => Tools::getMessage('MENU_MAIN_TEXT'),
        'title' => Tools::getMessage('MENU_MAIN_TITLE'),
        'icon' => 'ipol_demo_menu_icon', // CSS for icon
        'page_icon' => 'ipol_demo_page_icon', // CSS for icon
        'module_id' => IPOL_DEMO,
        'items_id' => IPOL_DEMO_LBL.'menu',
        'items' => array(),
    );

    // Parent pages
    $aMenu['items'][] = array(
        'text' => Tools::getMessage('MENU_ORDERS_TEXT'),
        'title' => Tools::getMessage('MENU_ORDERS_TITLE'),
        'module_id' => IPOL_DEMO,
        'url' => 'ipol_demo_orders.php?lang='.LANGUAGE_ID,
        //"more_url" => array("ipol_demo_orders_edit.php")  // Use it for admin pages like "Edit order with ID=..." and it will be marked in this menu as "opened"
    );

    $aMenu['items'][] = array(
        'text' => Tools::getMessage('MENU_SYNC_DATA_TEXT'),
        'title' => Tools::getMessage('MENU_SYNC_DATA_TITLE'),
        'module_id' => IPOL_DEMO,
        'url' => 'ipol_demo_sync_data.php?lang='.LANGUAGE_ID,
    );

    return $aMenu;
}
return false;