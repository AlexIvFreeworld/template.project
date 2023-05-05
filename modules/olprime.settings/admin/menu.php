<?php

###########################
#						  #
# module settings		  #
# @copyright 2018 olprime #
#						  #
###########################

AddEventHandler('main', 'OnBuildGlobalMenu', 'olprimeMenuSettings');

function olprimeMenuSettings(&$arGlobalMenu, &$arModuleMenu)
{
	IncludeModuleLangFile(__FILE__);
	$moduleName = 'olprime.settings';

	global $APPLICATION;
	$APPLICATION->SetAdditionalCss('/bitrix/css/'.$moduleName.'/menu.css');
	
	if($APPLICATION->GetGroupRight($moduleName) > 'D')
	{
		$arMenu = array(
			'menu_id' => 'olprime_settings',
			'items_id' => 'olprime_settings',
			'text' => GetMessage('OLPRIME_SETTINGS_MENU_TEXT'),
			'sort' => 900,
			'items' => array(
				array(
					'text' => GetMessage('OLPRIME_SETTINGS_SUBMENU_TEXT'),
					'sort' => 10,
					'url' => '/bitrix/admin/'.$moduleName.'_colorSwitch.php',
					'items_id' => 'olprime_settings_main',
				),					
			),
		);
	
		$arGlobalMenu[] = $arMenu;
	}
}