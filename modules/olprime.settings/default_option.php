<?php

###########################
#						  #
# module settings		  #
# @copyright 2018 olprime #
#						  #
###########################

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

Olprime::$massivParameters = array(
	'GENERAL' => array(
		'TITLE' => Loc::getMessage('OLPRIME_GENERAL_TITLE'),
		'OPTIONS' => array(
			'PERSONAL_INFO' => array(
				'TITLE' => Loc::getMessage('OLPRIME_PERSONAL_INFO_TITLE'),
				'TITLE_FOR_SWITCH' => Loc::getMessage('OLPRIME_PERSONAL_INFO_TITLE_FOR_SWITCH'),
				'TEXT_ERROR' => Loc::getMessage('OLPRIME_PERSONAL_INFO_TEXT_ERROR'),
				'TEXT_ONE' => Loc::getMessage('OLPRIME_PERSONAL_INFO_TEXT_ONE'),
				'TEXT_SECOND' => Loc::getMessage('OLPRIME_PERSONAL_INFO_TEXT_SECOND'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
			),
			'FORM_EMAIL' => array(
				'TITLE' => Loc::getMessage('OLPRIME_FORM_EMAIL_TITLE'),
				'TYPE' => 'text',
				'DEFAULT' => 'office@alesp.ru',
			),
			'MAIN_ADDRESS' => array(
				'TITLE' => Loc::getMessage('OLPRIME_MAIN_ADDRESS_TITLE'),
				'TYPE' => 'text',
				'DEFAULT' => Loc::getMessage('OLPRIME_MAIN_ADDRESS_TEXT'),
			),
			'MAIN_PHONE' => array(
				'TITLE' => Loc::getMessage('OLPRIME_MAIN_PHONE_TITLE'),
				'TYPE' => 'text',
				'DEFAULT' => '+7 831 261-00-09',
			),
			'LOGO' => array(
				'TITLE' => Loc::getMessage('OLPRIME_LOGO_TITLE'),
				'TYPE' => 'file',
				'DEFAULT' => '/img/logo.svg',
			),
			'NO_PHOTO' => array(
				'TITLE' => Loc::getMessage('OLPRIME_NO_PHOTO_TITLE'),
				'TYPE' => 'file',
				'DEFAULT' => '/img/no-photo.png',
			),
			'NO_VIDEO' => array(
				'TITLE' => Loc::getMessage('OLPRIME_NO_VIDEO_TITLE'),
				'TYPE' => 'file',
				'DEFAULT' => '/img/no-video.png',
			),
			'MENU_FIXED' => array(
				'TITLE' => Loc::getMessage('OLPRIME_MENU_FIXED_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
			),
			'BUTTON_HELP' => array(
				'TITLE' => Loc::getMessage('OLPRIME_BUTTON_HELP_TITLE'),
				'TYPE' => 'checkbox',
				'DEFAULT' => 'Y',
			),
		)
	),
	'STYLE_SETTINGS' => array(
		'TITLE' => Loc::getMessage('OLPRIME_STYLE_SETTINGS_TITLE'),
		'OPTIONS' => array(
			'COLOR_BLACKBACK' => array(
				'TITLE' => Loc::getMessage('OLPRIME_COLOR_BLACKBACK_TITLE'),
				'TITLE_FOR_SWITCH' => Loc::getMessage('OLPRIME_COLOR_BLACKBACK_TITLE_FOR_SWITCH'),
				'TYPE' => 'text',
				'DEFAULT' => 'rgba(76, 84, 92, .6)',
			),
		)
	),
	'INDEX' => array(
		'TITLE' => Loc::getMessage('OLPRIME_INDEX_TITLE'),
		'OPTIONS' => array(
			'SORT_ORDER' => array(
				'DEFAULT' => 'BLOCK_SEARCH,BLOCK_MESSAGE,BLOCK_SLIDER,BLOCK_THESES,BLOCK_NEWS',
				'TYPE' => 'blockbox',
				'LIST' => array(
					'BLOCK_SEARCH' => array(
						'TITLE' => Loc::getMessage('OLPRIME_BLOCKORDER_SEARCH'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'Y',
					),
					'BLOCK_MESSAGE' => array(
						'TITLE' => Loc::getMessage('OLPRIME_BLOCKORDER_MESSAGE'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'Y',
					),
					'BLOCK_SLIDER' => array(
						'TITLE' => Loc::getMessage('OLPRIME_BLOCKORDER_SLIDER'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'Y',
					),
					'BLOCK_THESES' => array(
						'TITLE' => Loc::getMessage('OLPRIME_BLOCKORDER_THESES'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'Y',
					),
					'BLOCK_NEWS' => array(
						'TITLE' => Loc::getMessage('OLPRIME_BLOCKORDER_NEWS'),
						'TYPE' => 'checkbox',
						'DEFAULT' => 'Y',
					)
				)
			)
		)
	)
);