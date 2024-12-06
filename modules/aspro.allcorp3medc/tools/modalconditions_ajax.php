<?
define('STOP_STATISTICS', true);
define('PUBLIC_AJAX_MODE', true);
define('BX_PUBLIC_MODE', 1);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
@include_once(__DIR__.'/../lib/catalog_cond.php');

use \Bitrix\Main\Localization\Loc,
	CAllcorp3Medc as Solution,
	Aspro\Allcorp3Medc\CCatalogCondTree as CatalogCondTree;

if (
	!check_bitrix_sessid() ||
	!\Bitrix\Main\Loader::includeModule('iblock') ||
	!class_exists('CAllcorp3Medc') || 
	!\Bitrix\Main\Loader::IncludeModule(Solution::moduleID)
)
	return;

const ModuleName = 'Allcorp3Medc';

$request = \Bitrix\Main\Application::getInstance()->getContext()->getRequest();
$request->addFilter(new \Bitrix\Main\Web\PostDecodeFilter);

$action = $request->get('action');
if($action){
	if($action === 'init' || $action === 'save'){
		require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_js.php');

		$eventManager = \Bitrix\Main\EventManager::getInstance();
		$eventManager->unRegisterEventHandler('catalog', 'OnCondCatControlBuildList', 'catalog', 'CCatalogCondCtrlGroup', 'GetControlDescr');
		$eventManager->unRegisterEventHandler('catalog', 'OnCondCatControlBuildList', 'catalog', 'CCatalogCondCtrlIBlockFields', 'GetControlDescr');
		$eventManager->unRegisterEventHandler('catalog', 'OnCondCatControlBuildList', 'catalog', 'CCatalogCondCtrlIBlockProps', 'GetControlDescr');

		$eventManager->addEventHandlerCompatible(
			'catalog',
			'OnCondCatControlBuildList', 
			array(
				'\Aspro\\'.ModuleName.'\CCatalogCondCtrlGroup',
				'GetControlDescr'
			)
		);
		$eventManager->addEventHandlerCompatible(
			'catalog',
			'OnCondCatControlBuildList', 
			array(
				'\Aspro\\'.ModuleName.'\Property\ModalConditions\CondCtrl',
				'GetControlDescr'
			)
		);

		$ids = $request->get('ids');
		$success = false;

		if(!empty($ids) && is_array($ids)){
			$condTree = new CatalogCondTree();
			$success = $condTree->Init(
				AS_COND_MODE_DEFAULT,
				AS_COND_BUILD_CATALOG,
				array(
					'FORM_NAME' => $ids['form'],
					'CONT_ID' => $ids['container'],
					'JS_NAME' => $ids['treeObject']
				)
			);
		}

		$eventManager->RegisterEventHandler('catalog', 'OnCondCatControlBuildList', 'catalog', 'CCatalogCondCtrlGroup', 'GetControlDescr');
		$eventManager->RegisterEventHandler('catalog', 'OnCondCatControlBuildList', 'catalog', 'CCatalogCondCtrlIBlockFields', 'GetControlDescr');
		$eventManager->RegisterEventHandler('catalog', 'OnCondCatControlBuildList', 'catalog', 'CCatalogCondCtrlIBlockProps', 'GetControlDescr');

		if($success){
			if($action === 'init'){
				try{
					$condition = \Bitrix\Main\Web\Json::decode($request->get('condition'));
				}
				catch (Exception $e){
					$condition = array();
				}

				$condTree->Show($condition);
			}
			elseif($action === 'save'){
				$result = $condTree->Parse();

				$GLOBALS['APPLICATION']->RestartBuffer();
				echo \Bitrix\Main\Web\Json::encode($result);
			}
		}

		\CMain::FinalActions();
		die();
	}
}
