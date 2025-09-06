<?php
if (!check_bitrix_sessid())
    return;

/** @global CMain $APPLICATION */

IncludeModuleLangFile(__FILE__);
?>
<form action="<?=$APPLICATION->GetCurPage()?>">
    <input type="hidden" name="lang" value="<?=LANG?>">

    <?=GetMessage("IPOL_DEMO_INSTALL_TEXT")?><br>

    <input style='display:none' type="submit" name="" value="OK">
</form>