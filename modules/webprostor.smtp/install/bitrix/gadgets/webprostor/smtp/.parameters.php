<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();

if(!CModule::IncludeModule('webprostor.smtp')) {
	return;
}

$sites = [
	'' => GetMessage("WEBPROSTOR_SMTP_GADGET_SITE_ID_ALL")
];
$rsSites = CSite::GetList($by="sort", $order="desc", []);
while ($arSite = $rsSites->Fetch())
{
	$sites[$arSite["ID"]] = $arSite["NAME"].' ['.$arSite["ID"].']';
}

$arTypes = array(
	"ALL" => GetMessage("WEBPROSTOR_SMTP_GADGET_TYPE_ALL"), 
	"OK" => GetMessage("WEBPROSTOR_SMTP_GADGET_TYPE_OK"), 
	"ERROR" => GetMessage("WEBPROSTOR_SMTP_GADGET_TYPE_ERROR")
);

$arUserParams = Array(
	'SITE_ID'=>Array(
		'NAME' => GetMessage("WEBPROSTOR_SMTP_GADGET_SITE_ID"),
		'TYPE' => 'LIST',
		'DEFAULT' => "",
		'VALUES' => $sites,
	),
	'TYPES'=>Array(
		'NAME' => GetMessage("WEBPROSTOR_SMTP_GADGET_TYPES"),
		'TYPE' => 'LIST',
		'MULTIPLE' => 'Y',
		'DEFAULT' => array("ALL", "OK", "ERROR"),
		'VALUES' => $arTypes,
	),
	'DAYS'=>Array(
		'NAME' => GetMessage("WEBPROSTOR_SMTP_GADGET_DAYS"),
		'TYPE' => 'STRING',
		'DEFAULT' => 7,
	),
	'WIDTH'=>Array(
		'NAME' => GetMessage("WEBPROSTOR_SMTP_GADGET_WIDTH"),
		'TYPE' => 'STRING',
		'DEFAULT' => 500,
	),
	'HEIGHT'=>Array(
		'NAME' => GetMessage("WEBPROSTOR_SMTP_GADGET_HEIGHT"),
		'TYPE' => 'STRING',
		'DEFAULT' => 300,
	),
);

$arParameters = [
	'USER_PARAMETERS' => $arUserParams
];
