<?php
use Ipol\Demo\Bitrix\Tools as Tools;

/** @var string $module_id */
/** @var string $LABEL */
?>
<script src="<?=Tools::getJSPath()?>wndController.js"></script>
<script type="text/javascript">
    <?=$LABEL?>setups.addPage('main',{
        init: function(){
            this.warehouses.init();
            this.specOptsInit();
        },

        delogin: function() {
            if (confirm('<?=Tools::getMessage('LBL_REALLYDELOGIN')?>')) {
                this.self.ajax({
                    data: {<?=$LABEL?>action: 'delogin'},
                    success: <?=$LABEL?>setups.reload
                });
            }
        },

        clearCache: function(){
            $('#<?=$LABEL?>chearCache').attr('disabled','disabled');
            this.self.ajax({
                data: {<?=$LABEL?>action: 'clearCache'},
                success: function(){
                    alert('<?=Tools::getMessage('LBL_CACHECLEARED')?>');
                    $('#<?=$LABEL?>chearCache').removeAttr('disabled');
                }
            });
        },

        resetCounter : function(){
            $('#<?=$LABEL?>resetBarCounter').attr('disabled','disabled');
            this.self.ajax({
                data: {<?=$LABEL?>action: 'resetBarCounter'},
                success: function(){
                    $('#<?=$LABEL?>barCounterPlace').html('0');
                    alert('<?=Tools::getMessage('LBL_BAKCRESETED')?>');
                    $('#<?=$LABEL?>resetBarCounter').removeAttr('disabled');
                }
            });
        },

        clearLog: function (code) {
            $('#<?=$LABEL?>clear'+code).closest('.adm-info-message-wrap').hide();
            this.self.ajax({
                data: {<?=$LABEL?>action: 'clearLog',src: code}
            })
        },

        showHidden: function(link){
            link.closest('tr').nextUntil(".heading").each(function(){
                $(this).removeClass('<?=$LABEL?>hidden');
            });
        },

        changeBarkCounter: function(el){
            $(el).parent("td").find('input').prop('type', 'text');
            $(el).hide();
            let html = $(el).parent("td").html();
            html = html.replace($(el).parent("td").find('input').val(),'');
            $(el).parent("td").html(html);
        },

        specOptsInit : function(){
            $('[name="barkID"]').attr('size',4).attr('placeholder','XXXX');
            $('[name="pvzLabel"]').attr('placeholder','<?=Tools::getMessage('SIGN_CHOOSEPOINTDEF')?>');
            $('[name="barkCounter"]').parent("td").append('<span onclick="<?=$LABEL?>setups.getPage(\'main\').changeBarkCounter(this)" style="font-size: 0.9em; cursor: pointer; color: blue; display: inline-block; margin-left: 5px; border-bottom: 1px dashed;"><?=Tools::getMessage('LBL_CHANGE')?></span>');
        },

        warehouses : {
            data : <?=\CUtil::PhpToJSObject(Tools::encodeFromUTF8(\Ipol\Demo\Warhouses::getWHInfo()))?>,
            init : function(){
                var self = <?=$LABEL?>setups.getPage('main').warehouses;
                if(self.data){
                    var cntr = 0;
                    self.data.forEach(function (wh){
                        $('#<?=$LABEL?>WH_tablesPlace').append($('#<?=$LABEL?>WH_PlaceHolder').html());
                        var handler = false;
                        $('#<?=$LABEL?>WH_tablesPlace').find('.<?=$LABEL?>WH_addTable').each(function () {
                            if (!$(this).attr('id')) {
                                handler = $(this);
                                handler.attr('id', '_tmpWH' + cntr);
                                handler.addClass('<?=$LABEL?>WH_placedTable');
                                return false;
                            }
                        });
                        if(handler) {
                            handler.find('tr:first').addClass('<?=$LABEL?>subHeading');
                            handler.find('input').each(function () {
                                if (typeof(wh[$(this).attr('name')]) !== 'undefined') {
                                    $(this).replaceWith(wh[$(this).attr('name')]);
                                }
                            });
                            handler.find('select').each(function () {
                                if ($(this).attr('name').indexOf('workingTime') === -1) {
                                    $(this).replaceWith(wh[$(this).attr('name')]);
                                } else {
                                    var reg = /workingTime\[(\d)\]\[(\S*)\]/;
                                    var newName = reg.exec($(this).attr('name'));
                                    if (newName) {
                                        if(typeof(wh['<?=$LABEL?>WH_workingTime'][newName[1]]) === 'undefined'){
                                            $(this).closest('tr').replaceWith('');
                                        } else {
                                            $(this).replaceWith(wh['<?=$LABEL?>WH_workingTime'][newName[1]][newName[2]]);
                                        }
                                    }
                                }
                            });
                            handler.find('.<?=$LABEL?>timerowDelete').replaceWith('');

                            handler.find('[name="<?=$LABEL?>WH_timeZone"]').replaceWith(wh.<?=$LABEL?>WH_timeZone);
                            cntr++;
                        }
                    });
                } else {
                    $('#<?=$LABEL?>whSave').css('display','none');
                    $('#<?=$LABEL?>WH_tablesPlace').css('height','auto');
                }
            },
            wnd : false,
            html : false,
            add : function(){
                var self = <?=$LABEL?>setups.getPage('main').warehouses;
                if(!self.wnd){
                    self.wnd = new idemo_wndController({
                        title   : '<?=Tools::getMessage('WH_wndTitle')?>',
                        buttons : ['<input type="button" value="OK" id="<?=$LABEL?>whOK" onclick="<?=$LABEL?>setups.getPage(\'main\').warehouses.ok()">'],
                        width   : '500'
                    });
                }
                if(!self.html){
                    self.html = $('#<?=$LABEL?>WH_PlaceHolder').html();
                }
                self.wnd.setContent('<form id="<?=$LABEL?>WH_form">' + self.html + '</form>');
                self.wnd.open();
            },
            ok : function(){
                var self = <?=$LABEL?>setups.getPage('main').warehouses;
                if(self.confirm()){
                    $('#<?=$LABEL?>whOK').attr('disabled','disabled');
                    <?=$LABEL?>setups.ajax({
                        data: $('#<?=$LABEL?>WH_form').serialize(),
                        dataType : 'json',
                        success : function(data){
                            if(data.success){
                                self.wnd.close();
                                <?=$LABEL?>setups.ajax({
                                    data : {<?=$LABEL?>action: 'getAjaxWH'},
                                    dataType : 'json',
                                    success  : function (data) {
                                        <?=$LABEL?>setups.getPage('main').warehouses.data = data;
                                        <?=$LABEL?>setups.getPage('main').warehouses.init();
                                    }
                                });
                            } else {
                                var errText = '<?=Tools::getMessage('ERROR_WAREHOUSENOTCREATED')?>' + ((data.error)?' '+data.error:'')
                                alert(errText);
                            }
                            $('#<?=$LABEL?>WH_tablesPlace').height('');
                            $('#<?=$LABEL?>whOK').removeAttr('disabled');
                        }
                    });
                } else {
                    alert('<?=Tools::getMessage('WH_checkParams')?>');
                }
            },
            selectRegion : function (sel) {
                if(sel.val()) {
                    $('[name="<?=$LABEL?>WH_regionCode"]').val(sel.val());
                    $('[name="<?=$LABEL?>WH_region"]').val(sel.find('[value="' + sel.val() + '"]').html());
                }
            },
            removeDayTime : function (wat) {
                var handler = wat.closest('tr');
                var opClass = '<?=$LABEL?>inactiveDayTime';
                if(handler.hasClass(opClass)) {
                    handler.removeClass(opClass);
                    handler.find('select').removeAttr('disabled');
                    handler.find('input').removeAttr('disabled');
                } else {
                    handler.addClass(opClass);
                    handler.find('select').attr('disabled', 'disabled');
                    handler.find('input').attr('disabled', 'disabled');
                }
            },

            loadWnd : false,
            loadAdd : function(){
                var self = <?=$LABEL?>setups.getPage('main').warehouses;
                if(!self.loadWnd){
                    self.loadWnd = new idemo_wndController({
                        title   : '<?=Tools::getMessage('WH_wndAddTitle')?>',
                        buttons : [],
                        width   : '500'
                    });
                }
                self.loadWnd.setContent('<form action="<?=Tools::getJSPath()?>/ajax.php" onsubmit="<?=$LABEL?>setups.getPage(\'main\').warehouses.loadWH" name="<?=$LABEL?>whAddForm" enctype="multipart/form-data" method="POST"><input name="userfile" type="file" />' +
                '<input type="submit" name="submit">Загрузить</button></form>');
                self.loadWnd.open();
            },

            _cnf : false,
            confirm : function(){
                <?=$LABEL?>setups.getPage('main').warehouses._cnf = true;
                $('#<?=$LABEL?>WH_form input').each(function(){
                    if(!$(this).val()){
                        <?=$LABEL?>setups.getPage('main').warehouses._cnf = false;
                    }
                });
                $('#<?=$LABEL?>WH_form select').each(function(){
                    if(!$(this).val()){
                        <?=$LABEL?>setups.getPage('main').warehouses._cnf = false;
                    }
                });
                return <?=$LABEL?>setups.getPage('main').warehouses._cnf;
            }
        }
    });
</script>
<style>
    .<?=$LABEL?>WH_addTable{
        width : 100%;
        border : 1px solid #e0e8ea !important;
        float : left;
    }
    .<?=$LABEL?>WH_placedTable{
        max-width: 600px;
        margin-bottom: 10px !important;
    }
    #<?=$LABEL?>WH_tablesPlace{
        height: 480px;
        overflow: auto;
    }
    #<?=$LABEL?>WH_regionC,#<?=$LABEL?>WH_federalDistrict{
        max-width: 150px;
    }
    .<?=$LABEL?>timerowDelete{
        background: url("<?=Tools::getImagePath()?>closer.png");
        background-position-y: 15px;
        display: inline-block;
        width : 15px;
        height : 15px;
        cursor : pointer;
    }
    .<?=$LABEL?>timerowDelete:hover,.<?=$LABEL?>inactiveDayTime .<?=$LABEL?>timerowDelete{
        background-position-y: 0px !important;
    }
    .<?=$LABEL?>inactiveDayTime td{
        color : gray !important;
    }
</style>
<?php
    $strInfo = '';
    foreach(array('statuses') as $logCode){
        if(\Ipol\Demo\Admin\Logger::getLogInfo($logCode)){
            $strInfo .= Tools::getMessage('NOTE_'.$logCode)." <a href='#IPOL_DEMO_clear".$logCode."'>".Tools::getMessage('LBL_GOTO')."</a><br><br>";
        }
    }
    if($strInfo){
        Tools::placeWarningLabel($strInfo);
    }
?>
<tr>
    <td class="adm-detail-content-cell-l" width="50%"><?=Tools::getMessage('LBL_YOULOGIN')?> <strong><?=substr(Ipol\Demo\Option::get('apiKey'),0,8)."(...)"?></strong><?=(\Ipol\Demo\Option::get('isTest')==='Y') ? '&nbsp;<span class="'.$LABEL.'warning">'.Tools::getMessage('OPT_isTest').'</span>' :''?></td>
    <td class="adm-detail-content-cell-r" width="50%"><input type="button" onclick="<?=$LABEL?>setups.getPage('main').delogin()" id="IPOL_DEMO_delogin" value="<?=Tools::getMessage('BTN_DELOGIN')?>"/><?=(\Ipol\Demo\Option::get('isTest') == 'Y') ? '&nbsp;&nbsp;<span class="'.$LABEL.'warning">'.Tools::getMessage('LBL_TESTMODE').'</span>' : ''?></td>
</tr>
<tr>
    <td colspan="2"><input type="button" onclick="<?=$LABEL?>setups.getPage('main').clearCache()" id="IPOL_DEMO_chearCache" value="<?=Tools::getMessage('BTN_CLEARCACHE')?>"/></td>
</tr>
<?php
// Common
Tools::placeOptionBlock('common');

// Warehouses
Tools::placeOptionBlock('warehouses');
Tools::placeOptionRow('','<div><div id="'.$LABEL.'WH_tablesPlace"></div></div>');
Tools::placeOptionRow('',
'<input type="button" value="'.Tools::getMessage('LBL_ADDWAREHOUSE').'" id="'.$LABEL.'whAdd" onclick="'.$LABEL.'setups.getPage(\'main\').warehouses.add()">&nbsp;
<a href="'.\Ipol\Demo\Warhouses::getRelativePath().'" download="" target="_blank"  id="'.$LABEL.'whSave"><input type="button" value="'.Tools::getMessage('LBL_SAVEWAREHOUSE').'"/></a>&nbsp'
);
//<input type="button" value="'.Tools::getMessage('LBL_LOADWAREHOUSE').'" id="'.$LABEL.'loadAdd" onclick="'.$LABEL.'setups.getPage(\'main\').warehouses.loadAdd()">'

// DefaultGabarites
Tools::placeOptionBlock('defaultGabarites');

// OrderProps
Tools::placeOptionBlock('orderProps');

// Goodprops
Tools::placeOptionBlock('goodprops');

// Statuses
Tools::placeOptionBlock('statuses');

// Delivery
Tools::placeOptionBlock('delivery');

// Barcodes
Tools::placeOptionBlock('barcodes');

// Widjet
Tools::placeOptionBlock('widjet');

// Payments
Tools::placeOptionBlock('payments');

// Service
Tools::placeOptionBlock('service', true);

// Form fow warehouse
$arTime = array();
for($i=0;$i<25;$i++){
    $label = ($i < 10) ? '0'.$i : $i;
    $key = ($i !== 24) ? $label.':00:00' : '23:59:59';
    $arTime[$key] = $label.':00';
}
$arRegions = array();
for($i=0;$i<100;$i++){
    if(Tools::getMessage('REGION_'.$i)) {
        $arRegions [$i] = Tools::getMessage('REGION_' . $i);
    }
}
$arFederalDistricts = array();
for($i=0;$i<9;$i++){
    $arFederalDistricts [Tools::getMessage('FEDERALDISTRICT_'.$i)] = Tools::getMessage('FEDERALDISTRICT_'.$i);
}
?>
<tr><td colspan="2">
<div style="display:none" id="<?=$LABEL?>WH_PlaceHolder">
    <table class="<?=$LABEL?>WH_addTable">
        <tr><td><?=Tools::getMessage('WH_name')?></td><td><input type="text" name="<?=$LABEL?>WH_name"/></td></tr>
        <tr><td><?=Tools::getMessage('WH_partnerLocationId')?></td><td><input type="text" name="<?=$LABEL?>WH_partnerLocationId"/></td></tr>
        <tr><td><?=Tools::getMessage('WH_regionC')?></td><td>
            <?=Tools::makeSelect($LABEL.'WH_regionC',$arRegions,false,'onchange="'.$LABEL.'setups.getPage(\'main\').warehouses.selectRegion($(this))"');?>
            <input type="hidden" name="<?=$LABEL?>WH_region"/>
            <input type="hidden" name="<?=$LABEL?>WH_regionCode"/>
        </td></tr>
        <tr><td><?=Tools::getMessage('WH_federalDistrict')?></td><td><?=Tools::makeSelect($LABEL.'WH_federalDistrict',$arFederalDistricts);?></td></tr>
        <tr><td><?=Tools::getMessage('WH_index')?></td><td><input type="text" name="<?=$LABEL?>WH_index"/></td></tr>
        <tr><td><?=Tools::getMessage('WH_city')?></td><td><input type="text" name="<?=$LABEL?>WH_city"/></td></tr>
        <tr><td><?=Tools::getMessage('WH_street')?></td><td><input type="text" name="<?=$LABEL?>WH_street"/></td></tr>
        <tr><td><?=Tools::getMessage('WH_houseNumber')?></td><td><input type="text" name="<?=$LABEL?>WH_houseNumber"/></td></tr>
        <tr><td><?=Tools::getMessage('WH_coordinates')?></td><td><input type="text" class="<?=$LABEL?>WH_coords" name="<?=$LABEL?>WH_coordinatesX"/>&nbsp;x&nbsp;<input type="text" class="<?=$LABEL?>WH_coords" name="<?=$LABEL?>WH_coordinatesY"/></td></tr>
        <tr><td><?=Tools::getMessage('WH_contactPhoneNumber')?></td><td><input type="text" placeholder="+7XXXXXXXXXX" name="<?=$LABEL?>WH_contactPhoneNumber"/></td></tr>
        <tr><td><?=Tools::getMessage('WH_timeZone')?></td><td><select name="<?=$LABEL?>WH_timeZone"/>
                <?php for($i=2;$i<13;$i++){
                    $label = ($i < 10) ? '0'.$i : $i;
                    ?><option value="+<?=$label?>:00">+<?=$label?>:00</option><?php
                }?>
            </select></td></tr>
        <tr class="<?=$LABEL?>subHeading"><td colspan="2"><?=Tools::getMessage('WH_workingTime')?></td></tr>
        <?php for($i=0;$i<7;$i++){?>
            <tr><td><?= Tools::getMessage('WH_day_'.$i)?></td><td>
                <?= Tools::makeSelect($LABEL."WH_workingTime[".$i."][timeFrom]",$arTime,'09:00:00')?>&nbsp;-&nbsp;
                <?= Tools::makeSelect($LABEL."WH_workingTime[".$i."][timeTill]",$arTime,'18:00:00')?>
                <input type="hidden" name="<?=$LABEL?>WH_workingTime[<?=$i?>][dayNumber]" value="<?=($i+1)?>"/>
                <span class="<?=$LABEL?>timerowDelete" onclick="<?=$LABEL?>setups.getPage('main').warehouses.removeDayTime($(this))"></span>
            </td></tr>
        <?php }?>
        <tr><td colspan="2"><input type="hidden" name="<?=$LABEL?>action" value="addAjaxWhInfo"></td></tr>
    </table>
</div>
</td></tr>