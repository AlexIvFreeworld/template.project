<?php

###########################
#						  #
# module settings	      #
# @copyright 2018 olprime #
#						  #
###########################

IncludeModuleLangFile(__FILE__);

class olprime_settings extends CModule
{
	var $MODULE_ID = 'olprime.settings';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	const partner = 'olprime';
	const solution = 'settings';

	function olprime_settings()
	{
		require(dirname(__FILE__).'/version.php');

		$this->MODULE_VERSION = $arModuleVersion['VERSION'];
		$this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

		$this->MODULE_NAME = GetMessage('OLPRIME_SETTINGS_INSTALL_NAME');
		$this->MODULE_DESCRIPTION = GetMessage('OLPRIME_SETTINGS_INSTALL_DESCRIPTION');
		$this->PARTNER_NAME = GetMessage('OLPRIME_SETTINGS_SPER_PARTNER');
		$this->PARTNER_URI = GetMessage('OLPRIME_SETTINGS_PARTNER_URI');
	}

	function InstallDB($install_wizard = true)
	{
		global $DB, $DBType, $APPLICATION;

        RegisterModule($this->MODULE_ID);
		
		return true;
	}

	function UnInstallDB($arParams = array())
	{
		global $DB, $DBType, $APPLICATION;

		UnRegisterModule($this->MODULE_ID);
		
		return true;
	}
	
	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}
	
	function InstallPublic(){}
	
	function InstallFiles()
	{
		CopyDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin', true, true);
		CopyDirFiles(__DIR__.'/css/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/css/'.$this->MODULE_ID, true, true);
		CopyDirFiles(__DIR__.'/js/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/js/'.$this->MODULE_ID, true, true);
		CopyDirFiles(__DIR__.'/images/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/images/'.$this->MODULE_ID, true, true);
		
		return true;
	}
	
	function UnInstallFiles()
	{
		DeleteDirFiles(__DIR__.'/admin/', $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin');
		DeleteDirFilesEx('/bitrix/css/'.$this->MODULE_ID.'/');
		DeleteDirFilesEx('/bitrix/js/'.$this->MODULE_ID.'/');
		DeleteDirFilesEx('/bitrix/images/'.$this->MODULE_ID.'/');
		
		return true;
	}
	
	function DoInstall()
	{
		global $APPLICATION, $step;

		$this->InstallFiles();
		$this->InstallDB(false);
		$this->InstallEvents();
		$this->InstallPublic();
	}

	function DoUninstall()
	{
 		global $APPLICATION, $step;

		$this->UnInstallDB();
		$this->UnInstallFiles();
		$this->UnInstallEvents();
	}
}