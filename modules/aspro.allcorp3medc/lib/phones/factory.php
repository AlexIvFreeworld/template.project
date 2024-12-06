<?php

namespace Aspro\Allcorp3Medc\Phones;

use Bitrix\Main\Web\Json;

class Factory
{
	static $arInstances;

	public static function create(string $type, array $arOptions)
	{
		if (!isset(static::$arInstances)) {
			static::$arInstances = [];
		}

		$arOptions['INSTANCE_TYPE'] = $type;

		$hash = md5(JSON::encode($arOptions));
		if (array_key_exists($hash, static::$arInstances)) {
			return static::$arInstances[$hash];
		}

		switch ($type) {
			case 'common':
			default:
				$obInstance = new Common($arOptions);
				break;
			case 'mobile':
				$obInstance = new Mobile($arOptions);
				break;
			case 'mobile_menu':
				$obInstance = new MobileMenu($arOptions);
				break;
			case 'contacts':
				$obInstance = new Contacts($arOptions);
				break;
		}

		static::$arInstances[$hash] = $obInstance;

		return $obInstance;
	}
}