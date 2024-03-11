<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
	die();

$aMenuLinksExt = $APPLICATION->IncludeComponent(
	"bitrix:menu.sections",
	"",
	array(
		"IS_SEF" => "Y",
		"SEF_BASE_URL" => "/catalog/",
		"SECTION_PAGE_URL" => "#SECTION_CODE_PATH#/",
		"DETAIL_PAGE_URL" => "#SECTION_CODE_PATH#/#ELEMENT_ID#",
		"IBLOCK_TYPE" => "products",
		"IBLOCK_ID" => "8",
		"DEPTH_LEVEL" => "3",
		"CACHE_TYPE" => "N",
		"CACHE_TIME" => "0"
	),
	false
);
$curPage = $APPLICATION->GetCurPage();
// R52::debug($curPage);
if (!empty($aMenuLinksExt)) : ?>
	<div class="catalog__sidebar">
		<div class="sidebar">
			<ul class="sidebar__list sidebar__list_level1">
				<? $lastLevel = 1; ?>
				<? foreach ($aMenuLinksExt as $arLink) : ?>
					<? if ($lastLevel > $arLink[3]["DEPTH_LEVEL"] && $arLink[3]["DEPTH_LEVEL"] == 1) : ?>
			</ul>
			</li>
		<? endif; ?>
		<? if ($arLink[3]["DEPTH_LEVEL"] == 1 && $arLink[3]["IS_PARENT"] == 1) : ?>
			<li class="sidebar__item sidebar__item_level1">
				<span class="list_level2_show"><?= (strpos($curPage, $arLink[1]) !== false) ? "-" : "+" ?></span>
				<a href="<?= $arLink[1] ?>" class="sidebar__link <?= (strpos($curPage, $arLink[1]) !== false) ? "open" : "" ?>"><?= $arLink[0] ?></a>
				<ul class="sidebar__list sidebar__list_level2">
				<? endif; ?>
				<? if ($arLink[3]["DEPTH_LEVEL"] == 1 && $arLink[3]["IS_PARENT"] != 1) : ?>
					<li class="sidebar__item sidebar__item_level1">
						<a href="<?= $arLink[1] ?>" class="sidebar__link"><?= $arLink[0] ?></a>
					</li>
				<? endif; ?>
				<? if ($arLink[3]["DEPTH_LEVEL"] == 2) : ?>
					<li class="sidebar__item sidebar__item_level2">
						<a href="<?= $arLink[1] ?>" class="sidebar__link"><?= $arLink[0] ?></a>
					</li>
				<? endif; ?>
				<? $lastLevel = $arLink[3]["DEPTH_LEVEL"] ?>
			<? endforeach; ?>
				</ul>
		</div>
	</div>
<?php endif; ?>