<?global $USER;?>
<?Bitrix\Main\Page\Frame::getInstance()->startDynamicWithID("form-block".$arParams["WEB_FORM_ID"]);?>
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
				$('.form.inline input[data-sid=CLIENT_NAME], .form.inline input[data-sid=FIO], .form.inline input[data-sid=NAME]').val('<?=$fio?>').addClass('input-filed');
			<?endif;?>
			<?if ($phone):?>
				$('.form.inline input[data-sid=PHONE]').val('<?=$phone?>').addClass('input-filed');
			<?endif;?>
			<?if ($email):?>
				$('.form.inline input[data-sid=EMAIL]').val('<?=$email?>').addClass('input-filed');
			<?endif;?>
		}
		catch(e){
		}
	});
	</script>
<?endif;?>
<?Bitrix\Main\Page\Frame::getInstance()->finishDynamicWithID("form-block".$arParams["WEB_FORM_ID"], "");?>