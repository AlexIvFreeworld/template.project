<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$MESS["SETTING_1"] = "Не указаны настройки для подключения к серверу";
$MESS["SETTING_2"] = "Не указаны авторизационные данные";
$MESS["SETTING_3"] = "Не указан тип защиты соединения SSL для порта 465";
$MESS["SETTING_4"] = "Не указан тип защиты соединения TLS для порта 587";
$MESS["SETTING_5"] = "Список получателей пуст";
$MESS["ERROR_0"] = "Не удалось подключиться к серверу #SMTP_HOST# через порт #SMTP_PORT#. Ошибка номер: #ERROR_NUMBER#. Текст ошибки: #ERROR_TEXT#";
$MESS["ERROR_1"] = "Не удалось отправить команду #HELO_COMMAND# на сервер #SMTP_HOST#";
$MESS["ERROR_2"] = "Не удалось найти ответ на запрос авторизаци";
$MESS["ERROR_3"] = "Логин авторизации #LOGIN# не был принят сервером";
$MESS["ERROR_4"] = "Пароль не был принят сервером как верный\r\nОшибка авторизации";
$MESS["ERROR_5"] = "Не удалось отправить комманду MAIL FROM:";
$MESS["ERROR_6"] = "Не удалось отправить комманду RCPT TO: <#RECIPIENT#>";
$MESS["ERROR_7"] = "Не удалось отправить комманду DATA";
$MESS["ERROR_8"] = "Не удалось отправить тело письма\r\nПисьмо не было отправленно";
$MESS["ERROR_9"] = "Произошли проблемы с отправкой почты. Ответ сервера #SERVER_RESPONSE#";
$MESS["ERROR_10"] = "Не удалось сменить защиту соединения на TLS";
$MESS["ERROR_11"] = "Невозможно запустить шифрование TLS";
$MESS["OK_1"] = "Сообщение с темой #SUBJECT# и текстом #MESSAGE# успешно отправлено получателю #TO#\r\n#ADDITIONAL_HEADERS#\r\n#ADDITIONAL_PARAMETERS#";
$MESS["OK_2"] = "Сообщение успешно отправлено получателям #RECIPIENTS#";

$MESS["LOGS_ARE_TOO_BIG"] = "webprostor.smtp: Слишком большой объемов логов! Это может привести к падению БД. Очистите журнал операций. <a href=\"/bitrix/admin/webprostor.smtp_logs.php?lang=".LANGUAGE_ID."\">перейти в журнал</a>";
?>