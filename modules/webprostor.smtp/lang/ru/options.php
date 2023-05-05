<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$MESS["WEBPROSTOR_SMTP_NO_ACCESS"] = "У вас нет права доступа к настройкам модуля.";
$MESS["WEBPROSTOR_SMTP_NOT_USE_BY_PHPMAILER"] = "Не учитывается с PHPMailer";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_MAIN"] = "Настройки";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_MODULE"] = "Включить модуль";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_PHPMAILER"] = "Использовать PHPMailer";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_PHPMAILER_NOTES"] = "Необходим для интеграции с модулями Рассылки, Email-маркетинг<br />Рекомендуется отключить опцию главного модуля \"Генерировать текстовую версию для html-писем\"";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_AUTO_ADD_INIT"] = "Создавать init.php для новых сайтов";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_AUTO_DEL_INIT"] = "Удалять init.php при удалении сайта";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_LOGS"] = "Журнал операций";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_LOG_ERRORS"] = "Включить логирование";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_INCLUDE_SEND_INFO_TO_LOG"] = "Добавлять тело письма к результату отправки";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_LOG_SEND_OK"] = "Записывать успешную отправку письма";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_LOG_COMMANDS"] = "Записывать выполняемые команды";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_SENDER"] = "Рассылки, Email-маркетинг";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_SENDER_SMTP"] = "Использовать отдельный smtp-сервер";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_SENDER_SMTP_NOTES"] = "Настройка модулей описана в инструкции";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_CONNECTION"] = "Настройки соединения";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SERVER"] = "Сервер";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SERVER_NOTES"] = "smtp.yandex.ru - для Яндекс.Почта, <br />smtp.gmail.com - для Gmail<br />smtp.mail.ru - для Mail.ru";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SERVER_SENDER_NOTES"] = "smtp-pulse.com - SendPulse";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_PORT"] = "Порт";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_PORT_NOTES"] = "25 - для незащищенного соединения, <br />465 - для защищенного SSL-соединения, <br />587 - для защищенного TLS-соединения";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SECURE"] = "Защита";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SECURE_NO"] = "Не использовать";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_AUTHORIZATION"] = "Авторизация";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_REQUIRES_AUTHENTICATION"] = "Требуется авторизация";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_HELO_COMMAND"] = "Тип приветствия";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_HELO_COMMAND_EHLO"] = "Расширенное HELO (EHLO)";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_LOGIN"] = "Логин";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_PASSWORD"] = "Пароль";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_XOAUTH2"] = "Использовать авторизацию XOAUTH2 (OAuth-токены)";

$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM"] = "Заменять адрес отправителя на логин авторизации";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM_TO_EMAIL"] = "Заменять адрес отправителя на email";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_SENDING"] = "Отправка";
$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_MAIL"] = "Написать письмо (тестовая отправка)";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_FROM"] = "Имя отправителя";
$MESS["WEBPROSTOR_SMTP_OPTIONS_REPLY_TO"] = "E-mail для ответа";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_CHARSET"] = "Кодировка";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_PRIORITY"] = "Важность";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_PRIORITY_HIGHT"] = "Высокая";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_PRIORITY_NORMAL"] = "Нормальная";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_PRIORITY_LOW"] = "Низкая";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DUPLICATE"] = "Дублировать сообщения на email из главного модуля";
?>