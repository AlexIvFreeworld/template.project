<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.smtp/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.smtp/include.php");

IncludeModuleLangFile(__FILE__);

$module_id = 'webprostor.smtp';
$moduleAccessLevel = $APPLICATION->GetGroupRight($module_id);

if ($moduleAccessLevel == "D")
    $APPLICATION->AuthForm(GetMessage("WEBPROSTOR_SMTP_ACCESS_DENIED"));

CAdminNotify::DeleteByTag("LOGS_ARE_TOO_BIG");

$sTableID = "webprostor_smtp_logs";

$oSort = new CAdminSorting($sTableID, "ID", "desc");
$arOrder = (strtoupper($by) === "ID"? array($by => $order): array($by => $order, "ID" => "ASC"));
$lAdmin = new CAdminUiList($sTableID, $oSort);

$cData = new CWebprostorSmtpLogs;

$arFilterFields = Array(
	"find_site_id",
	"find_date_create",
	"find_error_text",
	"find_error_number",
	"find_module_id",
);

$lAdmin->InitFilter($arFilterFields);

$arFilter = Array(
	"SITE_ID" => $find_site_id,
	"DATE_CREATE" => $find_date_create,
	"?ERROR_TEXT" => $find_error_text,
	"ERROR_NUMBER" => $find_error_number,
	"MODULE_ID" => $find_module_id,
);

/* Prepare data for new filter */
$rsSites = CSite::GetList($siteby="sort", $siteorder="asc", Array());
$sites = array();
while ($arSite = $rsSites->Fetch())
{
	$sites[$arSite["LID"]] = htmlspecialcharsbx($arSite["NAME"]).' ['.$arSite["LID"].']';
}

$filterFields = array(
	array(
		"id" => "SITE_ID",
		"name" => GetMessage("WEBPROSTOR_SMTP_SITE_ID"),
		"filterable" => "",
		"type" => "list",
		"items" => $sites,
		"default" => true
	),
	array(
		"id" => "DATE_CREATE",
		"name" => GetMessage("WEBPROSTOR_SMTP_DATE_CREATE"),
		"filterable" => "",
		"type" => "date",
		"default" => true
	),
	array(
		"id" => "ERROR_TEXT",
		"name" => GetMessage("WEBPROSTOR_SMTP_ERROR_TEXT"),
		"filterable" => "?",
		"quickSearch" => "?",
		"default" => true
	),
	array(
		"id" => "ERROR_NUMBER",
		"name" => GetMessage("WEBPROSTOR_SMTP_ERROR_NUMBER"),
		"filterable" => "",
		"default" => true
	),
	array(
		"id" => "MODULE_ID",
		"name" => GetMessage("WEBPROSTOR_SMTP_MODULE_ID"),
		"filterable" => "",
		"default" => true
	),
);

$lAdmin->AddFilter($filterFields, $arFilter);

if(($arID = $lAdmin->GroupAction()) && $moduleAccessLevel=="W")
{
	if (!empty($_REQUEST["action_all_rows_".$sTableID]) && $_REQUEST["action_all_rows_".$sTableID] === "Y")
	{
		$rsData = $cData->GetList(array($by=>$order), $arFilter);
		while($arRes = $rsData->Fetch())
			$arID[] = $arRes['ID'];
	}

	foreach($arID as $ID)
	{
		if(strlen($ID)<=0)
			continue;
		
		$ID = IntVal($ID);
    
		switch($_REQUEST['action'])
		{
			case "delete":
				@set_time_limit(0);
				$DB->StartTransaction();
				if(!$cData->Delete($ID))
				{
					$DB->Rollback();
					$lAdmin->AddGroupError(GetMessage("WEBPROSTOR_SMTP_DELETING_ERROR"), $ID);
				}
				$DB->Commit();
				break;
		}
	}
}

$arHeader = array(
	array(  
		"id"    =>	"ID",
		"content"  =>	"ID",
		"sort"    =>	"id",
		"align"    =>	"center",
		"default"  =>	true,
	),
	array(  
		"id"    =>	"SITE_ID",
		"content"  =>	GetMessage("WEBPROSTOR_SMTP_SITE_ID"),
		"sort"    =>	"site_id",
		"default"  =>	true,
	),
	array(  
		"id"    =>	"DATE_CREATE",
		"content"  =>	GetMessage("WEBPROSTOR_SMTP_DATE_CREATE"),
		"sort"    =>	"date_create",
		"default"  =>	true,
	),
	array(  
		"id"    =>	"ERROR_TEXT",
		"content"  =>	GetMessage("WEBPROSTOR_SMTP_ERROR_TEXT"),
		"sort"    =>	"error_text",
		"default"  =>	true,
	),
	array(  
		"id"    =>	"ERROR_NUMBER",
		"content"  =>	GetMessage("WEBPROSTOR_SMTP_ERROR_NUMBER"),
		"sort"    =>	"error_number",
		"default"  =>	true,
	),
	array(  
		"id"    =>	"MODULE_ID",
		"content"  =>	GetMessage("WEBPROSTOR_SMTP_MODULE_ID"),
		"sort"    =>	"module_id",
		"default"  =>	true,
	),
	array(  
		"id"    =>	"SEND_INFO",
		"content"  =>	GetMessage("WEBPROSTOR_SMTP_SEND_INFO"),
		"sort"    =>	"send_info",
		"default"  =>	false,
	),
);

$lAdmin->AddHeaders($arHeader);

$rsData = $cData->GetList(array($by=>$order), $arFilter);
$rsData = new CAdminUiResult($rsData, $sTableID);
$rsData->NavStart();

$lAdmin->SetNavigationParams($rsData);

while($arRes = $rsData->NavNext(true, "f_"))
{
	$log_view_link = "webprostor.smtp_log_view.php?ID=".$f_ID."&lang=".LANG;
	
	$row =& $lAdmin->AddRow($f_ID, $arRes, $log_view_link, GetMessage("WEBPROSTOR_SMTP_VIEW_LOG"));
	
	$row->AddViewField("ID", '<a href="'.$log_view_link.'">'.$f_ID.'</a>');
	$row->AddViewField("SITE_ID", '<a target="_blank" href="site_edit.php?LID='.$f_SITE_ID.'&lang='.LANG.'">'.$sites[$f_SITE_ID].'</a>');

	$arActions = Array();
	
	$arActions[] = array(
		"ICON"=>"delete",
		"TEXT"=>GetMessage("WEBPROSTOR_SMTP_DELETE_LOG"),
		"ACTION"=>"if(confirm('".GetMessageJS("WEBPROSTOR_SMTP_CONFIRM_DELETING")."')) ".$lAdmin->ActionDoGroup($f_ID, "delete")
    );
  
	$row->AddActions($arActions);
}

$lAdmin->AddFooter(
	array(
		array(
			"title"=>GetMessage("MAIN_ADMIN_LIST_SELECTED"),
			"value"=>$rsData->SelectedRowsCount()
		),
		array(
			"counter"=>true,
			"title"=>GetMessage("MAIN_ADMIN_LIST_CHECKED"),
			"value"=>"0"
		),
	)
);

if ($moduleAccessLevel>="W")
{
	$aContext = array();
	
	$lAdmin->AddAdminContextMenu($aContext);

	$lAdmin->AddGroupActionTable(
		Array(
			"delete"=>true,
			"for_all"=>true,
		)
	);
}
else
{
	$lAdmin->AddAdminContextMenu(array());
}

$lAdmin->CheckListMode();

$APPLICATION->SetTitle(GetMessage("WEBPROSTOR_SMTP_LOGS_PAGE_TITLE"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$lAdmin->DisplayFilter($filterFields);
$lAdmin->DisplayList();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>