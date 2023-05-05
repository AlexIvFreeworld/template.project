<?php

###########################
#						  #
# module settings		  #
# @copyright 2018 olprime #
#						  #
###########################

require_once(__DIR__.'/../../default_option.php');

use \Bitrix\Main\Config\Option;

class Olprime
{
	static $massivParameters;

	function arPrint($array)
	{
		echo '<pre>';
		print_r($array);
		echo '</pre>';
	}
	
	function in_multiarray($needle, $haystack, $strict = false)
	{
		if(is_array($haystack))
		{
			foreach ($haystack as $item)
			{
				if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && self::in_multiarray($needle, $item, $strict)))
				{
					return true;
				}
			}
		}

		return false;
	}
	
	function getParametrsFromAdmin($siteID)
	{
		$defaultOptions = $newOptions = array();
		
		// GET DEFAULT OPTIONS
		if(!empty(self::$massivParameters) && is_array(self::$massivParameters))
		{
			foreach(self::$massivParameters as $sectionCode => $section)
			{
				foreach($section['OPTIONS'] as $elementCode => $element)
				{
					if(!empty($element['LIST']) && $element['TYPE'] !== 'selectbox')
					{
						$defaultOptions[$elementCode] = $element['DEFAULT'];
						
						foreach($element['LIST'] as $listCode => $listItem)
							$defaultOptions[$listCode] = $listItem['DEFAULT'];
					}
					else
						$defaultOptions[$elementCode] = $element['DEFAULT'];
				}
			}
		}
		
		$newOptions = unserialize(Option::getRealValue(OLPRIME_MODULE_ID, 'NEW_PARAMETRS', $siteID));
		
		if(!empty($newOptions) && is_array($newOptions))
		{
			foreach($newOptions as $elementCode => $element)
			{
				if(!isset($defaultOptions[$elementCode]))
				{
					unset($newOptions[$elementCode]);
				}
			}
		}
		
		if(!empty($defaultOptions) && is_array($defaultOptions))
		{
			foreach($defaultOptions as $elementCode => $element)
			{
				if(!isset($newOptions[$elementCode]))
				{
					$newOptions[$elementCode] = $element;
				}
			}
		}

		return $newOptions;
	}
	
	function updateThemes($colorCustom, $siteID)
	{
		$lastCustomColor = Option::get(OLPRIME_MODULE_ID, 'lastCustomColor', '', $siteID);

		if($lastCustomColor != $colorCustom && defined('SITE_TEMPLATE_PATH') && !empty($colorCustom))
		{
			if(!class_exists('lessc'))
				require_once('lessc.inc.php');

			$less = new lessc;

			try
			{
				$themeDirPath = $_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/themes/custom/';	
				
				if(!is_dir($themeDirPath))
					mkdir($themeDirPath, 0755, true);		

				$less->setVariables(array('mainColor' => $colorCustom));
				$newColor = $less->compileFile(__DIR__.'/../../css/style.less', $themeDirPath.'/style.css');
				
				if($newColor)
					Option::set(OLPRIME_MODULE_ID, 'lastCustomColor', $colorCustom, $siteID);
			}
			catch(exception $e)
			{
				echo 'Fatal error: '.$e->getMessage();
				die();
			}
		}
	}

	function GetIBlockID($IBlockType, $SiteID, $IBlockCode)
    {

        \CModule::IncludeModule("iblock");

        $resIblock = \CIBlock::GetList(
            Array(), 
            Array(
                "TYPE" => $IBlockType, 
                "SITE_ID" => $SiteID,
                "CODE" => $IBlockCode
            ), true
        );

        while($arIblock = $resIblock->Fetch()) $iblockID = $arIblock["ID"];

        return $iblockID;

    } 
}