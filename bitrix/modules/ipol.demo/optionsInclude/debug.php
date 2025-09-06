<?php
use Ipol\Demo\Bitrix\Tools as Tools;

/** @var string $module_id */
/** @var string $LABEL */
?>
<script>
    <?=$LABEL?>setups.addPage('debug',{
        clearLog : function (code) {
            $('#<?=$LABEL?>sublog_'+code).replaceWith('');
            this.self.ajax({
                data: {<?=$LABEL?>action: 'clearLog',src: code}
            })
        }
    });
</script>
<?php
$arLogings = array('createOrder','warehouse','cancelOrderByNumber','getOrderStatus');
$arLogs = array();

foreach ($arLogings as $logCode){
    $hasLog = \Ipol\Demo\Admin\Logger::getLogInfo($logCode);
    if($hasLog){
        $arLogs [$logCode] = $hasLog;
    }
}

if(!empty($arLogs)){
    $strLog = '';
    foreach ($arLogs as $logCode => $logLink){
        $strLog .= '<div id="'.$LABEL.'sublog_'.$logCode.'" style="margin-bottom: 10px"><a href="'.Tools::getJSPath().'logs/'.$logCode.'.txt" target="_blank">'.
            Tools::getMessage('LABEL_LOG_'.$logCode).'</a>&nbsp
            <div onclick="'.$LABEL.'setups.getPage(\'debug\').clearLog(\''.$logCode.'\')" style="display: inline-block" class="'.$LABEL.'closer" title="'.Tools::getMessage('LABEL_KILLLOG').'"></div></div>';
    }

    Ipol\Demo\Bitrix\Tools::placeWarningLabel($strLog, Tools::getMessage('LABEL_haslog'));
}

// Logging
Tools::placeOptionBlock('logging');

// Events
Tools::placeOptionBlock('events');

$arEvents = array(
    'onCompabilityBefore' => Tools::getMessage('LABEL_onCompabilityBefore'),
    'onCalculate'         => Tools::getMessage('LABEL_onCalculate'),
);

foreach($arEvents as $code => $name){
    $arSubscribe = array();
    foreach(GetModuleEvents($module_id,$code,true) as $arEvent){
        $arSubscribe []= $arEvent['TO_NAME'];
    }
    if(!empty($arSubscribe)){
        ?>
        <tr class="subHeading"><td colspan="2" valign="top" align="center"><?=$name?></td></tr>
        <?php
        foreach($arSubscribe as $path){?>
            <tr><td colspan='2'><?=$path?></td></tr>
        <?php }
    }
}

// Constants
Tools::placeOptionBlock('constants');

$arConstants = array(
    \Ipol\Demo\Bitrix\Entity\Cache::getDeactCacheConst() => Tools::getMessage('LBL_NOCACHE')
);

foreach ($arConstants as $code => $lbl) {
    Tools::placeOptionRow($lbl, Tools::getMessage('LBL_CONSET'.(defined($code) ? '' : 'NOT')));
}