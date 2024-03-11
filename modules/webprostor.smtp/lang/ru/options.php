<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$MESS["WEBPROSTOR_SMTP_NO_ACCESS"] = "У вас нет права доступа к настройкам модуля.";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_MAIN"] = "Настройки";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_MODULE"] = "Включить модуль";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_PHPMAILER"] = "Использовать PHPMailer";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_PHPMAILER_NOTES"] = "Рекомендуется для большинства почтовых сервисов.<br />Необходим для интеграции с модулями Рассылки, Email-маркетинг<br />Рекомендуется отключить опцию главного модуля \"Генерировать текстовую версию для html-писем\"";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_AUTO_ADD_INIT"] = "Создавать init.php для новых сайтов";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_AUTO_DEL_INIT"] = "Удалять init.php при удалении сайта";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_DEFAULT_SITE_ID_IF_EMPTY"] = "Использовать настройки сайта по умолчанию, если не удалось определить ID сайта";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_LOGS"] = "Журнал сообщений";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_LOG_ERRORS"] = "Вести журнал отправки сообщений";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_NOTIFY_LIMIT"] = "Лимит записей для уведомления";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_AUTO_CLEANING_LOGS"] = "Автоматически очищать журнал отправки сообщений при достижении заданного лимита";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DONT_SAVE_SEND_INFO"] = "Не сохранять конечный текст письма";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_RESEND"] = "Повторная отправка";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_ENABLE_RESEND"] = "Отправлять повторно неотправленные письма";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_RESEND_RETRY_COUNT"] = "Максимальное количество попыток";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_RESEND_AGENT_INTERVAL"] = "Интервал работы агента (сек.)";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_RESEND_MESSAGE_COUNT"] = "Количество писем за одну попытку";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_DEBUG"] = "Отладка";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL"] = "Уровень отладки";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_NOTE"] = "Логи будут записываться в файл #FILE_PATH#";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_NOTE_ENABLE"] = "Для включения отладки необходимо указать в настройках PHP значение error_log";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_OFF"] = "-- Выключено --";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_CLIENT"] = "Сообщения и ошибки";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_SERVER"] = "Сообщения, ошибки и ответ сервера";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_CONNECTION"] = "Сообщения, ошибки, информация о соединении и ответ сервера";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_LOWLEVEL"] = "Вся информация";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_SENDER"] = "Рассылки, Email-маркетинг";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_SENDER_SMTP"] = "Использовать отдельный smtp-сервер";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_SENDER_SMTP_NOTES"] = "Настройка модулей описана в <a target=\"_blank\" href=\"https://webprostor.ru/learning/course/course6/lesson226/\">инструкции</a>";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_CONNECTION"] = "Настройки соединения";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SERVER"] = "Сервер";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SERVER_NOTES"] = "<strong>smtp.yandex.ru</strong> - для Яндекс.Почта, <br /><strong>smtp.gmail.com</strong> - для Gmail<br /><strong>smtp.mail.ru</strong> - для Mail.ru<br /><strong>smtp.timeweb.ru</strong> - для Timeweb";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SERVER_SENDER_NOTES"] = "<strong>smtp-pulse.com</strong> - SendPulse";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_PORT"] = "Порт";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_PORT_NOTES"] = "25 - для незащищенного соединения, <br />465 - для защищенного SSL-соединения, <br />587 - для защищенного TLS-соединения";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SECURE"] = "Защита";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SECURE_NOTES"] = "Защита соединения с сервером. В большинстве случаев используется SSL";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SECURE_NO"] = "Не использовать";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_AUTHORIZATION"] = "Авторизация";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_REQUIRES_AUTHENTICATION"] = "Требуется авторизация";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_REQUIRES_AUTHENTICATION_NOTES"] = "Авторизация требуется для большинства smtp серверов";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_LOGIN"] = "Логин";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_LOGIN_NOTES"] = "Полное имя почтового ящика, включая логин, @ и домен";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_PASSWORD"] = "Пароль";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_PASSWORD_NOTES"] = "Для <strong>yandex.ru</strong> можно указать пароль от учетной записи или создать отдельный <a target=\"_blank\" href=\"https://id.yandex.ru/profile/apppasswords-list\">пароль для приложения</a>, тип Почта<br />Для <strong>mail.ru</strong> необходимо указать <a target=\"_blank\" href=\"https://account.mail.ru/user/2-step-auth/passwords/\">пароль для внешнего приложения</a>.<br />Для остальных сервисов, в основном, указывается пароль от учетной записи.";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_XOAUTH2"] = "Использовать авторизацию XOAUTH2 (OAuth-токены)";

$MESS["WEBPROSTOR_SMTP_OPTIONS_GROUP_DKIM"] = "DKIM-подпись";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_DKIM"] = "Использовать подпись";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_DOMAIN"] = "Домен";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_DOMAIN_NOTES"] = "Название домена без https://";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_SELECTOR"] = "Селектор";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_SELECTOR_NOTES"] = "По умолчанию mail";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_PASSPHRASE"] = "Ключевая фраза";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_PASSPHRASE_NOTES"] = "По умолчанию пустое";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_PRIVATE_STRING"] = "Приватный ключ";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_PRIVATE_STRING_NOTES"] = "Содержимое файла ключа между -----BEGIN PUBLIC KEY----- и -----END PUBLIC KEY-----";

$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM"] = "Заменять адрес отправителя на логин авторизации";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM_NOTES"] = "Если E-Mail адрес по умолчанию отличается от логина авторизации";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM_TO_EMAIL"] = "Заменять адрес отправителя на E-Mail";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM_TO_EMAIL_NOTES"] = "Если необходимо указать иной E-mail отправителя";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM_NAME"] = "Имя отправителя для замены";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM_NAME_NOTES"] = "Если необходимо указать имя отправителя, отличное от названия веб-сайта";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DSN"] = "Отправлять уведомление о доставке";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DSN_NOTES"] = "<font color=\"red\"><strong>Внимание!</strong></font> Не все почтовые сервисы получателей поддерживают отправку уведомления о доставке.<br />Письма могут быть не отправлены";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DSN_SUCCESS"] = "Успешная отправка";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DSN_FAILURE"] = "Произошла ошибка";
$MESS["WEBPROSTOR_SMTP_OPTIONS_OPTION_DSN_DELAY"] = "Задержка доставки";

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