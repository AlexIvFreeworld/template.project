<?if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();?>

<?
$goalForm = '';
$goalButton = '';
if (!empty($arResult['FORM_GOAL_ID']) && !empty($arResult['FORM_GOAL_NUMBER'])){
    $goalForm = 'onSubmit="yaCounter' . $arResult['FORM_GOAL_NUMBER'] . '.reachGoal(\'' . $arResult['FORM_GOAL_ID'] . '\');"';
//    $goalForm = 'onsubmit="ym('.$arResult['FORM_GOAL_NUMBER'].', "reachGoal", '.$arResult['FORM_GOAL_ID'].'); return true;"';
}
if (!empty($arResult['BUTTON_GOAL_ID']) && !empty($arResult['BUTTON_GOAL_NUMBER'])){
    $goalButton = 'onclick="yaCounter' . $arResult['BUTTON_GOAL_NUMBER'] . '.reachGoal(\'' . $arResult['BUTTON_GOAL_ID'] . '\');"';
//    $goalButton = 'onclick="ym('.$arResult['BUTTON_GOAL_NUMBER'].', "reachGoal", '.$arResult['BUTTON_GOAL_ID'].'); return true;"';
}
?>

<?$frame = $this->createFrame()->begin();?>
<?$frame->beginStub();?>
    <div class="__header">
        <p class="__title"><?=$arResult["FIELDS_DESCRIPTION"]['FORM_HEADER']?></p>
        <p><?=$arResult["FIELDS_DESCRIPTION"]['FORM_DESCRIPTION']?></p>
    </div>
    <div class="__content">
        <form action="<?=$APPLICATION->GetCurPage(false)?>" method="POST" <?=$goalForm?> enctype="multipart/form-data">
        <?//R52::debug(__FILE__)?>
            <?=bitrix_sessid_post()?>
            <?if(!empty($arResult["ERROR_MESSAGE"])) {
                foreach($arResult["ERROR_MESSAGE"] as $v)
                    ShowError($v);echo '<br/>';
            }?>
            <?if(strlen($arResult["OK_MESSAGE"]) > 0) {?>
                <?//R52::debug($arParams)?>
                <div class="form-ok-text" style="color: green;"><?=$arResult["OK_MESSAGE"]?></div>
                <?if($arParams["EVENT_MESSAGE_TYPE"] == "INNET_SERVICES_CONSULTATION"):?>
                    <script>ym(96393505, 'reachGoal', 'OrderService');</script>
                <?endif?>
                <?if($arParams["EVENT_MESSAGE_TYPE"] == "INNET_SERVICES_ORDER"):?>
                    <script>ym(96393505, 'reachGoal', 'OrderService');</script>
                <?endif?>
                <?if($arParams["EVENT_MESSAGE_TYPE"] == "INNET_CALLBACK"):?>
                    <script>ym(96393505, 'reachGoal', 'OrderCall');</script>
                <?endif?>
            <?}?>

            <?foreach ($arResult["FIELDS"] as $code => $field){?>
                <?if ($field['PROPERTY_TYPE'] == 'S' && $field['USER_TYPE'] != 'HTML'){?>
                    <?
                    $mask_phone = '';
                    if ($field['CODE'] == 'PHONE'){
                        $mask_phone = 'js-mask--phone';
                    }
                    ?>
                    <div class="input-wrp">
                        <label for="<?=$field['ID']?>"><?=$field['NAME']?> <?if ($field['IS_REQUIRED'] == 'Y'){?><span>*</span><?}?></label>
                        <input name="<?=$field['CODE']?>" value="<?=$arResult['INNET_FORM'][$code]?>" id="<?=$field['ID']?>" class="textfield <?=$mask_phone?>" type="text" placeholder="<?=$field['NAME']?>"/>
                    </div>
                <?}?>

                <?if ($field['PROPERTY_TYPE'] == 'S' && $field['USER_TYPE'] == 'HTML'){?>
                    <div class="input-wrp">
                        <label for="<?=$field['ID']?>"><?=$field['NAME']?>:</label>
                        <textarea name="<?=$field['CODE']?>" id="<?=$field['ID']?>" class="textfield" placeholder="<?=$field['NAME']?>"><?=$arResult['INNET_FORM'][$code]?></textarea>
                    </div>
                <?}?>

                <?if ($field['PROPERTY_TYPE'] == 'L' && $field['LIST_TYPE'] == 'L'){?>
                    <div class="input-wrp">
                        <label for="<?=$field['ID']?>"><?=$field['NAME']?> <?if ($field['IS_REQUIRED'] == 'Y'){?><span>*</span><?}?></label>
                        <select id="<?=$field['ID']?>" class="js-select" name="<?=$field['CODE']?>" data-dropdown-options='{"theme":"fs-custom", "customClass":"fs-custom-style"}'>
                            <?foreach ($field['PROPERTIES_LIST'] as $list){?>
                                <option value="<?=$list['ID']?>" <?if($_GET[$field['CODE']] == $list['ID']){echo "selected";}?>><?=$list['VALUE']?></option>
                            <?}?>
                        </select>
                    </div>
                    <br/>
                <?}?>

                <?if ($field['PROPERTY_TYPE'] == 'L' && $field['LIST_TYPE'] == 'C'){?>
                    <?foreach ($field['PROPERTIES_LIST'] as $list){?>
                        <label class="custom-checkbox">
                            <input class="input-invisible" name="<?=$field['CODE']?>" type="checkbox" value="<?=$list['ID']?>"/>
                            <i></i><span><?=$list['~VALUE']?></span>
                        </label>
                        <br/>
                    <?}?>
                <?}?>

                <?if ($field['PROPERTY_TYPE'] == 'F'){?>
                    <div class="input-wrp">
                        <label for="<?=$field['ID']?>"><?=$field['NAME']?> <?if ($field['IS_REQUIRED'] == 'Y'){?><span>*</span><?}?></label>
                        <input name="<?=$field['CODE']?>[]" value="<?=$arResult['INNET_FORM'][$code]?>" id="<?=$field['ID']?>" class="textfield" type="file" placeholder="<?=$field['NAME']?>" multiple="multiple"/>
                    </div>
                <?}?>
            <?}?>

            <?if($arParams["USE_CAPTCHA"] == "Y"){?>
                <?if (COption::GetOptionString($GLOBALS['INNET_MODULE_ID'], "innet_use_google_captcha_" . SITE_ID) == 'Y'){?>
                    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

                    <script type="text/javascript">
                        BX.ready(function () {
                            BX.addCustomEvent('onAjaxSuccess', function(){
                                $.getScript('https://www.google.com/recaptcha/api.js', function(){
                                    grecaptcha.reset();
                                });
                            });
                        });
                    </script>
					
                    <div class="input-wrp">
                        <div class="g-recaptcha" data-sitekey="<?=COption::GetOptionString($GLOBALS['INNET_MODULE_ID'], "innet_key_" . SITE_ID)?>"></div>
                    </div>
                <?} else {?>
                    <div class="col col--md-6" style="width: 100%;margin-top: 15px;">
                        <div class="row">
                            <div class="col col--sm-6">
                                <div class="input-wrp">
                                    <div class="captcha">
                                        <img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["capCode"]?>" width="180" height="40" alt="CAPTCHA">
                                    </div>
                                </div>
                            </div>
                            <div class="col col--sm-6">
                                <div class="input-wrp">
                                    <input class="textfield" type="text" name="captcha_word" size="30" maxlength="50" value="">
                                    <input type="hidden" name="captcha_sid" value="<?=$arResult["capCode"]?>">
                                </div>
                            </div>
                        </div>
                    </div>
                <?}?>
            <?}?>

            <div class="text--center">
                <input type="hidden" name="LINK" value="<?=$_SERVER['SERVER_NAME'] . $APPLICATION->GetCurPage()?>">
                <input type="hidden" name="PARAMS_HASH" value="<?=$arResult["PARAMS_HASH"]?>">
                <p><button class="custom-btn custom-btn--style-2" type="submit" name="submit" role="button" value="OK" <?=$goalButton?>><?=$arResult["FIELDS_DESCRIPTION"]['FORM_BUTTON']?></button></p>
            </div>
        </form>
    </div>
<?$frame->beginStub();?>
<?$frame->end();?>