<?

namespace Aspro\Allcorp3Medc\Product;

use \Bitrix\Main\Localization\Loc,
	\Bitrix\Main\Web\Json;

use Aspro\Allcorp3Medc\Functions\CAsproAllcorp3 as Functions;

class DetailGallery extends Image
{
	public static $defaultSize = 600;
	protected $arConfig = [];
	protected $arItem = [];
	protected $currentOffer = [];
	protected $arParams = [];
	protected $arGallery = [];
	protected $arViewConfig = [
		'BLOCKS' => [],
	];

	protected $imagesVisible = 0;

	/**
	 * @param array $arOptions = [
	 * 	'ITEM' => array,
	 * 	'PARAMS' => array,
	 * 	'CLASS' => [
	 * 		'CONTAINER' => string,
	 * 		'NO_IMAGE' => string,
	 * 	],
	 * 	'SLIDER_CONFIG' => array,
	 *  'THUMBS_CONFIG' => array,
	 * ]
	 */
	public function __construct(array $arOptions = [])
	{
		$arDefaultConfig = [
			'CURRENT_OFFER' => [],
			'ITEM' => [],
			'PARAMS' => [],
			'SLIDER_CONFIG' => [],
			'THUMBS_CONFIG' => [],
			'CLASS' => [
				'CONTAINER' => '',
				'NO_IMAGE' => 'catalog-detail__gallery__picture',
			],
			'OUTER_STICKERS' => false,
			'CHECK_VISIBILITY' => false,
			'INNER_WRAPPER' => '',
			'DETAIL_BUTTON' => false,
			'SHOW_THUMBS' => true,
		];
		$this->arConfig = array_merge($arDefaultConfig, $arOptions);

		$this->arItem = $this->arConfig['ITEM'];
		$this->currentOffer = $this->arConfig['CURRENT_OFFER'];
		$this->arParams = $this->arConfig['PARAMS'];
		$this->arGallery = $this->arItem['GALLERY'];
		
		$this->prepareGallery();
	}

	public function show(): void
	{
		$this->prepareViewOptions();
		Functions::showBlockHtml([
			'FILE' => 'images/detail_gallery_view.php',
			'PARAMS' => $this->arViewConfig,
		]);
	}

	protected function prepareViewOptions(): void
	{
		$this->setGalleryOptions();
		$this->setBaseViewOptions();
		$this->setHtmlBlocks();
		$this->setDetailButtonOptions();
	}

	protected function setBaseViewOptions(): void
	{
		$bVertical = $this->arParams['GALLERY_THUMB_POSITION'] === 'vertical';

		$this->arViewConfig['IS_VERTICAL'] = $bVertical;
		
		$this->arViewConfig['CLASS']['CONTAINER'] = $this->arConfig['CLASS']['CONTAINER'] ?? '';
		$this->arViewConfig['CLASS']['CONTAINER'] .= ' catalog-detail__gallery--' . $this->arItem['GALLERY_SIZE'];
		$this->arViewConfig['CLASS']['CONTAINER'] .= ' ' . ($bVertical ? 'catalog-detail__gallery--vertical' : 'catalog-detail__gallery--horizontal');
		
		$this->arViewConfig['BLOCKS']['STICKERS'] = [
			'POSITION' => $this->getStickersPosition($bVertical),
			'HTML' => '',
		];
		$this->arViewConfig['INNER_WRAPPER'] = $this->arConfig['INNER_WRAPPER'];
	}

	protected function setGalleryOptions(): void
	{
		$this->arViewConfig['TOTAL_COUNT'] = $this->arConfig['TOTAL_COUNT'];
		$this->arViewConfig['GALLERY'] = array_map(fn (array $arImage) => [
			'ALT' => $arImage['ALT'],
			'TITLE' => $arImage['TITLE'],
			'SRC' => [
				'SMALL' => ($arImage['SMALL']['src'] ?: $arImage['SRC']),
				'BIG' => ($arImage['BIG']['src'] ?: $arImage['SRC']),
				'THUMB' => ($arImage['THUMB']['src'] ?: $arImage['SRC']),
			],
		], $this->arGallery);

		$this->arViewConfig['THUMB'] = [
			'SHOW' => $this->arConfig['SHOW_THUMBS'],
		];
		if ($this->arViewConfig['THUMB']['SHOW']) {
			$this->arViewConfig['THUMB']['MAX_WIDTH'] = $this->getViewMaxWidth();
			$this->arViewConfig['THUMB']['CONFIG'] = $this->getThumbsConfig();
			$this->arViewConfig['THUMB']['ITEM_CLASS'] = ($this->hasSlideImages() ? ' with-padding' : '');
		}

		$this->arViewConfig['SLIDER'] = [
			'CONFIG' => $this->getSliderConfig(),
		];

		if ($this->arItem['NO_IMAGE']) {
			$this->arViewConfig['SKU_IMG_CONFIG'] = Json::encode([
				'NO_IMAGE' => $this->arItem['NO_IMAGE'],
			]);
		}
	}

	protected function setHtmlBlocks(): void
	{
		/* stickers */
		ob_start();
		Functions::showStickers([
			'TYPE' => '',
			'ITEM' => $this->arItem,
			'PARAMS' => $this->arParams,
		]);
		$this->arViewConfig['BLOCKS']['STICKERS']['HTML'] = ob_get_clean();

		if (!$this->arConfig['SHOW_THUMBS']) {
			$this->arViewConfig['BLOCKS']['STICKERS']['WRAPPER'] = 'catalog-detail__gallery-icons';
		}
		/* */

		/* no-image block */
		ob_start();
		self::showNoImage([
			'SRC' => $this->arItem['NO_IMAGE']['SRC'] ?? '',
			'CLASS' => $this->arConfig['CLASS']['NO_IMAGE'],
			'FANCY' => true,
		]);
		$htmlNoImage = ob_get_clean();
		
		$this->arViewConfig['BLOCKS']['NO_IMAGE'] = [
			'HTML' => $htmlNoImage,
			'SRC' => $this->arItem['NO_IMAGE_PATH'],
			'FANCY' => !!$this->arItem['NO_IMAGE_PATH'],
		];
		/* */

		if ($this->arItem['POPUP_VIDEO']) {
			ob_start();
			Functions::showBlockHtml([
				'FILE' => 'images/popup_block_view.php',
				'PARAMS' => [
					'ADDITIONAL_CLASS' => $this->arConfig['TOTAL_COUNT'] > 3 ? ' fromtop' : '',
					'URL' => $this->arItem['POPUP_VIDEO'],
					'TITLE' => Loc::getMessage('VIDEO'),
				],
			]);
			$this->arViewConfig['BLOCKS']['POPUP_VIDEO'] = [
				'HTML' => ob_get_clean(),
			];

		}
	}

	protected function setDetailButtonOptions(): void
	{
		if ($this->arConfig['DETAIL_BUTTON']) {
			$this->arViewConfig['DETAIL_BUTTON'] = [
				'URL' => $this->arItem['DETAIL_PAGE_URL'],
				'TITLE' => Loc::getMessage('MORE_TEXT_ITEM'),
			];
		}
	}

	protected function getViewMaxWidth(): string
	{
		$maxWidth = $slidesCount = 0;
		$totalSpaceBetween = 48;
		if ($this->arConfig['CHECK_VISIBILITY']) {
			$slidesCount = $this->arViewConfig['TOTAL_COUNT'] <= 3 ? $this->arViewConfig['TOTAL_COUNT'] : $this->getVisibleImagesCount();
			$totalSpaceBetween = $this->hasSlideImages() ? 48 : 0;
		} else {
			$slidesCount = $this->arViewConfig['TOTAL_COUNT'] <= 3 ? $this->arViewConfig['TOTAL_COUNT'] : 3;
		}

		$maxWidth = ($slidesCount * 66) - 8 + $totalSpaceBetween;
		
		return ceil($maxWidth) . 'px';
	}

	protected function getVisibleImagesCount(): int
	{
		if (!$this->imagesVisible) {
			switch ($this->arItem['GALLERY_SIZE']) {
				case '586px':
					$this->imagesVisible = 5;
					break;
				case '520px':
					$this->imagesVisible = 4;
					break;
				default:
					$this->imagesVisible = 3;
					break;
			} 

			if ($this->imagesVisible > $this->arConfig['TOTAL_COUNT']) {
				$this->imagesVisible = $this->arConfig['TOTAL_COUNT'];
			}
		}

		return $this->imagesVisible;
	}

	protected function getThumbsConfig(): string
	{
		if ($this->arConfig['THUMBS_CONFIG']) {
			return Json::encode($this->arConfig['THUMBS_CONFIG']);
		}

		$arConfig = [
			'clickTo' => '.catalog-detail__gallery-slider.big',
			'dots' => false,
			'items' => '3',
			'loop' => false,
			'margin' => ($this->arParams['GALLERY_THUMB_POSITION'] === 'vertical' ? 7 : 8),
			'mouseDrag' => false,
			'nav' => true,
			'pullDrag' => false,
		];

		if ($this->arConfig['CHECK_VISIBILITY']) {
			$arConfig['items'] = $this->getVisibleImagesCount();
		}
		
		return Json::encode($arConfig);
	}

	protected function getSliderConfig(): string
	{
		if ($this->arConfig['SLIDER_CONFIG']) {
			return Json::encode($this->arConfig['SLIDER_CONFIG']);
		}

		$arConfig = [
			'dots' => true,
			'dotsContainer' => false,
			'items' => '1',
			'loop' => false,
			'nav' => true,
			'relatedTo' => '.catalog-detail__gallery-slider.thmb',
		];

		return Json::encode($arConfig);
	}

	protected function getStickersPosition(bool $isVertical): string
	{
		if ($this->arConfig['INNER_WRAPPER']) {
			return 'wrapper';
		}

		return $isVertical ? 'wrapper' : ($this->arConfig['OUTER_STICKERS'] ? 'outer' : 'container');
	}

	protected function prepareGallery(): void
	{
		if ($this->currentOffer) {
			$this->arItem['PARENT_IMG'] = '';
			if ($this->arItem['PREVIEW_PICTURE']) {
				$this->arItem['PARENT_IMG'] = $this->arItem['PREVIEW_PICTURE'];
			} elseif ($this->arItem['DETAIL_PICTURE']) {
				$this->arItem['PARENT_IMG'] = $this->arItem['DETAIL_PICTURE'];
			}

			if ($this->arParams['SHOW_GALLERY'] === 'Y') {
				$pictureID = $this->currentOffer['DETAIL_PICTURE'] ?? $this->currentOffer['PREVIEW_PICTURE'];
				if ($pictureID) {

					$arPicture = \CFile::GetFileArray($pictureID);

					$alt = $this->arParams['CHANGE_TITLE_ITEM_DETAIL'] === 'Y' ? $this->currentOffer['NAME'] : $this->arItem['NAME'];
					$title = $this->arParams['CHANGE_TITLE_ITEM_DETAIL'] === 'Y' ? $this->currentOffer['NAME'] : $this->arItem['NAME'];
					if ($this->arItem['ALT_TITLE_GET'] == 'SEO') {
						$alt = $arPicture['ALT'] ?: $alt;
						$title = $arPicture['TITLE'] ?: $title;
					} else {
						$alt = $arPicture['DESCRIPTION'] ?: $arPicture['ALT'] ?: $alt;
						$title = $arPicture['DESCRIPTION'] ?: $arPicture['TITLE'] ?: $title;
					}

					$arPicture['TITLE'] = $title;
					$arPicture['ALT'] = $alt;

					array_unshift($this->arGallery, $arPicture);
					array_splice($this->arGallery, $this->arParams['MAX_GALLERY_ITEMS']);
				}
			} else {
				if ($this->currentOffer['PREVIEW_PICTURE'] || $this->currentOffer['DETAIL_PICTURE']) {
					if ($this->currentOffer['PREVIEW_PICTURE']) {
						$this->arItem['PREVIEW_PICTURE'] = $this->currentOffer['PREVIEW_PICTURE'];
					} elseif ($this->currentOffer['DETAIL_PICTURE']) {
						$this->arItem['PREVIEW_PICTURE'] = $this->currentOffer['DETAIL_PICTURE'];
					}
				}
			}

			if (!$this->currentOffer['PREVIEW_PICTURE'] && !$this->currentOffer['DETAIL_PICTURE']) {
				if ($this->arItem['PREVIEW_PICTURE']) {
					$this->currentOffer['PREVIEW_PICTURE'] = $this->arItem['PREVIEW_PICTURE'];
				} elseif ($this->arItem['DETAIL_PICTURE']) {
					$this->currentOffer['PREVIEW_PICTURE'] = $this->arItem['DETAIL_PICTURE'];
				}
			}
		}

		$this->arGallery = Functions::resizeImages($this->arGallery);
		$this->arConfig['TOTAL_COUNT'] = count($this->arGallery);
	}

	protected function hasSlideImages(): bool
	{
		return $this->arConfig['CHECK_VISIBILITY'] && $this->arConfig['TOTAL_COUNT'] > $this->getVisibleImagesCount();
	}
}
