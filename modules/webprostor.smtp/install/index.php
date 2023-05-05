<?
IncludeModuleLangFile(__FILE__);

Class webprostor_smtp extends CModule
{
	const MODULE_ID = 'webprostor.smtp';
	var $MODULE_ID = 'webprostor.smtp'; 
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = GetMessage("WEBPROSTOR_SMTP_MODULE_NAME");
		$this->MODULE_DESCRIPTION = GetMessage("WEBPROSTOR_SMTP_MODULE_DESC");

		$this->PARTNER_NAME = GetMessage("WEBPROSTOR_SMTP_PARTNER_NAME");
		$this->PARTNER_URI = GetMessage("WEBPROSTOR_SMTP_PARTNER_URI");
	}

	function InstallDB($arParams = array())
	{
		global $DB, $APPLICATION;
		$this->errors = false;
		
		$this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/db/".strtolower($DB->type)."/install.sql");
		
		if($this->errors !== false){
			$APPLICATION->ThrowException(implode("<br>", $this->errors));
			return false;
		}
		
		return true;
	}

	function UnInstallDB($arParams = array())
	{
		global $DB, $APPLICATION;
		$this->errors = false;
		
		$this->errors = $DB->RunSQLBatch($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/db/".strtolower($DB->type)."/uninstall.sql");
		
		if($this->errors !== false){
			$APPLICATION->ThrowException(implode("<br>", $this->errors));
			return false;
		}
		
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
	
	private function RenameFileContent($filePath, $values)
	{
		$file_contents = file_get_contents($filePath);
		foreach($values as $code => $value){
			$file_contents = str_replace($code, $value, $file_contents);
		}
		file_put_contents($filePath, $file_contents);
		
		return true;
	}
	
	private function CheckInitFile($siteId = false, $root = false)
	{
		$php_interface_dir = (file_exists($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/')?"/local/php_interface/":"/bitrix/php_interface/");
		if($root)
			$filePath = $_SERVER['DOCUMENT_ROOT'].$php_interface_dir.'init.php';
		else
			$filePath = $_SERVER['DOCUMENT_ROOT'].$php_interface_dir.$siteId.'/init.php';
		
		if(!is_file($filePath))
		{
			CopyDirFiles($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".self::MODULE_ID."/install/init.php", $filePath, false);
			self::RenameFileContent($filePath, Array("#SITE_ID#" => ($root?"":$siteId)));
		}
	}
	
	function InstallInit()
	{
		$rsSites = CSite::GetList($siteby="sort", $siteorder="asc", Array());
		$sites = array();
		while ($arSite = $rsSites->Fetch())
		{
			$sites[] = $arSite["LID"];
		}
		if(count($sites)>1)
		{
			foreach($sites as $siteId)
			{
				self::CheckInitFile($siteId);
			}
		}
		elseif(count($sites) == 1)
		{
			self::CheckInitFile($sites[0], true);
		}
	}
	
	function UnInstallInit()
	{
		
	}

	function InstallFiles($arParams = array())
	{
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/admin'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.' || $item == 'menu.php')
						continue;
					file_put_contents($file = $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.self::MODULE_ID.'_'.$item,
					'<'.'? require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/'.self::MODULE_ID.'/admin/'.$item.'");?'.'>');
				}
				closedir($dir);
			}
		}
		CopyDirFiles($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".self::MODULE_ID."/install/bitrix/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/", true, true);
		return true;
	}

	function UnInstallFiles()
	{
		if (is_dir($p = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID.'/admin'))
		{
			if ($dir = opendir($p))
			{
				while (false !== $item = readdir($dir))
				{
					if ($item == '..' || $item == '.')
						continue;
					unlink($_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/'.self::MODULE_ID.'_'.$item);
				}
				closedir($dir);
			}
		}
		DeleteDirFiles($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.self::MODULE_ID."/install/bitrix/", $_SERVER["DOCUMENT_ROOT"]."/bitrix/");
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
		
		if(!CModule::IncludeModule("webprostor.core"))
		{
			$APPLICATION->IncludeAdminFile(GetMessage("WEBPROSTOR_CORE_ERROR"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/webprostor.core.php");
		}
		
		if(
			$this->InstallDB()
			&& $this->InstallEvents()
			&& $this->InstallFiles()
		)
		{
			COption::SetOptionString("main", "fill_to_mail", "Y");
			RegisterModule(self::MODULE_ID);
			RegisterModuleDependences('main', 'OnBeforeSiteAdd', self::MODULE_ID, 'CWebprostorSmtpSite', 'AddDirInit');
			RegisterModuleDependences('main', 'OnSiteDelete', self::MODULE_ID, 'CWebprostorSmtpSite', 'DeleteDirInit');
			$this->InstallInit();
			return true;
		}
		else
			return false;
	}

	function DoUninstall()
	{
		global $APPLICATION;
		
		if(
			$this->UnInstallDB() 
			&& $this->UnInstallEvents() 
			&& $this->UnInstallFiles()
		)
		{
			COption::RemoveOption(self::MODULE_ID);
			CAdminNotify::DeleteByModule(self::MODULE_ID);
			UnRegisterModule(self::MODULE_ID);
			UnRegisterModuleDependences('main', 'OnBeforeSiteAdd', self::MODULE_ID, 'CWebprostorSmtpSite', 'AddDirInit');
			UnRegisterModuleDependences('main', 'OnSiteDelete', self::MODULE_ID, 'CWebprostorSmtpSite', 'DeleteDirInit');

			$GLOBALS["APPLICATION"]->IncludeAdminFile(GetMessage("WEBPROSTOR_SMTP_DELETE_MODULE"), $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/uninstall.php");
		}
		else
			return false;
	}
}
?>
