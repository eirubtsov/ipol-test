<?php
if (!check_bitrix_sessid())
    return;

/** @global CMain $APPLICATION */

IncludeModuleLangFile(__FILE__);
?>
<form action="<?=$APPLICATION->GetCurPage()?>">
    <input type="hidden" name="lang" value="<?=LANG?>">
    <?=GetMessage("IPOL_DEMO_DEL_TEXT")?><br>
    <input type="submit" name="" value="Ok">
</form>