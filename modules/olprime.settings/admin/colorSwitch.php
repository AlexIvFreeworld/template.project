<?php

###########################
#						  #
# module settings		  #
# @copyright 2018 olprime #
#						  #
###########################

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');

if(!\Bitrix\Main\Loader::includeSharewareModule('olprime.settings'))
	die('Module "olprime.settings" not installed');

use \Bitrix\Main\Config\Option,
	\Bitrix\Main\Localization\Loc,
	\Bitrix\Main\Page\Asset;

Loc::loadMessages(__FILE__);

$APPLICATION->SetTitle(Loc::getMessage('OLPRIME_OPTIONS_TITLE'));
Asset::getInstance()->addJs('/bitrix/js/'.OLPRIME_MODULE_ID.'/script.js');

$arSites = $arTabs = array();
$moduleClass = OLPRIME_MODULE_CLASS;


// RESET OPTIONS
if(check_bitrix_sessid() && $_SERVER['REQUEST_METHOD'] === 'POST' && $_REQUEST['reset'] && $_REQUEST['olprimeTabControl'])
	Option::delete(OLPRIME_MODULE_ID, array('name' => 'NEW_PARAMETRS', 'site_id' => $_REQUEST['siteId_'.$_REQUEST['olprimeTabControl']]));

$dbResult = \Bitrix\Main\SiteTable::getList(
	array(
		'filter' => array('ACTIVE' => 'Y')
	)
);
while($result = $dbResult->fetch())
{
	$arSites[] = $result;
}

foreach($arSites as $key => $arSite)
{
	$parametrsFromAdmin = $moduleClass::getParametrsFromAdmin($arSite['LID']);

	$arTabs[] = array(
		'DIV' => 'edit'.($key + 1),
		'TAB' => Loc::getMessage('OLPRIME_OPTIONS_TAB', array('#SITE_NAME#' => $arSite['NAME'], '#SITE_ID#' => $arSite['LID'])),
		'TITLE' => Loc::getMessage('OLPRIME_OPTIONS_TAB_TITLE'),
		'SITE_ID' => $arSite['LID'],
		'DIR' => $arSite['DIR'],
		'PARAMETRS' => $parametrsFromAdmin,
	);
}

$tabControl = new CAdminTabControl('tabControl', $arTabs);


// UPDATE AND SET PARAMETRS
if(check_bitrix_sessid() && $_SERVER['REQUEST_METHOD'] === 'POST' && ($_REQUEST['save'] || $_REQUEST['apply']))
{
	foreach($arTabs as $key => $arTab)
	{
		foreach($moduleClass::$massivParameters as $sectionCode => $section)
		{
			foreach($section['OPTIONS'] as $elementCode => $element)
			{
				if(!empty($element['LIST']) && $element['TYPE'] !== 'selectbox')
				{
					$arTab['PARAMETRS'][$elementCode] = $_REQUEST[$elementCode.'_'.$arTab['SITE_ID']];
					
					foreach($element['LIST'] as $listCode => $listItem)
					{
						$value = $_REQUEST[$listCode.'_'.$arTab['SITE_ID']];
						
						if($listItem['TYPE'] == 'checkbox')
						{
							if($value !== 'Y' || empty($value))
								$value = 'N';
						}
						
						$arTab['PARAMETRS'][$listCode] = $value;
					}
				}
				else if($element['TYPE'] == 'file')
				{
					if($elementCode == 'LOGO')
					{
						$uploadfile = $_SERVER['DOCUMENT_ROOT'].'/local/templates/water-canal/img/logo_2.png';
						
						if (move_uploaded_file($_FILES[$elementCode.'_'.$arTab['SITE_ID']]['tmp_name'], $uploadfile)) {
							$arTab['PARAMETRS'][$elementCode] = '/img/logo_2.png';
						}
					}
					else
					{
						$uploadfile = $_SERVER['DOCUMENT_ROOT'].'/local/templates/water-canal/img/noPhoto_2.png';
						
						if (move_uploaded_file($_FILES[$elementCode.'_'.$arTab['SITE_ID']]['tmp_name'], $uploadfile)) {
							$arTab['PARAMETRS'][$elementCode] = '/img/noPhoto_2.png';
						}
					}
				}
				else
				{
					$value = $_REQUEST[$elementCode.'_'.$arTab['SITE_ID']];
					
					if($element['TYPE'] == 'checkbox')
					{
						if($value !== 'Y' || empty($value))
							$value = 'N';
					}
					
					if($elementCode === 'COLOR' && $_REQUEST[$elementCode.'_'.$arTab['SITE_ID']] === 'CUSTOM')
					{
						setcookie('OLPRIME_COLOR_CUSTOM_'.$arTab['SITE_ID'], $_REQUEST['OLPRIME_COLOR_CUSTOM_'.$arTab['SITE_ID']], time() + 60 * 60 * 24 * 30, '/');
					}
					
					$arTab['PARAMETRS'][$elementCode] = $value;
				}
			}
		}
		
		Option::set(OLPRIME_MODULE_ID, 'NEW_PARAMETRS', serialize($arTab['PARAMETRS']), $arTab['SITE_ID']);
		$arTabs[$key] = $arTab;
	}
	
	$APPLICATION->RestartBuffer();
}

require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');
$tabControl->Begin();

/***************************************************************************
							HTML
****************************************************************************/
?>

<form method="post" id="olprimeForm" enctype="multipart/form-data" action="<?=$APPLICATION->GetCurPage();?>?lang=<?=LANGUAGE_ID;?>">
	<?=bitrix_sessid_post();?>
	<?php
	foreach($arTabs as $arTab)
	{
		$tabControl->BeginNextTab();
		
		foreach($moduleClass::$massivParameters as $sectionCode => $section)
		{
			?>
			<tr class="heading">
				<td colspan="2"><?=$section['TITLE'];?></td>
			</tr>
			<?php
			foreach($section['OPTIONS'] as $elementCode => $element)
			{
				if($elementCode === 'SORT_ORDER')
				{
					if($arTab['PARAMETRS'][$elementCode])
						$element['DEFAULT'] = $arTab['PARAMETRS'][$elementCode];
					
					echo '<tr>
						<td colspan="2">
							<table class="adm-detail-content-table">
								<tbody class="sortOrder" data-site="'.$arTab['SITE_ID'].'">';
								
					foreach(explode(',', $element['DEFAULT']) as $listItem)
					{
						$elementValue = $element['LIST'][$listItem];
						$elementChecked = ($arTab['PARAMETRS'][$listItem] == 'Y' ? 'checked' : '');
					?>
						<tr class="block<?=($elementValue['DRAG'] == 'N' ? ' no_drag' : '');?>">
							<td class="adm-detail-content-cell-l" width="50%"><?=$elementValue['TITLE'];?></td>
							<td width="50%">
								<input type="checkbox" name="<?=$listItem.'_'.$arTab['SITE_ID'];?>" value="Y" <?=$elementChecked;?> />
								<span class="drag"></span>
							</td>
						</tr>
					<?php
					}
				
					echo '</tbody></table></td></tr>
						<tr><td>
							<input type="hidden" name="'.$elementCode.'_'.$arTab['SITE_ID'].'" value="'.$element['DEFAULT'].'" />
						</td></tr>';
				}
				else
				{
					$elementValue = $arTab['PARAMETRS'][$elementCode];
					$elementChecked = ($elementValue == 'Y' ? 'checked' : '');
				?>
					<tr>
						<td width="50%"><?=$element['TITLE'];?></td>
						<td width="50%">
							<?php if($element['TYPE'] == 'checkbox'):?>
								<input type="checkbox" name="<?=$elementCode.'_'.$arTab['SITE_ID'];?>" value="Y" <?=$elementChecked;?> />
								
							<?php elseif($element['TYPE'] == 'text'):?>
								<input placeholder="<?=$element['DEFAULT'];?>" type="text" name="<?=$elementCode.'_'.$arTab['SITE_ID'];?>" value="<?=$elementValue;?>" maxlength="<?=$element['SIZE'];?>" />
							
							<?php elseif($element['TYPE'] == 'file'):
								$url = '/local/templates/water-canal';
							?>
								<div class="divFile">
									<input type="file" accept="image/png, image/jpeg" name="<?=$elementCode.'_'.$arTab['SITE_ID'];?>" />
									<img src="<?=$url.$elementValue;?>" alt="logo" />
								</div>
								
							<?php elseif($element['TYPE'] === 'selectbox'):?>
								<select name="<?=$elementCode.'_'.$arTab['SITE_ID'];?>">
									<?php foreach($element['LIST'] as $listCode => $listItem):?>
										<option value="<?=$listCode;?>" <?=($elementValue != $listCode ? '' : 'selected');?>>
											<?=$listItem['TITLE'];?>
										</option>
									<?php endforeach;?>
								</select>
							<?php endif;?>
						</td>
					</tr>
				<?php
				}
			}
		}
		
		echo '<input type="hidden" name="siteId_'.$arTab['DIV'].'" value="'.$arTab['SITE_ID'].'" />';
	}
	
	$tabControl->Buttons(array());
	?>
	
	<input type="hidden" id="olprimeTabControl" name="olprimeTabControl" />
	<input type="submit" name="reset" value="<?=Loc::getMessage('OLPRIME_OPTIONS_RESET');?>" />
</form>

<?php $tabControl->End();?>

<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');?>