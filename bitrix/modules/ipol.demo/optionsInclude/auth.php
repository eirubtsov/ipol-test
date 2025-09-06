<?php
use Ipol\Demo\Bitrix\Tools as Tools;

/** @var string $LABEL */
?>
<script type="text/javascript">
    <?=$LABEL?>setups.addPage('auth',{
        init: function(){

        },

        auth: function () {
            this.demarkError();
            this.handleBlock(true);
            var apiKey = $('#<?=$LABEL?>key').val();
            if(!apiKey){
                this.markError($('#<?=$LABEL?>key'));
                this.handleBlock();
            }else{
                this.self.ajax({
                    data: {<?=$LABEL?>action:'auth',apiKey:apiKey},
                    success: function(data){
                        if(data.indexOf('%Y%') !== -1){
                            alert('<?=Tools::getMessage('LBL_AUTHORIZED')?>');
                            <?=$LABEL?>setups.reload();
                        }else {
                            if(data.indexOf('%T%') !== -1){
                                alert('<?=Tools::getMessage('LBL_AUTHORIZEDTEST')?>');
                                <?=$LABEL?>setups.reload();
                            } else {
                                alert("<?=Tools::getMessage('LBL_NOTAUTHORIZED')?>");
                                <?=$LABEL?>setups.getPage('auth').handleBlock();
                                $('.ipol_header').click();
                            }
                        }
                    }
                });
            }
        },

        markError: function (wat) {
            wat.addClass('<?=$LABEL?>errInput');
        },

        demarkError: function(){
            $('.IPOL_DEMO_errInput').removeClass('<?=$LABEL?>errInput');
        },

        handleBlock: function(block){
            if(typeof(block) === 'undefined' || !block)
            {
                $('#IPOL_DEMO_auth').removeAttr('disabled');
            }
            else
            {
                $('#IPOL_DEMO_auth').attr('disabled','disabled');
            }
        }
    });
</script>

<tr>
    <td class="adm-detail-content-cell-l" width="50%"><?=Tools::getMessage('OPT_apikey')?></td>
    <td class="adm-detail-content-cell-r" width="50%"><input type="text" name="<?=$LABEL?>key" id="<?=$LABEL?>key"/></td>
</tr>
<tr>
    <td colspan="2"><input id="IPOL_DEMO_auth" type="button" onclick="<?=$LABEL?>setups.getPage('auth').auth();" value="<?=Tools::getMessage('BTN_AUTH')?>"/></td>
</tr>

<tr><td colspan="2"><div style="margin-top: 30px">
    <?php Tools::placeFAQ('ACCESS');?>
</div></td></tr>