<?php
$MESS ['IPOL_DEMO_DELIVERY_NAME'] = "Служба доставки demo";
$MESS ['IPOL_DEMO_DELIVERY_DESCRIPTION'] = "Доставка заказа транспортной службой компании \"ДЕМО\".";

$MESS ['IPOL_DEMO_DELIVERY_HANDLER_STATUS_TAB_TITLE'] = "Состояние службы доставки";
$MESS ['IPOL_DEMO_DELIVERY_HANDLER_STATUS_TAB_DESCR'] = "Дополнительная информация о состоянии службы доставки и текущих настройках модуля";

$MESS ['IPOL_DEMO_DELIVERY_HANDLER_STATUS_TAB_APIKEY']           = "Текущий ApiKey:";
$MESS ['IPOL_DEMO_DELIVERY_HANDLER_STATUS_TAB_SYNC_DATA']        = "Синхронизация данных:";
$MESS ['IPOL_DEMO_DELIVERY_HANDLER_STATUS_TAB_SYNC_DATA_Y']      = "<span style='color:green'>Выполнена</span>";
$MESS ['IPOL_DEMO_DELIVERY_HANDLER_STATUS_TAB_SYNC_DATA_N']      = "<span style='color:red'>Не выполнена</span> -> <a href='/bitrix/admin/ipol_demo_sync_data.php' target='_blank'>Перейти к синхронизации</a>";
$MESS ['IPOL_DEMO_DELIVERY_HANDLER_STATUS_TAB_POINTS_LOADED']    = "Точки самовывоза:";
$MESS ['IPOL_DEMO_DELIVERY_HANDLER_STATUS_TAB_LOCATIONS_LOADED'] = "Города:";

$MESS ['IPOL_DEMO_DELIVERY_HANDLER_ERROR_NO_AUTH_TITLE'] = "Ошибка авторизации";
$MESS ['IPOL_DEMO_DELIVERY_HANDLER_ERROR_NO_AUTH_DESCR'] = "<p>Для использования службы доставки необходимо авторизоваться на <a href='/bitrix/admin/settings.php?lang=ru&mid=ipol.demo&mid_menu=1' target='_blank'>странице настроек модуля</a><p>";

$MESS ['IPOL_DEMO_DELIVERY_HANDLER_ERROR_NO_SYNC_TITLE'] = "Не выполнена загрузка и синхронизация внешних данных";
$MESS ['IPOL_DEMO_DELIVERY_HANDLER_ERROR_NO_SYNC_DESCR'] = "<p>Для использования службы доставки необходимо <a href='/bitrix/admin/ipol_demo_sync_data.php' target='_blank'>выполнить загрузку и синхронизацию внешних данных</a> по точкам самовывоза, тарифам и городам. <br>В противном случае обработчик не сможет работать и служба доставки не будет выводиться на странице оформления заказа.<p>";

$MESS ['IPOL_DEMO_DELIVERY_CALC_ERROR_NO_DIRECT_CALL'] = "Расчет стоимости доставки возможен только конкретным профилем обработчика службы доставки";