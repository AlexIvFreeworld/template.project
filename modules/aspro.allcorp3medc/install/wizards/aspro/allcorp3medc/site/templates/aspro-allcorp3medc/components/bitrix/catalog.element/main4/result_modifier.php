<?
/* category path */
if(
	$arResult['IBLOCK_SECTION_ID'] &&
	!$arResult['CATEGORY_PATH']
){
	$arCategoryPath = array();
	if(isset($arResult['SECTION']['PATH'])){
		foreach($arResult['SECTION']['PATH'] as $arCategory){
			$arCategoryPath[$arCategory['ID']] = $arCategory['NAME'];
		}
	}
	
	$arResult['CATEGORY_PATH'] = implode('/', $arCategoryPath);
}
/* */

// Event for manupulation arResult depends on properties
foreach (GetModuleEvents(VENDOR_MODULE_ID, 'onAsproCatalogElementModifyProperties', true) as $arEvent)
	ExecuteModuleEventEx($arEvent, [&$arResult, $arParams, $this->__name]);

/* main gallery */
$arResult['DETAIL_PICTURE'] = $arResult['DETAIL_PICTURE'] ?: $arResult['PREVIEW_PICTURE'];

$arResult['GALLERY'] = TSolution\Functions::getSliderForItem([
	'TYPE' => 'catalog_block',
	'PROP_CODE' => $arParams['ADD_PICT_PROP'],
	// 'ADD_DETAIL_SLIDER' => false,
	'ITEM' => $arResult,
	'PARAMS' => $arParams,
]);

/* get picture of section */
if ($arParams["REPLACE_NOIMAGE_WITH_SECTION_PICTURE"]) {
	$arSectionImages = TSolution\Product\DetailGallery::getSectionsImages([
		'ITEMS' => [$arResult],
		'WIDTH' => TSolution\Product\DetailGallery::$defaultSize,
		'HEIGHT' => TSolution\Product\DetailGallery::$defaultSize,
	]);
	$arPicture = $arSectionImages[$arResult['~IBLOCK_SECTION_ID']]['DETAIL_PICTURE'] ?? ($arSectionImages[$arResult['~IBLOCK_SECTION_ID']]['PICTURE']) ?? '';
	if (is_array($arPicture)) {
		if (!$arResult['GALLERY']) {
			$arResult['NO_IMAGE'] = [
				'ID' => $arPicture['id'],
				'SRC' => $arPicture['src'],
			];
		}

		if ($bShowSKU && $arResult['SKU']['CURRENT'] && !$arResult['SKU']['CURRENT']['PREVIEW_PICTURE']) {
			$arResult['SKU']['CURRENT']['NO_IMAGE'] = [
				'ID' => $arPicture['id'],
			];
		}
	}
}

/* big gallery */
if($arParams['SHOW_BIG_GALLERY'] === 'Y'){
	$arResult['BIG_GALLERY'] = array();
	
	if(
		$arParams['BIG_GALLERY_PROP_CODE'] && 
		isset($arResult['PROPERTIES'][$arParams['BIG_GALLERY_PROP_CODE']]) && 
		$arResult['PROPERTIES'][$arParams['BIG_GALLERY_PROP_CODE']]['VALUE']
	){
		foreach($arResult['PROPERTIES'][$arParams['BIG_GALLERY_PROP_CODE']]['VALUE'] as $img){
			$arPhoto = CFile::GetFileArray($img);

			$alt = $arPhoto['DESCRIPTION'] ?: $arPhoto['ALT'] ?: $arResult['NAME'];
			$title = $arPhoto['DESCRIPTION'] ?: $arPhoto['TITLE'] ?: $arResult['NAME'];;

			$arResult['BIG_GALLERY'][] = array(
				'DETAIL' => $arPhoto,
				'PREVIEW' => CFile::ResizeImageGet($img, array('width' => 1500, 'height' => 1500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true),
				'THUMB' => CFile::ResizeImageGet($img , array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_EXACT, true),
				'TITLE' => $title,
				'ALT' => $alt,
			);
		}
	}
}

/* brand item */
$arBrand = array();
if(
	strlen($arResult["DISPLAY_PROPERTIES"]["BRAND"]["VALUE"]) &&
	$arResult["PROPERTIES"]["BRAND"]["LINK_IBLOCK_ID"]
){
	$arBrand = TSolution\Cache::CIBLockElement_GetList(
		array(
			'CACHE' => array(
				"MULTI" =>"N", 
				"TAG" => TSolution\Cache::GetIBlockCacheTag($arResult["PROPERTIES"]["BRAND"]["LINK_IBLOCK_ID"])
			)
		),
		array(
			"IBLOCK_ID" => $arResult["PROPERTIES"]["BRAND"]["LINK_IBLOCK_ID"],
			"ACTIVE" => "Y", 
			"ID" => $arResult["DISPLAY_PROPERTIES"]["BRAND"]["VALUE"]
		),
		false,
		false,
		array("ID", "NAME", "CODE", "PREVIEW_TEXT", "PREVIEW_TEXT_TYPE", "DETAIL_TEXT", "DETAIL_TEXT_TYPE", "PREVIEW_PICTURE", "DETAIL_PICTURE", "DETAIL_PAGE_URL", "PROPERTY_SITE")
	);
	if($arBrand){
		$picture = ($arBrand["PREVIEW_PICTURE"] ? $arBrand["PREVIEW_PICTURE"] : $arBrand["DETAIL_PICTURE"]);
		if($picture){
			$arBrand["IMAGE"] = CFile::ResizeImageGet($picture, array("width" => 200, "height" => 40), BX_RESIZE_IMAGE_PROPORTIONAL, true);
			$arBrand["IMAGE"]["ALT"] = $arBrand["IMAGE"]["TITLE"] = $arBrand["NAME"];

			if($arBrand["DETAIL_PICTURE"]){
				$arBrand["IMAGE"]["INFO"] = CFile::GetFileArray($arBrand["DETAIL_PICTURE"]);

				$ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($arBrand["IBLOCK_ID"], $arBrand["ID"]);
				$arBrand["IMAGE"]["IPROPERTY_VALUES"] = $ipropValues->getValues();
				if($arBrand["IMAGE"]["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"])
					$arBrand["IMAGE"]["TITLE"] = $arBrand["IMAGE"]["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"];
				if($arBrand["IMAGE"]["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"])
					$arBrand["IMAGE"]["ALT"] = $arBrand["IMAGE"]["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"];
				if($arBrand["IMAGE"]["INFO"]["DESCRIPTION"])
					$arBrand["IMAGE"]["ALT"] = $arBrand["IMAGE"]["TITLE"] = $arBrand["IMAGE"]["INFO"]["DESCRIPTION"];
			}
		}
	}
}
$arResult["BRAND_ITEM"] = $arBrand;

// sef folder to include files
$sefFolder = rtrim($arParams["SEF_FOLDER"] ?? dirname($_SERVER['REAL_FILE_PATH']), '/');

// include text
ob_start();
$APPLICATION->IncludeFile($sefFolder."/index_garanty.php", array(), array("MODE" => "html", "NAME" => GetMessage('TITLE_INCLUDE')));
$arResult['INCLUDE_CONTENT'] = ob_get_contents();
ob_end_clean();

// price text
ob_start();
$APPLICATION->IncludeFile($sefFolder."/index_price.php", array(), array("MODE" => "html", "NAME" => GetMessage('TITLE_PRICE')));
$arResult['INCLUDE_PRICE'] = ob_get_contents();
ob_end_clean();

// compare licenses text
ob_start();
$APPLICATION->IncludeFile($sefFolder."/index_compare_licenses.php", array(), array("MODE" => "html", "NAME" => GetMessage('TITLE_COMPARE_LICENSES')));
$compare_licenses_text = ob_get_contents();
ob_end_clean();
if ($compare_licenses_text) {
	$arResult["INCLUDE_COMPARE_LICENSES"] = $sefFolder."/index_compare_licenses.php";
}

// buy tab
if($arParams['SHOW_BUY'] === 'Y'){
	$this->SetViewTarget('PRODUCT_BUY_INFO');
	$APPLICATION->IncludeFile($sefFolder."/index_howbuy.php", array(), array("MODE" => "html", "NAME" => GetMessage('T_BUY')));
	$this->EndViewTarget();
}

// payment tab
if($arParams['SHOW_PAYMENT'] === 'Y'){
	$this->SetViewTarget('PRODUCT_PAYMENT_INFO');
	$APPLICATION->IncludeFile($sefFolder."/index_payment.php", array(), array("MODE" => "html", "NAME" => GetMessage('T_PAYMENT')));
	$this->EndViewTarget();
}

// delivery tab
if($arParams['SHOW_DELIVERY'] === 'Y'){
	$this->SetViewTarget('PRODUCT_DELIVERY_INFO');
	$APPLICATION->IncludeFile($sefFolder."/index_delivery.php", array(), array("MODE" => "html", "NAME" => GetMessage('T_DELIVERY')));
	$this->EndViewTarget();
}

// dops tab
if($arParams['SHOW_DOPS'] === 'Y'){
	$dopsFile = TSolution\Functions::getPathFromDirectory([
		'RESULT' => $arResult, 
		'DIRECTORY' => [
			'SEF_FOLDER' => $sefFolder,
			'DIR' => '/dops/',
		]
	]);
	$this->SetViewTarget('PRODUCT_DOPS_INFO');
	$APPLICATION->IncludeFile($dopsFile ? $dopsFile : $sefFolder."/index_dops.php", array(), array("MODE" => "html", "NAME" => GetMessage('T_DOPS')));
	$this->EndViewTarget();
}

// ask question text
ob_start();
$APPLICATION->IncludeFile($sefFolder."/index_ask.php", array(), array("MODE" => "html", "NAME" => GetMessage('TITLE_ASK')));
$arResult['INCLUDE_ASK'] = ob_get_contents();
ob_end_clean();

$arResult['CHARACTERISTICS'] = $arResult['VIDEO'] = $arResult['VIDEO_IFRAME'] = $arResult['POPUP_VIDEO'] = $arResult['TIZERS'] = array();
$arResult['GALLERY_SIZE'] = $arParams['GALLERY_SIZE'];

/* docs property code */
$docsProp = $arParams['DETAIL_DOCS_PROP'] ? $arParams['DETAIL_DOCS_PROP'] : 'DOCUMENTS';

if($arResult['SECTION']){
	$arSectionSelect = array(
		'UF_INCLUDE_TEXT',
	);

	// get display properties
	$arDetailPageShowProps = \Bitrix\Iblock\Model\PropertyFeature::getDetailPageShowProperties(
		$arParams['IBLOCK_ID'],
		array('CODE' => 'Y')
	);
	if($arDetailPageShowProps === null){
		$arDetailPageShowProps = array();
	}
	
	if(
		in_array($docsProp, $arParams['PROPERTY_CODE']) || 
		in_array($docsProp, $arDetailPageShowProps)
	){
		$arSectionSelect[] = 'UF_FILES';
	}
	
	if(
		in_array('LINK_TIZERS', $arParams['PROPERTY_CODE']) || 
		in_array('LINK_TIZERS', $arDetailPageShowProps)
	){
		$arSectionSelect[] = 'UF_SECTION_TIZERS';
	}
	
	if(
		in_array('POPUP_VIDEO', $arParams['PROPERTY_CODE']) || 
		in_array('POPUP_VIDEO', $arDetailPageShowProps)
	){
		$arSectionSelect[] = 'UF_POPUP_VIDEO';
	}
	
	$arInherite = TSolution::getSectionInheritedUF(array(
		'sectionId' => $arResult['IBLOCK_SECTION_ID'],
		'iblockId' => $arParams['IBLOCK_ID'],
		'select' => $arSectionSelect,
		'filter' => array(
			'GLOBAL_ACTIVE' => 'Y', 
		),
	));
	
	if($arInherite['UF_SECTION_TIZERS']){
		$arResult['TIZERS'] = $arInherite['UF_SECTION_TIZERS'];
	}

	if($arInherite['UF_INCLUDE_TEXT']){
		$arResult['INCLUDE_CONTENT'] = $arInherite['UF_INCLUDE_TEXT'];
	}

	if($arInherite['UF_POPUP_VIDEO']){
		$arResult['POPUP_VIDEO'] = $arInherite['UF_POPUP_VIDEO'];
	}

	if($arInherite['UF_FILES']){
		$arResult['DOCUMENTS'] = $arInherite['UF_FILES'];
	}
}

if (array_key_exists('LINK_TIZERS', $arResult['DISPLAY_PROPERTIES']) && $arResult['DISPLAY_PROPERTIES']['LINK_TIZERS']['VALUE']) {
	$arResult['TIZERS'] = $arResult['DISPLAY_PROPERTIES']['LINK_TIZERS']['VALUE'];
}

if(isset($arResult['PROPERTIES']['INCLUDE_TEXT']['~VALUE']['TEXT']) && $arResult['PROPERTIES']['INCLUDE_TEXT']['~VALUE']['TEXT']){
	$arResult['INCLUDE_CONTENT'] = $arResult['PROPERTIES']['INCLUDE_TEXT']['~VALUE']['TEXT'];
}

if(
	array_key_exists($docsProp, $arResult["DISPLAY_PROPERTIES"]) &&
	is_array($arResult["DISPLAY_PROPERTIES"][$docsProp]) &&
	$arResult["DISPLAY_PROPERTIES"][$docsProp]["VALUE"]
){
	foreach($arResult['DISPLAY_PROPERTIES'][$docsProp]['VALUE'] as $key => $value){
		if(!intval($value)){
			unset($arResult['DISPLAY_PROPERTIES'][$docsProp]['VALUE'][$key]);
		}
	}

	if($arResult['DISPLAY_PROPERTIES'][$docsProp]['VALUE']){
		$arResult['DOCUMENTS'] = array_values($arResult['DISPLAY_PROPERTIES'][$docsProp]['VALUE']);
	}
}

if($arResult['DISPLAY_PROPERTIES']){
	$arResult['CHARACTERISTICS'] = TSolution::PrepareItemProps($arResult['DISPLAY_PROPERTIES'], ['EDITION_1C']);

	foreach($arResult['DISPLAY_PROPERTIES'] as $PCODE => $arProp){
		if(
			$arProp["VALUE"] ||
			strlen($arProp["VALUE"])
		){
			if($arProp['USER_TYPE'] === 'video') {
				if(count($arProp['PROPERTY_VALUE_ID']) >= 1) {
					foreach($arProp['VALUE'] as $val){
						if($val['path']){
							$arResult['VIDEO'][] = $val;
						}
					}
				}
				elseif($arProp['VALUE']['path']){
					$arResult['VIDEO'][] = $arProp['VALUE'];
				}
			}
			elseif($arProp['CODE'] === 'VIDEO_IFRAME'){
				$arProp['VIDEO_FRAMES'] = TSolution\Video\Iframe::getVideoBlock($arProp['~VALUE']);

				if ($arProp['VIDEO_FRAMES']) {
					$arResult['VIDEO'] = array_merge($arResult['VIDEO'], $arProp['VIDEO_FRAMES']);
				}
			}
			elseif($arProp['CODE'] === 'POPUP_VIDEO'){
				$arResult['POPUP_VIDEO'] = $arProp["VALUE"];
			}
		}
	}
}