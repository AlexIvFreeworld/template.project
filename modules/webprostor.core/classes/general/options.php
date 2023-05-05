<?
IncludeModuleLangFile(__FILE__);

Class CWebprostorCoreOptions
{
	public $arCurOptionValues = array();
	
	private $module_id = '';
	private $arTabs = array();
	private $arGroups = array();
	private $arOptions = array();
	private $need_access_tab = false;
	
	private $main_tab_div_id = 'edit_main_tab';
	const MAIN_TAB_DIV_ID = 'edit_main_tab';
	
	private $access_tab_div_id = 'edit_access_tab';
	const ACCESS_TAB_DIV_ID = 'edit_access_tab';
	
	public function GetTabs()
	{
		$i = 0; $arTabs = Array();
		$rsSites = CSite::GetList($by="sort", $order="asc", Array());
		while ($arSite = $rsSites->Fetch())
		{
			$arTabs[$i] = array(
				'DIV' => $arSite["LID"],
				'TAB' => $arSite["NAME"].' ['.$arSite["LID"].']',
				'ICON' => '',
				'TITLE' => $arSite["NAME"]
			);
	
			$i++;
		}
		
		return $arTabs;
	}
	
	public function GetGroups($groups = Array(), $arTabs = Array(), $additionalGroups = Array())
	{
		$arGroups = Array();
		foreach($arTabs as $i => $tab)
		{
			$groupPrefix = strtoupper($tab["DIV"]);
			foreach($groups as $code => $group)
			{
				$arGroups[$groupPrefix."_".$code] = array(
					'TITLE' => $group, 
					'TAB' => $i,
				);
			}
		}
		if(is_array($additionalGroups) && count($additionalGroups)>0)
		{
			foreach($additionalGroups as $code => $group)
			{
				$arGroups[$code] = array(
					'TITLE' => $group, 
					'TAB' => self::MAIN_TAB_DIV_ID,
				);
			}
		}
		
		return $arGroups;
	}
	
	public function GetOptions($options = Array(), $arTabs = Array(), $additionalOptions = Array())
	{
		$arOptions = Array();
		foreach($arTabs as $i => $tab)
		{
			$groupPrefix = strtoupper($tab["DIV"]);
			foreach($options as $option)
			{
				$arOptions[$groupPrefix."_".$option["CODE"]] = array(
					'GROUP' => $groupPrefix."_".$option["GROUP"],
					'TITLE' => $option["TITLE"],
					'TYPE' => $option["TYPE"],
					'SORT' => $option["SORT"],
					'NOTES' => $option["NOTES"],
					'COLS' => $option["COLS"],
					'ROWS' => $option["ROWS"],
					'REFRESH' => $option["REFRESH"],
					'VALUES' => $option["VALUES"],
					'FIELD_SIZE' => $option["FIELD_SIZE"],
					'FIELD_READONLY' => $option["FIELD_READONLY"],
					'BUTTON_TEXT' => $option["BUTTON_TEXT"],
					'MAXLENGTH' => $option["MAXLENGTH"],
				);
			}
		}
		
		if(is_array($additionalOptions) && count($additionalOptions)>0)
		{
			foreach($additionalOptions as $option)
			{
				$arOptions[$option["CODE"]] = array(
					'GROUP' => $option["GROUP"],
					'TITLE' => $option["TITLE"],
					'TYPE' => $option["TYPE"],
					'SORT' => $option["SORT"],
					'NOTES' => $option["NOTES"],
					'COLS' => $option["COLS"],
					'ROWS' => $option["ROWS"],
					'REFRESH' => $option["REFRESH"],
					'VALUES' => $option["VALUES"],
					'FIELD_SIZE' => $option["FIELD_SIZE"],
					'FIELD_READONLY' => $option["FIELD_READONLY"],
					'BUTTON_TEXT' => $option["BUTTON_TEXT"],
					'MAXLENGTH' => $option["MAXLENGTH"],
				);
			}
		}
		
		return $arOptions;
	}
	
	public function CWebprostorCoreOptions($module_id = false, $arTabs = Array(), $arGroups = Array(), $arOptions = Array(), $need_main_tab = false, $need_access_tab = false)
	{
		$this->module_id = $module_id;
		$this->arTabs = $arTabs;
		$this->arGroups = $arGroups;
		$this->arOptions = $arOptions;
		$this->need_access_tab = $need_access_tab;
		$this->moduleAccessLevel = CMain::GetGroupRight($module_id);
		
		if($need_main_tab)
		{
			$this->arTabs[] = array(
				'DIV' => $this->main_tab_div_id,
				'TAB' => GetMessage("WEBPROSTOR_CORE_OPTIONS_MAIN_TAB_NAME"),
				'ICON' => '',
				'TITLE' => GetMessage("WEBPROSTOR_CORE_OPTIONS_MAIN_TAB_TITLE")
			);
		}
		
		if($need_access_tab)
		{
			$this->arTabs[] = array(
				'DIV' => $this->access_tab_div_id,
				'TAB' => GetMessage("WEBPROSTOR_CORE_OPTIONS_ACCESS_TAB_NAME"),
				'ICON' => '',
				'TITLE' => GetMessage("WEBPROSTOR_CORE_OPTIONS_ACCESS_TAB_TITLE")
			);
		}

		if($_REQUEST['save'] && $this->moduleAccessLevel == "W" && check_bitrix_sessid())
		{
			$this->SaveOptions();
			if($this->need_access_tab)
			{
				$this->SaveGroupRight();
			}
		}
		
		if($_REQUEST['restoreDefault'] && $this->moduleAccessLevel == "W" && check_bitrix_sessid())
		{
			if(strlen($_REQUEST['restoreDefault'])>0)
				$this->RestoreOptions();
		}
		
		$this->GetCurOptionValues();
	}
	
	private function SaveOptions()
	{
		
		foreach($this->arOptions as $opt => $arOptParams)
		{
			if($arOptParams['TYPE'] != 'CUSTOM')
			{
				$val = $_REQUEST[$opt];
	
				if($arOptParams['TYPE'] == 'CHECKBOX' && $val != 'Y')
					$val = 'N';
				elseif(is_array($val))
					$val = serialize($val);

				\Bitrix\Main\Config\Option::set($this->module_id, $opt, $val);
			}
		}
	}
	
	private function RestoreOptions()
	{
		\Bitrix\Main\Config\Option::delete($this->module_id);
	}
    
    private function SaveGroupRight()
    {
		
        CMain::DelGroupRight($this->module_id);  
        $GROUP = $_REQUEST['GROUPS'];
        $RIGHT = $_REQUEST['RIGHTS'];
        
        foreach($GROUP as $k => $v) {
            if($k == 0)
			{
               // \Bitrix\Main\Config\Option::set($this->module_id, 'GROUP_DEFAULT_RIGHT', $RIGHT[0], 'Right for groups by default');       
                \Bitrix\Main\Config\Option::set($this->module_id, 'GROUP_DEFAULT_RIGHT', $RIGHT[0]);       
            } 
            else {
				if($GROUP[$k] && $RIGHT[$k])
					CMain::SetGroupRight($this->module_id, $GROUP[$k], $RIGHT[$k]);                
            }  
        }  
        
                   
    }
	
	private function GetCurOptionValues()
	{
		
		if(is_array($this->arOptions))
		{
			foreach($this->arOptions as $opt => $arOptParams)
			{
				if($arOptParams['TYPE'] != 'CUSTOM')
				{
					$this->arCurOptionValues[$opt] = \Bitrix\Main\Config\Option::get($this->module_id, $opt, $arOptParams['DEFAULT']);
					if(in_array($arOptParams['TYPE'], array('MSELECT')))
						$this->arCurOptionValues[$opt] = unserialize($this->arCurOptionValues[$opt]);
				}
			}
		}
	}
	
	public function ShowHTML()
	{
		global $APPLICATION;
		
		if(isset($_REQUEST["save"]))
		{
			CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("WEBPROSTOR_CORE_OPTIONS_SAVE_SUCCESS"), "TYPE"=>"OK"));
		}
		
		if(isset($_REQUEST["restoreDefault"]))
		{
			CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("WEBPROSTOR_CORE_OPTIONS_RESET_SUCCESS"), "TYPE"=>"OK"));
		}

		$arP = array();
		
		if(is_array($this->arGroups))
		{
			foreach($this->arGroups as $group_id => $group_params)
				$arP[$group_params['TAB']][$group_id] = array();
		}
		
		if(is_array($this->arOptions))
		{
			foreach($this->arOptions as $option => $arOptParams)
			{
				$val = $this->arCurOptionValues[$option];

				if($arOptParams['SORT'] < 0 || !isset($arOptParams['SORT']))
					$arOptParams['SORT'] = 0;
				
				$label = (isset($arOptParams['TITLE']) && $arOptParams['TITLE'] != '') ? $arOptParams['TITLE'] : '';
				$opt = htmlspecialcharsbx($option);

				switch($arOptParams['TYPE'])
				{
					case("IBLOCK"):
						if(CModule::IncludeModule("iblock"))
						{
							$input = GetIBlockDropDownListEx(
								intVal($val),
								'IBLOCK_TYPE_ID',
								$opt,
								array(
									"MIN_PERMISSION" => $arOptParams["MIN_PERMISSION"],
								),
								'',
								'',
								'class="adm-detail-iblock-types select-search"',
								'class="adm-detail-iblock-list select-search"'
							);
							if($arOptParams['REFRESH'] == 'Y')
								$input .= '<input type="submit" name="apply" value="OK" />';
						}
						break;
					case("HIGHLOAD_BLOCK"):
						if(CModule::IncludeModule("highloadblock"))
						{
							$HLblocks = Array(
								'REFERENCE' => Array(
									GetMessage("WEBPROSTOR_CORE_OPTIONS_SELECT"),
								),
								'REFERENCE_ID' => Array(
									""
								)
							);
							$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList();
							while($hldata = $rsData->Fetch())
							{
								$HLblocks["REFERENCE_ID"][] = $hldata["ID"];
								$HLblocks["REFERENCE"][] = htmlspecialcharsbx($hldata["NAME"]).' ['.$hldata["TABLE_NAME"].']';
							}
							$input = SelectBoxFromArray($opt, $HLblocks, $val, '', '', ($arOptParams['REFRESH'] == 'Y' ? true : false), ($arOptParams['REFRESH'] == 'Y' ? $this->module_id : ''));
							if($arOptParams['REFRESH'] == 'Y')
								$input .= '<input type="submit" name="refresh" value="OK" />';
						}
						break;
					case("BLOG"):
						if(CModule::IncludeModule("BLOG"))
						{
							$BLOGS = Array(
								'REFERENCE' => Array(
									GetMessage("WEBPROSTOR_CORE_OPTIONS_SELECT"),
								),
								'REFERENCE_ID' => Array(
									""
								)
							);
							$dbBlogs = CBlog::GetList(["NAME" => "ASC"], [], false, false, ["ID", "NAME"]);
							while ($arBlog = $dbBlogs->Fetch())
							{
								$BLOGS["REFERENCE_ID"][] = $arBlog["ID"];
								$BLOGS["REFERENCE"][] = htmlspecialcharsbx($arBlog["NAME"]).' ['.$arBlog["ID"].']';
							}
							$input = SelectBoxFromArray($opt, $BLOGS, $val, '', '', ($arOptParams['REFRESH'] == 'Y' ? true : false), ($arOptParams['REFRESH'] == 'Y' ? $this->module_id : ''));
							if($arOptParams['REFRESH'] == 'Y')
								$input .= '<input type="submit" name="refresh" value="OK" />';
						}
						break;
					case 'CHECKBOX':
						$input = '<input type="checkbox" name="'.$opt.'" id="'.$opt.'" value="Y"'.($val == 'Y' ? ' checked' : '').' '.($arOptParams['REFRESH'] == 'Y' ? 'onclick="document.forms[\''.$this->module_id.'\'].submit();"' : '').' />';
						break;
					case 'TEXTAREA':
					case 'TEXT':
						if(!isset($arOptParams['COLS']))
							$arOptParams['COLS'] = 25;
						if(!isset($arOptParams['ROWS']))
							$arOptParams['ROWS'] = 5;
						$input = '<textarea cols="'.$arOptParams['COLS'].'" rows="'.$arOptParams['ROWS'].'" name="'.$opt.'">'.htmlspecialcharsbx($val).'</textarea>';
						if($arOptParams['REFRESH'] == 'Y')
							$input .= '<input type="submit" name="refresh" value="OK" />';
						break;
					case 'SELECT':
						$input = SelectBoxFromArray($opt, $arOptParams['VALUES'], $val, '', '', ($arOptParams['REFRESH'] == 'Y' ? true : false), ($arOptParams['REFRESH'] == 'Y' ? $this->module_id : ''));
						if($arOptParams['REFRESH'] == 'Y')
							$input .= '<input type="submit" name="refresh" value="OK" />';
						break;
					case 'MSELECT':
						$input = SelectBoxMFromArray($opt.'[]', $arOptParams['VALUES'], $val);
						if($arOptParams['REFRESH'] == 'Y')
							$input .= '<input type="submit" name="refresh" value="OK" />';
						break;
					case 'COLORPICKER':
						if(!isset($arOptParams['FIELD_SIZE']))
							$arOptParams['FIELD_SIZE'] = 25;
						ob_start();
						echo 	'<input id="__CP_PARAM_'.$opt.'" name="'.$opt.'" size="'.$arOptParams['FIELD_SIZE'].'" value="'.htmlspecialcharsbx($val).'" type="text" style="float: left;" '.($arOptParams['FIELD_READONLY'] == 'Y' ? 'readonly' : '').' />
								<script>
									function onSelect_'.$opt.'(color, objColorPicker)
									{
										var oInput = BX("__CP_PARAM_'.$opt.'");
										oInput.value = color;
									}
								</script>';
						$APPLICATION->IncludeComponent('bitrix:main.colorpicker', '', Array(
								'SHOW_BUTTON' => 'Y',
								'ID' => $opt,
								'NAME' => 'Выбор цвета',
								'ONSELECT' => 'onSelect_'.$opt
							), false
						);
						$input = ob_get_clean();
						if($arOptParams['REFRESH'] == 'Y')
							$input .= '<input type="submit" name="refresh" value="OK" />';
						break;
					case 'FILE':
						if(!isset($arOptParams['FIELD_SIZE']))
							$arOptParams['FIELD_SIZE'] = 25;
						if(!isset($arOptParams['BUTTON_TEXT']))
							$arOptParams['BUTTON_TEXT'] = '...';
						CAdminFileDialog::ShowScript(Array(
							'event' => 'BX_FD_'.$opt,
							'arResultDest' => Array('FUNCTION_NAME' => 'BX_FD_ONRESULT_'.$opt),
							'arPath' => Array(),
							'select' => 'F',
							'operation' => 'O',
							'showUploadTab' => true,
							'showAddToMenuTab' => false,
							'fileFilter' => '',
							'allowAllFiles' => true,
							'SaveConfig' => true
						));
						$input = 	'<input id="__FD_PARAM_'.$opt.'" name="'.$opt.'" size="'.$arOptParams['FIELD_SIZE'].'" value="'.htmlspecialcharsbx($val).'" type="text" style="float: left;" '.($arOptParams['FIELD_READONLY'] == 'Y' ? 'readonly' : '').' />
									<input value="'.$arOptParams['BUTTON_TEXT'].'" type="button" onclick="window.BX_FD_'.$opt.'();" />
									<script>
										setTimeout(function(){
											if (BX("bx_fd_input_'.strtolower($opt).'"))
												BX("bx_fd_input_'.strtolower($opt).'").onclick = window.BX_FD_'.$opt.';
										}, 200);
										window.BX_FD_ONRESULT_'.$opt.' = function(filename, filepath)
										{
											var oInput = BX("__FD_PARAM_'.$opt.'");
											if (typeof filename == "object")
												oInput.value = filename.src;
											else
												oInput.value = (filepath + "/" + filename).replace(/\/\//ig, \'/\');
										}
									</script>';
						if($arOptParams['REFRESH'] == 'Y')
							$input .= '<input type="submit" name="refresh" value="OK" />';
						break;
					case 'SUBMIT':
						$input = '<input type="submit" name="'.$opt.'" value="'.$arOptParams['BUTTON_TEXT'].'">';
						break;
					case 'CUSTOM':
						$input = $arOptParams['VALUE'];
						break;
					default:
						if(!isset($arOptParams['SIZE']))
							$arOptParams['SIZE'] = 25;
						if(!isset($arOptParams['MAXLENGTH']))
							$arOptParams['MAXLENGTH'] = 255;
						$input = '<input type="'.($arOptParams['TYPE'] == 'INT' ? 'number' : ($arOptParams['TYPE'] == 'PASSWORD' ? 'password' :'text')).'" size="'.$arOptParams['SIZE'].'" maxlength="'.$arOptParams['MAXLENGTH'].'" value="'.htmlspecialcharsbx($val).'" name="'.htmlspecialcharsbx($option).'" />';
						if($arOptParams['REFRESH'] == 'Y')
							$input .= '<input type="submit" name="refresh" value="OK" />';
						break;
				}

				if(isset($arOptParams['NOTES']) && $arOptParams['NOTES'] != '')
					$input .= 	'<div class="notes">
									<table cellspacing="0" cellpadding="0" border="0" class="notes">
										<tbody>
											<tr class="top">
												<td class="left"><div class="empty"></div></td>
												<td><div class="empty"></div></td>
												<td class="right"><div class="empty"></div></td>
											</tr>
											<tr>
												<td class="left"><div class="empty"></div></td>
												<td class="content">
													'.$arOptParams['NOTES'].'
												</td>
												<td class="right"><div class="empty"></div></td>
											</tr>
											<tr class="bottom">
												<td class="left"><div class="empty"></div></td>
												<td><div class="empty"></div></td>
												<td class="right"><div class="empty"></div></td>
											</tr>
										</tbody>
									</table>
								</div>';

				$arP[$this->arGroups[$arOptParams['GROUP']]['TAB']][$arOptParams['GROUP']]['OPTIONS'][] = $label != '' ? '<tr><td valign="top" width="50%">'.$label.':</td><td valign="top" width="50%" nowrap>'.$input.'</td></tr>' : '<tr><td valign="top" colspan="2" align="center">'.$input.'</td></tr>';
				$arP[$this->arGroups[$arOptParams['GROUP']]['TAB']][$arOptParams['GROUP']]['OPTIONS_SORT'][] = $arOptParams['SORT'];
			}

			$tabControl = new CAdminTabControl('tabControl', $this->arTabs, true, true);
			$tabControl->Begin();
			echo '<form name="'.$this->module_id.'" method="POST" action="'.$APPLICATION->GetCurPage().'?mid='.$this->module_id.'&lang='.LANGUAGE_ID.'" enctype="multipart/form-data">'.bitrix_sessid_post();

			foreach($arP as $tab => $groups)
			{
				$tabControl->BeginNextTab();

				foreach($groups as $group_id => $group)
				{
					if(is_array($group['OPTIONS_SORT']) && count($group['OPTIONS_SORT']) > 0)
					{
						echo '<tr class="heading"><td colspan="2">'.$this->arGroups[$group_id]['TITLE'].'</td></tr>';
						
						array_multisort($group['OPTIONS_SORT'], $group['OPTIONS']);
						foreach($group['OPTIONS'] as $opt)
							echo $opt;
					}
				}
			}
			
			$module_id = $this->module_id;
			
			if($this->need_access_tab)
			{
				$tabControl->BeginNextTab();
				require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
			}
			
			$moduleAccessLevel = $APPLICATION->GetGroupRight($module_id);
			
			$tabControl->Buttons(
				array(
					"disabled" => ($moduleAccessLevel < "W"),
					"btnApply" => false,
					"back_url" => $_REQUEST["back_url"],
				)
			);
			
			echo '<input type="submit" name="restoreDefault" onclick="return confirm(\''.GetMessage("WEBPROSTOR_CORE_OPTIONS_RESET_TITLE").'\')" value="'.GetMessage("WEBPROSTOR_CORE_OPTIONS_RESET").'"'.(($moduleAccessLevel < "W")?" disabled":"").' />';

			$tabControl->End();
		}
	}
}
?>