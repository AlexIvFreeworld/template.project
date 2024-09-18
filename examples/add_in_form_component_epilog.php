<?global $USER;?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-block".$arParams["IBLOCK_ID"]);?>
<?if($USER->IsAuthorized()):?>
	<?
	$dbRes = CUser::GetList(($by = "id"), ($order = "asc"), array("ID" => $USER->GetID()), array("FIELDS" => array("ID", "PERSONAL_PHONE")));
	$arUser = $dbRes->Fetch();

    $fio = $USER->GetFullName();
	$phone = $arUser['PERSONAL_PHONE'];
	$email = $USER->GetEmail();
	?>
	<script type="text/javascript">
	$(document).ready(function() {
		try{
            <?if ($fio):?>
                $('.form.inline input[id=CLIENT_NAME], .form.inline input[id=FIO], .form.inline input[id=NAME]').val('<?=$USER->GetFullName()?>');
            <?endif;?>
            <?if ($phone):?>
                $('.form.inline input[id=PHONE]').val('<?=$arUser['PERSONAL_PHONE']?>');
            <?endif;?>
            <?if ($email):?>
                $('.form.inline input[id=EMAIL]').val('<?=$USER->GetEmail()?>');
            <?endif;?>
		}
		catch(e){
		}
	});
	</script>
<?endif;?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-block".$arParams["IBLOCK_ID"], "");?>