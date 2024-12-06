<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)	die();

$wizardSiteId = defined("WIZARD_SITE_ID") ? WIZARD_SITE_ID : $GLOBALS['WIZARD_SITE_ID'];
$wizardSiteDir = defined("WIZARD_SITE_DIR") ? WIZARD_SITE_DIR : $GLOBALS['WIZARD_SITE_DIR'];
$wizardSitePath = defined("WIZARD_SITE_PATH") ? WIZARD_SITE_PATH : $GLOBALS['WIZARD_SITE_PATH'];
if(!defined("WIZARD_TEMPLATE_ID")) return;
if(!defined("WIZARD_TEMPLATE_ABSOLUTE_PATH")) return;
if(!defined("WIZARD_THEME_ID")) return;

$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"].BX_PERSONAL_ROOT."/templates/".WIZARD_TEMPLATE_ID."/";
//$bitrixTemplateDir = $_SERVER["DOCUMENT_ROOT"]."/local/templates/".WIZARD_TEMPLATE_ID."/";	

set_time_limit(0);

if (!CModule::IncludeModule("highloadblock"))
	return;

if (!WIZARD_INSTALL_DEMO_DATA)
	return;

$HL_ID = $_SESSION["ALLCORP3MEDC_HBLOCK_SERVICES_ID"];
$HL_CURRENCY_FIELD_ID = $_SESSION['ALLCORP3MEDC_HBLOCK_SERVICES_CURRENCY_FIELD_ID'];
unset(
	$_SESSION["ALLCORP3MEDC_HBLOCK_SERVICES_ID"],
	$_SESSION['ALLCORP3MEDC_HBLOCK_SERVICES_CURRENCY_FIELD_ID']
);

//adding rows
WizardServices::IncludeServiceLang("references_services.php", LANGUAGE_ID);

use Bitrix\Highloadblock as HL;
global $USER_FIELD_MANAGER;

if($HL_ID){
	$hldata = HL\HighloadBlockTable::getById($HL_ID)->fetch();
	$hlentity = HL\HighloadBlockTable::compileEntity($hldata);

	if( $HL_CURRENCY_FIELD_ID ){
		$res = CUserFieldEnum::GetList([], [
			'USER_FIELD_ID' => $HL_CURRENCY_FIELD_ID,
			'XML_ID' => 'RUB',
		]);

		while($arr = $res->Fetch()){
			$currency_id = $arr['ID'];
		}
	}

	$entity_data_class = $hlentity->getDataClass();
	$arProfits = array(
		"CUT" => array(
			"XML_ID" => "CUT",
			'PRICE' => "0 #CURRENCY#",
			'PRICE_OLD' => "2 000 #CURRENCY#",
			'FILTER_PRICE' => "0",
		),
	);
	$sort = 100;
	foreach($arProfits as $profitName => $arFile){
		$arData = [
			'UF_NAME' => GetMessage("WZD_REF_SERVICES_".$profitName),
			'UF_SORT' => $sort,
			'UF_XML_ID' => ($arFile["XML_ID"] ?: ToLower($profitName)),
		];

		if( isset($arFile['PRICE']) ) 
			$arData['UF_PRICE'] = $arFile['PRICE'];

		if( isset($arFile['PRICE_OLD']) ) 
			$arData['UF_PRICE_OLD'] = $arFile['PRICE_OLD'];
			
		if( isset($arFile['FILTER_PRICE']) ) 
			$arData['UF_FILTER_PRICE'] = $arFile['FILTER_PRICE'];

		if( isset($currency_id) && $currency_id ) 
			$arData['UF_CURRENCY'] = $currency_id;

		$USER_FIELD_MANAGER->EditFormAddFields('HLBLOCK_'.$HL_ID, $arData);
		$USER_FIELD_MANAGER->checkFields('HLBLOCK_'.$HL_ID, null, $arData);
		$result = $entity_data_class::add($arData);
		$sort += 100;
	}
}
?>