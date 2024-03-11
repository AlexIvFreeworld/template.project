<?
IncludeModuleLangFile(__FILE__);

Class CWebprostorSmtpSite extends CMain
{
	private const MODULE_ID = 'webprostor.smtp';
	
	private static function Init()
	{
		global $debug, $add_init, $del_init;
		
		/*$debug = COption::GetOptionString(self::MODULE_ID, "LOG_ERRORS", false);
		if($debug == "N")
			$debug = false;*/
		$debug = false;
		
		$add_init = COption::GetOptionString(self::MODULE_ID, "AUTO_ADD_INIT", false);
		if($add_init == "N")
			$add_init = false;
		
		$del_init = COption::GetOptionString(self::MODULE_ID, "AUTO_DEL_INIT", false);
		if($del_init == "N")
			$del_init = false;
		
		if($debug)
		{
			global $logError;
			$logError = new CWebprostorSmtpLogs;
		}
	}
	
	private static function RenameFileContent($filePath, $values)
	{
		$file_contents = file_get_contents($filePath);
		foreach($values as $code => $value){
			$file_contents = str_replace($code, $value, $file_contents);
		}
		file_put_contents($filePath, $file_contents);
		
		return true;
	}
	
	public static function AddDirInit($siteId = false)
	{
		self::Init();
		global $debug, $logError, $add_init;
		
		if ($add_init) 
		{
			
			if(is_array($siteId) && isset($siteId["LID"]))
				$siteId = $siteId["LID"];
			
			$dirPath = $_SERVER['DOCUMENT_ROOT'].self::chechPhpInterfaceDir().$siteId;
			$filePath = $_SERVER['DOCUMENT_ROOT'].self::chechPhpInterfaceDir().$siteId.'/init.php';
			
			if ($debug) 
			{
				$logFields["SITE_ID"] = $siteId;
				$logFields["ERROR_TEXT"] = GetMessage("ADD_DIR_INIT", Array("#FILE_PATH#" => $filePath));
				$logError->Add($logFields);
			}
			
			if(!is_file($filePath))
			{
				CheckDirPath($dirPath);
				
				if (
					CopyDirFiles($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/".self::MODULE_ID."/install/init.php", $filePath, false) && 
					self::RenameFileContent($filePath, Array("#SITE_ID#" => $siteId)) && 
					self::RenameFileContent($filePath, Array("'#ADDITIONAL_HEADERS#'" => "''"))
				) 
				{
					if($debug)
					{
						$logFields["ERROR_TEXT"] = GetMessage("ADD_DIR_INIT_OK", Array("#FILE_PATH#" => $filePath));
						$logError->Add($logFields);
					}
				}
				else
				{
					if($debug)
					{
						$logFields["ERROR_TEXT"] = GetMessage("ADD_DIR_INIT_ERROR", Array("#FILE_PATH#" => $filePath));
						$logError->Add($logFields);
					}
				}
			}
			else
			{
				if($debug)
				{
					$logFields["ERROR_TEXT"] = GetMessage("ADD_DIR_INIT_EXIST", Array("#FILE_PATH#" => $filePath));
					$logError->Add($logFields);
				}
			}
		}
	}
	
	public static function chechPhpInterfaceDir()
	{
		return file_exists($_SERVER['DOCUMENT_ROOT'].'/local/php_interface/')?"/local/php_interface/":"/bitrix/php_interface/";
	}
	
	public static function DeleteDirInit($siteId = false)
	{
		self::Init();
		global $debug, $logError, $del_init;
		
		if ($del_init) 
		{
		
			$dirPath = $_SERVER['DOCUMENT_ROOT'].self::chechPhpInterfaceDir().$siteId;
			$filePath = $_SERVER['DOCUMENT_ROOT'].self::chechPhpInterfaceDir().$siteId.'/init.php';
			
			if ($debug) 
			{
				$logFields["SITE_ID"] = $siteId;
				$logFields["ERROR_TEXT"] = GetMessage("DELETE_DIR_INIT", Array("#FILE_PATH#" => $filePath));
				$logError->Add($logFields);
			}
			
			if(is_file($filePath))
			{
				if (
					unlink($filePath)
				) 
				{
					if($debug)
					{
						$logFields["ERROR_TEXT"] = GetMessage("DELETE_DIR_INIT_OK", Array("#FILE_PATH#" => $filePath));
						$logError->Add($logFields);
					}
				}
				else
				{
					if($debug)
					{
						$logFields["ERROR_TEXT"] = GetMessage("DELETE_DIR_INIT_ERROR", Array("#FILE_PATH#" => $filePath));
						$logError->Add($logFields);
					}
				}
			}
			
			if(is_dir($dirPath))
			{
				if (
					rmdir($dirPath)
				) 
				{
					if($debug)
					{
						$logFields["ERROR_TEXT"] = GetMessage("DELETE_SITE_DIR_OK", Array("#DIR_PATH#" => $dirPath));
						$logError->Add($logFields);
					}
				}
				else
				{
					if($debug)
					{
						$logFields["ERROR_TEXT"] = GetMessage("DELETE_SITE_ERROR", Array("#DIR_PATH#" => $dirPath));
						$logError->Add($logFields);
					}
				}
			}
		}
	}
}
?>