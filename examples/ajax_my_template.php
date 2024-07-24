<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
$start = true;
CUtil::InitJSCore(array('custom_main'));
?>

<? if ($USER->IsAdmin() || true) : ?>
	<div class="ctl-menu-wrapper">
		<div class="ctl-menu letters">
			<div class="ctl-menu__toggle">
				<img src="<?= SITE_TEMPLATE_PATH ?>/img/dots-grid.svg" alt="Иконка" aria-hidden="true">
				<span>Товары по алфавиту</span>
			</div>
			<div class="ctl-menu__dropdown">
				<ul class="ctl-menu__abc">
					<? foreach ($arResult["LETTERS"] as $letter) : ?>
						<li><a href="#<?= $letter ?>"><?= $letter ?></a></li>
					<? endforeach; ?>
				</ul>
			</div>
		</div>
		<div class="ctl-menu industries">
			<div class="ctl-menu__toggle">
				<img src="<?= SITE_TEMPLATE_PATH ?>/img/dots-grid.svg" alt="Иконка" aria-hidden="true">
				<span>Товары по отраслям</span>
			</div>
			<div class="ctl-menu__dropdown">
				<ul class="ctl-menu__list">
					<? foreach ($arResult["INDUSTRIES"] as $arItem) : ?>
						<li><a data-code="<?= $arItem["CODE"] ?>"><?= $arItem["NAME"] ?></a></li>
					<? endforeach; ?>
				</ul>
			</div>
		</div>
		<div class="ctl-menu materials">
			<div class="ctl-menu__toggle">
				<img src="<?= SITE_TEMPLATE_PATH ?>/img/dots-grid.svg" alt="Иконка" aria-hidden="true">
				<span>Товары по типу сырья</span>
			</div>
			<div class="ctl-menu__dropdown">
				<ul class="ctl-menu__list">
					<? foreach ($arResult["MATERIAL_TYPE"] as $arItem) : ?>
						<li><a data-code="<?= $arItem["CODE"] ?>"><?= $arItem["NAME"] ?></a></li>
					<? endforeach; ?>
				</ul>
			</div>
		</div>
	</div>
<? endif; ?>

<div class="bottom-size">
	<div class="letters dual">
		<? foreach ($arResult["ITEMS"] as $key => $arItem) : ?>
			<?
			$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
			// R52::debug($arItem["PROPERTIES"]["INDUSTRY"]["VALUE"]);
			?>
			<? if ($start || $first_letter != mb_substr($arItem['NAME'], 0, 1)) : ?>
				<? if (!$start) : ?>
					</ul>
	</div>
<? else : ?>
	<? $start = false ?>
<? endif ?>
<div class="letter-block">
    <span class="anchor-block" id="<?= mb_substr($arItem['NAME'], 0, 1) ?>"></span>
	<h3 class="h3"><? echo mb_substr($arItem['NAME'], 0, 1) ?></h3>
	<ul>
	<? endif ?>
	<li><a href="<? echo $arItem['DETAIL_PAGE_URL'] ?>"><? echo $arItem['NAME'] ?></a></li>

	<? $first_letter = mb_substr($arItem['NAME'], 0, 1); ?>
<? endforeach ?>
	</ul>
</div>
</div>