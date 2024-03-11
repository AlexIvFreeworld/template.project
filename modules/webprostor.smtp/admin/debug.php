<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.smtp/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.smtp/include.php");

IncludeModuleLangFile(__FILE__);

$module_id = 'webprostor.smtp';
$moduleAccessLevel = $APPLICATION->GetGroupRight($module_id);

if ($moduleAccessLevel == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

$DEBUG_LEVEL = COption::GetOptionString($module_id, "DEBUG_LEVEL", 0);

if($DEBUG_LEVEL <= 0)
	$strWarning = GetMessage("ENABLE_DEBUG_LEVEL");
else
	$strWarning = "";

$aTabs = array(
	array("DIV" => "main", "TAB" => GetMessage("ELEMENT_TAB"), "ICON"=>"", "TITLE"=>GetMessage("ELEMENT_TAB_TITLE")),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

if($REQUEST_METHOD == "GET" && $delete='Y' && $moduleAccessLevel=="W" && check_bitrix_sessid()) 
{
	$log_file = ini_get("error_log");
	if(is_file($log_file))
		if(unlink($log_file))
			LocalRedirect("/bitrix/admin/webprostor.smtp_debug.php?lang=".LANG);
}

$APPLICATION->SetTitle(GetMessage("PAGE_TITLE"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

CAdminMessage::ShowOldStyleError($strWarning);

$log_file = ini_get("error_log");

if(is_file($log_file))
{
	$aMenu[] = array(
		"TEXT"  => GetMessage("DELETE_ELEMENT"),
		"ICON"  => "btn_delete",
		"LINK" => "/bitrix/admin/webprostor.smtp_debug.php?delete=Y&lang=".LANGUAGE_ID."&".bitrix_sessid_get(),
	);
	
	$context = new CAdminContextMenu($aMenu);
	$context->Show();
}

$tabControl->Begin();
$tabControl->BeginNextTab();

$arFields["MAIN"]["LABEL"] = GetMessage("GROUP_MAIN");

if($log_file != '' && file_exists($log_file))
{
	$logs = file_get_contents($log_file);
	
	$arFields["MAIN"]["ITEMS"][] = Array(
		"CODE" => "LOGS",
		"ID" => "debug",
		"TYPE" => "TEXTAREA",
		"VALUE" => $logs,
		"PARAMS" => [
			'COLS' => '150',
			'ROWS' => '20'
		],
	);
}
else
{
	$arFields["MAIN"]["ITEMS"][] = Array(
		"CODE" => "LOGS",
		"ID" => "debug",
		"TYPE" => "NOTE",
		"VALUE" => GetMessage("NO_LOGS_FILE"),
		"PARAMS" => [
			'TYPE' => 'warning',
			'ICON' => 'info'
		],
	);
}

CWebprostorCoreFunctions::ShowFormFields($arFields);

$tabControl->End();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>