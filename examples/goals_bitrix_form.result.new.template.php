<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>

<div class="form_wrapper">

	<?if ($arResult["isFormNote"] != "Y") { ?>
		<h3><?=$arResult["FORM_TITLE"]?></h3>

		<?if ($arResult["isFormErrors"] == "Y"):?><?=$arResult["FORM_ERRORS_TEXT"];?><?endif;?>

		<?=$arResult["FORM_HEADER"]?>

		<? foreach ($arResult["QUESTIONS"] as $FIELD_SID => $arQuestion) {
			if ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] == 'hidden') {
				echo $arQuestion["HTML_CODE"];
			} elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] = 'text') { ?>
				<? $link = 'form_'.$arQuestion['STRUCTURE'][0]['FIELD_TYPE'].'_'.$arQuestion["STRUCTURE"][0]["ID"]; ?>
				<input type="text" name="<?=$link?>" placeholder="<?=$arQuestion["CAPTION"]?>" value="<?=$arResult["arrVALUES"][$link]?>" required>
			<? } elseif ($arQuestion['STRUCTURE'][0]['FIELD_TYPE'] = 'textarea') { ?>
				<? $link = 'form_'.$arQuestion['STRUCTURE'][0]['FIELD_TYPE'].'_'.$arQuestion["STRUCTURE"][0]["ID"]; ?>
				<textarea name="<?=$link?>" placeholder="<?=$arQuestion["CAPTION"]?>" required>
					<?=$arResult["arrVALUES"][$link]?>
				</textarea>
			<? } else { ?>
				<?=$arQuestion["HTML_CODE"]?>
			<? } ?>
		<? } ?>

		<? if($arResult["isUseCaptcha"] == "Y") { ?>
            <div class="mb30 text-center-block">
                <input type="hidden" name="captcha_sid" value="<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" />
                <img src="/bitrix/tools/captcha.php?captcha_sid=<?=htmlspecialcharsbx($arResult["CAPTCHACode"]);?>" width="180" height="40" />
                <input type="text" name="captcha_word" size="30" maxlength="50" value="" class="inputtext" />
            </div>
            <script type="text/javascript" src="https://www.google.com/recaptcha/api.js?onload=onloadRecaptchafree&amp;render=explicit&amp;hl=ru"></script>
		<? } ?>

		<div class="text-center">
			<input type="hidden" name="web_form_apply" value="Y" />
			<button class="btn btn-fill" value="<?=htmlspecialcharsbx(strlen(trim($arResult["arForm"]["BUTTON"])) <= 0 ? GetMessage("FORM_ADD") : $arResult["arForm"]["BUTTON"]);?>">Отправить</button>
		</div>

		<?=$arResult["FORM_FOOTER"]?>
	<? } else { ?>
		<h2>Спасибо за заявку</h2><p>В ближайщее время мы с Вами свяжемся</p>
		<?if($arResult["arForm"]["ID"] == 1):?>
		<script>
			ym(30529142, 'reachGoal', 'MakeAppointment');
		</script>
		<?endif;?>
	<? } ?>
</div>