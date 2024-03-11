<?
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.core/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.core/include.php");

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$module_id = 'webprostor.core';
$moduleAccessLevel = $APPLICATION->GetGroupRight($module_id);

if ($moduleAccessLevel == "D")
    $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));

$APPLICATION->SetTitle(Loc::getMessage('WEBPROSTOR_CORE_SUPPORT_TITLE'));
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_admin_after.php");

$payment = false;
$sites = false;

if(
	\Bitrix\Main\Loader::includeModule('webprostor.import') || 
	\Bitrix\Main\Loader::includeModule('webprostor.configurator') || 
	\Bitrix\Main\Loader::includeModule('webprostor.massprocessing') || 
	\Bitrix\Main\Loader::includeModule('webprostor.lpcomponents') || 
	\Bitrix\Main\Loader::includeModule('webprostor.simplecurrency') || 
	\Bitrix\Main\Loader::includeModule('webprostor.simpleforms')
)
{
	$payment = true;
}

if(
	\Bitrix\Main\Loader::includeModule('webprostor.carservice') || 
	\Bitrix\Main\Loader::includeModule('webprostor.stomatology') || 
	\Bitrix\Main\Loader::includeModule('webprostor.beautysalon') || 
	\Bitrix\Main\Loader::includeModule('webprostor.fitness')
)
{
	$sites = true;
}

?>
<div class="adm-detail-content-wrap">
	<div class="adm-detail-content" style="display: flex; padding: 20px;">
		<div style="padding-right: 20px;">
			<div class="ui-alert ui-alert-warning">
				<div>
					<?=Loc::getMessage('WEBPROSTOR_CORE_SUPPORT_CONTENT_1')?>
				</div>
			</div>
			<? if($sites) {?>
			<div class="ui-alert">
				<div>
					<?=Loc::getMessage('WEBPROSTOR_CORE_SUPPORT_CONTENT_2')?>
				</div>
			</div>
			<? } ?>
			<? if($payment) {?>
			<div class="ui-alert">
				<div>
					<?=Loc::getMessage('WEBPROSTOR_CORE_SUPPORT_CONTENT_4')?>
				</div>
			</div>
			<? } ?>
			<?if(COption::GetOptionString("main", "check_agents") != 'N') {?>
			<div class="ui-alert ui-alert-danger ui-alert-icon-danger">
				<div>
					 <span class="ui-alert-message"><?=Loc::getMessage('WEBPROSTOR_CORE_BX_CRONTAB_SUPPORT')?></span>
				</div>
			</div>
			<? } ?>
			<? if(!defined('BX_UTF') || (defined('BX_UTF') && BX_UTF !== true)) {?>
			<div class="ui-alert ui-alert-warning ui-alert-icon-warning">
				<div>
					 <span class="ui-alert-message"><?=Loc::getMessage('WEBPROSTOR_CORE_BX_UTF')?></span>
				</div>
			</div>
			<? } ?>
		</div>
		<div>
			<div style="margin-bottom: 15px;">
				<? if($payment) {?>
				<a style="margin-bottom: 10px;" class="ui-btn ui-btn-primary ui-btn-icon-chat" href="https://webprostor.ru/personal/profile/tickets/0/" target="_blank"><?=Loc::getMessage('WEBPROSTOR_CORE_SUPPORT_TICKET')?></a><br />
				<? } ?>
				<a style="margin-bottom: 10px;" class="ui-btn ui-btn-active ui-btn-icon-mail" href="mailto:solutions@webprostor.ru"><?=Loc::getMessage('WEBPROSTOR_CORE_SUPPORT_EMAIL')?></a><br />
				<? if($payment) {?>
				<a style="margin-bottom: 10px;" class="ui-btn ui-btn-success ui-btn-icon-phone-call" href="tel:+79162762086"><?=Loc::getMessage('WEBPROSTOR_CORE_SUPPORT_CALL')?></a>
				<? } ?>
			</div>
			<? if($payment) {?>
			<div style="margin-bottom: 25px;">
				<a class="ui-btn ui-btn-link" href="https://api.whatsapp.com/send?phone=+79162762086"><div class="ui-icon ui-icon-service-whatsapp ui-icon-xs"><i></i></div>&nbsp;WhatsApp</a><br />
				<a class="ui-btn ui-btn-link" href="tg://msg?to=79162762086"><div class="ui-icon ui-icon-service-telegram ui-icon-xs"><i></i></div>&nbsp;Telegram</a>
			</div>
			<? } ?>
			<div style="margin-bottom: 15px;">
				<a style="margin-bottom: 10px;" class="ui-btn ui-btn-secondary ui-btn-icon-page" href="https://webprostor.ru/learning/" target="_blank"><i class="fa fa-phone"></i><?=Loc::getMessage('WEBPROSTOR_CORE_SUPPORT_INSTRUCTIONS')?></a><br />
				<a style="margin-bottom: 10px;" class="ui-btn ui-btn-secondary ui-btn-icon-camera" href="https://www.youtube.com/@webprostor/playlists" target="_blank"><i class="fa fa-phone"></i><?=Loc::getMessage('WEBPROSTOR_CORE_SUPPORT_VIDEOS')?></a><br />
			</div>
		</div>
	</div>
</div>
<?
$modules_payment = [
	'import' => [
		'LOGO' => '/upload/resize_cache/update/e33/75_75_2/logo.png',
	],
	'configurator' => [
		'LOGO' => '/upload/resize_cache/update/837/75_75_2/logo.png',
	],
	'massprocessing' => [
		'LOGO' => '/upload/resize_cache/update/16a/75_75_2/logo.png',
	],
	'lpcomponents' => [
		'LOGO' => '/upload/resize_cache/update/516/75_75_2/logo.png',
	],
	'simplecurrency' => [
		'LOGO' => '/upload/resize_cache/update/b39/75_75_2/logo.png',
	],
	'simpleforms' => [
		'LOGO' => '/upload/resize_cache/update/903/75_75_2/logo.png',
	],
];
?>
<div class="adm-detail-content-wrap">
	<div class="adm-detail-content" style="padding: 20px;">
		<div class="adm-detail-title">
			<?=Loc::getMessage('WEBPROSTOR_CORE_MODULES_PAYMENT')?>
		</div>
		<div class="modules">
			<?foreach($modules_payment as $code => $module){
			$installed = 'N';
			$downloaded = 'N';
			$webprostor_module_id = 'webprostor.'.$code;
			$installed = \Bitrix\Main\Loader::includeModule($webprostor_module_id) ? 'Y' : 'N';
			if(@file_exists($DOCUMENT_ROOT."/bitrix/modules/".$webprostor_module_id."/install/index.php"))
			{
				$downloaded = 'Y';
			}
			?>
			<div class="module-item" data-downloaded="<?=$downloaded;?>" data-installed="<?=$installed;?>">
				<a href="/bitrix/admin/update_system_market.php?module=<?=$webprostor_module_id?>&lang=<?=LANGUAGE_ID?>" title="<?=Loc::getMessage('WEBPROSTOR_CORE_MODULES_MODULE_'.strtoupper($code))?>">
					<img src="//marketplace.1c-bitrix.ru<?=$module['LOGO']?>" />
				</a>
			</div>
			<? } ?>
		</div>
	</div>
</div>
<?
$modules_sites = [
	'fitness' => [
		'LOGO' => '/upload/resize_cache/update/77b/75_75_2/logo.png',
	],
	'stomatology' => [
		'LOGO' => '/upload/resize_cache/update/c7a/75_75_2/logo.png',
	],
	'beautysalon' => [
		'LOGO' => '/upload/resize_cache/update/dba/75_75_2/logo.png',
	],
	'carservice' => [
		'LOGO' => '/upload/resize_cache/update/a51/75_75_2/logo.png',
	],
];
?>
<div class="adm-detail-content-wrap">
	<div class="adm-detail-content" style="padding: 20px;">
		<div class="adm-detail-title">
			<?=Loc::getMessage('WEBPROSTOR_CORE_MODULES_SITES')?>
		</div>
		<div class="modules">
			<?foreach($modules_sites as $code => $module){
			$installed = 'N';
			$downloaded = 'N';
			$webprostor_module_id = 'webprostor.'.$code;
			$installed = \Bitrix\Main\Loader::includeModule($webprostor_module_id) ? 'Y' : 'N';
			if(@file_exists($DOCUMENT_ROOT."/bitrix/modules/".$webprostor_module_id."/install/index.php"))
			{
				$downloaded = 'Y';
			}
			?>
			<div class="module-item" data-downloaded="<?=$downloaded;?>" data-installed="<?=$installed;?>">
				<a href="/bitrix/admin/update_system_market.php?module=<?=$webprostor_module_id?>&lang=<?=LANGUAGE_ID?>" title="<?=Loc::getMessage('WEBPROSTOR_CORE_MODULES_MODULE_'.strtoupper($code))?>">
					<img src="//marketplace.1c-bitrix.ru<?=$module['LOGO']?>" />
				</a>
			</div>
			<? } ?>
		</div>
	</div>
</div>
<?
$modules_free = [
	'smtp' => [
		'LOGO' => '/upload/resize_cache/update/cb8/75_75_2/logo.png',
	],
	'underconstruct' => [
		'LOGO' => '/upload/resize_cache/update/2f1/75_75_2/logo.png',
	],
	'yaparser' => [
		'LOGO' => '/upload/resize_cache/update/f39/75_75_2/logo.png',
	],
	'resizer' => [
		'LOGO' => '/upload/resize_cache/update/f49/75_75_2/logo.png',
	],
	'massivefilesiblockuploader' => [
		'LOGO' => '/upload/resize_cache/update/139/75_75_2/logo.png',
	],
	'ordercanceller' => [
		'LOGO' => '/upload/resize_cache/update/fc5/75_75_2/logo.png',
	],
	'logotypes' => [
		'LOGO' => '/upload/resize_cache/update/cfe/75_75_2/logo.png',
	],
	'yclients' => [
		'LOGO' => '/upload/resize_cache/update/9a1/i5z0l7sq9892thjm986sye14j0fh7j4h/75_75_2/logo.png',
	],
	'marquiz' => [
		'LOGO' => '/upload/resize_cache/update/d4e/2buwubv0bzjjk3xxr1f9j95t99e61s1s/75_75_2/logo.png',
	],
];
?>
<div class="adm-detail-content-wrap">
	<div class="adm-detail-content" style="padding: 20px;">
		<div class="adm-detail-title">
			<?=Loc::getMessage('WEBPROSTOR_CORE_MODULES_FREE')?>
		</div>
		<div class="modules">
			<?
			foreach($modules_free as $code => $module)
			{
			$installed = 'N';
			$downloaded = 'N';
			$webprostor_module_id = 'webprostor.'.$code;
			$installed = \Bitrix\Main\Loader::includeModule($webprostor_module_id) ? 'Y' : 'N';
			if(@file_exists($DOCUMENT_ROOT."/bitrix/modules/".$webprostor_module_id."/install/index.php"))
			{
				$downloaded = 'Y';
			}
			?>
			<div class="module-item" data-downloaded="<?=$downloaded;?>" data-installed="<?=$installed;?>">
				<a href="/bitrix/admin/update_system_market.php?module=<?=$webprostor_module_id?>&lang=<?=LANGUAGE_ID?>" title="<?=Loc::getMessage('WEBPROSTOR_CORE_MODULES_MODULE_'.strtoupper($code))?>">
					<img src="//marketplace.1c-bitrix.ru<?=$module['LOGO']?>" />
				</a>
			</div>
			<? } ?>
		</div>
	</div>
</div>
<style>
.modules {display: flex; justify-content: start;}
.modules .module-item {position: relative;}
.modules .module-item:not(:last-child) {padding-right: 20px;}
.modules .module-item:before {position:absolute;top:-5px;left:-5px;color:#535c69;width:20px;height:20px;border-radius:10px;background:#bbed21;text-align:center;box-shadow:1px 1px 1px rgba(0,0,0,0.5)}
.modules .module-item[data-downloaded="Y"][data-installed="N"]:before {content:"!";background:#f8f4bc;color:#91711e;font-weight:700;font-size:120%;}
.modules .module-item[data-downloaded="Y"][data-installed="Y"]:before {content:"✔";}
.modules .module-item[data-downloaded="N"]:before {content:"+";background:#eee;font-weight:700;font-size:140%;}
</style>
<?
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');