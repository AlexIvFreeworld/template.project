<?

namespace Aspro\Allcorp3Medc\Form\Field;

abstract class Base
{
	protected const defaultColumnSize = 12;
	protected $arHiddenFields = ['ORDER_LIST', 'SESSION_ID', 'TOTAL_SUMM'];
	protected $arConfig = [];
	protected $arQuestion = [];
	protected $arViewOptions = [];

	public function __construct(array $arOptions = [])
	{
		$arDefaultOptions = [
			'QUESTION' => [],
			'FIELD_SID' => '',
			'TYPE' => '',
		];

		$this->arConfig = array_merge($arDefaultOptions, $arOptions);
		$this->arQuestion = $this->arConfig['QUESTION'];
		unset($this->arConfig['QUESTION']);
	}

	/**
	 * Draw field template
	 * 
	 * @param array $arOptions [
	 * 	'QUESTION' => array,
	 * 	'FIELD_SID' => string,
	 * 	'TYPE' => string
	 * ]
	 * 
	 * @return void
	 */
	abstract public function draw(): void;
	abstract protected function checkHidden(): bool;
	abstract protected function prepareDrawOptions(): void;

	abstract protected function setViewOptions(): void;

	public function isTypeDate(): bool
	{
		$sFieldType = $this->arQuestion['STRUCTURE'][0]['FIELD_TYPE'] ?? ($this->arQuestion['FIELD_TYPE'] ?? '');
		return in_array($sFieldType, ['date', 'datetime']);
	}
}
