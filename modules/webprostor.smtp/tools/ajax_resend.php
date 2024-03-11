<?
define("STOP_STATISTICS", true);

require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_before.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.smtp/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.smtp/include.php");

$module_id = 'webprostor.smtp';
$moduleAccessLevel = $APPLICATION->GetGroupRight($module_id);

if(
	$_SERVER["REQUEST_METHOD"] == "POST" 
	&& check_bitrix_sessid()
	&& $moduleAccessLevel>"D"
)
{
	
	$CSMTPLog = new CWebprostorSmtpLogs;
	
	if(intval($MESSAGE_ID)>0)
	{
		$logRes = $CSMTPLog->GetById($MESSAGE_ID);
		$messageArr = $logRes->Fetch();
	}
	
	if(!check_bitrix_sessid())
	{
		$arErrors[] = GetMessage("ACCESS_DENIED");
	}
	elseif(!$MESSAGE_ID)
	{
		$arErrors[] = GetMessage("MESSAGE_ID_NOT_SET");
	}
	elseif(!$messageArr && !is_array($messageArr))
	{
		$arErrors[] = GetMessage("MESSAGE_NOT_FOUND");
	}
	?>
	<script>
		CloseWaitWindow();
	</script>
	<?
	
	if(!is_array($arErrors))
	{
		
		$smtp = new CWebprostorSmtp($messageArr["SITE_ID"]);
		$send = $smtp->SendMail($messageArr["SOURCE_TO"], $messageArr["SOURCE_SUBJECT"], $messageArr["SOURCE_MESSAGE"], $messageArr["SOURCE_HEADERS"], $messageArr["SOURCE_PARAMETERS"], false, $MESSAGE_ID);
		
		if($send)
		{
			$arMessages[] = GetMessage("MESSAGE_RESENDED");
			echo '<script>$("#message_resend_button_"+'.$MESSAGE_ID.').after("<span class=\"adm-lamp adm-lamp-in-list adm-lamp-green\"></span>").remove();</script>';
		}
		else
		{
			$logResNew = $CSMTPLog->GetById($MESSAGE_ID);
			$messageArrNew = $logResNew->Fetch();
					
			$arErrors[] = GetMessage("MESSAGE_NOT_RESENDED").'<br />'.$messageArrNew["ERROR_TEXT"];
		}
		
		echo '<script>EndResend('.$MESSAGE_ID.');</script>';
	}
	
	if(is_array($arErrors))
	{
		foreach($arErrors as $strMessage)
		{
			CWebprostorCoreFunctions::showAlertBegin('danger', 'danger');
			echo $strMessage;
			CWebprostorCoreFunctions::showAlertEnd();
		}
	}
	
	if(is_array($arMessages))
	{
		foreach($arMessages as $strMessage)
		{
			CWebprostorCoreFunctions::showAlertBegin('success', 'info');
			echo $strMessage;
			CWebprostorCoreFunctions::showAlertEnd();
		}
	}
}