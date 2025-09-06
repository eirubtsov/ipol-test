<?php
namespace Ipol\Demo\Admin;

use Ipol\Demo\Bitrix\Adapter;
use Ipol\Demo\Bitrix\Tools;
use Ipol\Demo\Core\Order\Order;
use Ipol\Demo\Option;
use Ipol\Demo\Warhouses;

/** @var Order $order */
?>
<script type="text/javascript">
    <?=self::$MODULE_LBL?>export.addPage('main', {
        city : '<?=($order->getAddressTo()) ? $order->getAddressTo()->getCity() : false?>',
        pvz  : '<?=$order->getField('pickupPoint')?>',

        init: function () {
            if ($('#<?=self::$MODULE_LBL?>btn').length) return;

            // B24 support
            if ($('#<?=self::$MODULE_LBL?>btn_container').length)
            {
                $('#<?=self::$MODULE_LBL?>btn_container').prepend("<a href='javascript:void(0)' onclick='<?=self::$MODULE_LBL?>export.getPage(\"main\").open()' class='ui-btn ui-btn-light-border ui-btn-icon-edit' style='margin-left:12px;' id='<?=self::$MODULE_LBL?>btn'><?=Tools::getMessage('BTN_EXPORT')?></a>");
            }

            // Standard
            if ($('.adm-detail-toolbar').find('.adm-detail-toolbar-right').length)
            {
                $('.adm-detail-toolbar').find('.adm-detail-toolbar-right').prepend("<a href='javascript:void(0)' onclick='<?=self::$MODULE_LBL?>export.getPage(\"main\").open()' class='adm-btn' id='<?=self::$MODULE_LBL?>btn'><?=Tools::getMessage('BTN_EXPORT')?></a>");
            }


            var btn = $('#<?=self::$MODULE_LBL?>btn');

            switch (this.self.status) {
                case 'new'       :
                    break;
                case 'rejected'  :
                    btn.css('color', '#F13939');
                    break;
                default       :
                    btn.css('color', '#3A9640');
                    break;
            }
            var html = $('#<?=self::$MODULE_LBL?>PLACEFORFORM').html();
            $('#<?=self::$MODULE_LBL?>PLACEFORFORM').html(' ');

            if (!html) {
                this.self.log('unable to load data of the order');
            }
            else {
                <?php
                $error = false;
                if(!Warhouses::getWHInfo()){
                    $error = Tools::getMessage('ERROR_NOWARHOUSES');
                } elseif(!Option::get('barkID')){
                    $error = Tools::getMessage('ERROR_NOBARKID');
                }

                if (!$error && Adapter::statusIsSending(self::$status))
                    self::addButton("<input id='" . self::$MODULE_LBL . "sender' type='button' onclick='" . self::$MODULE_LBL . "export.getPage(\"main\").send()' value='" . Tools::getMessage('BTN_SEND') . "'>");
                else {
                    if (!Adapter::statusIsFinal(self::$status)) {
                        self::addButton("<input id='" . self::$MODULE_LBL . "checkStatus' type='button' onclick='" . self::$MODULE_LBL . "export.getPage(\"main\").act.checkStatus()' value='" . Tools::getMessage('BTN_CHECKSTATE') . "'>");
                    }
                    if (Adapter::statusIsReady(self::$status)) {
                        self::addButton("<input id='" . self::$MODULE_LBL . "getSticker' type='button' onclick='" . self::$MODULE_LBL . "export.getPage(\"main\").act.getSticker()' value='" . Tools::getMessage('BTN_GETSTICKER') . "'>");
                    }
                    if(Adapter::statusIsCancelable(self::$status)){
                        self::addButton("<input id='" . self::$MODULE_LBL . "cancelOrder' type='button' onclick='" . self::$MODULE_LBL . "export.getPage(\"main\").act.cancelOrder()' value='" . Tools::getMessage('BTN_CANCELORDER') . "'>");
                    }
                }
                self::addButton("<input id='" . self::$MODULE_LBL . "editGoods' type='button' onclick='" . self::$MODULE_LBL . "export.getPage(\"goods\").open()' value='" . Tools::getMessage('BTN_GOODSEDIT') . "'>");
                //self::addButton("<input id='" . self::$MODULE_LBL . "editLots'   type='button' onclick='" . self::$MODULE_LBL . "export.getPage(\"lots\").open()' value='" . Tools::getMessage('BTN_LOTSEDIT') . "'>");

                ?>
                this.mainWnd = new idemo_wndController({
                    title: '<?=Tools::getMessage('HDR_EXPORT')?>',
                    content: html,
                    resizable: true,
                    draggable: true,
                    height: '600',
                    width: '565',
                    buttons: <?=\CUtil::PhpToJSObject(self::$arButtons)?>
                });
                <?php if($error){?>this.self.error='<?=$error?>';<?}?>
            }

            this.act(this);
            this.events(this);
            this.onSend(this);
            this.onCalculate(this);

            this.widget.load();

            this.events.onPayedChange();
        },

        // wnd
        mainWnd : false,
        loaded  : false,

        open: function () {
            if (this.mainWnd)
                this.mainWnd.open();

            if(this.self.error){
                alert(this.self.error);
            }
        },

        // calculating
        calculate: function () {
//            $('#<?//=self::$MODULE_LBL?>//DeliveryModeContainer').html('<img src="<?//=Tools::getImagePath()?>//bigAjax.gif">');
//            $('#<?//=self::$MODULE_LBL?>//DeliveryModeError').html('');
//            $('#<?//=self::$MODULE_LBL?>//DeliveryModeError').addClass('<?//=self::$MODULE_LBL?>//hidden');
//            $('#<?//=self::$MODULE_LBL?>//DeliveryModeWarn').addClass('<?//=self::$MODULE_LBL?>//hidden');
//            var data = this.getInputs(true);
//
//            this.self.ajax({
//                data: this.self.concatObj(data.inputs, {
//                    <?//=self::$MODULE_LBL?>//action: 'calculateOrder',
//                    orderId: this.self.orderId
//                }),
//                dataType: 'json',
//                success: this.onCalculate
//            });
        },

        onCalculate: (function (self) {
        }),


// sending
        send: function () {
            $('#<?=self::$MODULE_LBL?>sender').css('display', 'none');
            $('.<?=self::$MODULE_LBL?>errInput').removeClass('<?=self::$MODULE_LBL?>errInput');
            var data = this.getInputs();
            if (data.success) {
                this.self.ajax({
                    data: this.self.concatObj(data.inputs, {
                        <?=self::$MODULE_LBL?>action: 'sendOrder',
                        orderId    : this.self.orderId,
                        shipmentId : this.self.shipmentId,
                        workMode   : this.self.workMode
                    }),
                    dataType: 'json',
                    success: this.onSend
                });
            }
            else {
                var alertStr = "<?=Tools::getMessage('MESS_NOTSENDED')?>\n<?=Tools::getMessage('MESS_FILL')?>";
                var headerDiff = {};
                for (var i in data.errors) {
                    var handler = $('#<?=self::$MODULE_LBL?>' + i);
                    handler.addClass('<?=self::$MODULE_LBL?>errInput');

                    handler = handler.parent().parent();

                    var label = (handler.children(':first-child').find('label').length) ? handler.children(':first-child').find('label').text().trim() : handler.children(':first-child').text().trim();
                    var header = false;
                    var iter = 0;

                    while (!header && iter < 30) {
                        if (handler.prev('.heading').length)
                            header = handler.prev('.heading').text().trim();
                        else
                            handler = handler.prev();
                        iter++;
                    }
                    if (typeof(headerDiff[header]) === 'undefined')
                        headerDiff[header] = {};
                    headerDiff[header][label] = label;
                }
                for (var i in headerDiff) {
                    alertStr += "\n" + i + ": ";
                    for (var j in headerDiff[i]) {
                        alertStr += j + ", ";
                    }
                    alertStr = alertStr.substring(0, alertStr.length - 2);
                }
                alert(alertStr);
                $('#<?=self::$MODULE_LBL?>sender').css('display', '');
            }
        },

        getInputs: function (giveAnyway) {
            var depths = this.dependences();

            var data = {
                inputs: {},
                errors: {}
            };

            for (var i in depths) {
                if (typeof(depths[i].need) !== 'undefined') {
                    var preVal = $('#<?=self::$MODULE_LBL?>' + i).val();
                    if ($('#<?=self::$MODULE_LBL?>' + i).attr('type') === 'checkbox')
                        preVal = ($('#<?=self::$MODULE_LBL?>' + i).prop('checked')) ? true : false;
                    if (typeof(depths[i].link) !== 'undefined') {
                        var checkVal = $('#<?=self::$MODULE_LBL?>' + depths[i].link).val();
                        if ($('#<?=self::$MODULE_LBL?>' + depths[i].link).attr('type') === 'checkbox')
                            checkVal = ($('#<?=self::$MODULE_LBL?>' + i).prop('checked')) ? true : false;
                    }
                    switch (depths[i].need) {
                        case 'dep' :
                            if (preVal)
                                data.inputs[i] = preVal;
                            else if (!checkVal)
                                data.errors[i] = i;
                            break;
                        case 'sub' :
                            var need = (typeof(depths[i].checkVal) !== 'undefined') ? (checkVal === depths[i].checkVal) : checkVal;
                            if (need) {
                                if (preVal)
                                    data.inputs[i] = preVal;
                                else
                                    data.errors[i] = i;
                            }
                            break;
                        case true :
                            if (preVal)
                                data.inputs[i] = preVal;
                            else
                                data.errors[i] = i;
                            break;
                        case false :
                            if (preVal)
                                data.inputs[i] = preVal;
                            break;
                    }
                }
            }

            if(this.self.isEmpty(this.self.getPage('goods').info)){
                data.inputs.items = this.self.getPage('goods').autoFill();
            } else {
                data.inputs.items = this.self.getPage('goods').info;
            }

            if (this.self.isEmpty(data.errors) || (typeof(giveAnyway) !== 'undefined' && giveAnyway))
                return {success: true, inputs: data.inputs};
            else
                return {success: false, errors: data.errors};
        },

        onSend: (function (self) {
            self.onSend = function (data) {
                if (data.success) {
                    alert("<?=Tools::getMessage('MESS_SENDED')?>" + data.demoUuid);
                    self.mainWnd.close();
                    window.location.reload();
                }
                else {
                    var str = '<?=Tools::getMessage('MESS_NOTSENDED')?>';
                    if (typeof(data.error) !== 'undefined') {
                        str += "\n" + data.error;
                    }

                    $('#<?=self::$MODULE_LBL?>sender').css('display', '');

                    alert(str);
                }
            };
        }),

        dependences: function () {
            var reqs = {
                number   : {need: true},
                barcode  : {need: false},
                barcodeGenerateByServer : {need: false},
                brandName: {need: false},
                clientName: {need: true},
                clientEmail: {need: true},
                clientPhone: {need: true},
                plannedReceiveDate: {need: false},
                receiverLocation: {need: true},
                senderCreateDate: {need: false},
                senderLocation: {need: true},
                shipmentDate : {need: false},
                undeliverableOption: {need: true},

                height: {need: true},
                length: {need: true},
                width: {need: true},
                weight: {need: true},

                goodsPrice:    {need: false},
                deliveryPrice: {need: false},
                estimatedCost: {need: true},
                payed:         {need: false},

                currency:             {need: true},
                deliveryCostCurrency: {need: 'sub', link: 'deliveryPrice'},
                paymentCurrency:      {need: true},
                prepaymentCurrency:   {need: true},
                priceCurrency:        {need: true},

                //price: {need: true},
                //deliveryCost: {need: false},
                //payment_sum: {need: false},
                //payment_prepayment: {need: false},
                payment_isBeznal: {need: false},
                paymentType:      {need: false},
            };

            return reqs;
        },

// actions
        act: (function (self) {
            self.act = {
                selectNewPvz : function () {
                    if(self.widget.ready){
                        self.widget.open();
                    } else {
                    }
                },
                checkStatus: function () {
                    $('#<?=self::$MODULE_LBL?>checkStatus').css('display', 'none');
                    self.self.ajax({
                        data: {
                            <?=self::$MODULE_LBL?>action: 'checkStatusByBitrixIAjax',
                            bitrixId: self.self.orderId
                        },
                        success: function (data) {
                            window.location.reload();
                        }
                    });
                },
                getSticker: function () {
                    $('#<?=self::$MODULE_LBL?>getSticker').css('display', 'none');
                    if ($('#<?=self::$MODULE_LBL?>barcodeGenerateByServer').val()) {
                        /* Server-side barcode generation */
                        self.self.ajax({
                            data: {
                                <?=self::$MODULE_LBL?>action: 'getStickerRequest',
                                bitrixId: self.self.orderId
                            },
                            dataType: 'json',
                            success: function(data){
                                if (data.success) {
                                    if (data.files !== 'undefined') {
                                        for (var i in data.files) {
                                            if (!data.files.hasOwnProperty(i))
                                                continue;
                                            window.open(data.files[i]);
                                        }
                                    }
                                    if (data.errors) {
                                        setTimeout(() => alert(data.errors), 1000);
                                    }
                                } else {
                                    alert('<?=Tools::getMessage("MESS_STICKER_ERROR")?>' + data.errors);
                                }
                                $('#<?=self::$MODULE_LBL?>getSticker').css('display', '');
                            }
                        });
                    } else {
                        /* Module-side barcode generation */
                        window.open("<?=Tools::getJSPath()."ajax.php?".self::$MODULE_LBL."action=printBKsRequest&bitrixId=".self::$orderId?>");
                        $('#<?=self::$MODULE_LBL?>getSticker').css('display', '');
                    }
                },
                cancelOrder : function(){
                    if(confirm('<?=Tools::getMessage('MESS_DOCANCEL')?>')){
                        $('#<?=self::$MODULE_LBL?>cancelOrder').css('display', 'none');
                        self.self.ajax({
                            data: {
                                <?=self::$MODULE_LBL?>action: 'deleteOrder',
                                bitrixId: self.self.orderId
                            },
                            dataType: 'json',
                            success: function (data) {
                                if(data.success){
                                    alert('<?=Tools::getMessage('MESS_CANCELED')?>');
                                    window.location.reload();
                                } else {
                                    alert('<?=Tools::getMessage('MESS_NOTCANCELED')?>'+data.error);
                                    $('#<?=self::$MODULE_LBL?>cancelOrder').css('display', '');
                                }
                            }
                        });
                    }

                },
            }
        }),

// events, lol
        events: (function (self) {
            self.events = {
                onDeliveryPriceChange: function () {
                    var delivery   = $('#<?=self::$MODULE_LBL?>deliveryPrice');
                    var total      = $('#<?=self::$MODULE_LBL?>paymentTotal');
                    var totalLabel = $('#<?=self::$MODULE_LBL?>hidLabel_paymentTotal');

                    if (!($('#<?=self::$MODULE_LBL?>paymentIsBeznal').prop('checked'))) {
                        var totalPayed = parseFloat(delivery.val()) + parseFloat(self.self.goodsPrice) - parseFloat(self.self.payed);

                        total.val(totalPayed);
                        totalLabel.html(totalPayed);
                    }
                },
                onPayedChange : function () {
                    var delivery    = $('#<?=self::$MODULE_LBL?>deliveryPrice');
                    var paymentType = $('#<?=self::$MODULE_LBL?>paymentType');
                    var total       = $('#<?=self::$MODULE_LBL?>paymentTotal');
                    var totalLabel  = $('#<?=self::$MODULE_LBL?>hidLabel_paymentTotal');

                    if ($('#<?=self::$MODULE_LBL?>payment_isBeznal').prop('checked')) {
                        delivery.val(0);
                        delivery.attr('readonly', 'readonly');

                        total.val(0);
                        totalLabel.html(0);

                        paymentType.children().each(function (ind, stuff) {
                            if ($(stuff).val() === 'PREPAYMENT') {
                                $(stuff).removeAttr('disabled');
                                $(stuff).attr('selected', 'selected');
                            } else
                                $(stuff).attr('disabled', 'disabled');
                        });

                    } else {
                        var totalPayed;

                        totalPayed = parseFloat(self.self.deliveryPrice) + parseFloat(self.self.goodsPrice) - parseFloat(self.self.payed);

                        total.val(totalPayed);
                        totalLabel.html(totalPayed);

                        delivery.val(self.self.deliveryPrice);
                        delivery.removeAttr('readonly');

                        paymentType.children().each(function (ind, stuff) {
                            if ($(stuff).val() === 'PREPAYMENT') {
                                $(stuff).attr('disabled', 'disabled');
                                $(stuff).removeAttr('selected');
                            } else {
                                $(stuff).removeAttr('disabled');
                            }
                        });
                    }
                },
                onPlannedReceiveDateChange : function (stuff) {
                    $('#<?=self::$MODULE_LBL?>plannedReceiveDate').val(parseInt(stuff.getTime()/1000));
                },
                onShipmentDateChange : function (stuff) {
                    $('#<?=self::$MODULE_LBL?>shipmentDate').val(parseInt(stuff.getTime()/1000));
                },
                onEstimatedCostChange : function(estimatedTotal) {
                    if (estimatedTotal >= 0) {
                        $('#<?=self::$MODULE_LBL?>estimatedCost').val(estimatedTotal);
                        $('#<?=self::$MODULE_LBL?>hidLabel_estimatedCost').html(estimatedTotal);
                    }
                },
            }
        }),

        widget : {
            ready : false,

            controller : false,

            load  : function () {
                <?php if ($order->getAddressTo()->getCode()) {?>
                this.controller = new IPOL_DEMO_Widjet({
                    popup: true,
                    defaultCity : '<?=$order->getAddressTo()->getCode()?>',
                    path        : '<?=Tools::getJSPath()?>widjet/scripts/',
                    servicepath : '<?=Tools::getJSPath()?>ajax.php',
                    apikey      : '<?=(Option::get('ymapsAPIKey')) ?: \Bitrix\Main\Config\Option::get('fileman', 'yandex_map_api_key', 'ad06a7e1-2f4f-42a8-88ea-72f24589c578')?>',
                    yMapsSearch     : <?=(Option::get('widgetSearch') === 'Y') ? 'true' : 'false'?>,
                    yMapsSearchMark : <?=(Option::get('widgetSearchMark') === 'Y') ? 'true' : 'false'?>,
                    showListPVZ     : <?=(Option::get('widgetShowListPvz') === 'Y') ? 'true' : 'false'?>,
                    noCitySelector : true,
                    onReady : function(){
                        $('#<?=self::$MODULE_LBL?>pvzPickerPreloader').css('display','none');
                        $('#<?=self::$MODULE_LBL?>pvzPickerPicker').css('display','inline');
                        <?=self::$MODULE_LBL?>export.getPage('main').widget.ready = true;
                    },
                    goods : <?=\CUtil::PhpToJSObject(array(array(
                        'length'     => $order->getGoods()->getLength(),
                        'width'      => $order->getGoods()->getWidth(),
                        'height'     => $order->getGoods()->getHeight(),
                        'price'      => $order->getPayment()->getGoods()->getAmount(),
                        'weight'     => $order->getGoods()->getWeight()
                    )))?>,
                    onChoose : <?=self::$MODULE_LBL?>export.getPage('main').widget.selectPVZ,
                    choose   : <?=(Adapter::statusIsSending($order->getStatus())) ? 'true' : 'false'?>
                });
                this.controller.setCalcRequestConcat({
                    getOrder : '<?=self::$orderId?>',
                });
                <?php } else {?>
                    $('#<?=self::$MODULE_LBL?>pvzPickerPreloader').css('display','none');
                    $('#<?=self::$MODULE_LBL?>pvzPickerPickerError').css('display','inline');
                <?php }?>
            },

            open : function () {
                this.controller.open();
            },

            selectPVZ : function (PVZ) {
                $('#<?=self::$MODULE_LBL?>receiverLocation').val(PVZ.PVZ.POINT_GUID);
                $('#<?=self::$MODULE_LBL?>hidLabel_receiverLocation').html(PVZ.PVZ.FULL_ADDRESS);
                $('#<?=self::$MODULE_LBL?>hidLabel_receiverLocationID').html(PVZ.PVZ.POINT_GUID);
                <?=self::$MODULE_LBL?>export.getPage('main').widget.controller.close();
            }
        },

// ui
        ui: {
            toggleBlock: function (code) {
                $('.<?=self::$MODULE_LBL?>block_' + code).toggle();
            },
            makeUnseen: function (wat, mode) {
                if (mode) {
                    wat.addClass('<?=self::$MODULE_LBL?>unseen');
                }
                else {
                    wat.removeClass('<?=self::$MODULE_LBL?>unseen');
                }
            }
        }
    });
</script>