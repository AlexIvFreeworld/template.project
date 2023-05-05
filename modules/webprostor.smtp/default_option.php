<?
IncludeModuleLangFile(__FILE__);

$webprostor_smtp_default_option = array(
	"USE_MODULE" 	=> "N",
	"AUTO_ADD_INIT" => "Y",
	"AUTO_DEL_INIT" => "N",
	"LOG_ERRORS" => "Y",
);

$rsSites = CSite::GetList($by="sort", $order="asc", Array());
while ($arSite = $rsSites->Fetch())
{
	$SITE_CODE = strtoupper($arSite["LID"])."_";
	$webprostor_smtp_default_option[$SITE_CODE."REPLACE_FROM"] = "Y";
	$webprostor_smtp_default_option[$SITE_CODE."REQUIRES_AUTHENTICATION"] = "Y";
	$webprostor_smtp_default_option[$SITE_CODE."HELO_COMMAND"] = "EHLO";
	$webprostor_smtp_default_option[$SITE_CODE."CHARSET"] = "utf-8";
	$webprostor_smtp_default_option[$SITE_CODE."PRIORITY"] = 3;
}