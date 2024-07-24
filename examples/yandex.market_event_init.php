<?php
function debug($data)
{
	global $USER;
	if ($USER->IsAdmin()) {
		echo "Вы администратор!";
		echo "<pre>" . print_r($data, 1) . "</pre>";
	}
}
define("SHOW_BANNER_SLIDER_SERVICE", true);
define("SHOW_B24_FORM", true);
define("SHOW_IMAGE_SIZE", false);

use Bitrix\Main;
use Yandex\Market;

$eventManager = Main\EventManager::getInstance();

$eventManager->addEventHandler('yandex.market', 'onExportCategoryExtendData', function (Main\Event $event) {

	/** @var $tagValueList Market\Result\XmlValue[] */
	$tagValueList = $event->getParameter('TAG_VALUE_LIST');
	// \Bitrix\Main\Diag\Debug::dumpToFile(array('$tagValueList' => $tagValueList), "", "/log.txt");
	foreach($tagValueList as $tagValue){
		$tagValue->setTagValue('category', "Врач");
	}
});

$eventManager->addEventHandler('yandex.market', 'onExportRootWriteData', function (\Bitrix\Main\Event $event) {
	/** @var Yandex\Market\Result\XmlNode[] $tagResults */
	$tagResults = $event->getParameter('TAG_RESULT_LIST');
	// \Bitrix\Main\Diag\Debug::dumpToFile(array('$tagResults' => $tagResults), "", "/log.txt");

	$tagResult = $tagResults[0];
	$context = $event->getParameter('CONTEXT');

	if ((int)$context['SETUP_ID'] === 1) {
		/** @var \SimpleXMLElement $xmlTag */
		$xmlTag = $tagResult->getXmlElement();
		$arTag = array();
		foreach ($xmlTag->shop->children() as $index => $data) {
			// \Bitrix\Main\Diag\Debug::dumpToFile(array('$index' => $index), "", "/log.txt");
			// \Bitrix\Main\Diag\Debug::dumpToFile(array('$data->getName()' => $data->getName()), "", "/log.txt");
			if ($index != "platform") {
				$arTag[$index] = $data->__toString();
			}
			if ($index == "url") {
				$arTag["email"] = "info-center@klinika-innomed.ru";
				$arTag["picture"] = "https://klinika-innomed.ru/include/logo.svg";
			}
		}
		// \Bitrix\Main\Diag\Debug::dumpToFile(array('$arTag' => $arTag), "", "/log.txt");
		unset($xmlTag->shop);
		// unset($xmlTag->shop->platform);
		$xmlTag->addChild('shop');
		// $xmlTag->shop->addChild('email', 'info-center@klinika-innomed.ru');
		// $xmlTag->shop->addChild('picture', 'https://klinika-innomed.ru/include/logo.svg');
		foreach ($arTag as $name => $value) {
			$xmlTag->shop->addChild($name, $value);
		}
	}
});
