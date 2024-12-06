<?

namespace Aspro\Allcorp3Medc\Form\Field;

use \Aspro\Allcorp3Medc\Functions\CAsproAllcorp3 as Functions,
	\CAllcorp3Medc as Solution;

class Webform extends Base
{
	private $columnSize = parent::defaultColumnSize;

	public function __construct(array $arOptions)
	{
		parent::__construct($arOptions);
	}

	public function draw(): void
	{
		// event for manipulation params of drawed form field
		foreach (GetModuleEvents(Solution::moduleID, 'BeforeAsproDrawFormField', true) as $arEvent) {
			ExecuteModuleEventEx($arEvent, array($this->arConfig['FIELD_SID'], &$this->arQuestion, &$this->arConfig['TYPE'], $this->arConfig['PARAMS']));
		}

		$this->setHtmlCodePropsCommon();
		if ($this->checkHidden()) {
			echo $this->arQuestion['HTML_CODE'];
			return;
		}

		$this->prepareDrawOptions();

		Functions::showBlockHtml([
			'FILE' => 'form/field.php',
			'PARAMS' => $this->arViewOptions,
		]);

	}

	protected function checkHidden(): bool
	{
		$bHidden = $this->arQuestion['STRUCTURE'][0]['FIELD_TYPE'] === 'hidden' || in_array($this->arConfig['FIELD_SID'], $this->arHiddenFields);
		if ($bHidden) {
			if (
				$this->arConfig['FIELD_SID'] === 'TOTAL_SUMM'
				&& $this->arConfig['PARAMS'][$this->arConfig['FIELD_SID']]
			) {
				$this->arQuestion['HTML_CODE'] = str_replace('value="', 'value="' . $this->arConfig['PARAMS'][$this->arConfig['FIELD_SID']], $this->arQuestion["HTML_CODE"]);
			}

			if (in_array($this->arConfig['FIELD_SID'], $this->arHiddenFields)) {
				if (strpos($this->arQuestion['HTML_CODE'], '<input')) {
					$this->arQuestion['HTML_CODE'] = preg_replace('/type=[\'"]\w+[\'"]/s', 'type="hidden"', $this->arQuestion['HTML_CODE']);
				} else {
					$this->arQuestion['HTML_CODE'] = str_replace('class="', 'class="hidden ', $this->arQuestion['HTML_CODE']);
				}
			}
		}

		return $bHidden;
	}

	private function isCheckbox(): bool
	{
		return in_array($this->arQuestion['STRUCTURE'][0]['FIELD_TYPE'], ['checkbox', 'radiobox']);
	}

	protected function prepareDrawOptions(): void
	{
		$this->setHtmlCodeProps();
		$this->setColumnSize();
		$this->setViewOptions();
	}

	private function setHtmlCodePropsCommon(): void
	{
		$this->arQuestion['HTML_CODE'] = str_replace(
			['name=', 'left', 'size="0"'],
			['data-sid="' . $this->arConfig['FIELD_SID'] . '" name=', '', ''],
			$this->arQuestion['HTML_CODE']
		);
		// $this->arQuestion["HTML_CODE"] = str_replace('name=', 'data-sid="' . $this->arConfig['FIELD_SID'] . '" name=', $this->arQuestion["HTML_CODE"]);
		// $this->arQuestion["HTML_CODE"] = str_replace('left', '', $this->arQuestion["HTML_CODE"]);
		// $this->arQuestion["HTML_CODE"] = str_replace('size="0"', '', $this->arQuestion["HTML_CODE"]);
	}

	private function setHtmlCodeProps(): void
	{
		$application = \Bitrix\Main\Application::getInstance();
		$request = $application->getContext()->getRequest();

		$filedClassList = '';
		if (
			$this->arQuestion['VALUE']
			|| $request['form_' . $this->arQuestion['STRUCTURE'][0]['FIELD_TYPE'] . '_' . $this->arQuestion['STRUCTURE'][0]['ID']]
			|| $this->arQuestion['STRUCTURE'][0]['VALUE']
		) {
			$filedClassList .= ' input-filed';
		}

		if (strpos($this->arQuestion["HTML_CODE"], "class=") === false) {
			$this->arQuestion["HTML_CODE"] = str_replace(
				['<input', '<select'],
				['<input class=""', '<select class=""'],
				$this->arQuestion["HTML_CODE"]
			);
		}
		if (!isset($arParams['NO-CONTROL'])) {
			$this->arQuestion["HTML_CODE"] = str_replace('class="', 'class="form-control ', $this->arQuestion["HTML_CODE"]);
			$this->arQuestion["HTML_CODE"] = str_replace('class="', 'id="' . $this->getFieldAttrFor() . '" class="', $this->arQuestion["HTML_CODE"]);
		}

		if (
			isset($arResult)
			&& is_array($arResult["FORM_ERRORS"])
			&& array_key_exists($this->arConfig['FIELD_SID'], $arResult['FORM_ERRORS'])
		) {
			$this->arQuestion["HTML_CODE"] = str_replace('class="', 'class="error ', $this->arQuestion["HTML_CODE"]);
		}

		if ($this->arQuestion["REQUIRED"] === "Y") {
			$this->arQuestion["HTML_CODE"] = str_replace('name=', 'required name=', $this->arQuestion["HTML_CODE"]);
		}

		if ($this->arQuestion["STRUCTURE"][0]["FIELD_TYPE"] === "email") {
			$this->arQuestion["HTML_CODE"] = str_replace('type="text"', 'type="email" placeholder=""', $this->arQuestion["HTML_CODE"]);
		}

		if (strpos($this->arQuestion["HTML_CODE"], "phone") !== false) {
			$this->arQuestion["HTML_CODE"] = str_replace('type="text"', 'type="tel"', $this->arQuestion["HTML_CODE"]);
		}

		if ($filedClassList) {
			$this->arQuestion["HTML_CODE"] = str_replace('class="', 'class="' . $filedClassList . ' ', $this->arQuestion["HTML_CODE"]);
		}
	}

	private function setColumnSize(): void
	{
		preg_match('/\bcol-(\d+)\b/', $this->arQuestion['STRUCTURE'][0]['FIELD_PARAM'], $matches);
		$this->columnSize = isset($matches[1]) && $matches[1] < static::defaultColumnSize && $matches[1] > 0
			? $matches[1]
			: static::defaultColumnSize;
	}

	protected function setViewOptions(): void
	{
		$this->arViewOptions = [
			'HTML_CODE' => $this->getFieldHtmlCode(),
			'HINT' => !empty($arQuestion["HINT"]) ? $arQuestion["HINT"] : '',
			'DATA_SID' => $this->arConfig['FIELD_SID'],
			'QUESTION_CLASS_LIST' => $this->getQuestionClassList(),
			'FORM_GROUP_CLASS_LIST' => $this->getFormGroupClassList(),
			'HTML_CODE_WRAPPER_CLASS_LIST' => $this->isTypeDate() ? ' dates' : '',
			'CAPTION' => $this->getCaptionHtml(),
		];
	}

	private function getFieldHtmlCode(): string
	{
		if ($this->arConfig['FIELD_SID'] === 'RATING') {
			ob_start();
			Functions::showBlockHtml([
				'FILE' => 'form/vote.php',
				'PARAMS' => [
					'HTML_CODE' => str_replace('type="text"', 'type="hidden"', $this->arQuestion["HTML_CODE"]),
				],
			]);
			$html = ob_get_clean();
			return $html;
		}

		if ($this->isCheckbox()) {
			ob_start();
			foreach ($this->arQuestion['STRUCTURE'] as $arItem) {
				$name = $arItem["FIELD_TYPE"] . "_" . $this->arConfig['FIELD_SID'];
				if ($this->arQuestion['STRUCTURE'][0]['FIELD_TYPE'] === "checkbox") {
					$name .= '[]';
				}
				$typeField = $this->arQuestion['STRUCTURE'][0]['FIELD_TYPE'] === 'checkbox' ? 'checkbox' : 'radiobox';

				Functions::showBlockHtml([
					'FILE' => 'form/checkbox.php',
					'PARAMS' => [
						'FIELD_TYPE' => $arItem['FIELD_TYPE'],
						'ID' => $arItem['ID'],
						'FIELD_TYPE_STRUCT' => $typeField,
						'MESSAGE' => $arItem['MESSAGE'],
					],
				]);
			}
			$html = ob_get_clean();
			return $html;
		}

		return $this->arQuestion['HTML_CODE'];
	}

	private function getCaptionHtml(): string
	{
		$html = '<label class="font_13 color_999" for="' . $this->getFieldAttrFor() . '">' .
			'<span>' . $this->arQuestion["CAPTION"] . ($this->arQuestion["REQUIRED"] === "Y" ? '&nbsp;<span class="required-star">*</span>' : '') . '</span>' .
			'</label>';

		return $html;
	}

	private function getQuestionClassList(): string
	{
		$questionClassList = 'col-xs-12';
		if ($this->columnSize < static::defaultColumnSize) {
			$questionClassList .= ' col-sm-' . $this->columnSize;
		}

		if ($this->isCheckbox()) {
			$questionClassList .= ' style_check bx_filter';
		}

		return $questionClassList;
	}

	private function getFormGroupClassList(): string
	{
		return in_array($this->arQuestion['STRUCTURE'][0]['FIELD_TYPE'], ["file", "image", "checkbox", "radio", "multiselect"])
			? ' fill-animate'
			: '';
	}

	private function getFieldAttrFor(): string
	{
		return ($this->arConfig['TYPE'] ? $this->arConfig['TYPE'] . '_' : '') . $this->arConfig['FIELD_SID'];
	}
}
