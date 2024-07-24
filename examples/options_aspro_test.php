<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetTitle("Видеоматериалы");

use \Bitrix\Main\Localization\Loc;
// aspro.allcorp3
$showRegion = \Bitrix\Main\Config\Option::get("aspro.allcorp3", 'SHOW_REGION_CONTACT', 'N');
$arModuleParams = \Bitrix\Main\Config\Option::getForModule("aspro.allcorp3");
// R52::debug($arModuleParams);
// [HEADER_PHONES] => 3
// [HEADER_PHONES_array_PHONE_DESCRIPTION_0] => Отдел продаж
//     [HEADER_PHONES_array_PHONE_DESCRIPTION_1] => Техподдержка
//     [HEADER_PHONES_array_PHONE_DESCRIPTION_2] => Служба персонала
//     [HEADER_PHONES_array_PHONE_VALUE_0] => +7 (831) 260-13-08
//     [HEADER_PHONES_array_PHONE_VALUE_1] => +7 (831) 260-13-08
//     [HEADER_PHONES_array_PHONE_VALUE_2] => +7 (915) 956-90-28
?>
<div class="contacts-detail__property">
	<div class="contact-property contact-property--phones">
		<div class="contact-property__label font_13 color_999">Телефон</div>

		<div class="contact-property__value dark_link" itemprop="telephone">
			<? for ($i = 0; $i < intval($arModuleParams["HEADER_PHONES"]); ++$i) : ?>
				<?
				$phone = $arModuleParams["HEADER_PHONES_array_PHONE_VALUE_" . $i];
				$href = 'tel:' . str_replace(array(' ', '-', '(', ')'), '', $phone);
				$description = $arModuleParams["HEADER_PHONES_array_PHONE_DESCRIPTION_" . $i];
				?>
				<div class="contact-property__value dark_link" itemprop="telephone"><a title="<?= $description ?>" href="<?= $href ?>"><?= $phone ?></a><span><?= " (" . $description . ")" ?></span></div>
			<? endfor; ?>
		</div>
	</div>
</div>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>