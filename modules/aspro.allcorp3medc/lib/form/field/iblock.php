<?

namespace Aspro\Allcorp3Medc\Form\Field;

use \Aspro\Allcorp3Medc\Functions\CAsproAllcorp3 as Functions;

class Iblock extends Base
{
	private $columSize = parent::defaultColumnSize;

	public function __construct(array $arOptions = [])
	{
		parent::__construct($arOptions);
	}

	public function draw(): void
	{
		// check if field is hidden
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

	protected function prepareDrawOptions(): void
	{
		$this->setCaption();
		$this->setColumnSize();
		$this->setFormType();
		$this->setViewOptions();
	}

	protected function checkHidden(): bool
	{
		$bHidden = in_array($this->arConfig['FIELD_SID'], $this->arHiddenFields) || $this->arQuestion['FIELD_TYPE'] === 'hidden';
		if ($bHidden && !strpos($this->arQuestion['HTML_CODE'], 'hidden')) {
			$this->arQuestion['HTML_CODE'] = preg_replace('/type=[\'"]\w+[\'"]/s', 'type="hidden"', $this->arQuestion['HTML_CODE']);
		}

		return $bHidden;
	}

	private function checkFileMultiple(): bool
	{
		return $this->arQuestion['FIELD_TYPE'] === "file" && $this->arQuestion['MULTIPLE'] === 'Y';
	}

	private function setCaption(): void
	{
		if ($this->arQuestion['CAPTION']) {
			if ($this->arConfig['TYPE']) {
				$this->arQuestion['CAPTION'] = str_replace(' for="', ' for="' . $this->arConfig['TYPE'] . '_', $this->arQuestion["CAPTION"]);
			}
		} else {
			$this->arQuestion['CAPTION'] = '';
		}
	}

	private function setColumnSize(): void
	{
		if (strlen($this->arQuestion['HINT'])) {
			preg_match('/\bcol-(\d+)\b/', $this->arQuestion['HINT'], $matches);
			$this->columSize = isset($matches[1]) && $matches[1] < static::defaultColumnSize && $matches[1] > 0
				? $matches[1]
				: static::defaultColumnSize;

			if ($matches[0]) {
				$this->arQuestion['HINT'] = trim(str_replace($matches[0], '', $this->arQuestion['HINT']));
			}
		}
	}

	private function setFormType(): void
	{
		if ($this->arConfig['TYPE']) {
			$this->arQuestion['HTML_CODE'] = str_replace(' id="', ' id="' . $this->arConfig['TYPE'] . '_', $this->arQuestion["HTML_CODE"]);
			
		}
	}

	protected function setViewOptions(): void
	{
		// show view for field
		$this->arViewOptions = [
			'CAPTION' => $this->arQuestion['CAPTION'],
			'DATA_SID' => $this->arConfig['FIELD_SID'],
			'HINT' => trim($this->arQuestion["HINT"]),
			'HTML_CODE' => $this->arQuestion['HTML_CODE'],
			'IS_FILE_MULTIPLE' => $this->checkFileMultiple(),
			'QUESTION_CLASS_LIST' => $this->getQuestionClassList(),
			'FORM_TYPE' => $this->arConfig['TYPE'],
		];
	}

	private function getQuestionClassList(): string
	{
		$questionClassList = 'col-xs-12';
		if ($this->columSize < static::defaultColumnSize) {
			$questionClassList .= ' col-sm-' . $this->columSize;
		}

		if ($this->arQuestion['FIELD_TYPE'] === 'checkbox') {
			$questionClassList .= ' style_check bx_filter';
		}

		return $questionClassList;
	}
}
