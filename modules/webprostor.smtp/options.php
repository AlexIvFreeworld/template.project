<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.smtp/prolog.php");

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
	"RESEND" => GetMessage("WEBPROSTOR_SMTP_OPTIONS_GROUP_RESEND"),
	"DEBUG" => GetMessage("WEBPROSTOR_SMTP_OPTIONS_GROUP_DEBUG"),
);
$groupsSites = Array(
	"CONNECTION" => GetMessage("WEBPROSTOR_SMTP_OPTIONS_GROUP_CONNECTION"),
	"AUTHORIZATION" => GetMessage("WEBPROSTOR_SMTP_OPTIONS_GROUP_AUTHORIZATION"),
	"DKIM" => GetMessage("WEBPROSTOR_SMTP_OPTIONS_GROUP_DKIM"),
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
		'CODE' => "USE_DEFAULT_SITE_ID_IF_EMPTY",
		'GROUP' => "MAIN",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_DEFAULT_SITE_ID_IF_EMPTY"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '18',
	),
	Array(
		'CODE' => "LOG_ERRORS",
		'GROUP' => "LOGS",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_LOG_ERRORS"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '20',
	),
	Array(
		'CODE' => "NOTIFY_LIMIT",
		'GROUP' => "LOGS",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_NOTIFY_LIMIT"),
		'SORT' => '43',
		'TYPE' => 'INT',
		'MIN' => '0',
	),
	Array(
		'CODE' => "AUTO_CLEANING_LOGS",
		'GROUP' => "LOGS",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_AUTO_CLEANING_LOGS"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '46',
	),
	Array(
		'CODE' => "DONT_SAVE_SEND_INFO",
		'GROUP' => "LOGS",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DONT_SAVE_SEND_INFO"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '50',
	),
	Array(
		'CODE' => "ENABLE_RESEND",
		'GROUP' => "RESEND",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_ENABLE_RESEND"),
		'SORT' => '10',
		'TYPE' => 'CHECKBOX',
	),
	Array(
		'CODE' => "RESEND_RETRY_COUNT",
		'GROUP' => "RESEND",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_RESEND_RETRY_COUNT"),
		'SORT' => '20',
		'TYPE' => 'INT',
		'MIN' => '0',
		'SIZE' => '1',
		'MAXLENGTH' => '1',
		'MAX' => '9',
	),
	Array(
		'CODE' => "RESEND_AGENT_INTERVAL",
		'GROUP' => "RESEND",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_RESEND_AGENT_INTERVAL"),
		'SORT' => '30',
		'TYPE' => 'INT',
		'MIN' => '60',
		'SIZE' => '1',
	),
	Array(
		'CODE' => "RESEND_MESSAGE_COUNT",
		'GROUP' => "RESEND",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_RESEND_MESSAGE_COUNT"),
		'SORT' => '40',
		'TYPE' => 'INT',
		'MIN' => '1',
		'SIZE' => '1',
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
		'TYPE' => 'INT',
		'MIN' => '0',
		'SORT' => '20',
	),
	Array(
		'CODE' => "SECURE",
		'GROUP' => "SENDER",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SECURE"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SECURE_NOTES"),
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
if(ini_get("error_log") != '')
{
	$optionsMain[] = Array(
		'CODE' => "DEBUG_LEVEL",
		'GROUP' => "DEBUG",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_NOTE", ["#FILE_PATH#" => ini_get("error_log")]),
		'SORT' => '50',
		'TYPE' => 'SELECT',
		'VALUES' => Array(
			'REFERENCE' => Array(
				GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_OFF"), 
				GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_CLIENT"),
				GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_SERVER"),
				GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_CONNECTION"),
				GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_LOWLEVEL"),
			),
			'REFERENCE_ID' => Array(
				"0", "1", "2", "3", "4",
			),
		),
	);
}
else
{
	$optionsMain[] = Array(
		'CODE' => "DEBUG_LEVEL",
		'GROUP' => "DEBUG",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DEBUG_LEVEL_NOTE_ENABLE"),
		'SORT' => '50',
		'TYPE' => 'CUSTOM',
	);
}

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
		'TYPE' => 'INT',
		'MIN' => '0',
		'SORT' => '20',
	),
	Array(
		'CODE' => "SECURE",
		'GROUP' => "CONNECTION",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SECURE"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_SMTP_SECURE_NOTES"),
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
		'CODE' => "REQUIRES_AUTHENTICATION",
		'GROUP' => "AUTHORIZATION",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_REQUIRES_AUTHENTICATION"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_REQUIRES_AUTHENTICATION_NOTES"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '29',
	),
	Array(
		'CODE' => "LOGIN",
		'GROUP' => "AUTHORIZATION",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_LOGIN"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_LOGIN_NOTES"),
		'SORT' => '30',
	),
	Array(
		'CODE' => "PASSWORD",
		'GROUP' => "AUTHORIZATION",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_PASSWORD"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_PASSWORD_NOTES"),
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
		'CODE' => "USE_DKIM",
		'GROUP' => "DKIM",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_USE_DKIM"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '29',
	),
	Array(
		'CODE' => "DKIM_DOMAIN",
		'GROUP' => "DKIM",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_DOMAIN"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_DOMAIN_NOTES"),
		'SORT' => '30',
	),
	Array(
		'CODE' => "DKIM_SELECTOR",
		'GROUP' => "DKIM",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_SELECTOR"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_SELECTOR_NOTES"),
		'SORT' => '31',
	),
	Array(
		'CODE' => "DKIM_PASSPHRASE",
		'GROUP' => "DKIM",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_PASSPHRASE"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_PASSPHRASE_NOTES"),
		'SORT' => '33',
	),
	Array(
		'CODE' => "DKIM_PRIVATE_STRING",
		'GROUP' => "DKIM",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_PRIVATE_STRING"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DKIM_PRIVATE_STRING_NOTES"),
		'TYPE' => 'TEXTAREA',
		'SORT' => '34',
	),
	Array(
		'CODE' => "REPLACE_FROM",
		'GROUP' => "SENDING",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM_NOTES"),
		'TYPE' => 'CHECKBOX',
		'SORT' => '45',
	),
	Array(
		'CODE' => "REPLACE_FROM_TO_EMAIL",
		'GROUP' => "SENDING",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM_TO_EMAIL"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM_TO_EMAIL_NOTES"),
		'SORT' => '46',
	),
	Array(
		'CODE' => "REPLACE_FROM_NAME",
		'GROUP' => "SENDING",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM_NAME"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_REPLACE_FROM_NAME_NOTES"),
		'SORT' => '48',
	),
	Array(
		'CODE' => "DSN",
		'GROUP' => "SENDING",
		'TITLE' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DSN"),
		'NOTES' => GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DSN_NOTES"),
		'SORT' => '50',
		'TYPE' => 'MSELECT',
		'MULTIPLE' => 'Y',
		'VALUES' => Array(
			'REFERENCE' => Array(
				GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DSN_SUCCESS"), GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DSN_FAILURE"), GetMessage("WEBPROSTOR_SMTP_OPTIONS_OPTION_DSN_DELAY"),
			),
			'REFERENCE_ID' => Array(
				"SUCCESS", "FAILURE", "DELAY",
			),
		),
	),
	/*Array(
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
	),*/
);

$arOptions = CWebprostorCoreOptions::GetOptions($optionsSites, $arTabs, $optionsMain);

$opt = new CWebprostorCoreOptions($module_id, $arTabs, $arGroups, $arOptions, $showMainTab = true, $showRightsTab = true);
$opt->ShowHTML();

if ($moduleAccessLevel >= 'R')
{
	if($REQUEST_METHOD=="POST" && strlen($save)>0 && check_bitrix_sessid())
	{
		$enable_resend = COption::GetOptionString($module_id, "ENABLE_RESEND", "N");
		$resend_agent_interval = COption::GetOptionString($module_id, "RESEND_AGENT_INTERVAL", "300");
		
		global $DB;
		$cAgentResults = $DB->Query("SELECT * FROM `b_agent` WHERE `NAME` = 'CWebprostorSmtpAgent::Resend();'");
		$cAgentArray = array();
		
		while ($cAgentRow = $cAgentResults->Fetch()) {
			array_push($cAgentArray, $cAgentRow);
		}
		
		if($enable_resend == "Y")
		{
			foreach($cAgentArray as $key => $agent)
			{
				if($resend_agent_interval != $agent["AGENT_INTERVAL"])
				{
					CAgent::Delete($agent["ID"]);
					unset($cAgentArray[$key]);
				}
			}
			if(count($cAgentArray) == 0)
			{
				CAgent::AddAgent(
					"CWebprostorSmtpAgent::Resend();",
					"webprostor.smtp",
					"N",
					$resend_agent_interval,
					date("d.m.Y H:i:s"),
					"Y",
					date("d.m.Y H:i:s"), 
					30
				);
			}
		}
		elseif($enable_resend == "N" && count($cAgentArray) > 0)
		{
			foreach($cAgentArray as $agent)
			{
				CAgent::Delete($agent["ID"]);
			}
		}
	}
}
?>