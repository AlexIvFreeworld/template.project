<?
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use IAlex\IhelpXML;

Loc::loadMessages(__FILE__);

$module_id = 'ialex.importxml';
$moduleAccessLevel = $APPLICATION->GetGroupRight($module_id);
Loader::includeModule($module_id);

if ($moduleAccessLevel == "D")
	$APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));

$APPLICATION->SetTitle(Loc::getMessage('AILEX_SUPPORT_TITLE'));
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
CJSCore::Init(array('ialex_main', 'ialex_ajax'));
if (CModule::IncludeModule('iblock')) {
	$arBlocks = array();
	$resBlocks = CIBlock::GetList(
		array("SORT" => "ASC"),
		array(
			// 'SITE_ID' => SITE_ID,
			'ACTIVE' => 'Y',
			"CNT_ACTIVE" => "Y",
		),
		true
	);
	while ($ar_res = $resBlocks->GetNext()) {
		$arBlocks[] = $ar_res;
	}
}
?>
<? //IhelpXML::seyText("hello")
?>
<div class="ialex-adm">
	<form enctype="multipart/form-data" action="/local/modules/ialex.importxml/admin/options.php" onsubmit="event.preventDefault(); submitForm(this);" method="POST" class="form">
		<input type="hidden" name="type" value="xml">
		<div class="popup__body">
			<div class="form__item">
				<label for="request_block_id" class="form__label">ИД информационного блока: *</label>
				<select name="block_id" id="request_block_id">
					<option value="select">Выбрать</option>
					<? foreach ($arBlocks as $arBlock): ?>
						<option value="<?= $arBlock["ID"] ?>"><?= $arBlock["ID"] ?></option>
					<? endforeach; ?>
				</select>
				<!-- <input type="text" name="block_id" id="request_block_id" class="form__field" required> -->
			</div>
			<div class="form__item">
				<label for="request_currency" class="form__label">Валюта: *</label>
				<select name="currency" id="request_currency">
					<option value="RUR">RUR</option>
					<option value="USD">USD</option>
				</select>
				<!-- <input type="text" name="currency" id="request_currency" class="form__field" required> -->
			</div>
			<div class="form__item ajax-sections-wrap">
			</div>
		</div>

		<div class="popup__footer ialex-hide">
			<button type="submit" class="btn form__submit">Сформировать XML</button>
		</div>
	</form>
	<div class="container_result"></div>
</div>
<?
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_admin.php');
