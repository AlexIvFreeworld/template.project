<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.smtp/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.smtp/include.php");

IncludeModuleLangFile(__FILE__);

$module_id = 'webprostor.smtp';
$moduleAccessLevel = $APPLICATION->GetGroupRight($module_id);

if ($moduleAccessLevel == "D")
    $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));

if($back_url=='')
	$back_url = '/bitrix/admin/webprostor.smtp_logs.php?lang='.$lang;

$strWarning = "";

$aTabs = array(
	array("DIV" => "main", "TAB" => GetMessage("ELEMENT_TAB"), "ICON"=>"", "TITLE"=>GetMessage("ELEMENT_TAB_TITLE")),
);

$tabControl = new CAdminTabControl("tabControl", $aTabs);

$sTableID = "webprostor_smtp_logs";
$ID = intval($ID);

$cData = new CWebprostorSmtpLogs;

ClearVars("str_");

if($ID>0)
{
	$result = $cData->GetById($ID);
	if(!$result->ExtractFields("str_"))
	{
		$ID=0;
		$strWarning.= GetMessage("WEBPROSTOR_SMTP_LOG_NOT_FOUND");
	}
}

$APPLICATION->SetTitle(GetMessage("PAGE_TITLE").($ID>0? ': '.$str_ID : ""));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$aMenu = array(
	array(
		"TEXT"=>GetMessage("ELEMENTS_LIST"),
		"LINK"=>"webprostor.smtp_logs.php?lang=".LANG,
		"ICON"=>"btn_list",
	)
);

if($ID>0)
{
	$aMenu[] = array("SEPARATOR"=>"Y");

	$aMenu[] = array(
		"TEXT"  => GetMessage("BTN_ACTIONS"),
		"ICON"  => "btn_new",
		"MENU"  => Array(
			array(
				"TEXT"  => GetMessage("DELETE_ELEMENT"),
				"LINK" => "javascript:if(confirm('".GetMessageJS("CONFIRM_DELETING")."')) window.location='/bitrix/admin/webprostor.smtp_logs.php?ID=".$ID."&action=delete&lang=".LANGUAGE_ID."&".bitrix_sessid_get()."';",
				"ICON"  => "delete",
			)
		),
	);
}

$context = new CAdminContextMenu($aMenu);
$context->Show();
?>
<?CAdminMessage::ShowOldStyleError($strWarning);?>
<?
if($ID>0) {
?>
<form method="POST" Action="<?echo $APPLICATION->GetCurPage()?>" name="form" id="form">
<?echo bitrix_sessid_post();?>
<input type="hidden" name="ID" value="<?echo $ID?>">
<?if(strlen($back_url)>0):?><input type="hidden" name="back_url" value="<?=htmlspecialcharsbx($back_url)?>"><?endif?>
<?
$tabControl->Begin();
$tabControl->BeginNextTab();

$arFields["MAIN"]["LABEL"] = GetMessage("GROUP_MAIN");

$arFields["MAIN"]["ITEMS"][] = Array(
	"CODE" => "ID",
	"TYPE" => "LABEL",
	"LABEL" => GetMessage("LOG_ID"),
	"VALUE" => $str_ID,
);
if($str_SITE_ID != "")
{
	$rsSites = CSite::GetByID($str_SITE_ID);
	$arSite = $rsSites->Fetch();
	
	$arFields["MAIN"]["ITEMS"][] = Array(
		"CODE" => "SITE_ID",
		"TYPE" => "LABEL",
		"LABEL" => GetMessage("SITE_ID"),
		"VALUE" => '<a target="_blank" href="site_edit.php?LID='.$str_SITE_ID.'&lang='.LANG.'">'.$arSite["NAME"].' ['.$str_SITE_ID.']</a>',
	);
}
$arFields["MAIN"]["ITEMS"][] = Array(
	"CODE" => "DATE_CREATE",
	"TYPE" => "LABEL",
	"LABEL" => GetMessage("DATE_CREATE"),
	"VALUE" => $str_DATE_CREATE,
);
if($str_MODULE_ID != '')
{
	$arFields["MAIN"]["ITEMS"][] = Array(
		"CODE" => "MODULE_ID",
		"TYPE" => "LABEL",
		"LABEL" => GetMessage("MODULE_ID"),
		"VALUE" => $str_MODULE_ID,
	);
}

$arFields["SERVICE"]["LABEL"] = GetMessage("GROUP_SERVICE");

$arFields["SERVICE"]["ITEMS"][] = Array(
	"CODE" => "ERROR_TEXT",
	"TYPE" => "LABEL",
	"LABEL" => GetMessage("ERROR_TEXT"),
	"VALUE" => $str_ERROR_TEXT,
);
if($str_ERROR_NUMBER)
{
	$arFields["SERVICE"]["ITEMS"][] = Array(
		"CODE" => "ERROR_NUMBER",
		"TYPE" => "LABEL",
		"LABEL" => GetMessage("ERROR_NUMBER"),
		"VALUE" => $str_ERROR_NUMBER,
	);
}

if($str_SEND_INFO != "")
{
	$messageFields = parseMailHeading($str_SEND_INFO);
	$arFields["MESSAGE_FIELDS"]["LABEL"] = GetMessage("GROUP_MESSAGE_FIELDS");
	
	foreach($messageFields as $code => $value)
	{
		if($value != "")
		{
			$arFields["MESSAGE_FIELDS"]["ITEMS"][] = Array(
				"CODE" => $code,
				"TYPE" => "LABEL",
				"LABEL" => $code,
				"VALUE" => $value,
			);
		}
	}
	
	$str_SEND_INFO = str_replace("\r\n\r\n", "", $str_SEND_INFO);
	$str_SEND_INFO = str_replace("\r\n", "<br />", $str_SEND_INFO);
	
	$arFields["SEND_INFO"]["LABEL"] = GetMessage("GROUP_SEND_INFO");
	$arFields["SEND_INFO"]["ITEMS"][] = Array(
		"CODE" => "SEND_INFO",
		"ID" => "send_info",
		"TYPE" => "EDITOR",
		"VALUE" => $str_SEND_INFO,
	);
}

CWebprostorCoreFunctions::ShowFormFields($arFields);

$tabControl->Buttons(
	array(
		"btnApply" => false,
		"btnSave" => false,
		"back_url"=>$back_url,
	)
);
$tabControl->End();

}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>