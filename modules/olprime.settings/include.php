<?php

###########################
#						  #
# module settings		  #
# @copyright 2018 olprime #
#						  #
###########################

if(!defined('OLPRIME_MODULE_ID'))
	define('OLPRIME_MODULE_ID', 'olprime.settings');

if(!defined('OLPRIME_MODULE_CLASS'))
	define('OLPRIME_MODULE_CLASS', 'Olprime');

if(!defined('OLPRIME_MODULE_TEMPLATE'))
	define('OLPRIME_MODULE_TEMPLATE', 'settings');

\Bitrix\Main\Loader::registerAutoLoadClasses(
	OLPRIME_MODULE_ID,
	array(
		OLPRIME_MODULE_CLASS => 'classes/general/main.php'
	)
);