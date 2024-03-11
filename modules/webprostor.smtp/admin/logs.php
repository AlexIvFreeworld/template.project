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

$MODULES = [
	'' => GetMessage("WEBPROSTOR_SMTP_MODULE_SENDER_SYSTEM"),
	'subscribe' => GetMessage("WEBPROSTOR_SMTP_MODULE_SENDER_SUBSCRIBE"),
	'sender' => GetMessage("WEBPROSTOR_SMTP_MODULE_SENDER_SENDER")
];

$cData = new CWebprostorSmtpLogs;

$arFilterFields = Array(
	"find_site_id",
	"find_date_create",
	"find_error_text",
	//"find_error_number",
	"find_module_id",
	"find_recipients",
	"find_sended",
);

$lAdmin->InitFilter($arFilterFields);

$arFilter = Array(
	"SITE_ID" => $find_site_id,
	"DATE_CREATE" => $find_date_create,
	"?ERROR_TEXT" => $find_error_text,
	//"ERROR_NUMBER" => $find_error_number,
	"MODULE_ID" => $find_module_id,
	"RECIPIENTS" => $find_recipients,
	"SENDED" => $find_sended,
);

/* Prepare data for new filter */
$rsSites = CSite::GetList($siteby="sort", $siteorder="asc", Array());
$sites = array();
while ($arSite = $rsSites->Fetch())
{
	$sites[$arSite["LID"]] = $arSite["LID"];
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
		"id" => "MODULE_ID",
		"name" => GetMessage("WEBPROSTOR_SMTP_MODULE_ID"),
		"filterable" => "",
		"type" => "list",
		"items" => $MODULES,
		"default" => true
	),
	array(
		"id" => "RECIPIENTS",
		"name" => GetMessage("WEBPROSTOR_SMTP_RECIPIENTS"),
		"filterable" => "?",
		"quickSearch" => "?",
		"default" => true
	),
	array(
		"id" => "SENDED",
		"name" => GetMessage("WEBPROSTOR_SMTP_SENDED"),
		"type" => "checkbox",
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
		
		if(($rsData = $cData->GetByID($ID)) && ($arData = $rsData->Fetch()))
		{
			if(is_array($arFields))
			{
				foreach($arFields as $key=>$value)
					$arData[$key]=$value;
			}
		}
    
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
			case "resend":
				$smtp = new CWebprostorSmtp($arData["SITE_ID"]);
				$send = $smtp->SendMail($arData["SOURCE_TO"], $arData["SOURCE_SUBJECT"], $arData["SOURCE_MESSAGE"], $arData["SOURCE_HEADERS"], $arData["SOURCE_PARAMETERS"], false, $ID);
				if(!$send)
				{
					$logRes = $cData->GetById($ID);
					$messageArr = $logRes->Fetch();
							
					$lAdmin->AddGroupError(GetMessage("WEBPROSTOR_SMTP_SENDED_RESEND_ERROR").$ID.': '.$messageArr["ERROR_TEXT"], $ID);
				}

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
		"id"    =>	"MODULE_ID",
		"content"  =>	GetMessage("WEBPROSTOR_SMTP_MODULE_ID"),
		"sort"    =>	"module_id",
		"default"  =>	true,
	),
	array(  
		"id"    =>	"SUBJECT",
		"content"  =>	GetMessage("WEBPROSTOR_SMTP_SUBJECT"),
		"default"  =>	true,
	),
	array(  
		"id"    =>	"RECIPIENTS",
		"content"  =>	GetMessage("WEBPROSTOR_SMTP_RECIPIENTS"),
		"sort"    =>	"recipients",
		"default"  =>	true,
	),
	array(  
		"id"    =>	"SENDED",
		"content"  =>	GetMessage("WEBPROSTOR_SMTP_SENDED"),
		"sort"    =>	"sended",
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
		"default"  =>	false,
	),
	array(  
		"id"    =>	"RETRY_COUNT",
		"content"  =>	GetMessage("WEBPROSTOR_SMTP_RETRY_COUNT"),
		"sort"    =>	"retry_count",
		"default"  =>	false,
	),
);

$lAdmin->AddHeaders($arHeader);

$rsData = $cData->GetList(array($by=>$order), $arFilter, ['ID', 'SITE_ID', 'MODULE_ID', 'SOURCE_SUBJECT', 'RECIPIENTS', 'SENDED', 'DATE_CREATE', 'ERROR_TEXT', 'RETRY_COUNT']);
$rsData = new CAdminUiResult($rsData, $sTableID);
$rsData->NavStart();

$lAdmin->SetNavigationParams($rsData);

while($arRes = $rsData->NavNext(true, "f_"))
{
	$log_view_link = "webprostor.smtp_log_view.php?ID=".$f_ID."&lang=".LANG;
	
	$row =& $lAdmin->AddRow($f_ID, $arRes, $log_view_link, GetMessage("WEBPROSTOR_SMTP_VIEW_LOG"));
	
	$row->AddViewField("ID", '<a href="'.$log_view_link.'">'.$f_ID.'</a>');
	$row->AddViewField("SITE_ID", '<a target="_blank" href="site_edit.php?LID='.$f_SITE_ID.'&lang='.LANG.'">'.$sites[$f_SITE_ID].'</a>');
	
	$row->AddViewField("MODULE_ID", $MODULES[$f_MODULE_ID]);
	
	if(strpos($f_SOURCE_SUBJECT, "UTF-8") !== false || strpos($f_SOURCE_SUBJECT, "windows-1251") !== false)
	{
		$f_SOURCE_SUBJECT = str_replace("=?UTF-8?B?", "", $f_SOURCE_SUBJECT);
		$f_SOURCE_SUBJECT = str_replace("=?windows-1251?B?", "", $f_SOURCE_SUBJECT);
		$f_SOURCE_SUBJECT = str_replace("=?=", "", $f_SOURCE_SUBJECT);
		
		$row->AddViewField("SUBJECT", base64_decode($f_SOURCE_SUBJECT));
	}
	else
	{
		$row->AddViewField("SUBJECT", $f_SOURCE_SUBJECT);
	}
	$row->AddViewField("SENDED", ($f_SENDED=="Y"?"<span class=\"adm-lamp adm-lamp-in-list adm-lamp-green\"></span>":"<button class=\"ui-btn ui-btn-icon-mail\" id='message_resend_button_".$f_ID."' class=\"adm-btn\" onClick=\"StartResend('".$f_ID."');\" title=\"".GetMessage("WEBPROSTOR_SMTP_SENDED_RESEND")."\"></button>"));

	$arActions = Array();
	
	if ($moduleAccessLevel>="W")
	{
		$arActions[] = array(
			"ICON"=>"delete",
			"TEXT"=>GetMessage("WEBPROSTOR_SMTP_DELETE_LOG"),
			"ACTION"=>"if(confirm('".GetMessageJS("WEBPROSTOR_SMTP_CONFIRM_DELETING")."')) ".$lAdmin->ActionDoGroup($f_ID, "delete")
		);
	}
  
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
			"resend"=>GetMessage("WEBPROSTOR_SMTP_SENDED_RESEND"),
		)
	);
}
else
{
	$lAdmin->AddAdminContextMenu(array());
}

$lAdmin->CheckListMode();

if($_SERVER["REQUEST_METHOD"] == "POST" && $_REQUEST["resend"]=="Y")
{
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_js.php");
	
	?>
	
	<script>
		CloseWaitWindow();
	</script>
	<?

	require($_SERVER["DOCUMENT_ROOT"].BX_ROOT."/modules/main/include/epilog_admin_js.php");
}

$APPLICATION->SetTitle(GetMessage("WEBPROSTOR_SMTP_LOGS_PAGE_TITLE"));

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");
?>
<div id="resend_result"></div>
<?

$lAdmin->DisplayFilter($filterFields);
$lAdmin->DisplayList();

?>
<script>
function StartResend(ID)
{
	if(running == false)
	{
		var node = BX('resend_result');

		var windowPos = BX.GetWindowSize();
		var pos = BX.pos(node);

		/*if(pos.top < windowPos.scrollTop + windowPos.innerHeight)
		{
			window.scrollTo(windowPos.scrollLeft, pos.top + 150 - windowPos.innerHeight);
		}*/
		
		running = true;

		if($("#message_resend_button_"+ID).is(":visible") && $("#message_resend_button_"+ID).attr('disabled') != "disabled")
		{
			$("#message_resend_button_"+ID).addClass('ui-btn-wait').attr("disabled", "disabled");
		}
		
		RunResend(ID);
	}
}

function EndResend(ID)
{
	running = false;
	
	if($("#message_resend_button_"+ID).is(":visible"))
	{
		$("#message_resend_button_"+ID).removeClass('ui-btn-wait').removeAttr("disabled");
	}
	
	window.history.pushState("webprostor_smtp_logs", "", "webprostor.smtp_logs.php?lang=<?=LANG?>" );
}

var running = false;

function RunResend(ID, NS)
{
	var queryString = 'MESSAGE_ID=' + ID
		+ '&lang=<?echo LANG?>'
		+ '&<?echo bitrix_sessid_get()?>'
	;
	
	if(running)
	{
		ShowWaitWindow();
		BX.ajax.post(
			'/bitrix/tools/<?=$module_id?>/ajax_resend.php?'+queryString,
			NS,
			function(result){
				document.getElementById('resend_result').innerHTML = result;
			}
		);
	}
}
</script>
<?
if(isset($_REQUEST['resend']) && check_bitrix_sessid())
{
	$ID = intval($_REQUEST['resend']);
	if($ID > 0)
	{
?>
<script>
BX.ready(BX.defer(function(){
	resend(<?=$ID?>);
}));
</script>
<?
	}
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>