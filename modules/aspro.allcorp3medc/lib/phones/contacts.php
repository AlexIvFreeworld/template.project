<?php

namespace Aspro\Allcorp3Medc\Phones;

use Bitrix\Main\Localization\Loc;

class Contacts extends Base
{
	function __construct(array $arOptions = [])
	{
		parent::__construct($arOptions);
		$this->arConfig['REGION_CONDITION'] = $this->arBackParameters['SHOW_REGION_CONTACT']
			&& $this->arBackParameters['SHOW_REGION_CONTACT'] === 'Y'
			&& !empty($GLOBALS['arRegion']);

		$this->viewName = 'contacts_view';
	}

	protected function prepareViewOptions(): void
	{
		$this->setViewOptions();
		$this->setPhones();
	}

	protected function setPhones(): void
	{
		for ($i = 0; $i < $this->arViewOptions['TOTAL_COUNT']; $i++) {
			$arItem = $this->getPhoneProps($i);
			$arItem['WRAPPER_CLASS_LIST'] = $this->arConfig['CLASS']
				? ' ' . $this->arConfig['CLASS']
				: ' dark_link';

			$this->arViewOptions['PHONES'][] = $arItem;
		}
	}

	private function setViewOptions(): void
	{
		$this->arViewOptions['LABEL'] = $this->arConfig['LABEL'] ?: Loc::getMessage('SPRAVKA');
		$this->arViewOptions['FROM_REGION'] = $this->arConfig['REGION_CONDITION'];

		$this->setWrapperClass();
	}

	private function setWrapperClass(): void
	{
		$this->arViewOptions['WRAPPER_CLASS_LIST'] = $this->arConfig['REGION_CONDITION']
			? $this->arConfig['CLASS']
			: 'contact-property__value ' . ($this->arConfig['CLASS'] ?: 'dark_link');
	}
}
