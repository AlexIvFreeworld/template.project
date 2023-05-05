<?
IncludeModuleLangFile(__FILE__);
$MODULE_ID = "webprostor.smtp";

if($APPLICATION->GetGroupRight($MODULE_ID)>"D")
{
	if(!CModule::IncludeModule($MODULE_ID))
		return false;
	
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/panel/webprostor.smtp/menu.css');
	
	$aMenu = array(
		"parent_menu" => "global_menu_services",
		"section" => $MODULE_ID,
		"sort" => 1000,
		"text" => GetMessage("WEBPROSTOR_SMTP_MAIN_MENU_TEXT"),
		"icon" => "webprostor_smtp",
		"page_icon" => "",
		"items_id" => "webprostor_smtp",
		"more_url" => array(),
		"items" => array(
			array(
				"module_id" => $MODULE_ID,
				"icon" => "sender_menu_icon",
				"text" => GetMessage("WEBPROSTOR_SMTP_INNER_MENU_SEND_TEXT"),
				"title" => GetMessage("WEBPROSTOR_SMTP_INNER_MENU_SEND_TITLE"),
				"url" => "webprostor.smtp_send.php?lang=".LANGUAGE_ID,
				"items_id" => "webprostor_smtp_send",
			),
			array(
				"module_id" => $MODULE_ID,
				"icon" => "update_marketplace",
				"text" => GetMessage("WEBPROSTOR_SMTP_INNER_MENU_LOGS_TEXT"),
				"title" => GetMessage("WEBPROSTOR_SMTP_INNER_MENU_LOGS_TITLE"),
				"url" => "webprostor.smtp_logs.php?lang=".LANGUAGE_ID,
				"more_url" => array("webprostor.smtp_log_view.php"),
				"items_id" => "webprostor_smtp_logs",
			),
			array(
				"module_id" => $MODULE_ID,
				"text" => GetMessage("WEBPROSTOR_THANK_THE_DEVELOPER"),
				"icon" => "currency_menu_icon",
				"url" => "https://my.qiwi.com/form/Egor-SU2sWXXKYW",
			),
			array(
				"module_id" => $MODULE_ID,
				"text" => GetMessage("WEBPROSTOR_INSTRUCTION"),
				"icon" => "learning_menu_icon",
				"url" => "https://webprostor.ru/learning/course/course6/index",
			),
		)
	);

	return $aMenu;
}

return false;
?> 
