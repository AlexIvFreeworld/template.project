<?

namespace Aspro\Allcorp3Medc\Form\Field;

class Factory {
	public static function create(string $type, array $arOptions) {
		switch ($type) {
			case 'iblock':
				return new Iblock($arOptions);
			case 'webform':
			default:
				return new Webform($arOptions);
		}
	}
}