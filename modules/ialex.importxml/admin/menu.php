<?
use Bitrix\Main\Localization\Loc;

AddEventHandler('main', 'OnBuildGlobalMenu', 'OnBuildGlobalMenuHandlerIAlex');
function OnBuildGlobalMenuHandlerIAlex(&$arGlobalMenu, &$arModuleMenu){
	if(!defined('IALEX_MENU_INCLUDED')){
		define('IALEX_MENU_INCLUDED', true);

		IncludeModuleLangFile(__FILE__);
		$moduleID = 'ialex.importxml';
        // \Bitrix\Main\Diag\Debug::dumpToFile(array('__FILE__' => __FILE__), "", "/log.txt");

		$GLOBALS['APPLICATION']->SetAdditionalCss("/bitrix/css/".$moduleID."/menu.css");

		if($GLOBALS['APPLICATION']->GetGroupRight($moduleID) >= 'R'){
			$arMenu = array(
				'menu_id' => 'global_menu_ialex',
				'text' => Loc::getMessage('IALEX_GLOBAL_MENU_TEXT'),
				'title' => Loc::getMessage('IALEX_GLOBAL_MENU_TITLE'),
				'sort' => 10,
				'items_id' => 'global_menu_ialex_items',
				'icon' => 'imi_max',
				'items' => array(
					array(
						'text' => Loc::getMessage('IALEX_MENU_CONTROL_CENTER_TEXT'),
						'title' => Loc::getMessage('IALEX_MENU_CONTROL_CENTER_TITLE'),
						'sort' => 10,
						'url' => "ialex.importxml_support.php?lang=".LANGUAGE_ID,
						'icon' => 'imi_control_center',
						'page_icon' => 'pi_control_center',
						'items_id' => 'control_center',
					),
				),
			);

			if(!isset($arGlobalMenu['global_menu_ialex'])){
				$arGlobalMenu['global_menu_ialex'] = array(
					'menu_id' => 'global_menu_ialex',
					'text' => Loc::getMessage('IALEX_GLOBAL_MENU_TEXT'),
					'title' => Loc::getMessage('IALEX_GLOBAL_MENU_TITLE'),
					'sort' => 1000,
					'items_id' => 'global_menu_ialex_items',
				);
			}

			$arGlobalMenu['global_menu_ialex']['items'][$moduleID] = $arMenu;
		}
	}
}
?>