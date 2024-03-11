<?
IncludeModuleLangFile(__FILE__);
$MODULE_ID = "webprostor.smtp";

if($APPLICATION->GetGroupRight($MODULE_ID)>"D")
{
	if(!CModule::IncludeModule($MODULE_ID))
		return false;
	
	$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/panel/webprostor.smtp/menu.css');
	
	$aMenu = array(
		"parent_menu" => "global_menu_webprostor",
		"section" => $MODULE_ID,
		"sort" => 700,
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
				"url" => "webprostor.smtp_send.php?lang=".LANGUAGE_ID,
			),
			array(
				"module_id" => $MODULE_ID,
				"icon" => "update_marketplace",
				"text" => GetMessage("WEBPROSTOR_SMTP_INNER_MENU_LOGS_TEXT"),
				"url" => "webprostor.smtp_logs.php?lang=".LANGUAGE_ID,
				"more_url" => array("webprostor.smtp_log_view.php"),
			),
			array(
				"module_id" => $MODULE_ID,
				"icon" => "fileman_sticker_icon",
				"text" => GetMessage("WEBPROSTOR_SMTP_INNER_MENU_DEBUG_TEXT"),
				"url" => "webprostor.smtp_debug.php?lang=".LANGUAGE_ID,
			),
			array(
				"module_id" => $MODULE_ID,
				"text" => GetMessage("WEBPROSTOR_THANK_THE_DEVELOPER"),
				"icon" => "blog_menu_icon",
				"url" => "https://marketplace.1c-bitrix.ru/solutions/{$MODULE_ID}/#tab-rating-link",
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
