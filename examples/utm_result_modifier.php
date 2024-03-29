<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die() ?>
<?php

use Bitrix\Main\Loader;
use intec\Core;
use intec\core\helpers\ArrayHelper;
use intec\core\helpers\Html;
use intec\core\helpers\RegExp;
use intec\core\helpers\StringHelper;
use intec\template\Properties;

/**
 * @var array $arResult
 * @var array $arParams
 */
// debug($_SERVER["HTTP_REFERER"]);
// debug($_COOKIE["_ym_uid"]);
if($arResult["isFormNote"] == "Y"){
// \Bitrix\Main\Diag\Debug::dumpToFile(array('$_REQUEST' => $_REQUEST), "", "/log.txt");
// \Bitrix\Main\Diag\Debug::dumpToFile(array('$arResult' => $arResult), "", "/log.txt");

$resUrldecode = urldecode($_SERVER["HTTP_REFERER"]);
$arRefererGet = explode("?",$resUrldecode);
$arReferer = explode("&",$arRefererGet[1]);
// debug($arReferer);
$arRefResult = array();
$arFilterKey = array("utm_source","utm_medium","utm_campaign","utm_content","utm_term");
foreach($arReferer as $arItem){
    $arTmp = explode("=",$arItem);
    if(in_array($arTmp[0],$arFilterKey)){
        $arRefResult[$arTmp[0]] = $arTmp[1];
    }
}
// debug($arRefResult);
// \Bitrix\Main\Diag\Debug::dumpToFile(array('$arRefResult' => $arRefResult), "", "/log.txt");
//формируем массив для передачи в bitrix24
		//данные берем из элемента добавленного инфоблока
		$queryData = http_build_query(array(
			'fields' => array(
				"TITLE" => 'Лид с сайта '.SITE_SERVER_NAME, //Заголовок лида
				"SOURCE_ID" => 'WEB', //Источник лида
				"CREATED_BY_ID" => $_COOKIE["_ym_uid"], // Кем создана
				"UTM_SOURCE" => $arRefResult['utm_source'], //UTM метка
				"UTM_MEDIUM" => $arRefResult['utm_medium'], //UTM метка
				"UTM_CAMPAIGN" => $arRefResult['utm_campaign'], //UTM метка
				"UTM_CONTENT" => $arRefResult['utm_content'], //UTM метка
				"UTM_TERM" => $arRefResult['utm_term'], //UTM метка
			),
			'params' => array("REGISTER_SONET_EVENT" => "Y"), //Говорим, что требуется зарегистрировать новое событие и оповестить всех прчиастных
		));

		//обращаемся к Битрикс24 при помощи функции curl_exec
		//метод crm.lead.add.json добавляет лид
		$rest = 'crm.lead.add.json';
		
		//url берется из созданного вебхука, удалив в нем окончание prifile/
		//и добавив метод $rest на добавление лида
		$queryUrl = 'https://innomed.bitrix24.ru/rest/29/udf3reykklfx9os7/'.$rest;
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $queryUrl,
			CURLOPT_POSTFIELDS => $queryData,
		));
		
		$result = curl_exec($curl);
		curl_close($curl);
		$result = json_decode($result, 1);

		if (array_key_exists('error', $result))
		{
			debug( "Ошибка при сохранении лида: ".$result['error_description']."");
		}
		else
		{
			debug( $result['result']);
		}
}
$arParams = ArrayHelper::merge([
    'CONSENT_URL' => null
], $arParams);

$arResult['CONSENT'] = [
    'SHOW' => false,
    'URL' => null
];

if (!Loader::includeModule('intec.core'))
    return;

$oRequest = Core::$app->request;
$arResult['CONSENT'] = [
    'SHOW' => !defined('EDITOR') ? Properties::get('base-consent') : null,
    'URL' => $arParams['CONSENT_URL']
];

if (!empty($arResult['CONSENT']['URL'])) {
    $arResult['CONSENT']['URL'] = StringHelper::replaceMacros($arResult['CONSENT']['URL'], [
        'SITE_DIR' => SITE_DIR
    ]);
} else {
    $arResult['CONSENT']['SHOW'] = false;
}

if ($arResult['isFormErrors'])
    $arResult['isFormErrors'] = $oRequest->post('web_form_sent') === 'Y';

foreach ($arResult['QUESTIONS'] as &$arQuestion) {
    $arQuestion['HTML_CODE'] = trim($arQuestion['HTML_CODE']);
    $sType = ArrayHelper::getValue($arQuestion, ['STRUCTURE', 0, 'FIELD_TYPE']);

    if ($sType === 'radio' || $sType === 'checkbox') {
        $arFields = explode('<br />', $arQuestion['HTML_CODE']);

        foreach ($arFields as $iIndex => $sField) {
            $arMatches = [];
            $sClass = null;

            if ($sType === 'radio') {
                $arMatches = RegExp::matchesBy('/<label>.*(<input.*?\\/?>).*<\\/label>.*<label[^>]*>(.*)?<\\/label>/is', $arQuestion['HTML_CODE'], false, 0);
                $sClass = 'intec-ui intec-ui-control-radiobox intec-ui-scheme-current';
            } else {
                $arMatches = RegExp::matchesBy('/(<input.*?\\/?>).*<label[^>]*>(.*)?<\\/label>/is', $arQuestion['HTML_CODE'], false, 0);
                $sClass = 'intec-ui intec-ui-control-checkbox intec-ui-scheme-current';
            }

            if (!empty($arMatches))
                $arFields[$iIndex] =
                    Html::beginTag('label', [
                        'class' => $sClass
                    ]).
                        $arMatches[1].
                        Html::tag('span', null, [
                            'class' => 'intec-ui-part-selector'
                        ]).
                        Html::tag('span', $arMatches[2], [
                            'class' => 'intec-ui-part-content'
                        ]).
                    Html::endTag('label');
        }

        $arQuestion['HTML_CODE'] = implode('<br />', $arFields);
    } else {
        $arMatches = RegExp::matchesBy('/^(<(input|select|textarea)[^>]*?class=")([^>]*?)(".*?\\/?>)(.*)/is', $arQuestion['HTML_CODE'], false, 0);
        $sClass = 'intec-ui intec-ui-control-input intec-ui-mod-block intec-ui-mod-round-3 intec-ui-size-2';

        if (!empty($arMatches)) {
            $arQuestion['HTML_CODE'] = $arMatches[1].(!empty($arMatches[3]) ? $arMatches[3] . ' ' : null).$sClass.$arMatches[4].$arMatches[5];
        } else {
            $arMatches = RegExp::matchesBy('/^(<(input|select|textarea)[^>]*?)(\\/?>)(.*)/is', $arQuestion['HTML_CODE'], false, 0);

            if (!empty($arMatches)) {
                $arQuestion['HTML_CODE'] = $arMatches[1].' class="'.$sClass.'"'.$arMatches[3];

                if (!empty($arMatches[4]))
                    $arQuestion['HTML_CODE'] =
                        Html::beginTag('div', [
                            'class' => 'intec-grid intec-grid-nowrap intec-grid-i-h-5 intec-grid-a-v-center'
                        ]).
                            Html::tag('div', $arQuestion['HTML_CODE'], [
                                'class' => 'intec-grid-item intec-grid-item-shrink-1'
                            ]).
                            Html::tag('div', $arMatches[4], [
                                'class' => 'intec-grid-item-auto'
                            ]).
                        Html::endTag('div');
            }
        }
    }
}

unset($sType);
unset($arQuestion);