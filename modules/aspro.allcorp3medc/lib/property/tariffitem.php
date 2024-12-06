<?
namespace Aspro\Allcorp3Medc\Property;

use Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader,
	Bitrix\Main\Web\Json,
	CAllcorp3Medc as Solution,
	CAllcorp3MedcCache as SolutionCache,
	Aspro\Allcorp3Medc\Functions\Extensions;

Loc::loadMessages(__FILE__);

class TariffItem {
	const TAB_FILTER_INDEX = 999;

	public static function OnIBlockPropertyBuildList(){
		return array(
			'PROPERTY_TYPE' => 'S',
			'USER_TYPE' => 'SAsproAllcorp3MedcTariffItem',
			'DESCRIPTION' => Loc::getMessage('ALLCORP3MEDC_TARIFF_ITEM_PROP_TITLE'),
            'PrepareSettings' => array(__CLASS__, 'PrepareSettings'),
			'GetSettingsHTML' => array(__CLASS__, 'GetSettingsHTML'),
            'GetAdminListViewHTML' => array(__CLASS__, 'GetAdminListViewHTML'),
			'GetPropertyFieldHtml' => array(__CLASS__, 'GetPropertyFieldHtml'),
			'ConvertToDB' => array(__CLASS__, 'ConvertToDB'),
		);
	}

    public static function PrepareSettings($arFields){
		$arFields['FILTRABLE'] = $arFields['SMART_FILTER'] = $arFields['SEARCHABLE'] = 'N';
		$arFields['MULTIPLE_CNT'] = 1;

        return $arFields;
	}

    public static function GetSettingsHTML($arProperty, $strHTMLControlName, &$arPropertyFields){
		$arPropertyFields = array(
            'HIDE' => array(
            	'SMART_FILTER',
            	'FILTRABLE',
            	//'DEFAULT_VALUE',
            	'SEARCHABLE',
            	'COL_COUNT',
            	'FILTER_HINT',
            ),
            'SET' => array(
            	'SMART_FILTER' => 'N',
            	'FILTRABLE' => 'N',
            	'SEARCHABLE' => 'N',
            	'MULTIPLE_CNT' => '1',
            ),
        );

		return '';
	}

	public static function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName){
		static $cache;

		$bAdminList = $strHTMLControlName['MODE'] === 'iblock_element_admin';
		$bMultiple = $arProperty['MULTIPLE'] === 'Y';
		$bWithDescription = $arProperty['WITH_DESCRIPTION'] === 'Y';

		if(!isset($cache)){
			$cache = array();

			Loader::includeModule('fileman');

			$moduleID = self::getModuleId();

			$GLOBALS['APPLICATION']->AddHeadScript('/bitrix/js/'.$moduleID.'/sort/Sortable.js');

			Extensions::register();
			Extensions::init('tariffitem');
		}

		$value = self::ConvertFromDB($arProperty, $value);

		ob_start();

		if($value){
			$title = is_array($value['VALUE']) ? $value['VALUE']['TITLE'] : '';
			$titleName = $strHTMLControlName['VALUE'].'[TITLE]';
			$filterPrice = is_array($value['VALUE']) ? $value['VALUE']['FILTER_PRICE'] : '';
			$filterPriceName = $strHTMLControlName['VALUE'].'[FILTER_PRICE]';
			$price = is_array($value['VALUE']) ? $value['VALUE']['PRICE'] : '';
			$priceName = $strHTMLControlName['VALUE'].'[PRICE]';
			$priceDiscount = is_array($value['VALUE']) ? $value['VALUE']['PRICE_DISCOUNT'] : '';
			$priceDiscountName = $strHTMLControlName['VALUE'].'[PRICE_DISCOUNT]';
			$economy = is_array($value['VALUE']) ? $value['VALUE']['ECONOMY'] : '';
			$economyName = $strHTMLControlName['VALUE'].'[ECONOMY]';
			$description = $value['DESCRIPTION'];
			$descriptionName = str_replace('VALUE', 'DESCRIPTION', $strHTMLControlName['VALUE']);
		}
		?>
		<div class="aspro_property_tariffitem_item wide">
			<div class="wrapper">
				<div class="inner_wrapper">
					<div class="inner wide">
						<div class="value_wrapper"><?=htmlspecialcharsbx($title)?></div>
					</div>
					<div class="inner">
						<div class="value_wrapper"><?=htmlspecialcharsbx($price)?></div>
					</div>
					<div class="inner">
						<div class="value_wrapper"><?=htmlspecialcharsbx($filterPrice)?></div>
					</div>
					<div class="inner">
						<div class="value_wrapper"><?=htmlspecialcharsbx($priceDiscount)?></div>
					</div>
					<div class="inner">
						<div class="value_wrapper"><?=htmlspecialcharsbx($economy)?></div>
					</div>
					<?if($bWithDescription):?>
						<div class="inner wide">
							<div class="value_wrapper"><?=$description?></div>
						</div>
					<?endif;?>
				</div>
			</div>
		</div>
		<?

		return ob_get_clean();
	}

	public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName){
		static $cache;

		$bAdminList = $strHTMLControlName['MODE'] === 'iblock_element_admin';
		$bEditProperty = $strHTMLControlName['MODE'] === 'EDIT_FORM';
		$bDetailPage = $strHTMLControlName['MODE'] === 'FORM_FILL';
		$bMultiple = $arProperty['MULTIPLE'] === 'Y';
		$bWithDescription = $arProperty['WITH_DESCRIPTION'] === 'Y';

		if(!isset($cache)){
			$cache = array();

			Loader::includeModule('fileman');

			$moduleID = self::getModuleId();

			$GLOBALS['APPLICATION']->AddHeadScript('/bitrix/js/'.$moduleID.'/sort/Sortable.js');

			Extensions::register();
			Extensions::init('tariffitem');
		}

		$value = self::ConvertFromDB($arProperty, $value);

		if($bAdminList){
			preg_match('/FIELDS\[([\D]+)(\d+)\]\[PROPERTY_'.$arProperty['ID'].'\]\[([^\]]*)\]\[VALUE\]/', $strHTMLControlName['VALUE'], $arMatch);
			$elementType = $arMatch[1];
			$elementId = $arMatch[2];
			$valueId = $arMatch[3];
			$tableId = 'tb'.md5($elementType.$elementId.':'.$arProperty['ID']);

			if(!$valueId){
				return '';
			}
		}
		else{
			preg_match('/PROP\['.$arProperty['ID'].'\]\[([^\]]*)\]\[VALUE\]/', $strHTMLControlName['VALUE'], $arMatch);
			$valueId = $arMatch[1];
			if($bEditProperty){
				$tableId = 'form_content';
			}
			else{
				$tableId = 'tb'.md5(htmlspecialcharsbx('PROP['.$arProperty['ID'].']'));
			}
		}

		if($value){
			$title = is_array($value['VALUE']) ? $value['VALUE']['TITLE'] : '';
			$titleName = $strHTMLControlName['VALUE'].'[TITLE]';
			$filterPrice = is_array($value['VALUE']) ? $value['VALUE']['FILTER_PRICE'] : '';
			$filterPriceName = $strHTMLControlName['VALUE'].'[FILTER_PRICE]';
			$price = is_array($value['VALUE']) ? $value['VALUE']['PRICE'] : '';
			$priceName = $strHTMLControlName['VALUE'].'[PRICE]';
			$priceDiscount = is_array($value['VALUE']) ? $value['VALUE']['PRICE_DISCOUNT'] : '';
			$priceDiscountName = $strHTMLControlName['VALUE'].'[PRICE_DISCOUNT]';
			$economy = is_array($value['VALUE']) ? $value['VALUE']['ECONOMY'] : '';
			$economyName = $strHTMLControlName['VALUE'].'[ECONOMY]';
			$description = $value['DESCRIPTION'];
			$descriptionName = str_replace('VALUE', 'DESCRIPTION', $strHTMLControlName['VALUE']);
		}

		ob_start();

		$bFirstTime = $bAdminList ? !in_array($elementId, $cache) : !in_array($arProperty['ID'], $cache);
		?>
		<?if($bFirstTime):?>
			<?
			if($bAdminList){
				$cache[] = $elementId;
			}
			else{
				$cache[] = $arProperty['ID'];
			}

			$GLOBALS['APPLICATION']->AddHeadString('<script>new JTariffItem(\''.$tableId.'\');</script>');
			?>
			<?if($bEditProperty):?>
				<table><tbody><tr><td>
			<?endif;?>

			<div class="aspro_property_tariffitem_item_note">
				<?=BeginNote('align="left"');?>
				<?=GetMessage('ALLCORP3_TARIFF_ITEM_NOTE')?>
				<?=EndNote();?>
			</div>
			</td></tr><tr><td>
		<?endif;?>
		<div class="aspro_property_tariffitem_item<?=($bAdminList ? ' aspro_property_tariffitem_item--admlistedit' : '')?>">
			<div class="wrapper">
				<div class="inner_wrapper">
					<div class="inner wide">
						<div class="value_wrapper">
							<input type="text" name="<?=$titleName?>" value="<?=htmlspecialcharsbx($title)?>" maxlength="255" placeholder="<?=htmlspecialcharsbx(Loc::getMessage('ALLCORP3_TARIFF_ITEM_TITLE_TITLE'))?>" title="<?=htmlspecialcharsbx(Loc::getMessage('ALLCORP3_TARIFF_ITEM_TITLE_TITLE'))?>" autocomplete="off" />
						</div>
					</div><br />
					<div class="inner">
						<div class="value_wrapper">
							<input type="text" name="<?=$priceName?>" value="<?=htmlspecialcharsbx($price)?>" maxlength="100" placeholder="<?=htmlspecialcharsbx(Loc::getMessage('ALLCORP3_TARIFF_ITEM_PRICE_TITLE'))?>" title="<?=htmlspecialcharsbx(Loc::getMessage('ALLCORP3_TARIFF_ITEM_PRICE_TITLE'))?>" autocomplete="off" />
						</div>
					</div>
					<div class="inner">
						<div class="value_wrapper">
							<input type="text" name="<?=$filterPriceName?>" value="<?=htmlspecialcharsbx($filterPrice)?>" maxlength="100" placeholder="<?=htmlspecialcharsbx(Loc::getMessage('ALLCORP3_TARIFF_ITEM_PRICE_FILTER_TITLE'))?>" title="<?=htmlspecialcharsbx(Loc::getMessage('ALLCORP3_TARIFF_ITEM_PRICE_FILTER_TITLE'))?>" autocomplete="off" />
						</div>
					</div><br />
					<div class="inner">
						<div class="value_wrapper">
							<input type="text" name="<?=$priceDiscountName?>" value="<?=htmlspecialcharsbx($priceDiscount)?>" maxlength="100" placeholder="<?=htmlspecialcharsbx(Loc::getMessage('ALLCORP3_TARIFF_ITEM_PRICE_DISCOUNT_TITLE'))?>" title="<?=htmlspecialcharsbx(Loc::getMessage('ALLCORP3_TARIFF_ITEM_PRICE_DISCOUNT_TITLE'))?>" autocomplete="off" />
						</div>
					</div>
					<div class="inner">
						<div class="value_wrapper">
							<input type="text" name="<?=$economyName?>" value="<?=htmlspecialcharsbx($economy)?>" maxlength="100" placeholder="<?=htmlspecialcharsbx(Loc::getMessage('ALLCORP3_TARIFF_ITEM_ECONOMY_TITLE'))?>" title="<?=htmlspecialcharsbx(Loc::getMessage('ALLCORP3_TARIFF_ITEM_ECONOMY_TITLE'))?>" autocomplete="off" />
						</div>
					</div><br />
					<div class="inner wide">
						<div class="value_wrapper">
							<?if($bWithDescription):?>
								<input type="text" name="<?=$descriptionName?>" value="<?=htmlspecialcharsbx($description)?>" maxlength="255" placeholder="<?=Loc::getMessage('ALLCORP3_TARIFF_ITEM_DESCRIPTION_TITLE')?>" title="<?=Loc::getMessage('ALLCORP3_TARIFF_ITEM_DESCRIPTION_TITLE')?>" <?=($bEditProperty ? 'readonly disabled' : '')?> autocomplete="off" />
							<?endif;?>
						</div>
					</div>
					<?if(!$bEditProperty):?>
						<div class="remove" title="<?=Loc::getMessage('ALLCORP3_TARIFF_ITEM_DELETE_TITLE')?>"></div>
						<div class="drag" title="<?=Loc::getMessage('ALLCORP3_TARIFF_ITEM_DRAG_TITLE')?>"></div>
					<?endif;?>
				</div>
			</div>
		</div>

		<?if($bEditProperty):?>
			</td></tr></tbody></table>
		<?endif;?>
		<?

		return ob_get_clean();
	}

	public static function ConvertToDB($arProperty, $value){
		if(
			!is_array($value['VALUE']) ||
			!strlen($value['VALUE']['TITLE'])
		){
			return array(
				'VALUE' => '',
				'DESCRIPTION' => '',
			);
		}

		$value['VALUE'] = array(
			'TITLE' => $itemTitle = strlen($value['VALUE']['TITLE']) ? $value['VALUE']['TITLE'] : '',
			'KEY' => static::translit($itemTitle),
			'FILTER_PRICE' => strlen($value['VALUE']['FILTER_PRICE']) ? $value['VALUE']['FILTER_PRICE'] : '',
			'PRICE' => strlen($value['VALUE']['PRICE']) ? $value['VALUE']['PRICE'] : '',
			'PRICE_DISCOUNT' => strlen($value['VALUE']['PRICE_DISCOUNT']) ? $value['VALUE']['PRICE_DISCOUNT'] : '',
			'ECONOMY' => strlen($value['VALUE']['ECONOMY']) ? $value['VALUE']['ECONOMY'] : '',
		);

		$value['VALUE'] = Json::encode($value['VALUE']);

		return $value;
	}

	public static function ConvertFromDB($arProperty, $value){
		if(!is_array($value['VALUE'])){
			$value['VALUE'] = strlen($value['VALUE']) ? $value['VALUE'] : '[]';

			try {
				$value['VALUE'] = Json::decode($value['VALUE']);
			}
			catch(\Exception $e) {
				$value['VALUE'] = [];
			}
		}

		if(
			!$value['VALUE'] ||
			!is_array($value['VALUE']) ||
			!strlen($value['VALUE']['TITLE'])
		){
			$value['VALUE'] = array(
				'TITLE' => '',
				'KEY' => '',
				'FILTER_PRICE' => '',
				'PRICE' => '',
				'PRICE_DISCOUNT' => '',
				'ECONOMY' => '',
			);
		}

		return $value;
	}

	public static function getDefaultValue($arProperty){
		if(!is_array($arProperty['DEFAULT_VALUE'])){
			$defaultValue = strlen($arProperty['DEFAULT_VALUE']) ? $arProperty['DEFAULT_VALUE'] : '[]';

			try {
				$arProperty['DEFAULT_VALUE'] = Json::decode($defaultValue);
			}
			catch(\Exception $e) {
				$arProperty['DEFAULT_VALUE'] = [];
			}
		}

		if(
			!$arProperty['DEFAULT_VALUE'] ||
			!is_array($arProperty['DEFAULT_VALUE']) ||
			!strlen($arProperty['DEFAULT_VALUE']['TITLE'])
		){
			$arProperty['DEFAULT_VALUE'] = array(
				'TITLE' => '',
				'KEY' => '',
				'FILTER_PRICE' => '',
				'PRICE' => '',
				'PRICE_DISCOUNT' => '',
				'ECONOMY' => '',
			);
		}

		return $arProperty['DEFAULT_VALUE'];
	}

	public static function getModuleId(){
		return Solution::moduleID;
	}

	public static function decodePropertyValue($arValue){
		$arValue = is_array($arValue) ? $arValue : (array)$arValue;

		foreach ($arValue as $i => $value) {
			if (!is_array($value)) {
				$value = htmlspecialchars_decode($value);
				$value = strlen($value) ? $value : '[]';

				try {
					$value = Json::decode($value);
				}
				catch(\Exception $e) {
					$value = [];
				}

				if(
					!$value ||
					!is_array($value) ||
					!strlen($value['TITLE'])
				) {
					unset($arValue[$i]);
				}
				else {
					$arValue[$i] = $value;
				}
			}
		}

		return $arValue;
	}

	public static function collectPricesPropsKeys(int $iblockId) {
		$arPropKeys = [];

		if ($iblockId > 0) {
			Loader::includeModule('iblock');

			$arFilter = [
				'ACTIVE' => 'Y',
				'IBLOCK_ID' => $iblockId,
				'CODE' => 'TARIF_PRICE_%',
				'PROPERTY_TYPE' => 'S',
			];

			$obCache = new \CPHPCache();
			$cacheTime = 3600000;
			$cacheTag = SolutionCache::GetIBlockCacheTag($iblockId);
			$cachePath = '/CAllcorp3MedcCache/iblock/'.__FUNCTION__.'/'.$cacheTag.'/';
			$cacheId = __FUNCTION__.'_'.$cacheTag.md5(serialize((array)$arFilter));

			if (
				SolutionCache::$bEnabled &&
				$obCache->InitCache($cacheTime, $cacheId, $cachePath)
			) {
				$res = $obCache->GetVars();
				$arPropKeys = $res['arRes'];
			}
			else {
				$dbRes = \CIBlockProperty::GetList(
					['sort' => 'asc'],
					$arFilter
				);
				while ($arProperty = $dbRes->Fetch()) {
					$arPropCode = explode('_', $arProperty['CODE']);
					$propKey = $arPropCode[count($arPropCode) - 1];
					if (is_numeric($propKey) || $propKey === 'DEFAULT') {
						$arPropKeys[$propKey] = $arProperty['NAME'];
					}
				}

				if (
					SolutionCache::$bEnabled &&
					$cacheTime > 0
				) {
					$obCache->StartDataCache($cacheTime, $cacheId, $cachePath);
	
					if (strlen($cacheTag)) {
						global $CACHE_MANAGER;
						$CACHE_MANAGER->StartTagCache($cachePath);
						$CACHE_MANAGER->RegisterTag($cacheTag);
						$CACHE_MANAGER->EndTagCache();
					}
	
					$obCache->EndDataCache(['arRes' => $arPropKeys]);
				}
			}
		}

		return $arPropKeys;
	}

	public static function collectTabs(int $iblockId, string $filterName) {
		$arTabs = [
			'PROPS_TABS' => [],
			'ITEMS_TABS' => [],
		];

		if ($iblockId > 0) {
			Loader::includeModule('iblock');

			$arFilter = strlen($filterName) && is_array($GLOBALS[$filterName]) ? $GLOBALS[$filterName] : [];

			if ($arFilter) {
				// unset tabs filter
				foreach (static::collectPricesPropsKeys($iblockId) as $propKey => $propTitle) {
					unset($arFilter['!PROPERTY_TARIF_PRICE_'.$propKey]);
				}

				unset($arFilter[static::TAB_FILTER_INDEX]);
			}

			$arTmpFilter = array(
				'LOGIC' => 'OR',
				array(
					'!PROPERTY_TARIF_ITEM' => false
				),
			);
			foreach (static::collectPricesPropsKeys($iblockId) as $propKey => $propTitle) {
				$arTmpFilter[] = [
					'!PROPERTY_TARIF_PRICE_'.$propKey => false,
				];
			}

			$arFilter = array_merge(
				array(
					'IBLOCK_ID' => $iblockId,
					$arTmpFilter
				),
				$arFilter
			);

			$obCache = new \CPHPCache();
			$cacheTime = 3600000;
			$cacheTag = SolutionCache::GetIBlockCacheTag($iblockId);
			$cachePath = '/CAllcorp3MedcCache/iblock/'.__FUNCTION__.'/'.$cacheTag.'/';
			$cacheId = __FUNCTION__.'_'.$cacheTag.md5(serialize((array)$arFilter));

			if (
				SolutionCache::$bEnabled &&
				$obCache->InitCache($cacheTime, $cacheId, $cachePath)
			) {
				$res = $obCache->GetVars();
				$arTabs = $res['arRes'];
			}
			else {
				\CIBlockElement::GetPropertyValuesArray(
					$arAllItems,
					$iblockId,
					$arFilter,
					array(
						'CODE' => array(
							'TARIF_PRICE_1',
							'TARIF_PRICE_2',
							'TARIF_PRICE_3',
							'TARIF_PRICE_DEFAULT',
							'TARIF_ITEM',
						)
					),
					array(
						'PROPERTY_FIELDS' => array(
							'ID',
							'SORT',
							'NAME'
						),
					)
				);

				if ($arAllItems) {
					$arPropsSort = [];

					foreach ($arAllItems as $arItemPrices) {
						if ($arItemPrices['TARIF_ITEM']) {
							$arItemPrices['TARIF_ITEM']['VALUE'] = static::decodePropertyValue($arItemPrices['TARIF_ITEM']['VALUE']);
						}
			
						foreach ($arItemPrices as $propCode => $arPrice) {
							if ($propCode === 'TARIF_ITEM') {
								foreach ($arItemPrices['TARIF_ITEM']['VALUE'] as $i => $value) {
									$itemTitle = $value['TITLE'];
									$price_key = $value['KEY'] ?? static::translit($itemTitle);
									$arTabs['ITEMS_TABS'][$price_key] = $itemTitle;
								}
							}
							else {
								if (
									!$arItemPrices['TARIF_ITEM'] ||
									!$arItemPrices['TARIF_ITEM']['VALUE']
								) {
									$arPropCode = explode('_', $propCode);
									$propKey = $arPropCode[count($arPropCode) - 1];
					
									// price title
									$priceTitle = str_replace(Loc::getMessage('ALLCORP3_TARIFF_ITEM_REPLACE_PRICE_NAME'), '', $arPrice['NAME']);
									$priceTitle = str_replace(
										array(
											Loc::getMessage('ALLCORP3_TARIFF_ITEM_REPLACE_MONTH6'),
											Loc::getMessage('ALLCORP3_TARIFF_ITEM_REPLACE_MONTH2'),
											Loc::getMessage('ALLCORP3_TARIFF_ITEM_REPLACE_MONTH1'),
										),
										Loc::getMessage('ALLCORP3_TARIFF_ITEM_REPLACE_MONTH_SHORT'),
										$priceTitle
									);
									$priceTitle = str_replace(Loc::getMessage('ALLCORP3_TARIFF_ITEM_REPLACE_ONE_YEAR'), Loc::getMessage('ALLCORP3_TARIFF_ITEM_REPLACE_YEAR'), $priceTitle);

									$arPropsSort[$propKey] = $arPrice['SORT'];
					
									$arTabs['PROPS_TABS'][$propKey] = $priceTitle;
								}
							}
						}
					}

					if ($arTabs['PROPS_TABS']) {
						asort($arPropsSort);

						uksort($arTabs['PROPS_TABS'], function($a, $b) use ($arPropsSort) {
							return $arPropsSort[$a] <=> $arPropsSort[$b];
						});
					}
				}

				if (
					SolutionCache::$bEnabled &&
					$cacheTime > 0
				) {
					$obCache->StartDataCache($cacheTime, $cacheId, $cachePath);
	
					if (strlen($cacheTag)) {
						global $CACHE_MANAGER;
						$CACHE_MANAGER->StartTagCache($cachePath);
						$CACHE_MANAGER->RegisterTag($cacheTag);
						$CACHE_MANAGER->EndTagCache();
					}
	
					$obCache->EndDataCache(['arRes' => $arTabs]);
				}
			}
		}

		return $arTabs;
	}

	public static function mkTabFilter(int $iblockId, string $filterName) {
		$priceKey = '';

		if (
			$iblockId > 0 &&
			strlen($filterName)
		) {
			$arTabs = static::collectTabs($iblockId, $filterName);
	
			$defaultPriceKey = Solution::GetFrontParametrValue('TARIFFS_DEFAULT_PRICE_KEY', SITE_ID);
			$defaultItemName = Solution::GetFrontParametrValue('TARIFFS_DEFAULT_ITEM_NAME', SITE_ID);
			$defaultFallback = Solution::GetFrontParametrValue('TARIFFS_DEFAULT_FALLBACK', SITE_ID);
	
			$requestPriceKey = $_REQUEST['tariffs_price_key'] ?? '';
			if (
				strlen($requestPriceKey) &&
				(
					(
						$arTabs['PROPS_TABS'] &&
						isset($arTabs['PROPS_TABS'][$requestPriceKey])
					) ||
					(
						$arTabs['ITEMS_TABS'] &&
						isset($arTabs['ITEMS_TABS'][$requestPriceKey])
					)
				)
			) {
				$priceKey = $requestPriceKey;
			}
			else {
				if ($arTabs['PROPS_TABS']) {
					if (isset($arTabs['PROPS_TABS'][$defaultPriceKey])) {
						$priceKey = $defaultPriceKey;
					}
					else {
						if ($defaultFallback === 'FIRST') {
							$priceKey = reset(array_keys($arTabs['PROPS_TABS']));
						}
						else {
							$priceKey = end(array_keys($arTabs['PROPS_TABS']));
						}
					}
				}
				elseif ($arTabs['ITEMS_TABS']) {
					if (isset($arTabs['ITEMS_TABS'][$defaultItemName])) {
						$priceKey = $defaultItemName;
					}
					else {
						if ($defaultFallback === 'FIRST') {
							$priceKey = reset(array_keys($arTabs['ITEMS_TABS']));
						}
						else {
							$priceKey = end(array_keys($arTabs['ITEMS_TABS']));
						}
					}
				}
			}
	
			if ($priceKey) {
				if (
					$arTabs['PROPS_TABS'] &&
					isset($arTabs['PROPS_TABS'][$priceKey]) &&
					(
						!$arTabs['ITEMS_TABS'] ||
						!isset($arTabs['ITEMS_TABS'][$priceKey])
					)
				) {
					$GLOBALS[$filterName][static::TAB_FILTER_INDEX] = [
						'!PROPERTY_TARIF_PRICE_'.$priceKey => false,
						'PROPERTY_TARIF_ITEM' => false,
					];
				}
				elseif (
					$arTabs['ITEMS_TABS'] &&
					isset($arTabs['ITEMS_TABS'][$priceKey]) &&
					(
						!$arTabs['PROPS_TABS'] ||
						!isset($arTabs['PROPS_TABS'][$priceKey])
					)
				) {
					$GLOBALS[$filterName][static::TAB_FILTER_INDEX] = [
						'PROPERTY_TARIF_ITEM' => '%"KEY":"'.$priceKey.'"%',
					];
				}
				else {
					$GLOBALS[$filterName][static::TAB_FILTER_INDEX] = [
						'LOGIC' => 'OR',
						[
							'!PROPERTY_TARIF_PRICE_'.$priceKey => false,
							'PROPERTY_TARIF_ITEM' => false,
						],
						[
							'PROPERTY_TARIF_ITEM' => '%"KEY":"'.$priceKey.'"%',
						]
					];
				}
			}
		}

		return $priceKey;
	}

	public static function translit($title) {
		return \CUtil::translit($title, 'ru', ['replace_space' => '-', 'replace_other' => '-']);
	}
}
