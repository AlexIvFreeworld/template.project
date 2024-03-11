<?
IncludeModuleLangFile(__FILE__);

class CWebprostorSmtpLogs
{
	var $DB_NAME = "webprostor_smtp_logs";
	var $LAST_ERROR = "";
	var $LAST_MESSAGE = "";
	
	public function GetList($arOrder = Array("SORT"=>"ASC"), $arFilter = false, $arSelect = false, $limit = false)
	{
		global $DB;
		
		if(is_array($arSelect) && count($arSelect) > 0)
		{
			$strSelect = '';
			foreach($arSelect as $by=>$select)
			{
				switch($select)
				{
					case("ID"):
						$strSelect .= "ID, ";
						break;
					case("SITE_ID"):
						$strSelect .= "SITE_ID, ";
						break;
					case("DATE_CREATE"):
						$strSelect .= "DATE_CREATE, ";
						break;
					case("ERROR_TEXT"):
						$strSelect .= "ERROR_TEXT, ";
						break;
					case("SEND_INFO"):
						$strSelect .= "SEND_INFO, ";
						break;
					case("MODULE_ID"):
						$strSelect .= "MODULE_ID, ";
						break;
					case("RECIPIENTS"):
						$strSelect .= "RECIPIENTS, ";
						break;
					case("SENDED"):
						$strSelect .= "SENDED, ";
						break;
					case("RETRY_COUNT"):
						$strSelect .= "RETRY_COUNT, ";
						break;
					case("SOURCE_SUBJECT"):
						$strSelect .= "SOURCE_SUBJECT, ";
						break;
				}
			}
			$strSelect = trim($strSelect, ", ");
		}
		else
			$strSelect = '*';
		
		$strSql = "
			SELECT
				{$strSelect}
			FROM `{$this->DB_NAME}`
		";
		
		$arSqlWhere = Array();
		if(is_array($arFilter) && count($arFilter)>0)
		{
			foreach($arFilter as $prop=>$value)
			{
				$prop = strtoupper($prop);
				
				if ($value)
				{
					if ($prop == "ID") $arSqlWhere[$prop] = ' `ID` = "'.$value.'" ';
					elseif ($prop == "SITE_ID") $arSqlWhere[$prop] = ' `SITE_ID` = "'.$value.'" ';
					elseif ($prop == "DATE_CREATE") $arSqlWhere[$prop] = ' `DATE_CREATE` = "'.ConvertDateTime($value, "YYYY-MM-DD HH:MI:SS", "ru").'" ';
					elseif ($prop == ">=DATE_CREATE") $arSqlWhere[$prop] = ' `DATE_CREATE` >= "'.ConvertDateTime($value, "YYYY-MM-DD HH:MI:SS", "ru").'" ';
					elseif ($prop == "<=DATE_CREATE") $arSqlWhere[$prop] = ' `DATE_CREATE` <= "'.ConvertDateTime($value, "YYYY-MM-DD HH:MI:SS", "ru").'" ';
					elseif ($prop == "ERROR_TEXT") $arSqlWhere[$prop] = " `ERROR_TEXT` = '".$value."' ";
					elseif ($prop == "?ERROR_TEXT") $arSqlWhere[$prop] = " `ERROR_TEXT` LIKE '%".$value."%' ";
					elseif ($prop == "SEND_INFO") $arSqlWhere[$prop] = ' `SEND_INFO` = "'.$value.'" ';
					elseif ($prop == "MODULE_ID") $arSqlWhere[$prop] = ' `MODULE_ID` = "'.$value.'" ';
					elseif ($prop == "RECIPIENTS") $arSqlWhere[$prop] = " `RECIPIENTS` = '".$value."' ";
					elseif ($prop == "?RECIPIENTS") $arSqlWhere[$prop] = " `RECIPIENTS` LIKE '%".$value."%' ";
					elseif ($prop == "SENDED") $arSqlWhere[$prop] = ' `SENDED` = "'.$value.'" ';
					elseif ($prop == "RETRY_COUNT") $arSqlWhere[$prop] = ' `RETRY_COUNT` = "'.$value.'" ';
					elseif ($prop == "<RETRY_COUNT") $arSqlWhere[$prop] = ' `RETRY_COUNT` < "'.$value.'" ';
				}
			}
		}

		if(is_array($arSqlWhere) && count($arSqlWhere) > 0)
			$strSqlWhere = " WHERE ".implode("AND", $arSqlWhere);
		else
			$strSqlWhere = "";
		
		$arSqlOrder = Array();
		if(is_array($arOrder))
		{
			foreach($arOrder as $by=>$order)
			{
				$by = strtoupper($by);
				$order = strtoupper($order);
				if ($order!="ASC")
					$order = "DESC";
				
				if ($by == "ID") $arSqlOrder[$by] = " `ID` ".$order." ";
				elseif ($by == "SITE_ID") $arSqlOrder[$by] = " `SITE_ID` ".$order." ";
				elseif ($by == "DATE_CREATE") $arSqlOrder[$by] = " `DATE_CREATE` ".$order." ";
				elseif ($by == "ERROR_TEXT") $arSqlOrder[$by] = " `ERROR_TEXT` ".$order." ";
				elseif ($by == "SEND_INFO") $arSqlOrder[$by] = " `SEND_INFO` ".$order." ";
				elseif ($by == "MODULE_ID") $arSqlOrder[$by] = " `MODULE_ID` ".$order." ";
				elseif ($by == "RECIPIENTS") $arSqlOrder[$by] = " `RECIPIENTS` ".$order." ";
				elseif ($by == "SENDED") $arSqlOrder[$by] = " `SENDED` ".$order." ";
				elseif ($by == "RETRY_COUNT") $arSqlOrder[$by] = " `RETRY_COUNT` ".$order." ";
			}
		}

		if(is_array($arSqlOrder) && count($arSqlOrder) > 0)
			$strSqlOrder = " ORDER BY ".implode(",", $arSqlOrder);
		else
			$strSqlOrder = "";
		
		if($limit > 0)
			$strLimit = ' LIMIT '.$limit;
		else
			$strLimit = "";
		
		$res = $DB->Query($strSql.$strSqlWhere.$strSqlOrder.$strLimit, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		return $res;
		
	}
	
	public function GetById($ID)
	{
		global $DB;
		$ID = IntVal($ID);
		$strSql = '
			SELECT 
				*
			FROM 
				`'.$this->DB_NAME.'`
			WHERE 
				`ID` = "'.$ID.'"
		';
		$res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		return $res;
		
	}
	
	private function CheckFields($arFields, $ID = false)
	{
		global $DB;
		
		$this->LAST_ERROR = "";
		$aMsg = array();
		
		if(!empty($aMsg))
		{
			$e = new CAdminException($aMsg);
			$GLOBALS["APPLICATION"]->ThrowException($e);
			$this->LAST_ERROR = $e->GetString();
			return false;
		}
		return true;
	}
	
	function Add($arFields)
	{
		global $DB;
		
		if(!$this->CheckFields($arFields))
			return false;
		
		$strUpdate = $DB->PrepareInsert($this->DB_NAME, $arFields);
		if($strUpdate != "")
		{
			$strSql = "
			INSERT INTO 
				`{$this->DB_NAME}` 
			(".$strUpdate[0].") values(".$strUpdate[1].")
			";
			if(!$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__))
				return false;
		}
		
		return intval($DB->LastID());
		
	}
	
	public function Update($ID, $arFields)
	{
		global $DB;
		$ID = IntVal($ID);
		
		if(!$this->CheckFields($arFields, $ID))
			return false;
		
		$strUpdate = $DB->PrepareUpdate($this->DB_NAME, $arFields);
		if($strUpdate != "")
		{
			$strSql = '
			UPDATE 
				`'.$this->DB_NAME.'` 
			SET '.$strUpdate.'
			WHERE 
				ID = "'.$DB->ForSql($ID).'"
			';
			if(!$DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__))
				return false;
		}
		
		return true;
		
	}
	
	function Delete($ID)
	{
		global $DB;
		$ID = IntVal($ID);
		
		$DB->StartTransaction();
		$strSql = '
			DELETE 
			FROM 
				`'.$this->DB_NAME.'` 
			WHERE 
				`ID` = "'.$ID.'"
		';
		$res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		
		if($res)
			$DB->Commit();
		else
			$DB->Rollback();

		return $res;
	}
	
	function ClearLogs()
	{
		global $DB;
		
		$DB->StartTransaction();
		$strSql = '
			TRUNCATE 
				`'.$this->DB_NAME.'`
		';
		$res = $DB->Query($strSql, false, "File: ".__FILE__."<br>Line: ".__LINE__);
		
		if($res)
			$DB->Commit();
		else
			$DB->Rollback();

		return $res;
	}
}
?>