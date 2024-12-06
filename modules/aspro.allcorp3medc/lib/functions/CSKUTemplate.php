<?
namespace Aspro\Allcorp3Medc\Functions;

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\IO\File;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Type\Collection;
use Bitrix\Main\Config\Option;
use Bitrix\Iblock;
use \CAllcorp3Medc as Solution;
use \Aspro\Allcorp3Medc\Functions\CAsproAllcorp3 as Functions;

Loc::loadMessages(__FILE__);

if(!class_exists('CSKUTemplate'))
{
	/**
	 * Class for show sku items
	 */
	class CSKUTemplate{
		/**
		 * @var string MODULE_ID solution
		 */
		const MODULE_ID = Solution::moduleID;

		public $props = [];
		public $items = [];
		public $linkedProp = [];
		public $linkCodeProp = '';
		
		/**
		 * Show html sku tree props
		 * @param array $arProps 
		 */
		public static function showSkuPropsHtml(array $arProps = [])
		{
			foreach ($arProps as $code => $arProp) {
				Functions::showBlockHtml([
					'TYPE' => 'SKU',
					'FILE' => 'sku/properties_in_'.$arProp['SHOW_MODE'].'.php',
					'PARAMS' => $arProp
				]);
			}
		}
	}
}?>