<?php
/**
 * Aspro:Max module thematics
 * @copyright 2021 Aspro
 */

IncludeModuleLangFile(__FILE__);
$moduleClass = 'CAllcorp3Medc';

// initialize module parametrs list and default values
$moduleClass::$arThematicsList = array(
	'UNIVERSAL' => array(
		'CODE' => 'UNIVERSAL',
		'TITLE' => GetMessage('THEMATIC_UNIVERSAL_TITLE'),
		'DESCRIPTION' => GetMessage('THEMATIC_UNIVERSAL_DESCRIPTION'),
		'PREVIEW_PICTURE' => '/bitrix/images/aspro.allcorp3medc/themes/thematic_preview_universal.png',
		'URL' => '',
		'OPTIONS' => array(
		),
		'PRESETS' => array(
			'DEFAULT' => 498,
			'LIST' => array(
				0 => 498,
				1 => 835,
				2 => 163,
				3 => 762,
			),
		),
	),
);