<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
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

$wideStart = '<div class="container">';
$wideEnd = '</div>';
$strTitle = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_TITLE"]
	: $arResult['NAME']
);
$strAlt = (
	isset($arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]) && $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"] != ''
	? $arResult["IPROPERTY_VALUES"]["ELEMENT_DETAIL_PICTURE_FILE_ALT"]
	: $arResult['NAME']
);
//remove next string to hide empty image
if (is_array($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"]) && count($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"]) == 0) $arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"][0] = 0;

// R52::debug(__FILE__);
?><div class="row catalog-detail__holder" itemscope itemtype="http://schema.org/Product"><?
	?><meta itemprop="name" content="<?=$arResult["NAME"]?>"><?
	if (is_array($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"]) && count($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"]) > 0):
		?><div class="col-md-6"><div class="flexslider loading catalog-detail__slider" id="photo<?=$arResult["ID"]?>"><ul class="slides"><?
			$i = 0;
			foreach ($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $key => $PHOTO):
				$arFile = CFile::GetFileArray(intval($PHOTO));
				// R52::debug($arFile);
				if ($PHOTO > 0):
					$file = CFile::ResizeImageGet($PHOTO, array('width'=>1140, 'height'=>1140), BX_RESIZE_IMAGE_PROPORTIONAL, true, array());
					?><li><a itemscope itemtype="http://schema.org/ImageObject" href="<?=$file["src"];?>" title="<?=$arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["DESCRIPTION"][$key]?>" data-fancybox="photo<?//=$arResult["ID"]?>"><?
							?><img itemprop="contentUrl" src="<?=$file["src"];?>" alt="<?=$strAlt?>" /><i class="zoom"><i class="material-icons">search</i></i><?
							?><span class="title-img-r52"><?=$arFile["ORIGINAL_NAME"]?></span><?
							?><meta itemprop="name" content="<?=$strTitle?>"><?
							?><meta itemprop="width" content="<?=$file['width']?> px"><?
							?><meta itemprop="height" content="<?=$file['height']?> px"><?
							if ($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["DESCRIPTION"][$key]!=''):
								?><meta itemprop="description" content="<?=$arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["DESCRIPTION"][$key]?>"><? 
							endif;
						?></a><?
						if ($i == 0)
						{
							?><img alt="<?=$strTitle?>" itemprop="image" src="<?=$file["src"];?>" class="hide"><?
						}
						if ($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["DESCRIPTION"][$key]!=''):
							?><p class="flex-caption"><?=$arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["DESCRIPTION"][$key]?></p><? 
						endif;
					?></li><?
				else:
					?><li><?
						?><img src="<?=SITE_TEMPLATE_PATH.'/images/no_photo.png';?>" alt="" /><?
					?></li><?
				endif;
				$i++;
			endforeach;
		?></ul></div><?
		if (is_array($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"]) && count($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"]) > 1):
			?><div id="photothumbs<?=$arResult["ID"]?>" class="flexslider flexslider-thumbs loading"><?
				?><ul class="slides"><?
				foreach ($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"] as $key => $PHOTO):
					$file = CFile::ResizeImageGet($PHOTO, array('width'=>200, 'height'=>150), BX_RESIZE_IMAGE_EXACT, false, array());
					?><li><img src="<?=$file["src"];?>" alt="" /></li><?
				endforeach;
			?></ul><?
		?></div><?
		endif;
		?></div>
		<script>
			$(document).ready(function() {
				<?if (is_array($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"]) && count($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"]) > 1):?>
				$('#photothumbs<?=$arResult["ID"]?>').flexslider({
					animation: "slide",
					controlNav: false,
					animationLoop: false,
					slideshow: false,
					itemWidth: 100,
					asNavFor: '#photo<?=$arResult["ID"]?>',
					minItems: 2,
					maxItems: 4,
					itemMargin: 10
				});
				<?endif;?>
				$('#photo<?=$arResult["ID"]?>').flexslider({
					animation: "slide",
					controlNav: false,
					smoothHeight: true,
					animationLoop: false,
					slideshow: false,
					<?if (is_array($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"]) && count($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"]) > 1):?>
					sync: "#photothumbs<?=$arResult["ID"]?>",
					<?endif;?>
					start: function(slider){
						$('.flexslider').removeClass('loading');
					}
				});
				$( '[data-fancybox="photo"]').fancybox({
                   caption : function( instance, item ) {
                    return $(this).find('span').html();
                    }
                });
			});
		</script><?
	endif;
	?><div class="col-md-<?echo (is_array($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"]) && count($arResult["DISPLAY_PROPERTIES"]["MORE_PHOTO"]["VALUE"]) > 0)?'6':'12';?>"><?
		if (($arResult["DISPLAY_PROPERTIES"]["AVAILABILITY"]["VALUE"]!='' || $arResult["DISPLAY_PROPERTIES"]["MARKING"]["VALUE"]!='') && SHOW_CATALOG_DETAIL_SHORT):
			?><div class="catalog-detail__short"><?
				if ($arResult["DISPLAY_PROPERTIES"]["AVAILABILITY"]["VALUE"]!=''):
					?><span class="catalog-detail__availability"><?
					if ($arResult["DISPLAY_PROPERTIES"]["AVAILABILITY"]["VALUE_XML_ID"] == 'available'):
						?><i class="catalog-detail__stock catalog-detail__stock_yes material-icons">done</i> <?
					endif;
					if ($arResult["DISPLAY_PROPERTIES"]["AVAILABILITY"]["VALUE_XML_ID"] == 'waiting'):
						?><i class="catalog-detail__stock catalog-detail__stock_waiting material-icons">local_shipping</i> <?
					endif;
					if ($arResult["DISPLAY_PROPERTIES"]["AVAILABILITY"]["VALUE_XML_ID"] == 'order'):
						?><i class="catalog-detail__stock catalog-detail__stock_order material-icons">error_outline</i> <?
					endif;
					?><?=$arResult["DISPLAY_PROPERTIES"]["AVAILABILITY"]["VALUE"]?></span> <?
				endif;
				if ($arResult["DISPLAY_PROPERTIES"]["MARKING"]["VALUE"]!=''):
					?><span class="catalog-detail__marking"><?=GetMessage("MARKING")?>: <?=$arResult["DISPLAY_PROPERTIES"]["MARKING"]["VALUE"]?></span><?
				endif;
			?></div><?
		endif;
		if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arResult["FIELDS"]["PREVIEW_TEXT"]):
			?><p itemprop="description"><?=$arResult["FIELDS"]["PREVIEW_TEXT"];unset($arResult["FIELDS"]["PREVIEW_TEXT"]);?></p><?
		endif;
		?>
		<?if(SHOW_CATALOG_DETAIL_ORDER):?>
		<div class="catalog-detail__order"><?
			if ($arResult["DISPLAY_PROPERTIES"]["PRICE"]["VALUE"]!=''):
			endif;
			?><div class="catalog-detail__price"><?
				if ($arResult["DISPLAY_PROPERTIES"]["PRICE"]["VALUE"]!=''):
					?><div class="catalog-detail__currentprice" itemprop="offers" itemscope itemtype="http://schema.org/Offer"><?
						if (floatval($arResult["DISPLAY_PROPERTIES"]["PRICE"]["VALUE"]) > 0):
							echo number_format($arResult["DISPLAY_PROPERTIES"]["PRICE"]["VALUE"], 0, '.', ' ').' &#8381;';
							?><meta itemprop="price" content="<?=floatval($arResult["DISPLAY_PROPERTIES"]["PRICE"]["VALUE"]); ?>"><meta itemprop="priceCurrency" content="RUB"><?
						else:
							echo $arResult["DISPLAY_PROPERTIES"]["PRICE"]["VALUE"];
						endif;
						if ($arResult["DISPLAY_PROPERTIES"]["OLD_PRICE"]["VALUE"]!=''):
							?><span class="catalog-detail__oldprice"><?
								if (floatval($arResult["DISPLAY_PROPERTIES"]["OLD_PRICE"]["VALUE"]) > 0):
									echo number_format($arResult["DISPLAY_PROPERTIES"]["OLD_PRICE"]["VALUE"], 0, '.', ' ').' &#8381;';
								else:
									echo $arResult["DISPLAY_PROPERTIES"]["OLD_PRICE"]["VALUE"];
								endif;
							?></span><?
						endif;
					?></div><?
				endif;
				if ($arResult["DISPLAY_PROPERTIES"]["OLD_PRICE"]["VALUE"]!='' && floatval($arResult["DISPLAY_PROPERTIES"]["PRICE"]["VALUE"]) > 0 && floatval($arResult["DISPLAY_PROPERTIES"]["OLD_PRICE"]["VALUE"]) > 0):
					?><div class="catalog-detail__discount"><?
						?><span class="catalog-detail__economy"><?=GetMessage("ECONOMY")?>: <?echo number_format(floatval($arResult["DISPLAY_PROPERTIES"]["OLD_PRICE"]["VALUE"]) - floatval($arResult["DISPLAY_PROPERTIES"]["PRICE"]["VALUE"]), 0, '.', ' ').' &#8381;';?></span><?
					?></div><?
				endif;
				?><div class="form-inline catalog-detail__actions"><?
					?><div class="form-group catalog-detail__basket" data-item="<?=$arResult["ID"]?>"><?
						?><div class="form-group catalog-detail__tocart"><?
							?><input type="number" class="form-control form-control_short" value="1" min="1" data-quantity="1"><?
							?><button class="btn btn-primary catalog-detail__tocart" data-basket-add="<?=$arResult["ID"]?>"><i class="material-icons md-18">shopping_cart</i> <?=GetMessage("TOCART")?></button><?
						?></div><?
						?><a href="<?=SITE_DIR?>cart/" class="btn btn-primary catalog-detail__incart"><i class="material-icons md-18">done</i> <?=GetMessage("CARTCOMPLETE")?></a><?
					?></div><div class="form-group"><?$APPLICATION->IncludeComponent(
	"inteo:form.show", 
	".default", 
	array(
		"ADD_EMAIL" => "",
		"CACHE_TIME" => "36000000",
		"CACHE_TYPE" => "A",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"ERROR_MODE" => "COMPACT",
		"FORM_TEMPLATE" => "1",
		"IBLOCK_ID" => \Inteo\Corporation\Subcache::$arIBlock["inteo_corp_form_oneclick"],
		"IBLOCK_TYPE" => "inteo_corp_forms",
		"PHONE_MASK" => "RU",
		"POPUP" => "Y",
		"POPUP_BUTTON_CSS" => "btn btn-default",
		"POPUP_SIZE" => "MEDIUM",
		"POPUP_TITLE" => '<i class="material-icons md-18">call</i> '.GetMessage("ONECLICK"),
		"SEND_BUTTON_CLASS" => "btn btn-primary",
		"SEND_BUTTON_TEXT" => GetMessage("ONECLICK_BUTTON"),
		"SHOW_REQUIRED" => "Y",
		"SHOW_TITLE" => "Y",
		"SUCCESS_MESSAGE" => GetMessage("ONECLICK_SUCCESS"),
		"USETYPE" => "FULL",
		"USE_CAPTCHA" => "N",
		"COMPONENT_TEMPLATE" => ".default",
		"CUSTOM_INPUTFILE" => "Y",
		"SHOW_AGREEMENT" => "Y",
		"CUSTOM_BOXES" => "Y",
		"SET_FIELD" => array(
			0 => "PRODUCT:".base64_encode($arResult["NAME"]),
			1 => "",
		)
	),
	false
);?></div></div><?
			?></div><?
		?></div>
		<?endif;?>
		<?
	?></div><?
?></div><?

//Collect tabs
$arTabs = array();

//Tab - Description
if ($arResult["NAV_RESULT"] || strlen($arResult["DETAIL_TEXT"])>0):
	$arTabContent = array();
	$arTabContent["NAME"] = GetMessage("TAB_DESCRIPTION");
	$arTabContent["TAG"] = 'description';
	ob_start();
		if($arResult["NAV_RESULT"]):
			if($arParams["DISPLAY_TOP_PAGER"]):?><?=$arResult["NAV_STRING"]?><br /><?endif;
			echo $arResult["NAV_TEXT"];
			if($arParams["DISPLAY_BOTTOM_PAGER"]):?><br /><?=$arResult["NAV_STRING"]?><?endif;
		elseif(strlen($arResult["DETAIL_TEXT"])>0):
			echo $arResult["DETAIL_TEXT"];
		endif;
	$arTabContent["CONTENT"] = ob_get_clean();
	$arTabs[] = $arTabContent;
endif;
// R52::debug($arResult['PROPERTIES']);
//Tab - Characteristics
if (!empty($arResult['DISPLAY_PROPERTIES']['CHARS']) || !empty($arResult['PROPERTIES']["CHARS_HTML"]["VALUE"]["TEXT"])):
	$arTabContent = array();
	$arTabContent["NAME"] = GetMessage("TAB_CHARS");
	$arTabContent["TAG"] = 'characteristics';
	//2 columns
	/*
	$charCount = count($arResult["DISPLAY_PROPERTIES"]["CHARS"]["VALUE"]);
	if ($arResult["DISPLAY_PROPERTIES"]["PRODUCER"]["VALUE"]!='') $charCount++;
	$charCounter = 1;
	ob_start();
		if ($charCount > 1) echo '<div class="wide-columns clearfix"><div class="wide-columns__col">';
			?><ul class="headline"><?
				if ($arResult["DISPLAY_PROPERTIES"]["PRODUCER"]["VALUE"]!=''):
					?><li><span><?=$arResult["DISPLAY_PROPERTIES"]["PRODUCER"]["NAME"]?></span><span><?=$arResult["DISPLAY_PROPERTIES"]["PRODUCER"]["VALUE"]?></span></li><?
					$charCounter++;
				endif;
				foreach($arResult["DISPLAY_PROPERTIES"]["CHARS"]["VALUE"] as $k=>$value):
					if (strpos($arResult["DISPLAY_PROPERTIES"]["CHARS"]["DESCRIPTION"][$k],'http') === 0) 
						$arResult["DISPLAY_PROPERTIES"]["CHARS"]["DESCRIPTION"][$k] = '<!--noindex--><a href="'.$arResult["DISPLAY_PROPERTIES"]["CHARS"]["DESCRIPTION"][$k].'" rel="external nofollow" target="_blank">'.$arResult["DISPLAY_PROPERTIES"]["CHARS"]["DESCRIPTION"][$k].'</a><!--/noindex-->';
					?><li><span><?=$value?></span><span><?=$arResult["DISPLAY_PROPERTIES"]["CHARS"]["DESCRIPTION"][$k]?></span></li><?
						if ($charCount > 1 && $charCounter == ceil($charCount/2)) echo '</ul></div><div class="wide-columns__col"><ul class="headline">';
					$charCounter++;
				endforeach;
			?></ul><?
		if ($charCount > 1) echo '</div></div>';
	$arTabContent["CONTENT"] = ob_get_clean();
	*/
	ob_start();
	if(empty($arResult['PROPERTIES']["CHARS_HTML"]["VALUE"]["TEXT"])){
		?><ul class="headline"><?
			if ($arResult["DISPLAY_PROPERTIES"]["PRODUCER"]["VALUE"]!=''):
				?><li><span><?=$arResult["DISPLAY_PROPERTIES"]["PRODUCER"]["NAME"]?></span><span><?=$arResult["DISPLAY_PROPERTIES"]["PRODUCER"]["VALUE"]?></span></li><?
			endif;
			foreach($arResult["DISPLAY_PROPERTIES"]["CHARS"]["VALUE"] as $k=>$value):
				if (strpos($arResult["DISPLAY_PROPERTIES"]["CHARS"]["DESCRIPTION"][$k],'http') === 0) 
					$arResult["DISPLAY_PROPERTIES"]["CHARS"]["DESCRIPTION"][$k] = '<!--noindex--><a href="'.$arResult["DISPLAY_PROPERTIES"]["CHARS"]["DESCRIPTION"][$k].'" rel="external nofollow" target="_blank">'.$arResult["DISPLAY_PROPERTIES"]["CHARS"]["DESCRIPTION"][$k].'</a><!--/noindex-->';
				?><li><span><?=$value?></span><span><?=$arResult["DISPLAY_PROPERTIES"]["CHARS"]["DESCRIPTION"][$k]?></span></li><?
			endforeach;
		?></ul><?
	}?>
	<?if(!empty($arResult['PROPERTIES']["CHARS_HTML"]["VALUE"]["TEXT"])):?>
	<?=$arResult['PROPERTIES']["CHARS_HTML"]["~VALUE"]["TEXT"]?>
	<?endif;?>
	<?
	$arTabContent["CONTENT"] = ob_get_clean();
	$arTabs[] = $arTabContent;
endif;

//Tab - Contents of delivery
if (!empty($arResult["PROPERTIES"]["CONTENTS_DELIVERY"]["VALUE"]["TEXT"])):
	$arTabContent = array();
	$arTabContent["NAME"] = GetMessage("TAB_CONTENTS_DELIVERY");
	$arTabContent["TAG"] = 'contents_delivery';
	ob_start();
	echo $arResult["PROPERTIES"]["CONTENTS_DELIVERY"]["~VALUE"]["TEXT"];
	$arTabContent["CONTENT"] = ob_get_clean();
	$arTabs[] = $arTabContent;
endif;

//Tab - Documents
if (is_array($arResult["DISPLAY_PROPERTIES"]["DOCUMENTS"]["VALUE"]) && count($arResult["DISPLAY_PROPERTIES"]["DOCUMENTS"]["VALUE"]) > 0):
	$arTabContent = array();
	$arTabContent["TAG"] = 'documents';
	$arTabContent["NAME"] = GetMessage("TAB_DOCUMENTS");
	ob_start();
		if (empty($arResult["DISPLAY_PROPERTIES"]["DOCUMENTS"]["FILE_VALUE"]["ID"])):
			foreach ($arResult["DISPLAY_PROPERTIES"]["DOCUMENTS"]["FILE_VALUE"] as $key => $DOCUMENT):
				?><div class="file file_<?=strtolower(end(explode(".", $DOCUMENT['SRC'])));?>"><?
					?><a href="<?=$DOCUMENT['SRC']?>" target="_blank"><? echo (($DOCUMENT['DESCRIPTION']!='')?$DOCUMENT['DESCRIPTION']:$DOCUMENT['ORIGINAL_NAME']);?></a> <?
					?><span class="file__size">(<?=CFile::FormatSize($DOCUMENT['FILE_SIZE'],1);?>)</span><?
				?></div><?
			endforeach;
		else:
			$DOCUMENT = $arResult["DISPLAY_PROPERTIES"]["DOCUMENTS"]["FILE_VALUE"];
			?><div class="file file_<?=strtolower(end(explode(".", $DOCUMENT['SRC'])));?>"><?
				?><a href="<?=$DOCUMENT['SRC']?>" target="_blank"><? echo (($DOCUMENT['DESCRIPTION']!='')?$DOCUMENT['DESCRIPTION']:$DOCUMENT['ORIGINAL_NAME']);?></a> <?
				?><span class="file__size">(<?=CFile::FormatSize($DOCUMENT['FILE_SIZE'],1);?>)</span><?
			?></div><?
		endif;
	$arTabContent["CONTENT"] = ob_get_clean();
	$arTabs[] = $arTabContent;
endif;

if (count($arTabs) > 0):
	echo $wideEnd;
	?><div class="catalog-detail__info"><div class="container"><?
		?><ul class="nav nav-tabs" role="tablist"><?
			$tabCounter = 1;
			foreach ($arTabs as $arTab):
				?><li role="presentation"<?if ($tabCounter == 1) echo ' class="active"';?>><a href="#<?=$arTab["TAG"]?>" aria-controls="<?=$arTab["TAG"]?>" role="tab" data-toggle="tab"><?=$arTab["NAME"]?></a></li><?
				$tabCounter++;
			endforeach;
		?></ul><?
		?><div class="tab-content"><?
			$tabCounter = 1;
			foreach ($arTabs as $arTab):
				?><div role="tabpanel" class="tab-pane<?if ($tabCounter == 1) echo ' active';?>" id="<?=$arTab["TAG"]?>"><?
					echo $arTab["CONTENT"];
				?></div><?
				$tabCounter++;
			endforeach;
		?></div><?
	?></div></div><?
	echo $wideStart;
endif;

//Popup message after adding item to cart
?><script type="text/x-tmpl" id="tmpl-success"><?
	?><div class="message"><?
		?><h2><?=GetMessage("SUCCESS__TITLE")?></h2><?
		?><div class="cart-success"><?
			?><p class="cart-success__image"><img src="{%=o.IMAGE%}" alt=""></p><?
			?><p class="cart-success__title">{%=o.NAME%}</p><?
			?><div class="cart-success__buttons"><?
				?><a href="<?=$arParams['PATH_TO_BASKET']?>" class="btn btn-primary"><?=GetMessage("WINDOW__TOCART")?></a><?
				?><a href="javascript:;" class="btn btn-default fancy-close"><?=GetMessage("WINDOW__CLOSE")?></a><?
			?></div><?
		?></div><?
	?></div><?
?></script>