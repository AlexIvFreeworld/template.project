<?

namespace Aspro\Allcorp3Medc\Solution;

use \CAllcorp3Medc as Solution,
	\CAllcorp3MedcCache as Cache;

class Form
{
	public static function OnAsproAdditionalFormHandler($arParams)
	{
	}
	public static function OnAsproGetBackParametrsValuesFormOptionsHandler(array &$arFormsOptions, string $code)
	{
		if (strpos($code, '_ONLINE_FORM') !== false) {
			$arFormsOptions[$code]['DEPENDENT_PARAMS'] =
				array(
					'TYPE' . $code => array(
						'TITLE' => GetMessage('ONLINE_RECORD_TYPE'),
						'TO_TOP' => 'Y',
						'TYPE' => 'selectbox',
						'LIST' => array(
							'BIG' => GetMessage('ONLINE_RECORD_TYPE_BIG'),
							'SHORT' => GetMessage('ONLINE_RECORD_TYPE_SHORT'),
						),
						'CONDITIONAL_VALUE' => 'COMPLEX',
						'DEFAULT' => 'BIG',
						'THEME' => 'N',
					)
				) +
				$arFormsOptions[$code]['DEPENDENT_PARAMS'];
		}
	}

	public static function BeforeAsproDrawFormFieldHandler($FIELD_SID, &$arQuestion, &$type, &$arParams)
	{
		if (in_array($FIELD_SID, ['SPECIALIZATION', 'SPECIALIST'])) {
			$type = 'POPUP';
			$arParams = array_merge($arParams, [
				'NO-CONTROL' => 'Y',
			]);
		}

		if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] === 'date') {
			$arQuestion["HTML_CODE"] = preg_replace('/(<input.*?>).*/s', '${1}', $arQuestion["HTML_CODE"]);
			if (strpos($arQuestion['HTML_CODE'], 'class="') !== false) {
				$arQuestion["HTML_CODE"] = str_replace('class="', 'readonly class="datetime ', $arQuestion["HTML_CODE"]);
			} else {
				$arQuestion["HTML_CODE"] = str_replace('id="', 'class="datetime" readonly id="', $arQuestion["HTML_CODE"]);
			}
		}
	}

	public static function OnAsproAddFormResultToIBlockFieldHandler(array $arrAnswersVarname, $RESULT_ID, array &$PROP)
	{
		if ($arrAnswersVarname[$RESULT_ID]['SPECIALIST'][0]['USER_TEXT']) {
			$siteId = SITE_ID;
			if ($staffIBlockId = Cache::$arIBlocks[$siteId]["aspro_" . Solution::themesSolutionName . "_content"]["aspro_" . Solution::themesSolutionName . "_staff"][0]) {
				$staffName = $arrAnswersVarname[$RESULT_ID]['SPECIALIST'][0]['USER_TEXT'];
				if (strlen($staffName)) {
					$staffID = Cache::CIBlockElement_GetList(
						[
							'CACHE' => [
								'TAG' => Cache::GetIBlockCacheTag($staffIBlockId),
								'RESULT' => ['ID'],
								'MULTI' => 'N'
							]
						],
						[
							'IBLOCK_ID' => $staffIBlockId,
							'NAME' => $staffName,
						],
						false,
						false,
						['ID']
					);

					$PROP['LINK_STAFF'] = $staffID;
				}
			}
		}
	}

	public static function OnAsproAfterIBlockElementAddPropsHandler(&$PROP, $arFields) {
		if ($arFields['PROPERTY_VALUES']['SPECIALIST']) {
			$PROP['LINK_STAFF'] = $arFields['PROPERTY_VALUES']['SPECIALIST'];
		}
	}

	public static function BeforeAsproGetFormIDHandler(&$shortCode)
	{
		if (strpos($shortCode, 'online') !== false) {
			$code = 'aspro_' . Solution::themesSolutionName . '_online';
			$type = \Bitrix\Main\Config\Option::get(Solution::moduleID, 'TYPE' . strtoupper($code) . '_FORM', 'BIG', SITE_ID);
			$shortCode = $type === 'SHORT' ? $code . '_short' : $code;
		}
	}

	public static function OnAsproIBlockFormDrawFieldsHandler($FIELD_CODE, &$arQuestion) {
		if (
			in_array($arQuestion["FIELD_TYPE"], ['date', 'datetime']) 
			&& strpos($arQuestion['HTML_CODE'], 'readonly') === false
		) {
			$arQuestion['HTML_CODE'] = str_replace('type=', 'readonly type=', $arQuestion['HTML_CODE']);
		}
		
		// if (strpos($FIELD_CODE, 'PRODUCT') !== false) {
		// 	$arQuestion['HTML_CODE'] = str_replace('type="text"', 'type="hidden"', $arQuestion['HTML_CODE']);
		// 	$arQuestion['FIELD_TYPE'] = 'hidden';
		// 	$arQuestion['STRUCTURE'][0]['FIELD_TYPE'] = 'hidden';
		// }
	}
}
