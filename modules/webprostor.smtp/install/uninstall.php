<?
if(!check_bitrix_sessid()) return;
IncludeModuleLangFile(__FILE__);

$filePath = $_SERVER['DOCUMENT_ROOT'].'/bitrix/php_interface/init.php';
if(is_file($filePath))
	$inits = array('/bitrix/php_interface/init.php');
else
	$inits = array();

$rsSites = CSite::GetList($siteby="LID", $siteorder="asc", Array());
while ($arSite = $rsSites->Fetch())
{
	$init = '/bitrix/php_interface/'.$arSite["LID"].'/init.php';
	$filePath = $_SERVER['DOCUMENT_ROOT'].$init;
	if(is_file($filePath))
	{
		$inits[] = $init;
	}
}
?>

<?CAdminMessage::ShowMessage(array("MESSAGE"=>GetMessage("WEBPROSTOR_SMTP_DELETE_RESULT"), "TYPE"=>"OK"));?>

<?if(count($inits)>0) { ?>
<?CAdminMessage::ShowMessage(GetMessage("WEBPROSTOR_SMTP_CHECK_INIT_FILES"))?>
<div style="padding: 10px 20px; border: 1px solid yellow; background: #fff; margin-bottom: 10px;">
	<?
	foreach($inits as $init)
	{
		echo $init.'<br />';
	}
	?>
</div>
<? } ?>
<form action="<?=$APPLICATION->GetCurPage();?>">
	<input type="hidden" name="lang" value="<?=LANG;?>">
	<input type="submit" name="" value="<?=GetMessage("MOD_BACK");?>">
<form>