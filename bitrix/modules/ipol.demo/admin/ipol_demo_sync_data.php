<?php
use \Ipol\Demo\SyncHandler;
use \Ipol\Demo\Bitrix\Tools;
use \Ipol\Demo\Bitrix\Entity\Options;

use \Bitrix\Main\Result;
use \Bitrix\Main\Localization\Loc;

define("ADMIN_MODULE_NAME", "ipol.demo");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin.php");
global $APPLICATION, $USER;

Loc::loadMessages(__FILE__);

if (!CModule::IncludeModule(ADMIN_MODULE_NAME))
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$APPLICATION->SetTitle(Tools::getMessage('ADMIN_SYNC_DATA_TITLE'));

?><form id="ipol_demo_sync_data_form" action="<?=$APPLICATION->GetCurPageParam()?>" method="POST">
    <input type="hidden" name="run" value="Y"><?php

if (isset($_REQUEST['run']))
{
    $result = SyncHandler::syncServiceData();

    $data = $result->getData();
    $step     = $data['CURRENT_STEP'];
    $nextStep = $data['NEXT_STEP'];
    $position = $data['NEXT_PAGE'];
    $total    = $data['TOTAL_PAGES'];

    $errors = $result->isSuccess() ? '' : implode('<br>', $result->getErrorMessages());
    $continueBtn = $errors ? '' : '<br>'.Tools::getMessage('SYNC_CONTINUE', ['ON_CLICK' => 'javascript:document.getElementById(\'ipol_demo_sync_data_form\').submit()']);

    switch ($step)
    {
        case 'SYNC_REFRESH_DATA':
            // Cause position resets to 0 after last page loading
            if ($nextStep !== $step && $position == 0)
                $position = $total;

            CAdminMessage::ShowMessage(array(
                "MESSAGE"        => Tools::getMessage($step.'_TITLE'),
                "DETAILS"        => '#PROGRESS_BAR#'.$continueBtn,
                "TYPE"           => "PROGRESS",
                "HTML"           => true,
                "PROGRESS_TOTAL" => $total,
                "PROGRESS_VALUE" => $position,
            ));
            break;
        case 'SYNC_TOGGLE_INACTIVE_POINTS':
        case 'SYNC_REFRESH_LOCATIONS':
            CAdminMessage::ShowMessage(array(
                "MESSAGE"        => Tools::getMessage($step.'_TITLE'),
                "DETAILS"        => $continueBtn,
                "TYPE"           => "PROGRESS",
                "HTML"           => true,
            ));
            break;
        case 'SYNC_FINISH':
            CAdminMessage::ShowMessage(array(
                'MESSAGE' => Tools::getMessage($step.'_TITLE'),
                'DETAILS' => Tools::getMessage($step.'_DESCR').'<br><br><hr>'.SyncHandler::makeStatistic(),
                'TYPE'    => 'OK',
                'HTML'    => true,
            ));
            unset($_REQUEST['run']);
            break;
    }

    if ($errors)
    {
        CAdminMessage::ShowMessage(array(
            "MESSAGE"        => Tools::getMessage('SYNC_ERRORS'),
            "DETAILS"        => $errors.'<br><br>'.Tools::getMessage('SYNC_CONTINUE_AFTER_ERRORS', ['ON_CLICK' => 'javascript:document.getElementById(\'ipol_demo_sync_data_form\').submit()']),
            'TYPE'           => 'PROGRESS',
            "HTML"           => true,
        ));
    }

    if ($step !== 'SYNC_FINISH' && !$errors)
        echo '<script type="text/javascript">setTimeout(function(){BX.showWait(); document.getElementById(\'ipol_demo_sync_data_form\').submit();}, 3000)</script>';

}
else
{
    CAdminMessage::ShowMessage(array(
        "MESSAGE"        => Tools::getMessage('SYNC_START_TITLE'),
        "DETAILS"        => Tools::getMessage('SYNC_START_DESCR').'<br><br><hr>'.SyncHandler::makeStatistic(),
        'TYPE'           => 'PROGRESS',
        "HTML"           => true,
    ));
    ?><input type="submit" value="<?=Tools::getMessage('SYNC_START_BTN')?>" class="adm-btn-save"><?php
}
?></form><?php

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");