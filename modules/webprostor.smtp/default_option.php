<?
IncludeModuleLangFile(__FILE__);

$webprostor_smtp_default_option = array(
	"USE_MODULE" 	=> "N",
	"AUTO_ADD_INIT" => "Y",
	"AUTO_DEL_INIT" => "N",
	"USE_DEFAULT_SITE_ID_IF_EMPTY" => "Y",
	"LOG_ERRORS" => "Y",
	"NOTIFY_LIMIT" => "1000",
	"DONT_SAVE_SEND_INFO" => "Y",
	"ENABLE_RESEND" => "N",
	"RESEND_RETRY_COUNT" => "3",
	"RESEND_AGENT_INTERVAL" => "300",
	"RESEND_MESSAGE_COUNT" => "50",
	"DEBUG_LEVEL" => "0",
);

$rsSites = CSite::GetList($by="sort", $order="asc", Array());
while ($arSite = $rsSites->Fetch())
{
	$SITE_CODE = strtoupper($arSite["LID"])."_";
	$webprostor_smtp_default_option[$SITE_CODE."REPLACE_FROM"] = "Y";
	$webprostor_smtp_default_option[$SITE_CODE."REQUIRES_AUTHENTICATION"] = "Y";
	$webprostor_smtp_default_option[$SITE_CODE."USE_DKIM"] = "N";
	$webprostor_smtp_default_option[$SITE_CODE."DKIM_SELECTOR"] = "mail";
	if($arSite["SERVER_NAME"] != '')
		$webprostor_smtp_default_option[$SITE_CODE."DKIM_DOMAIN"] = $arSite["SERVER_NAME"];
	
	if(defined("BX_UTF") && BX_UTF)
		$webprostor_smtp_default_option[$SITE_CODE."CHARSET"] = "utf-8";
	else
		$webprostor_smtp_default_option[$SITE_CODE."CHARSET"] = "windows-1251";
	
	$webprostor_smtp_default_option[$SITE_CODE."CHARSET"] = "utf-8";
	$webprostor_smtp_default_option[$SITE_CODE."PRIORITY"] = 5;
}