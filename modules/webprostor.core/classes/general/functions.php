<?
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.core/prolog.php");

Class CWebprostorCoreFunctions
{
	public static function dump($data)
	{
		echo '<pre>';
		var_dump($data);
		echo '</pre>';
	}
	
	public static function GenerateRandomCode($lenght = false)
	{
		$min_lenght= 0;
		$max_lenght = 100;
		$bigL = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$smallL = "abcdefghijklmnopqrstuvwxyz";
		$number = "0123456789";
		$bigB = str_shuffle($bigL);
		$smallS = str_shuffle($smallL);
		$numberS = str_shuffle($number);
		$subA = substr($bigB,0,5);
		$subB = substr($bigB,6,5);
		$subC = substr($bigB,10,5);
		$subD = substr($smallS,0,5);
		$subE = substr($smallS,6,5);
		$subF = substr($smallS,10,5);
		$subG = substr($numberS,0,5);
		$subH = substr($numberS,6,5);
		$subI = substr($numberS,10,5);
		$RandCode1 = str_shuffle($subA.$subG.$subD.$subB.$subH.$subF.$subC.$subI.$subE);
		$RandCode2 = str_shuffle($RandCode1);
		$RandCode = $RandCode1.$RandCode2;
		
		if ($lenght>$min_lenght && $lenght<$max_lenght)
		{
			$result = substr($RandCode,0,$lenght);
		}
		else
		{
			$result = $RandCode;
		}
		
		return $result;
	}
	
	public static function GetCorrectWord($num, $str1, $str2, $str3)
	{
		$val = $num % 100;

		if ($val > 10 && $val < 20) return $num .' '. $str3;
		else {
			$val = $num % 10;
			if ($val == 1) return $num .' '. $str1;
			elseif ($val > 1 && $val < 5) return $num .' '. $str2;
			else return $num .' '. $str3;
		}
	}
	
	public static function ReturnBytes($val)
	{
		$val = trim($val);
		$last = strtolower($val[strlen($val)-1]);
		switch($last) {
			case 'g':
				$val *= 1024;
			case 'm':
				$val *= 1024;
			case 'k':
				$val *= 1024;
		}

		return $val;
	}
	
	public static function mb_ucfirst($str)
	{
		$result = '';
		
		if(is_string($str) && strlen($str) > 0)
			$result = mb_strtoupper(mb_substr($str, 0, 1)).mb_substr($str, 1);

		return $result;
	}
	
	public static function ShowFormFields($arFields = Array())
	{
		if(count($arFields))
		{
			foreach($arFields as $arSection)
			{
			if($arSection["LABEL"])
			{
			?>
			<tr class="heading">
				<td colspan="2"><?=$arSection["LABEL"]?></td>
			</tr>
			<?
			}
			if(is_array($arSection["ITEMS"]) && count($arSection["ITEMS"]))
			{
			foreach($arSection["ITEMS"] as $field)
			{
			?>
			<tr<?if($field["REQUIRED"]=="Y"):?> class="adm-detail-required-field"<?endif;?>>
				<?if($field["LABEL"]):?><td width="50%"><?=$field["LABEL"]?>:</td><?endif;?>
				<td<?if(!$field["LABEL"]):?> colspan="2"<?endif;?>>
					<?
					switch($field["TYPE"])
					{
						case("EDITOR"):
							global $APPLICATION;
						?>
						<?$APPLICATION->IncludeComponent(
							"bitrix:fileman.light_editor",
							"",
							Array(
								"CONTENT" => html_entity_decode($field["VALUE"]),
								"INPUT_NAME" => $field["CODE"],
								"INPUT_ID" => $field["ID"],
								"WIDTH" => "100%",
								"HEIGHT" => "300px",
								"RESIZABLE" => "Y",
								"AUTO_RESIZE" => "Y",
								"VIDEO_ALLOW_VIDEO" => "N",
								"USE_FILE_DIALOGS" => "N",
								"ID" => "",	
								"JS_OBJ_NAME" => ""
							)
						);
						?>
						<?
						break;
						case("PICTURE"):
						?>
						<?
						echo CFile::InputFile("PICTURE", $field["PARAMS"]["SIZE"]?$field["PARAMS"]["SIZE"]:20, $field["VALUE"], false, $field["PARAMS"]["MAX_FILE_SIZE"]?$field["PARAMS"]["MAX_FILE_SIZE"]:0, $field["PARAMS"]["FILE_TYPE"]?$field["PARAMS"]["FILE_TYPE"]:"IMAGE", "class=typefile", $field["PARAMS"]["DESCRIPTION_SIZE"]?$field["PARAMS"]["DESCRIPTION_SIZE"]:0, "class=typeinput", "", true, true);
						if (!is_array($field["VALUE"]) && strlen($field["VALUE"])>0 || is_array($field["VALUE"]) && count($field["VALUE"]) > 0):
							?>
							<input type="hidden" name="<?=$field["CODE"]?>_old" value="<?=$field["VALUE"]?>" /><br>
							<?
							if($field["PARAMS"]["SHOW_IMAGE"] != "N")
							{
								echo CFile::ShowImage($field["VALUE"], $field["PARAMS"]["IMAGE_WIDTH"]?$field["PARAMS"]["IMAGE_WIDTH"]:200, $field["PARAMS"]["IMAGE_HEIGHT"]?$field["PARAMS"]["IMAGE_HEIGHT"]:200, "border=0", "", true);
							}
						endif;
						?>
						<?
						break;
						case("USER_GROUP"):
						$arUserGroupList = array();

						$rsUserGroups = CGroup::GetList(($by="sort"), ($order="asc"));
						while ($arGroup = $rsUserGroups->Fetch())
						{
							$arUserGroupList[] = array(
								'ID' => intval($arGroup['ID']),
								'NAME' => htmlspecialcharsbx($arGroup['NAME']),
							);
						}
						?>
					<select name="<?=$field["CODE"]?>" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>" multiple size="8" class="select-search">
					<?
					foreach ($arUserGroupList as &$arOneGroup)
					{
						?><option value="<? echo $arOneGroup["ID"]; ?>" <?if (in_array($arOneGroup["ID"], $field["VALUE"])) echo " selected"?>><? echo "[".$arOneGroup["ID"]."] ".htmlspecialcharsbx($arOneGroup["NAME"]); ?></option><?
					}
					if (isset($arOneGroup))
						unset($arOneGroup);
					?>
					</select>
					<?if($field["PARAMS"]["CHECK_ALL"] == "Y" && $field["PARAMS"]["MULTIPLE"] == "Y") {?>
					&nbsp;<label for="<?=$field["ID"]?>_checkbox"><input type="checkbox" id="<?=$field["ID"]?>_checkbox" > <?=GetMessage("WEBPROSTOR_CORE_FUNCTIONS_SELECT_ALL")?></label>
					<script>
					$(document).ready(function(){
						$('#<?=$field["ID"]?>_checkbox').click(function()
						{
							if($(this).is(':checked'))
							{
								$('select#<?=$field["ID"]?>').find('option').prop("selected",true);
								$('select#<?=$field["ID"]?>').trigger('change');
							}
							else
							{
								$('select#<?=$field["ID"]?>').find('option').prop("selected",false);
								$('select#<?=$field["ID"]?>').trigger('change');
							}
						});
					});
					</script>
					<? } ?>
						<?
						break;
						case("IBLOCK"):
							echo GetIBlockDropDownListEx(
								intVal($field["VALUE"]),
								$field["PARAMS"]["IBLOCK_TYPE_ID"]?$field["PARAMS"]["IBLOCK_TYPE_ID"]:"IBLOCK_TYPE_ID",
								$field["CODE"]?$field["CODE"]:"IBLOCK_ID",
								array(
									"MIN_PERMISSION" => $field["PARAMS"]["MIN_PERMISSION"],
								),
								'',
								'',
								'class="adm-detail-iblock-types select-search"',
								'class="adm-detail-iblock-list select-search"'
							);
							if($field['REFRESH'] == 'Y')
								echo '<button class="ui-btn ui-btn-sm" type="submit" name="apply" value="Y">'.GetMessage("WEBPROSTOR_CORE_FUNCTIONS_APPLY").'</button>';
							break;
						break;
						case("IBLOCK_TREE"):
							?>
							<?if($field["DISABLED"] == "Y") {?><input type="hidden" name="<?=$field["CODE"]?>" value="<?=$field["VALUE"]?>" /><? } ?>
							<select name="<?=$field["CODE"]?>" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>" class="<?=$field["CLASS"]?$field["CLASS"]:"select-search"?>"<?if($field["DISABLED"] == "Y") {?> disabled<? } ?>>
								<?
								if($field["PARAMS"]["ADD_ZERO"] == "Y")
								{
								?>
									<option value=""><?=$field["PARAMS"]["ZERO_LABEL"]?$field["PARAMS"]["ZERO_LABEL"]:GetMessage('WEBPROSTOR_CORE_FUNCTIONS_SELECT_IBLOCK')?></option>
								<?
								}
								$db_iblock_type = CIBlockType::GetList();
								while($ar_iblock_type = $db_iblock_type->Fetch())
								{
									if($arIBType = CIBlockType::GetByIDLang($ar_iblock_type["ID"], LANG))
									{
										$resIblocks = CIBlock::GetList(Array("NAME"=>"ASC"), Array("TYPE"=>$ar_iblock_type["ID"], "MIN_PERMISSION" => $field["PARAMS"]["MIN_PERMISSION"]));
										if(intVal($resIblocks->SelectedRowsCount())>0)
										{
											?>
											<optgroup label="<?echo htmlspecialcharsEx($arIBType["NAME"]);?> [<?=$ar_iblock_type["ID"]?>]">
											<?
											while($iblock = $resIblocks->Fetch()):
											?>
											<option value="<?=$iblock["ID"]?>" <?if($field["VALUE"]==$iblock["ID"]) echo 'selected';?>><?=htmlspecialcharsbx($iblock["NAME"])?> [<?=$iblock["ID"];?>]</option>
											<?
											endwhile;
											?>
											</optgroup>
											<?
									   }
									}
								}
								?>
							</select>
							<?
						break;
						case("BLOG"):
							if (CModule::IncludeModule('blog'))
							{
							?>
							<?if($field["DISABLED"] == "Y") {?><input type="hidden" name="<?=$field["CODE"]?>" value="<?=$field["VALUE"]?>" /><? } ?>
							<select name="<?=$field["CODE"]?>" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>" class="<?=$field["CLASS"]?$field["CLASS"]:"select-search"?>"<?if($field["DISABLED"] == "Y") {?> disabled<? } ?>>
								<?
								if($field["PARAMS"]["ADD_ZERO"] == "Y")
								{
								?>
									<option value=""><?=$field["PARAMS"]["ZERO_LABEL"]?$field["PARAMS"]["ZERO_LABEL"]:GetMessage('WEBPROSTOR_CORE_FUNCTIONS_SELECT_IBLOCK')?></option>
								<?
								}
								$dbBlogs = CBlog::GetList(["NAME" => "ASC"], [], false, false, ["ID", "NAME"]);
								while ($arBlog = $dbBlogs->Fetch())
								{
									?>
									<option value="<?=$arBlog["ID"]?>" <?if($field["VALUE"]==$arBlog["ID"]) echo 'selected';?>><?=htmlspecialcharsbx($arBlog["NAME"])?> [<?=$arBlog["ID"];?>]</option>
									<?
								}
								?>
							</select>
							<?
							}
						break;
						case("HIGHLOAD_BLOCK"):
							if (CModule::IncludeModule('highloadblock'))
							{
								if($field["PARAMS"]["ADD_ZERO"] != "N")
									$HLblocks = Array('' => $field["PARAMS"]["ZERO_LABEL"]?$field["PARAMS"]["ZERO_LABEL"]:GetMessage('WEBPROSTOR_CORE_FUNCTIONS_SELECT_HB'));
								else
									$HLblocks = Array();
								$rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList();
								while($hldata = $rsData->Fetch())
								{
									$rights = \Bitrix\HighloadBlock\HighloadBlockRightsTable::getOperationsName($hldata["ID"]);
									
									if(is_array($rights) && in_array('hl_element_write', $rights))
									{
										$HLblocks[$hldata["ID"]] = htmlspecialcharsbx($hldata["NAME"]).' ['.$hldata["TABLE_NAME"].']';
									}
									
									unset($rights);
								}
						?>
					<?if($field["DISABLED"] == "Y") {?><input type="hidden" name="<?=$field["CODE"]?>" value="<?=$field["VALUE"]?>" /><? } ?>
					<select name="<?=$field["CODE"]?>" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>"<?if($field["REFRESH"] == "Y") {?> onchange="this.form.submit()"<? } ?> class="<?=$field["CLASS"]?$field["CLASS"]:"select-search"?>"<?if($field["PARAMS"]["MULTIPLE"] == "Y") {?> multiple<? } ?><?if($field["DISABLED"] == "Y") {?> disabled<? } ?>>
						<?foreach($HLblocks as $id => $value):?>
						<option value="<?=$id?>" <?if($field["VALUE"]==$id || (is_array($field["VALUE"]) && in_array($id, $field["VALUE"]))) echo 'selected';?>><?=$value?></option>
						<?endforeach;?>
					</select>
					<?if($field["PARAMS"]["CHECK_ALL"] == "Y" && $field["PARAMS"]["MULTIPLE"] == "Y") {?>
					&nbsp;<label for="<?=$field["ID"]?>_checkbox"><input type="checkbox" id="<?=$field["ID"]?>_checkbox" > <?=GetMessage("WEBPROSTOR_CORE_FUNCTIONS_SELECT_ALL")?></label>
					<script>
					$(document).ready(function(){
						$('#<?=$field["ID"]?>_checkbox').click(function()
						{
							if($(this).is(':checked'))
							{
								$('select#<?=$field["ID"]?>').find('option').prop("selected",true);
								$('select#<?=$field["ID"]?>').trigger('change');
							}
							else
							{
								$('select#<?=$field["ID"]?>').find('option').prop("selected",false);
								$('select#<?=$field["ID"]?>').trigger('change');
							}
						});
					});
					</script>
					<? } ?>
						<?
							}
						break;
						case("LIST"):
						case("SELECT"):
						?>
					<?if($field["DISABLED"] == "Y") {?><input type="hidden" name="<?=$field["CODE"]?>" value="<?=$field["VALUE"]?>" /><? } ?>
					<select name="<?=$field["CODE"]?>" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>"<?if($field["REFRESH"] == "Y") {?> onchange="this.form.submit()"<? } ?> class="<?=$field["CLASS"]?$field["CLASS"]:"select-search"?>"<?if($field["PARAMS"]["MULTIPLE"] == "Y") {?> multiple<? } ?><?if($field["DISABLED"] == "Y") {?> disabled<? } ?>>
						<?foreach($field["ITEMS"] as $id => $value):?>
						<option value="<?=$id?>" <?if($field["VALUE"]==$id || (is_array($field["VALUE"]) && in_array($id, $field["VALUE"]))) echo 'selected';?>><?=$value?></option>
						<?endforeach;?>
					</select>
					<?if($field["PARAMS"]["CHECK_ALL"] == "Y" && $field["PARAMS"]["MULTIPLE"] == "Y") {?>
					&nbsp;<label for="<?=$field["ID"]?>_checkbox"><input type="checkbox" id="<?=$field["ID"]?>_checkbox"<?if($field["PARAMS"]["CHECK_ALL_CHECKED"] == "Y") echo ' checked';?>> <?=GetMessage("WEBPROSTOR_CORE_FUNCTIONS_SELECT_ALL")?></label>
					<script>
					$(document).ready(function(){
						$('#<?=$field["ID"]?>_checkbox').click(function()
						{
							if($(this).is(':checked'))
							{
								$('select#<?=$field["ID"]?>').find('option').prop("selected",true);
								$('select#<?=$field["ID"]?>').trigger('change');
							}
							else
							{
								$('select#<?=$field["ID"]?>').find('option').prop("selected",false);
								$('select#<?=$field["ID"]?>').trigger('change');
							}
						});
					});
					</script>
					<? } ?>
						<?
						if($field['REFRESH'] == 'Y')
							echo '<button class="ui-btn ui-btn-sm" type="submit" name="apply" value="Y">'.GetMessage("WEBPROSTOR_CORE_FUNCTIONS_APPLY").'</button>';
						break;
						case("RANGE"):
						?>
					<?=GetMessage("WEBPROSTOR_CORE_FUNCTIONS_FROM")?>: <input type="text" name="<?=$field["CODE"]?>[MIN]" size="3"  maxlength="<?=$field["PARAMS"]["MAXLENGTH"]?>" value="<?=isset($field["VALUE"]["MIN"])?$field["VALUE"]["MIN"]:""?>">
					<?=GetMessage("WEBPROSTOR_CORE_FUNCTIONS_TO")?>: <input type="text" name="<?=$field["CODE"]?>[MAX]" size="3"  maxlength="<?=$field["PARAMS"]["MAXLENGTH"]?>" value="<?=isset($field["VALUE"]["MAX"])?$field["VALUE"]["MAX"]:""?>">
						<?
						break;
						case("TEXT"):
						?>
					<input type="text" placeholder="<?=$field["PARAMS"]["PLACEHOLDER"]?>" name="<?=$field["CODE"]?>" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>" size="<?=$field["PARAMS"]["SIZE"]?>"  maxlength="<?=$field["PARAMS"]["MAXLENGTH"]?>" value="<?=$field["VALUE"]?>"<?if($field["DISABLED"] == "Y") {?> disabled<? } ?><?if(isset($field["PARAMS"]["AUTOCOMPLETE"])) {?> autocomplete="<?=$field["PARAMS"]["AUTOCOMPLETE"]?"on":"off";?>"<? } ?><?if(isset($field["PARAMS"]["LIST"]) && is_array($field["PARAMS"]["LIST"])){?> list="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>_list"<? } ?>>
					<?if(isset($field["PARAMS"]["LIST"]) && is_array($field["PARAMS"]["LIST"])){?>
					<datalist id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>_list">
					<?foreach($field["PARAMS"]["LIST"] as $option){?>
						<option><?=$option?></option>
					<? } ?>
					</datalist>
					<? } ?>
						<?
						break;
						case("HIDDEN"):
						?>
					<input type="hidden" placeholder="<?=$field["PARAMS"]["PLACEHOLDER"]?>" name="<?=$field["CODE"]?>" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>" size="<?=$field["PARAMS"]["SIZE"]?>"  maxlength="<?=$field["PARAMS"]["MAXLENGTH"]?>" value="<?=$field["VALUE"]?>"<?if($field["DISABLED"] == "Y") {?> disabled<? } ?>> <?=$field["VALUE"]?>
						<?
						break;
						case("PASSWORD"):
						?>
					<input type="password" placeholder="<?=$field["PARAMS"]["PLACEHOLDER"]?>" name="<?=$field["CODE"]?>" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>" size="<?=$field["PARAMS"]["SIZE"]?>"  maxlength="<?=$field["PARAMS"]["MAXLENGTH"]?>" value="<?=$field["VALUE"]?>"<?if($field["DISABLED"] == "Y") {?> disabled<? } ?> autocomplete="new-password">
						<?
						break;
						case("TEXTAREA"):
						?>
					<textarea type="text" name="<?=$field["CODE"]?>" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>" cols="<?=$field["PARAMS"]["COLS"]?>" rows="<?=$field["PARAMS"]["ROWS"]?>" maxlength="<?=$field["PARAMS"]["MAXLENGTH"]?>"<?if($field["DISABLED"] == "Y") {?> disabled<? } ?>><?=$field["VALUE"]?></textarea>
						<?
						break;
						case("CALENDAR"):
						?>
					<input type="text" name="<?=$field["CODE"]?>" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>" size="<?=$field["PARAMS"]["SIZE"]?>" value="<?=$field["VALUE"]?>" class="typeinput"<?if($field["DISABLED"] == "Y") {?> disabled<? } ?>>
					<?=Calendar($field["CODE"], "")?>
						<?
						break;
						case("CALENDAR_DATE"):
						?>
					<?=CalendarDate($field["CODE"], $field["VALUE"], $field["PARAMS"]["FORM_NAME"], $field["PARAMS"]["SIZE"]?$field["PARAMS"]["SIZE"]:15, $field["PARAMS"]["CLASS"])?>
						<?
						break;
						case("CALENDAR_PERIOD"):
						?>
					<?=CalendarPeriod($field["CODE"]["FROM"], $field["VALUE"]["FROM"], $field["CODE"]["TO"], $field["VALUE"]["TO"], "", $field["PARAMS"]["SELECT_ENABLED"]?$field["PARAMS"]["SELECT_ENABLED"]:"N", "typeselect", "typeinput", $field["PARAMS"]["SIZE"])?>
						<?
						break;
						case("NUMBER"):
						?>
					<input type="number" class="adm-input" name="<?=$field["CODE"]?>" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>" size="<?=$field["PARAMS"]["SIZE"]?>" min="<?=$field["PARAMS"]["MIN"]?>" max="<?=$field["PARAMS"]["MAX"]?>" step="<?=$field["PARAMS"]["STEP"]?>" maxlength="<?=$field["PARAMS"]["MAXLENGTH"]?>" value="<?=$field["VALUE"]?>"<?if($field["DISABLED"] == "Y") {?> disabled<? } ?>>
						<?
						break;
						case("CHECKBOX"):?>
					<input type="hidden" name="<?=$field["CODE"]?>" value="N">
					<input type="checkbox" name="<?=$field["CODE"]?>" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>" value="<?=($field["VALUE"]?$field["VALUE"]:'N')?>" onClick="javascript:WebprostorCoreCheckActive('<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>')"<?if($field["VALUE"]=="Y") echo ' checked';?>>
						<?
						break;
						case("FILE"):?>
						<input type="file" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>" name="<?=$field["CODE"]?>"<?if($field["PARAMS"]["MULTIPLE"] == "Y") {?> multiple<? } ?> accept="<?=$field["PARAMS"]["ACCEPT"]?>" >
						<?
						break;
						case("BUTTON"):?>
						<a class="ui-btn ui-btn-sm" href="<?=$field["VALUE"]?>" target="<?=$field["PARAMS"]["TARGET"]?$field["PARAMS"]["TARGET"]:"_blank";?>"><span><?=$field["PARAMS"]["TEXT"]?></span></a>
						<?
						break;
						case("WINDOW_DIALOG"):?>
						<script>
						$(document).ready(function(){
							
							function save_<?=$field["ID"]?>()
							{
								var form_<?=$field["ID"]?> = JSON.stringify($( "#<?=$field["CODE"]?>_RESULTS" ).serializeArray());
								
								$("#<?=$field["ID"]?>").val(form_<?=$field["ID"]?>);
								
								$("#dialog_<?=$field["ID"]?>").dialog( "close" );
							}
							
							$("#dialog_<?=$field["ID"]?>").dialog(
								{
									title: "<?=$field["LABEL"]?>",
									autoOpen: false,
									height: <?=$field["PARAMS"]["HEIGHT"]?$field["PARAMS"]["HEIGHT"]:400?>,
									width: <?=$field["PARAMS"]["WIDTH"]?$field["PARAMS"]["WIDTH"]:600?>,
									modal: <?=$field["PARAMS"]["MODAL"] != "N"?"true":"false"?>,
									draggable: <?=$field["PARAMS"]["DRAGGABLE"] != "N"?"true":"false"?>,
									buttons: {
										"<?=GetMessage("WEBPROSTOR_CORE_FUNCTIONS_APPLY");?>": save_<?=$field["ID"]?>,
									}
								}
							);
							
							$( "#open_<?=$field["ID"]?>" ).button().on( "click", function() {

								var dataValues = <?=CUtil::PhpToJsObject($field["DATA"])?>;
								dataValues.INPUT_VALUE = $('input[name=<?=$field["CODE"]?>]').val();
								$.ajax(
								{
									url: "<?=$field["PARAMS"]["AJAX_URL"]?>",
									data: dataValues,
									success: function(data){
										$("#dialog_<?=$field["ID"]?>").html(data);
									}   
								}
								);
								$("#dialog_<?=$field["ID"]?>").dialog( "open" );
							});
						});
						</script>
						<div id="dialog_<?=$field["ID"]?>"></div>
						<input type="hidden" id="<?=$field["ID"]?>" name="<?=$field["CODE"]?>" value="<?=$field["VALUE"]?>">
						<a id="open_<?=$field["ID"]?>" class="ui-btn ui-btn-sm ui-btn-light-border" ><?=GetMessage("WEBPROSTOR_CORE_FUNCTIONS_OPEN");?></a>
						<?
						break;
						case("FILE_DIALOG"):
							$BtnClickFileEvent = $field["CODE"].'_open';
							?>
						<input type="text" name="<?=$field["CODE"]?>" id="<?=$field["ID"]?$field["ID"]:strtolower($field["CODE"])?>" value="<?=$field["VALUE"]?>" placeholder="<?=$field["PARAMS"]["PLACEHOLDER"]?>" size="<?=$field["PARAMS"]["SIZE"]?>" maxlength="<?=$field["PARAMS"]["MAXLENGTH"]?>" />
						<button type="button" class="ui-btn ui-btn-sm ui-btn-icon-add ui-btn-light-border" value="<?echo GetMessage("WEBPROSTOR_CORE_FUNCTIONS_OPEN"); ?>" OnClick="<?=$BtnClickFileEvent?>()"><?echo GetMessage("WEBPROSTOR_CORE_FUNCTIONS_OPEN"); ?></button>
						<?
						CAdminFileDialog::ShowScript(
							array(
								"event" => $BtnClickFileEvent,
								"arResultDest" => array(
									"FORM_NAME" => $field["PARAMS"]["FORM_NAME"],
									"FORM_ELEMENT_NAME" => $field["CODE"],
								) ,
								"arPath" => array(
									"SITE" => SITE_ID,
									"PATH" => $field["PARAMS"]["PATH"],
								) ,
								"select" => $field["PARAMS"]["SELECT"]?$field["PARAMS"]["SELECT"]:"F",
								"operation" => 'O',
								"showUploadTab" => $field["PARAMS"]["ALLOW_UPLOAD"] == "Y"?true:false,
								"showAddToMenuTab" => false,
								"fileFilter" => $field["PARAMS"]["ALLOW_FILE_FORMATS"]?$field["PARAMS"]["ALLOW_FILE_FORMATS"]:"",
								"allowAllFiles" => false,
								"SaveConfig" => false,
							)
						);
						?>
						<?
						break;
						case("NOTE"):
							echo CWebprostorCoreFunctions::showAlertBegin($field["PARAMS"]["TYPE"], $field["PARAMS"]["ICON"]);
							echo $field["VALUE"];
							echo CWebprostorCoreFunctions::showAlertEnd();
						break;
						default:?>
						<?=$field["VALUE"]?>
						<?
						break;
					} 
					?>
					<?
					if($field["DESCRIPTION"])
					{
						//echo ShowJSHint($field["DESCRIPTION"]);
						?>
						<span data-hint="<?=$field["DESCRIPTION"]?>"></span>
						<?
					} 
					?>
				</td>
			</tr>
			<?
			}
			}
			} 
		} 
	}
	
	public static function PrepareProxyList($list = false)
	{
		if(strlen($list)>0)
		{
			$result = Array();
			$list_arr = explode("\n",$list);
			foreach($list_arr as $proxy)
			{
				$proxy = trim($proxy);
				if(strlen($proxy)>0)
				{
					if(strpos($proxy, "|") && strpos($proxy, ":"))
					{
						$proxy_temp = explode('|', $proxy);
						$proxy_temp_ip = explode(':', $proxy_temp[0]);
						$temp_result_ip = Array(
							"ip" => $proxy_temp_ip[0],
							"port" => $proxy_temp_ip[1],
						);
						$proxy_temp_user = explode(':', $proxy_temp[1]);
						$temp_result_user = Array(
							"login" => $proxy_temp_user[0],
							"password" => $proxy_temp_user[1],
						);
						$result[] = array_merge($temp_result_ip, $temp_result_user);
					}
					elseif(strpos($proxy, ":"))
					{
						$proxy_temp = explode(':', $proxy);
						$result[] = Array(
							"ip" => $proxy_temp[0],
							"port" => $proxy_temp[1],
						);
					}
				}
			}
			if(count($result)>0)
				return $result;
		}
		
		return false;
	}
	
	public static function ConvertFileSize($size)
	{
		$unit = ['b','kb','mb','gb','tb','pb'];
		return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
	}
	
	public static function showAlertBegin($type = '', $icon = '')
	{
		echo '<div class="ui-alert ui-alert-'.($type?$type:"default").''.($icon?" ui-alert-icon-".$icon:"").'">';
		echo '<div class="ui-alert-message">';
	}
	
	public static function showAlertEnd()
	{
		echo '</div></div>';
	}
	
	public static function showProgressBar($type = 'default', $text_before = '', $text_after = '', $value = 0, $total = 100)
	{
		echo '
			<div class="ui-progressbar ui-progressbar-'.$type.' ui-progressbar-bg ui-progressbar-lg">
				<div class="ui-progressbar-text-before">
					<strong>'.$text_before.'</strong>
				</div>
				<div class="ui-progressbar-track">
					<div class="ui-progressbar-bar" style="width:'.$value/($total/100).'%;"></div>
				</div>
				<div class="ui-progressbar-text-after">'.$text_after.'</div>
			</div>';
	}
}
?>