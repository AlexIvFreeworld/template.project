<?

namespace Aspro\Allcorp3Medc\Product;

use CAllcorp3Medc as Solution;

class Common
{
	/**
	 * Filter blocks for component_epilog of catalog.element
	 * 
	 * @param array $arBlocks [
	 * 	'ORDERED' => [...]
	 * 	'STATIC' => [...]
	 * 	'EXCLUDED' => [...]
	 * ]
	 * @param string $templatePath
	 * @param string $templateName
	 * 
	 * @return array [
	 * 	'STATIC' => [...],
	 *  'ORDERED' => [...],
	 * ]
	 */
	public static function showEpilogBlocks(array $arBlocks, string $templatePath, string $templateName = ''): array
	{
		$epilogBlockPath = $templatePath . '/epilog_blocks/';
		$arBlocksStatic = ['tizers'];
		$arBlocksExcluded = [];
		
		// event for setup static blocks for solutions default templates
		foreach (GetModuleEvents(Solution::moduleID, 'beforeAsproShowEpilogBlock', true) as $arEvent)
			ExecuteModuleEventEx($arEvent, [&$arBlocksStatic, &$arBlocksExcluded, $templateName]);

		if ($arBlocks['STATIC']) {
			$arBlocksStatic = $arBlocks['STATIC'];
		}

		if ($arBlocks['EXCLUDED']) {
			$arBlocksExcluded = $arBlocks['EXCLUDED'];
		}

		$arBlocksOrdered = $arBlocks['ORDERED'] ?? [];

		if ($arBlocksStatic) {
			$arBlocksStatic = array_map(function ($blockCode) use ($epilogBlockPath) {
				$path = $epilogBlockPath . $blockCode . '.php';

				return file_exists($path) ? $path : '';
			}, $arBlocksStatic);
			$arBlocksStatic = array_diff($arBlocksStatic, ['']);
		}

		if ($arBlocksOrdered) {
			$arBlocksOrdered = array_map(function ($blockCode) use ($epilogBlockPath, $arBlocksStatic, $arBlocksExcluded) {
				$path = $epilogBlockPath . $blockCode . '.php';

				return file_exists($path) && !in_array($blockCode, $arBlocksStatic) && !in_array($blockCode, $arBlocksExcluded)
					? $path
					: '';
			}, $arBlocksOrdered);
			$arBlocksOrdered = array_diff($arBlocksOrdered, ['']);
		}

		return [
			'STATIC' => $arBlocksStatic,
			'ORDERED' => $arBlocksOrdered,
		];
	}
}
