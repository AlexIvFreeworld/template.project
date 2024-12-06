<?

namespace Aspro\Allcorp3Medc\Phones;

use CAllcorp3Medc as Solution,
	\Aspro\Allcorp3Medc\Functions\CAsproAllcorp3 as Functions,
	\Aspro\Allcorp3Medc\Iconset;

abstract class Base
{
	static $callIndex;
	protected $arConfig;
	protected $arBackParameters;
	protected $arViewOptions = [
		'TOTAL_COUNT' => 0,
		'PHONES' => [],
		'ADDITIONAL_BLOCKS' => [],
	];

	protected $viewName = 'common_view';
	protected $viewStorage;

	public function __debugInfo()
	{
		return [
			'callIndex' => static::$callIndex,
			'arConfig' => $this->arConfig,
			'arViewOptions' => $this->arViewOptions,
		];
	}

	public function __construct(array $arOptions = [])
	{
		$arDefaultConfig = [
			'REGION_CONDITION' => true,
			'CALLBACK' => true,
			'CLASS' => '',
			'ICON' => '',
			'SHOW_ONLY_ICON' => false,
			'DROPDOWN_TOP' => false,
			'IS_DROPDOWN_CALLBACK' => true,
			'IS_DROPDOWN_EMAIL' => true,
			'IS_DROPDOWN_SOCIAL' => true,
			'IS_DROPDOWN_ADDRESS' => true,
			'IS_DROPDOWN_SCHEDULE' => true
		];
		$this->arConfig = array_merge($arDefaultConfig, $arOptions);
		$this->arBackParameters = Solution::GetBackParametrsValues(SITE_ID);

		$this->setTotalCount();
	}

	protected function getPhoneProps(int $index = 0, array $arProps = ['PHONE', 'HREF', 'ICON', 'DESCRIPTION']): array
	{
		if (!$arProps) return [];
		$arProps = array_fill_keys($arProps, '');
		$arProps = $GLOBALS['arRegion'] && $this->arConfig['REGION_CONDITION'] ? $this->getPhonePropsRegion($index, $arProps) : $this->getPhonePropsModule($index, $arProps);

		if (array_key_exists('HREF', $arProps) && !strlen($arProps['HREF'])) {
			$arProps['HREF'] = 'javascript:;';
		}

		if (array_key_exists('ICON', $arProps) && $arProps['ICON']) {
			$arProps['ICON'] = '<span class="icon">' . Iconset::showIcon($arProps['ICON'], false, false) . '</span>';
		}

		return $arProps;
	}

	protected function getPhonePropsRegion(int $index, array $arProps): array
	{
		if (is_array($GLOBALS['arRegion']['PHONES']) && !empty($GLOBALS['arRegion']['PHONES']) && is_array($GLOBALS['arRegion']['PHONES'][$index])) {
			if (array_key_exists('PHONE', $arProps) && array_key_exists('PHONE', $GLOBALS['arRegion']['PHONES'][$index])) {
				$arProps['PHONE'] = $GLOBALS['arRegion']['PHONES'][$index]['PHONE'];
			}

			if (array_key_exists('HREF', $arProps) && array_key_exists('HREF', $GLOBALS['arRegion']['PHONES'][$index])) {
				$arProps['HREF'] = $GLOBALS['arRegion']['PHONES'][$index]['HREF'];
			}

			if (array_key_exists('ICON', $arProps) && array_key_exists('ICON', $GLOBALS['arRegion']['PHONES'][$index])) {
				$arProps['ICON'] = $GLOBALS['arRegion']['PHONES'][$index]['ICON'];
			}
			if (array_key_exists('DESCRIPTION', $arProps) && isset($GLOBALS['arRegion']['PROPERTY_PHONES_DESCRIPTION'][$index]))
			{
				$arProps['DESCRIPTION'] = $GLOBALS['arRegion']['PROPERTY_PHONES_DESCRIPTION'][$index];
			}
		}

		return $arProps;
	}

	protected function getPhonePropsModule(int $index, array $arProps): array
	{
		if (array_key_exists('PHONE', $arProps)) {
			$arProps['PHONE'] = $this->arBackParameters['HEADER_PHONES_array_PHONE_VALUE_' . $index];
		}

		if (array_key_exists('HREF', $arProps)) {
			$arProps['HREF'] = $this->arBackParameters['HEADER_PHONES_array_PHONE_HREF_' . $index];
		}

		if (array_key_exists('ICON', $arProps)) {
			$arProps['ICON'] = $this->arBackParameters['HEADER_PHONES_array_PHONE_ICON_' . $index];
		}

		if (array_key_exists('DESCRIPTION', $arProps)) {
			$arProps['DESCRIPTION'] = $this->arBackParameters['HEADER_PHONES_array_PHONE_DESCRIPTION_' . $index];
		}

		return $arProps;
	}

	protected function setTotalCount(): void
	{
		global $arRegion;
		$this->arViewOptions['TOTAL_COUNT'] = $arRegion && $this->arConfig['REGION_CONDITION']
			? (isset($arRegion['PHONES']) && is_array($arRegion['PHONES']) ? count($arRegion['PHONES']) : 0)
			: (int)$this->arBackParameters['HEADER_PHONES'];
	}

	protected function setPhones(): void
	{
		for ($i = 0; $i < $this->arViewOptions['TOTAL_COUNT']; $i++) {
			$arItem = $this->getPhoneProps($i);

			$this->arViewOptions['PHONES'][] = $arItem;
		}
	}

	public function show(): void
	{
		if (!$this->arViewOptions['TOTAL_COUNT']) return;

		if (!isset(static::$callIndex)) {
			static::$callIndex = 0;
		}

		global $arRegion;

		if ($arRegion) {
			$compositeID = $this->arConfig['INSTANCE_TYPE'] . '-phones-block-' . static::$callIndex++;

			$frame = new \Bitrix\Main\Composite\BufferArea($compositeID);
			$frame->begin();
		}

		if (!$this->viewStorage) {
			$this->prepareViewOptions();

			ob_start();
			Functions::showBlockHtml([
				'FILE' => 'phones/' . $this->viewName . '.php',
				'PARAMS' => $this->arViewOptions,
			]);
			$this->viewStorage = ob_get_clean();
		}

		echo $this->viewStorage;


		if ($arRegion) {
			$frame->end();
		}
	}


	abstract protected function prepareViewOptions(): void;
}
