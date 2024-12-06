<?php

namespace Aspro\Allcorp3Medc\Phones;

use Bitrix\Main\Localization\Loc,
	CAllcorp3Medc as Solution,
	Aspro\Allcorp3Medc\Functions\CAsproAllcorp3 as Functions;

class Mobile extends Base
{
	protected $arViewOptions = [
		'ICON' => [
			'CLOSE' => [
				'IMAGE' => '',
				'TITLE' => '',
			],
			'PHONE' => '',
		],
		'TITLE' => '',
		'TOTAL_COUNT' => '',
		'PHONES' => [],
		'ADDITIONAL_BLOCKS' => [],
	];

	function __construct(array $arOptions = [])
	{
		parent::__construct($arOptions);

		$this->viewName = 'mobile_view';
	}

	protected function prepareViewOptions(): void
	{
		$this->setViewOptions();
		$this->setPhones();
	}
	
	private function setViewOptions(): void
	{
		$this->arViewOptions['TITLE'] = Loc::getMessage('ALLCORP3_T_MENU_CALLBACK');
		$this->arViewOptions['ICON']['CLOSE']['TITLE'] = Loc::getMessage('CLOSE_BLOCK');
		$this->arViewOptions['ICON']['CLOSE']['IMAGE'] = Solution::showIconSvg('', SITE_TEMPLATE_PATH . '/images/svg/Close.svg');
		$this->arViewOptions['ICON']['PHONE'] = Solution::showIconSvg('', SITE_TEMPLATE_PATH . '/images/svg/Phone_big.svg');
		
		$this->setAdditionalBlocks();
	}


	private function setAdditionalBlocks(): void
	{
		global $APPLICATION;

		if ($this->arConfig['CALLBACK']) {
			ob_start();
			Functions::showBlockHtml([
				'FILE' => 'phones/button_view.php',
				'PARAMS' => [
					'DATASET' => [
						'NAME' => 'callback',
						'PARAM_ID' => Solution::getFormID('callback'),
					],
					'ADDITIONAL_CLASS' => ' btn-transparent-border',
					'TEXT' => Loc::getMessage('CALLBACK'),
					'WRAPPER_CLASS' => 'mobilephones__menu-item mobilephones__menu-item--callback',
				],
			]);
			$this->arViewOptions['ADDITIONAL_BLOCKS']['CALLBACK'] = ob_get_clean();
		}

		if ($this->arConfig['IS_DROPDOWN_EMAIL']) {
			ob_start();
			Solution::showEmail([
				'CLASS' => 'phones__dropdown-value',
				'SHOW_SVG' => false,
				'TITLE' => Loc::getMessage('EMAIL'),
				'TITLE_CLASS' => 'phones__dropdown-title',
				'LINK_CLASS' => 'dark_link',
				'WRAPPER' => 'mobilephones__menu-item mobilephones__menu-item--with-padding',
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
				'WRAPPER' => 'mobilephones__menu-item mobilephones__menu-item--with-padding',
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
				'WRAPPER' => 'mobilephones__menu-item mobilephones__menu-item--with-padding',
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
