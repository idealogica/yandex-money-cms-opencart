<?php
// Heading
$_['heading_title']      = 'Яндекс.Деньги';

// Text
$_['text_yandexmoney']   = '<a onclick="window.open(\'https://money.yandex.ru\');"><img src="view/image/payment/yandexmoney.png" alt="Яндекс.Деньги" title="Яндекс.Деньги" /></a>';

$_['text_yes']       = 'Да';
$_['text_no']       = 'Нет';
$_['text_pay']       = 'Оплата';
$_['text_success']       = 'Настройки модуля обновлены!';
$_['text_all_zones']       = 'Все зоны';
$_['text_disabled']       = 'Выключено';
$_['text_enabled']       = 'Включено';
$_['text_need_update']       = "У вас неактуальная версия модуля. Вы можете <a target='_blank' href='https://github.com/yandex-money/yandex-money-cms-opencart/releases'>загрузить и установить</a> новую (%s)";

$_['yandexmoney_license']       = '<p>Любое использование Вами программы означает полное и безоговорочное принятие Вами условий лицензионного договора, размещенного по адресу  <a href="https://money.yandex.ru/doc.xml?id=527132"> https://money.yandex.ru/doc.xml?id=527132 </a>(далее – «Лицензионный договор»). Если Вы не принимаете условия Лицензионного договора в полном объёме, Вы не имеете права использовать программу в каких-либо целях.</p>';

$_['text_welcome1']       = '<p>Если у вас нет аккаунта в Яндекс-Деньги, то следует зарегистрироваться тут - <a href="https://money.yandex.ru/">https://money.yandex.ru/</a></p><p><b>ВАЖНО!</b> Вам нужно будет указать ссылку для приема HTTP уведомлений здесь - <a href="https://sp-money.yandex.ru/myservices/online.xml" target="_blank">https://sp-money.yandex.ru/myservices/online.xml</a>';

$_['text_welcome2']       = '<p>Для работы с модулем необходимо <a href="https://money.yandex.ru/joinups/">подключить магазин к Яндекc.Кассе</a>. После подключения вы получите параметры для приема платежей (идентификатор магазина — shopId и номер витрины — scid).</p>';

$_['text_params']       = 'Параметры для заполнения в личном кабинете';
$_['text_param_name']       = 'Название параметра';
$_['text_param_value']       = 'Значение';
$_['text_aviso1']       = 'Адрес приема HTTP уведомлений';
$_['text_aviso2']       = 'checkURL / avisoURL';
$_['title_default']       = 'Yandex Payment Solution (bank cards, e-money, and other payment methods)';


// Entry
$_['entry_version']         = 'Версия модуля:';
$_['entry_license']         = 'Лицензионный договор:';
$_['entry_testmode']         = 'Использовать в тестовом режиме?';
$_['entry_modes']         = 'Выберите режим оплаты:';
$_['entry_mode1']         = 'На счет физического лица в электронной валюте Яндекс.Денег';
$_['entry_mode2']         = 'На расчетный счет организации с заключением договора с Яндекс.Деньгами';

$_['entry_methods']         = 'Укажите необходимые способы оплаты:';
$_['entry_method_ym']         = 'Оплата из кошелька в Яндекс.Деньгах';
$_['entry_method_cards']         = 'Оплата с произвольной банковской карты';
$_['entry_method_cash']         = 'Оплата наличными через кассы и терминалы';
$_['entry_method_mobile']         = 'Платеж со счета мобильного телефона';
$_['entry_method_wm']         = 'Оплата из кошелька в системе WebMoney';
$_['entry_method_ab']         = 'Оплата через Альфа-Клик';
$_['entry_method_sb']         = 'Оплата через Сбербанк: оплата по SMS или Сбербанк Онлайн';
$_['entry_method_ma']         = 'Оплата через MasterPass';
$_['entry_method_pb']         = 'Оплата через интернет-банк Промсвязьбанка';
$_['entry_method_qp']         = 'Оплата через доверительный платеж (Куппи.ру)';
$_['entry_method_qw']         = 'Оплата через QIWI Wallet';
$_['entry_method_mp']         = 'Оплата через мобильный терминал';
$_['entry_default_method']         = 'Способ оплаты по умолчанию';

$_['entry_page_mpos']         = 'Страница с инструкцией для платежей через мобильный терминал';
$_['entry_page_success']         = 'Пользовательская страница успеха';
$_['entry_page_fail']         = 'Пользовательская страница отказа';

$_['entry_shopid']         = 'Идентификатор вашего магазина в Яндекс.Деньгах (ShopID):';
$_['entry_scid']         = 'Идентификатор витрины вашего магазина в Яндекс.Деньгах (scid):';
$_['entry_title'] 	= 'Наименование платежного сервиса:';
$_['entry_total']         = 'Минимальная сумма:';
$_['entry_total2']         = 'Минимальная сумма заказа. Ниже этой суммы метод будет недоступен.';


$_['entry_password']         = 'Секретное слово (shopPassword) для обмена сообщениями:';
$_['entry_account']         = 'Номер кошелька Яндекс:';

$_['entry_order_status'] = 'Статус заказа после оплаты:';
$_['entry_notify'] = 'Уведомлять плательщика о смене статуса';
$_['entry_geo_zone']     = 'Географическая зона:';
$_['entry_status']       = 'Статус:';
$_['entry_sort_order']   = 'Порядок сортировки:';

// Error
$_['error_permission']   = 'У Вас нет прав для управления этим модулем!';
$_['error_methods']   = 'Нужно выбрать хотя бы один способ оплаты!';
$_['error_password']   = 'Это поле обязательно к заполнению!';
$_['error_shopid']   = 'Это поле обязательно к заполнению!';
$_['error_scid']   = 'Это поле обязательно к заполнению!';
$_['error_title']   = 'Это поле обязательно к заполнению!';
$_['error_account']   = 'Это поле обязательно к заполнению!';
?>