<?

namespace Aspro\Allcorp3Medc\Phones;

use Bitrix\Main\Localization\Loc,
	CAllcorp3Medc as Solution,
	\Aspro\Allcorp3Medc\Functions\CAsproAllcorp3 as Functions;

class Common extends Base
{
	protected $arViewOptions = [
		'WRAPPER_CLASS_LIST' => '',
		'ICON' => [
			'ONLY' => '',
			'PHONE' => '',
			'MORE_ARROW' => '',
		],
		'SHOW_ONLY_ICON' => '',
		'TOTAL_COUNT' => '',
		'PHONES' => [],
		'ADDITIONAL_BLOCKS' => [],
	];

	function __construct(array $arOptions = [])
	{
		parent::__construct($arOptions);
	}

	protected function prepareViewOptions(): void
	{
		$this->setViewOptions();
		$this->setPhones();
	}

	private function setViewOptions(): void
	{
		$this->arViewOptions['ICON']['ONLY'] = Solution::showIconSvg("", SITE_TEMPLATE_PATH . "/images/svg/Phone_big.svg");
		$this->arViewOptions['ICON']['PHONE'] = Solution::showIconSvg("", SITE_TEMPLATE_PATH . "/images/svg/" . ($this->arConfig['ICON'] ?: 'Phone_sm.svg'));
		$this->arViewOptions['ICON']['MORE_ARROW'] = Solution::showIconSvg("", SITE_TEMPLATE_PATH . "/images/svg/more_arrow.svg", "", "", false);
		$this->arViewOptions['SHOW_ONLY_ICON'] = $this->arConfig['SHOW_ONLY_ICON'];
		
		$this->setWrapperClass();
		$this->setAdditionalBlocks();
	}

	private function setWrapperClass(): void
	{
		if ($this->arViewOptions['TOTAL_COUNT'] > 1) {
			$this->arViewOptions['WRAPPER_CLASS_LIST'] .= ' phones__inner--with_dropdown';
		}

		if (strlen($this->arConfig['CLASS'])) {
			$this->arViewOptions['WRAPPER_CLASS_LIST'] .= ' ' . $this->arConfig['CLASS'];
		}
	}

	protected function setPhones(): void
	{
		$arItem = $this->getPhoneProps(0);
		$arItem['WRAPPER_CLASS_LIST'] = ' dropdown__item--first';
		
		if ($this->arViewOptions['TOTAL_COUNT'] === 1) {
			$arItem['WRAPPER_CLASS_LIST'] .= ' dropdown__item--last';
		}
		
		if (!$arItem['DESCRIPTION']) {
			$arItem['LINK_CLASS_LIST'] = ' phones__phone-link--no_descript';
		}

		$this->arViewOptions['PHONES'][] = $arItem;

		// array phones
		if ($this->arViewOptions['TOTAL_COUNT'] >= 1 || $this->arConfig['SHOW_ONLY_ICON']) {
			for ($i = 1; $i < $this->arViewOptions['TOTAL_COUNT']; $i++) {
				$arItem = $this->getPhoneProps($i);
				$arItem['WRAPPER_CLASS_LIST'] = $arItem['LINK_CLASS_LIST'] = '';
				
				if ($i === $this->arViewOptions['TOTAL_COUNT'] - 1) {
					$arItem['WRAPPER_CLASS_LIST'] .= ' dropdown__item--last';
				}
				
				if (!$arItem['DESCRIPTION']) {
					$arItem['LINK_CLASS_LIST'] = ' phones__phone-link--no_descript';
				}

				$this->arViewOptions['PHONES'][] = $arItem;
			}
		}
	}

	private function setAdditionalBlocks(): void
	{
		global $APPLICATION;
		if ($this->arConfig['IS_DROPDOWN_CALLBACK']) {
			ob_start();
			Functions::showBlockHtml([
				'FILE' => 'phones/button_view.php',
				'PARAMS' => [
					'DATASET' => [
						'NAME' => 'callback',
						'PARAM_ID' => Solution::getFormID('callback'),
					],
					'TEXT' => Loc::getMessage('CALLBACK'),
					'WRAPPER_CLASS' => 'phones__dropdown-item callback-item',
				],
			]);
			$this->arViewOptions['ADDITIONAL_BLOCKS']['CALLBACK'] = trim(ob_get_clean());
		}

		if ($this->arConfig['IS_DROPDOWN_EMAIL']) {
			ob_start();
			Solution::showEmail([
				'CLASS' => 'phones__dropdown-value',
				'SHOW_SVG' => false,
				'TITLE' => Loc::getMessage('EMAIL'),
				'TITLE_CLASS' => 'phones__dropdown-title',
				'LINK_CLASS' => 'dark_link',
				'WRAPPER' => 'phones__dropdown-item',
			]);
			$this->arViewOptions['ADDITIONAL_BLOCKS']['EMAIL'] = trim(ob_get_clean());
		}
		
		if ($this->arConfig['IS_DROPDOWN_ADDRESS']) {
			ob_start();
			Solution::showAddress([
				'CLASS' => 'phones__dropdown-value',
				'SHOW_SVG' => false,
				'TITLE' => Loc::getMessage('ADDRESS'),
				'TITLE_CLASS' => 'phones__dropdown-title',
				'WRAPPER' => 'phones__dropdown-item',
				'NO_LIGHT' => true,
				'LARGE' => true,
			]);
			$this->arViewOptions['ADDITIONAL_BLOCKS']['ADDRESS'] = trim(ob_get_clean());
		}

		if ($this->arConfig['IS_DROPDOWN_SCHEDULE']) {
			ob_start();
			Solution::showSchedule([
				'CLASS' => 'phones__dropdown-value',
				'SHOW_SVG' => false,
				'TITLE' => Loc::getMessage('SCHEDULE'),
				'TITLE_CLASS' => 'phones__dropdown-title',
				'WRAPPER' => 'phones__dropdown-item',
				'NO_LIGHT' => true,
				'LARGE' => true,
			]);
			$this->arViewOptions['ADDITIONAL_BLOCKS']['SCHEDULE'] = trim(ob_get_clean());
		}

		if ($this->arConfig['IS_DROPDOWN_SOCIAL']) {
			ob_start();
			include $_SERVER['DOCUMENT_ROOT'] . SITE_DIR . 'include/header/phones-social.info.php';
			$this->arViewOptions['ADDITIONAL_BLOCKS']['SOCIAL'] = trim(ob_get_clean());
		}
	}
}
