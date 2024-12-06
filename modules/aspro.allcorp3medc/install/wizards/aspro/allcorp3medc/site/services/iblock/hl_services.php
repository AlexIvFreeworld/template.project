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
	
if(!IsModuleInstalled("highloadblock") && file_exists($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/highloadblock/")){
	$installFile = $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/highloadblock/install/index.php";
	if(!file_exists($installFile))
		return false;
	include_once($installFile);

	$moduleIdTmp = str_replace(".", "_", "highloadblock");
	if(!class_exists($moduleIdTmp))
		return false;

	$module = new $moduleIdTmp;
	if(!$module->InstallDB())
		return false;

	$module->InstallEvents();
	if(!$module->InstallFiles())
		return false;
}

if (!CModule::IncludeModule("highloadblock"))
	return;

if (!WIZARD_INSTALL_DEMO_DATA)
	return;

use Bitrix\Highloadblock as HL;

unset($_SESSION["ALLCORP3MEDC_HBLOCK_SERVICES_ID"]);

$dbHblock = HL\HighloadBlockTable::getList(array("filter" => array("NAME" => "AsproAllcorp3MedcServices")));
if(!$dbHblock->Fetch()){
	$data = array('NAME' => 'AsproAllcorp3MedcServices', 'TABLE_NAME' => 'b_hlbd_complectservices');
	$result = HL\HighloadBlockTable::add($data);
	$HL_ID = $result->getId();
	$_SESSION["ALLCORP3MEDC_HBLOCK_SERVICES_ID"] = $HL_ID;
	
	if($HL_ID){
		$hldata = HL\HighloadBlockTable::getById($HL_ID)->fetch();
		$hlentity = HL\HighloadBlockTable::compileEntity($hldata);
		
		//adding user fields
		$arUserFields = array (
			array (
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_NAME',
				'USER_TYPE_ID' => 'string',
				'XML_ID' => 'UF_NAME',
				'SORT' => '100',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'N',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
            array (
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_XML_ID',
				'USER_TYPE_ID' => 'string',
				'XML_ID' => 'UF_XML_ID',
				'SORT' => '200',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'N',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
            array (
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_PRICE',
				'USER_TYPE_ID' => 'string',
				'XML_ID' => 'UF_PRICE',
				'SORT' => '300',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'Y',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
            array (
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_PRICE_OLD',
				'USER_TYPE_ID' => 'string',
				'XML_ID' => 'UF_PRICE_OLD',
				'SORT' => '400',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'N',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
            array (
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_FILTER_PRICE',
				'USER_TYPE_ID' => 'string',
				'XML_ID' => 'UF_FILTER_PRICE',
				'SORT' => '500',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'N',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
            array (
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_CURRENCY',
				'USER_TYPE_ID' => 'enumeration',
				'XML_ID' => 'UF_CURRENCY',
				'SORT' => '600',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'N',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
			array (
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_SORT',
				'USER_TYPE_ID' => 'double',
				'XML_ID' => 'UF_PROFIT_SORT',
				'SORT' => '700',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'N',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
			array (
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_DESCRIPTION',
				'USER_TYPE_ID' => 'string',
				'XML_ID' => 'UF_DESCRIPTION',
				'SORT' => '800',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'N',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
            array (
				'ENTITY_ID' => 'HLBLOCK_'.$HL_ID,
				'FIELD_NAME' => 'UF_FULL_DESCRIPTION',
				'USER_TYPE_ID' => 'string',
				'XML_ID' => 'UF_FULL_DESCRIPTION',
				'SORT' => '900',
				'MULTIPLE' => 'N',
				'MANDATORY' => 'N',
				'SHOW_FILTER' => 'N',
				'SHOW_IN_LIST' => 'Y',
				'EDIT_IN_LIST' => 'Y',
				'IS_SEARCHABLE' => 'N',
			),
		);
		
		$arLanguages = Array();
		$rsLanguage = CLanguage::GetList($by, $order, array());
		while($arLanguage = $rsLanguage->Fetch()){
			$arLanguages[] = $arLanguage["LID"];
		}

		$obUserField  = new CUserTypeEntity;
		foreach($arUserFields as $arFields){
			$dbRes = CUserTypeEntity::GetList(Array(), Array("ENTITY_ID" => $arFields["ENTITY_ID"], "FIELD_NAME" => $arFields["FIELD_NAME"]));
			if($dbRes->Fetch()){
				continue;
			}

			$arLabelNames = Array();
			foreach($arLanguages as $languageID){
				WizardServices::IncludeServiceLang("references_services.php", $languageID);
				$arLabelNames[$languageID] = GetMessage($arFields["FIELD_NAME"]);
			}

			$arFields["EDIT_FORM_LABEL"] = $arLabelNames;
			$arFields["LIST_COLUMN_LABEL"] = $arLabelNames;
			$arFields["LIST_FILTER_LABEL"] = $arLabelNames;
			$ID_USER_FIELD = $obUserField->Add($arFields);

			if( $arFields['FIELD_NAME'] === 'UF_CURRENCY' ){
				$_SESSION['ALLCORP3MEDC_HBLOCK_SERVICES_CURRENCY_FIELD_ID'] = $ID_USER_FIELD;
				$enum = new \CUserFieldEnum;

				$enum->setEnumValues($ID_USER_FIELD, [
					'n0' => [
						'VALUE' => GetMessage('WZD_OPTION_REF_CURRENCY_EUR'),
						'XML_ID' => 'EUR',
					],
					'n1' => [
						'VALUE' => GetMessage('WZD_OPTION_REF_CURRENCY_RUB'),
						'XML_ID' => 'RUB',
					],
					'n2' => [
						'VALUE' => GetMessage('WZD_OPTION_REF_CURRENCY_UAH'),
						'XML_ID' => 'UAH',
					],
					'n3' => [
						'VALUE' => GetMessage('WZD_OPTION_REF_CURRENCY_USD'),
						'XML_ID' => 'USD',
					],
				]);
			}
		}
	}
}
?>