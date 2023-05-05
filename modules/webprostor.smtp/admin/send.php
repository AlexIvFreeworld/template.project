<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.smtp/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.smtp/include.php");

IncludeModuleLangFile(__FILE__);

$module_id = 'webprostor.smtp';
$moduleAccessLevel = $APPLICATION->GetGroupRight($module_id);

if ($moduleAccessLevel == "D")
    $APPLICATION->AuthForm(GetMessage("WEBPROSTOR_SMTP_ACCESS_DENIED"));

if(isset($_REQUEST["Add"]))
{
	$notAllRequest = false;
	$send = false;
	$errorEmail = false;
	if(empty($_REQUEST["site_id"]) || empty($_REQUEST["to"]) || empty($_REQUEST["subject"]) || empty($_REQUEST["message"]))
	{
		$notAllRequest = true;
	} 
	elseif(!filter_var($_REQUEST["to"], FILTER_VALIDATE_EMAIL))
	{
		$errorEmail = true;
	}
	else
	{
		$smtp = new CWebprostorSmtp($_REQUEST["site_id"]);
		$send = $smtp->SendMail($to, $subject, $message, false, false);
	}
}

$aTabs = array(
	array(
		"DIV" => "FORM",
		"TAB" => GetMessage("WEBPROSTOR_SMTP_TAB_NAME"),
		"ICON" => "",
		"TITLE" => GetMessage("WEBPROSTOR_SMTP_TAB_DESCRIPTION")
	),
);
$tabControl = new CAdminTabControl("tabControl", $aTabs);

$APPLICATION->SetTitle( GetMessage("WEBPROSTOR_SMTP_PAGE_TITLE") );

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$showReq = false;

if(isset($_REQUEST["Add"]))
{
	if($notAllRequest)
	{
		$showReq = true;
		echo CAdminMessage::ShowMessage( GetMessage("WEBPROSTOR_SMTP_NOTE_NOT_ALL_REQUEST") );
	}
	elseif($errorEmail)
	{
		$showReq = true;
		echo CAdminMessage::ShowMessage( GetMessage("WEBPROSTOR_SMTP_NOTE_EMAIL_NOT_VALID") );
	} 
	elseif(!$send)
	{
		$showReq = true;
		echo CAdminMessage::ShowMessage( GetMessage("WEBPROSTOR_SMTP_NOTE_UNDEFINED_ERROR") );
	} 
	else
	{
		echo CAdminMessage::ShowNote( GetMessage("WEBPROSTOR_SMTP_NOTE_SENDED") );
	}
}
else
{
	CAdminMessage::ShowMessage(GetMessage("WEBPROSTOR_SMTP_PAGE_DESCRIPTION"));
}

$sites = Array();
$rsSites = CSite::GetList($by="sort", $order="asc", Array());
while ($arSite = $rsSites->Fetch())
{
	$sites[] = Array(
		"ID" => $arSite["LID"],
		"NAME" => $arSite["NAME"],
	);
}

?>
<form id="webprostor_smtp_send" method="POST" action="<?=$APPLICATION->GetCurPage()?>?lang=<?echo LANG?>" ENCTYPE="multipart/form-data" name="webprostor_smtp_send">
<?
echo bitrix_sessid_post();
$tabControl->Begin();
$tabControl->BeginNextTab();

?>
	<tr>
		<td width="15%" valign="top">
			<span class="required">*</span> <?=GetMessage("WEBPROSTOR_SMTP_TD_SITE_ID")?>:
		</td>
		<td width="85%">
			<select id="site_id" name="site_id">
			<?foreach($sites as $site):?>
			<option value="<?=$site["ID"]?>"<?if($showReq && $_REQUEST["site_id"]==$site["ID"]):?> selected<?endif;?>><?=$site["NAME"]?> [<?=$site["ID"]?>]</option>
			<?endforeach;?>
			</select>
		</td>
	</tr>
	<tr>
		<td width="15%" valign="top">
			<span class="required">*</span> <?=GetMessage("WEBPROSTOR_SMTP_TD_TO_MAIL")?>:
		</td>
		<td width="85%">
			<input type="email" id="to" name="to" value="<?if($showReq):?><?=htmlspecialchars($_REQUEST["to"])?><?endif;?>" />
		</td>
	</tr>
	<tr>
		<td width="15%" valign="top">
			<span class="required">*</span> <?=GetMessage("WEBPROSTOR_SMTP_TD_SUBJECT")?>:
		</td>
		<td width="85%">
			<input type="text" id="subject" name="subject" value="<?if($showReq):?><?=htmlspecialchars($_REQUEST["subject"])?><?endif;?>" />
		</td>
	</tr>
	<tr>
		<td width="15%" valign="top">
			<span class="required">*</span> <?=GetMessage("WEBPROSTOR_SMTP_TD_BODY")?>:
		</td>
		<td width="85%">
			<textarea id="message" name="message" style="width:100%;height:200px;"><?if($showReq):?><?=htmlspecialchars($_REQUEST["message"])?><?endif;?></textarea>
		</td>
	</tr>
<?
$tabControl->Buttons();
?>
	<input type="submit" name="Add" value="<?echo GetMessage("WEBPROSTOR_SMTP_BUTTON_SUBMIT")?>" class="adm-btn-save" />
	<input type="reset" name="Reset" value="<?=GetMessage("WEBPROSTOR_SMTP_BUTTON_RESET")?>" />
<?
$tabControl->End();
echo BeginNote();?>
<span class="required">*</span> <?=GetMessage("REQUIRED_FIELDS")?><br />
<?echo EndNote();
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_admin.php");
?>
