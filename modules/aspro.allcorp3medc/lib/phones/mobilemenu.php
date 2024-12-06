<?php

namespace Aspro\Allcorp3Medc\Phones;

use Bitrix\Main\Localization\Loc,
	CAllcorp3Medc as Solution;

class MobileMenu extends Base
{
	function __construct(array $arOptions = [])
	{
		parent::__construct($arOptions);

		$this->viewName = 'mobile_menu_view';
	}

	protected function prepareViewOptions(): void
	{
		$this->setViewOptions();
		$this->setPhones();
	}

	private function setViewOptions(): void
	{
		$this->arViewOptions['ICONS'] = [
			'PHONE' => Solution::showIconSvg('phone mobilemenu__menu-item-svg fill-theme-target', SITE_TEMPLATE_PATH . "/images/svg/Phone_big.svg"),
			'TRIANGLE' => Solution::showIconSvg(' down menu-arrow bg-opacity-theme-target fill-theme-target fill-dark-light-block', SITE_TEMPLATE_PATH . '/images/svg/Triangle_right.svg', '', '', true, false),
			'ARROW_BACK' => Solution::showIconSvg(' arrow-all__item-arrow', SITE_TEMPLATE_PATH . '/images/svg/Arrow_lg.svg'),
		];

		$this->arViewOptions['MENU_TITLE'] = Loc::getMessage('ALLCORP3_T_MENU_CALLBACK');

		if ($this->arConfig['CALLBACK']) {
			$this->arViewOptions['CALLBACK'] = [
				'TEXT' => Loc::getMessage('CALLBACK'),
				'DATASET' => [
					'NAME' => 'callback',
					'PARAM_ID' => Solution::getFormID('callback'),
				],
			];
		}
	}
}
