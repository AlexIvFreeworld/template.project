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
	foreach ($tagValueList as $tagValue) {
		$tagValue->setTagValue('category', "Врач");
	}
});

$eventManager->addEventHandler('yandex.market', 'onExportRootWriteData', function (\Bitrix\Main\Event $event) {
	/** @var Yandex\Market\Result\XmlNode[] $tagResults */
	$tagResults = $event->getParameter('TAG_RESULT_LIST');
	// \Bitrix\Main\Diag\Debug::dumpToFile(array('$event' => $event), "", "/log.txt");

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
			if ($index == "categories") {
				$arTag["sets"] = "";
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

$eventManager->addEventHandler('yandex.market', 'onExportOfferExtendData', function (Main\Event $event) {

	/** @var $tagValueList Market\Result\XmlValue[] */
	/** @var $elementList array */
	/** @var $context array */
	/** @var $parentList array */
	$tagValueList = $event->getParameter('TAG_VALUE_LIST');
	$elementList = $event->getParameter('ELEMENT_LIST');
	// \Bitrix\Main\Diag\Debug::dumpToFile(array('$tagValueList' => $tagValueList), "", "/log.txt");
	$context = $event->getParameter('CONTEXT');
	$parentList = $event->getParameter('PARENT_LIST');
	$IBLOCK_ID_STAFF = 27;
	$setIds = "";
	$arIds = array();
	$arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
	$arFilter = array('IBLOCK_ID' => $IBLOCK_ID_STAFF, 'ACTIVE' => 'Y');
	$resStaff = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
	while ($ob = $resStaff->GetNextElement()) {
		$arFields = $ob->GetFields();
		$arProps = $ob->GetProperties();
		foreach ($arProps['SPECIALIZATION']['VALUE'] as $id) {
			$arIds[$arFields["ID"]][] = $id;
		}
	}

	$filePath = $_SERVER["DOCUMENT_ROOT"] . "/bitrix/catalog_export/export_016.xml";
	$filePathOut = $_SERVER["DOCUMENT_ROOT"] . "/doctors.xml";
	$xml = simplexml_load_file($filePath);
	// \Bitrix\Main\Diag\Debug::dumpToFile(array('$xml' => $xml), "", "/log.txt");
	$IBLOCK_ID = 40;
	$arIdCode = array();
	$arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
	$arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
	$res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
	while ($ob = $res->GetNextElement()) {
		$arFields = $ob->GetFields();
		$arProps = $ob->GetProperties();
		$arIdCode[$arFields["ID"]] = $arFields["CODE"];
		// $setTag->addAttribute('id', $arFields["CODE"]);
		// $setTag = $xmlTag->shop->sets->addChild("set")->addAttribute('id', $arFields["CODE"]);
		$setTag = $xml->shop->sets->addChild("set");
		$setTag->addAttribute('id', $arFields["CODE"]);
		$setTag->addChild("name", $arFields["NAME"]);
		$setTag->addChild("url", $arProps["URL"]["VALUE"]);
	}
	foreach ($arIds  as $key => $arItem) {
		$setIds = "";
		$count = count($arItem);
		foreach ($arItem as $key2 => $id) {
			if($key2 < $count-1){
				$arIds[$key] = $setIds .= $arIdCode[$id] . ",";
			}else{
				$arIds[$key] = $setIds .= $arIdCode[$id];
			}
		}
	}
	foreach ($tagValueList as $elementId => $tagValue) {

		$tagValue->setTagValue('set-ids', $arIds[$elementId]);
	}
	// unset($xml->shop->sets);
	$fd = fopen($filePathOut, 'w') or die("не удалось открыть файл");
	// \Bitrix\Main\Diag\Debug::dumpToFile(array('$xml->asXML()' => $xml->asXML()), "", "/log.txt");
	fwrite($fd, $xml->asXML());
	fclose($fd);
});
