<?php

namespace Aspro\Allcorp3Medc\Product;

use CAllcorp3Medc as Solution,
	CAllcorp3MedcCache as Cache,
	Aspro\Allcorp3Medc\Functions\CAsproAllcorp3 as Functions;

class Image
{
	public static $defaultSize = 350;
	const noImagePath = SITE_TEMPLATE_PATH . '/images/svg/noimage_product.svg';

	/**
	 * @param array $arOptions = [
	 * 	'ITEM' => array,
	 * 	'ADDITIONAL_CLASS' => string,
	 * 	'ALT' => string,
	 * 	'TITLE' => string,
	 * ]
	 */
	public static function showNoImage(array $arOptions = []): void
	{
		$arDefaultConfig = [
			'CLASS' => 'img-responsive',
			'ADDITIONAL_CLASS' => '',
			'ALT' => '',
			'TITLE' => '',
			'SRC' => static::noImagePath,
			'FANCY' => false,
		];
		$arConfig = array_merge($arDefaultConfig, $arOptions);
		
		if (!$arConfig['SRC']) {
			$arConfig["SRC"] = static::noImagePath;
		}
		

		Functions::showBlockHtml([
			'FILE' => 'images/no_image_view.php',
			'PARAMS' => $arConfig,
		]);
	}

	/**
	 * Get section images for items without defail or preview picture
	 */
	public static function getSectionsImages(array $arOptions = []): array
	{
		$arDefaultConfig = [
			'ITEMS' => [],
			'WIDTH' => static::$defaultSize,
			'HEIGHT' => static::$defaultSize,
		];
		$arConfig = array_merge($arDefaultConfig, $arOptions);
		$arItems = $arConfig['ITEMS'];

		$arConfig['IBLOCK_ID'] = Solution::GetFrontParametrValue('CATALOG_IBLOCK_ID');
		$arSectionsID = array_reduce($arItems, function ($arIDs, &$arItem) use ($arConfig) {
			if ($arItem['IBLOCK_ID'] == $arConfig['IBLOCK_ID']) {
				if ($arItem['~IBLOCK_SECTION_ID'] && !in_array($arItem['~IBLOCK_SECTION_ID'], $arIDs)) {
					$arIDs[] = $arItem['~IBLOCK_SECTION_ID'];
				}
			}

			return $arIDs;
		}, []);

		$arSections = [];
		if ($arSectionsID) {
			$arSections = Cache::CIBlockSection_GetList(
				["CACHE" => ["MULTI" => "N", "TAG" => Cache::GetIBlockCacheTag($arConfig['IBLOCK_ID']), "GROUP" => "ID"]], 
				["IBLOCK_ID" => $arConfig['IBLOCK_ID'], "ID" => $arSectionsID], 
				false, 
				["ID", "PICTURE", "DETAIL_PICTURE"]
			);
			foreach ($arSections as $key => $arSection) {
				foreach (['PICTURE', 'DETAIL_PICTURE'] as $prop) {
					if ($arSection[$prop]) {
						$arPicture = \CFile::ResizeImageGet($arSection[$prop], [
							"width" => $arConfig['WIDTH'], 
							"height" => $arConfig['HEIGHT'],
						], BX_RESIZE_IMAGE_PROPORTIONAL, true);
						$arPicture['id'] = $arSection[$prop];
						$arSections[$key][$prop] = $arPicture;
					}
				}
			}
		}

		return $arSections;
	}
}
