<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");

$module_id = 'webprostor.smtp';
$moduleAccessLevel = $APPLICATION->GetGroupRight($module_id);

if ($moduleAccessLevel < 'R')
{
	$APPLICATION->AuthForm(GetMessage("WEBPROSTOR_SMTP_NO_ACCESS"));
}

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$module_id.'/include.php');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".$module_id."/prolog.php");

$arTabs = CWebprostorCoreOptions::GetTabs();

$groupsMain = Array(
	"MAIN" => GetMessage("WEBPROSTOR_SMTP_OPTIONS_GROUP_MAIN"),
	"SENDER" => GetMessage("WEBPROSTOR_SMTP_OPTIONS_GROUP_SENDER"),
	"LOGS" => GetMessage("WEBPROSTOR_SMTP_OPTIONS_GROUP_LOGS"),
);
$groupsSites = Array(
	"CONNECTION" => GetMessage("WEBPROSTOR_SMTP_OPTIONS_GROUP_CONNECTION"),
	"AUTHORIZATION" => GetMessage("WEBPROSTOR_SMTP_OPTIONS_GROUP_AUTHORIZATION"),
	"SENDING" => GetMessage("WEBPROSTOR_SMTP_OPTIONS_GROUP_SENDING"),
	"MAIL" => GetMessage("WEBPROSTOR_SMTP_OPTIONS_GROUP_MAIL"),
);

$arGroups = CWebprostorCoreOptions::GetGroups($groupsSites, $arTabs, $groupsMain);

$optionsMain = Array(
	Array(
		'CODE' => "USE_MODULE",
		'GROUP' => "MAIN",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_MODULE"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '10',
	),
	Array(
		'CODE' => "USE_PHPMAILER",
		'GROUP' => "MAIN",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_PHPMAILER"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_PHPMAILER_NOTES"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '12',
	),
	Array(
		'CODE' => "AUTO_ADD_INIT",
		'GROUP' => "MAIN",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_AUTO_ADD_INIT"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '15',
	),
	Array(
		'CODE' => "AUTO_DEL_INIT",
		'GROUP' => "MAIN",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_AUTO_DEL_INIT"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '17',
	),
	Array(
		'CODE' => "LOG_ERRORS",
		'GROUP' => "LOGS",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_LOG_ERRORS"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '20',
	),
	Array(
		'CODE' => "LOG_COMMANDS",
		'GROUP' => "LOGS",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_LOG_COMMANDS"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_NOT_USE_BY_PHPMAILER"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '35',
	),
	Array(
		'CODE' => "LOG_SEND_OK",
		'GROUP' => "LOGS",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_LOG_SEND_OK"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '40',
	),
	Array(
		'CODE' => "INCLUDE_SEND_INFO_TO_LOG",
		'GROUP' => "LOGS",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_INCLUDE_SEND_INFO_TO_LOG"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '41',
	),
	Array(
		'CODE' => "USE_SENDER_SMTP",
		'GROUP' => "SENDER",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_SENDER_SMTP"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_SENDER_SMTP_NOTES"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '5',
	),
	Array(
		'CODE' => "SMTP_SERVER",
		'GROUP' => "SENDER",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SERVER"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SERVER_SENDER_NOTES"),
		'SORT' => '10',
	),
	Array(
		'CODE' => "SMTP_PORT",
		'GROUP' => "SENDER",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_PORT"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_PORT_NOTES"),
		'SORT' => '20',
	),
	Array(
		'CODE' => "SECURE",
		'GROUP' => "SENDER",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SECURE"),
		'SORT' => '25',
		'TYPE' => 'SELECT',
		'VALUES' => Array(
			'REFERENCE' => Array(
				GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SECURE_NO"), "TLS", "SSL",
			),
			'REFERENCE_ID' => Array(
				"", "tls", "ssl",
			),
		),
	),
	Array(
		'CODE' => "LOGIN",
		'GROUP' => "SENDER",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_LOGIN"),
		'SORT' => '30',
	),
	Array(
		'CODE' => "PASSWORD",
		'GROUP' => "SENDER",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_PASSWORD"),
		'TYPE' => 'PASSWORD',
		'SORT' => '40',
	),
);

$optionsSites = Array(
	Array(
		'CODE' => "SMTP_SERVER",
		'GROUP' => "CONNECTION",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SERVER"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SERVER_NOTES"),
		'SORT' => '10',
	),
	Array(
		'CODE' => "SMTP_PORT",
		'GROUP' => "CONNECTION",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_PORT"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_PORT_NOTES"),
		'SORT' => '20',
	),
	Array(
		'CODE' => "SECURE",
		'GROUP' => "CONNECTION",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SECURE"),
		'SORT' => '25',
		'TYPE' => 'SELECT',
		'VALUES' => Array(
			'REFERENCE' => Array(
				GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SECURE_NO"), "TLS", "SSL",
			),
			'REFERENCE_ID' => Array(
				"", "tls", "ssl",
			),
		),
	),
	Array(
		'CODE' => "HELO_COMMAND",
		'GROUP' => "CONNECTION",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_HELO_COMMAND"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_NOT_USE_BY_PHPMAILER"),
		'TYPE' => 'SELECT',
		'SORT' => '27',
		'VALUES' => Array(
			'REFERENCE' => Array(
				"HELO", GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_HELO_COMMAND_EHLO"),
			),
			'REFERENCE_ID' => Array(
				"HELO", "EHLO",
			),
		),
	),
	Array(
		'CODE' => "REQUIRES_AUTHENTICATION",
		'GROUP' => "AUTHORIZATION",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_REQUIRES_AUTHENTICATION"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '29',
	),
	Array(
		'CODE' => "LOGIN",
		'GROUP' => "AUTHORIZATION",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_LOGIN"),
		'SORT' => '30',
	),
	Array(
		'CODE' => "PASSWORD",
		'GROUP' => "AUTHORIZATION",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_PASSWORD"),
		'TYPE' => 'PASSWORD',
		'SORT' => '40',
	),
	/*Array(
		'CODE' => "USE_XOAUTH2",
		'GROUP' => "AUTHORIZATION",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_XOAUTH2"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '42',
	),*/
	Array(
		'CODE' => "REPLACE_FROM",
		'GROUP' => "SENDING",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '45',
	),
	Array(
		'CODE' => "REPLACE_FROM_TO_EMAIL",
		'GROUP' => "SENDING",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM_TO_EMAIL"),
		'SORT' => '46',
	),
	Array(
		'CODE' => "FROM",
		'GROUP' => "MAIL",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_FROM"),
		'SORT' => '10',
	),
	Array(
		'CODE' => "REPLY_TO",
		'GROUP' => "MAIL",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_REPLY_TO"),
		'SORT' => '15',
	),
	Array(
		'CODE' => "CHARSET",
		'GROUP' => "MAIL",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_CHARSET"),
		'SORT' => '20',
		'TYPE' => 'SELECT',
		'VALUES' => Array(
			'REFERENCE' => Array(
				"utf-8", "windows-1251",
			),
			'REFERENCE_ID' => Array(
				"utf-8", "windows-1251",
			),
		),
	),
	Array(
		'CODE' => "PRIORITY",
		'GROUP' => "MAIL",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_PRIORITY"),
		'SORT' => '25',
		'TYPE' => 'SELECT',
		'VALUES' => Array(
			'REFERENCE' => Array(
				"", GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_PRIORITY_HIGHT"), GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_PRIORITY_NORMAL"), GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_PRIORITY_LOW"),
			),
			'REFERENCE_ID' => Array(
				"", 1, 3, 5,
			),
		),
	),
	Array(
		'CODE' => "DUPLICATE",
		'GROUP' => "MAIL",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DUPLICATE", Array("#EMAILS#" => COption::GetOptionString("main", "all_bcc"))),
		'TYPE' => 'CHECKBOX',
		'SORT' => '30',
	),
);

$arOptions = CWebprostorCoreOptions::GetOptions($optionsSites, $arTabs, $optionsMain);

$opt = new CWebprostorCoreOptions($module_id, $arTabs, $arGroups, $arOptions, $showMainTab = true, $showRightsTab = true);
$opt->ShowHTML();
?>