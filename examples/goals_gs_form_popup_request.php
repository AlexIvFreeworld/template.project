<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use \Bitrix\Main\Loader;
use \Gvozdevsoft\ZavodGs\Settings;
if(Loader::includeModule('gvozdevsoft.zavodgs'))
{
	$managerPhoto = Settings::getFilePath('form_manager_photo',SITE_ID);
	$managerFio = Settings::get('form_manager_fio',SITE_ID,'Сафронова Екатерина');
	$managerPost = Settings::get('form_manager_post',SITE_ID,'Ведущий специалист');
	$managerPhone = Settings::get('form_manager_phone',SITE_ID);
	$managerEmail = Settings::get('form_manager_email',SITE_ID);
	// $managerDesc = Settings::get('form_manager_desc',SITE_ID,'Мы свяжемся с вами в течение 5 минут');
	$managerDesc = 'Заполните заявку и наши специалисты с вами свяжутся.';
	$formManager = Settings::get('form_manager',SITE_ID,'Y');
	$formEmail = Settings::get('form_email',SITE_ID,'N');
	$useCaptcha = Settings::get('form_captcha',SITE_ID,'N');
}
if ($useCaptcha === "Y"){	include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/captcha.php");	$cpt = new CCaptcha();	$captchaPass = COption::GetOptionString("main", "captcha_password", ""); if(strlen($captchaPass) <= 0){$captchaPass = randString(10); COption::SetOptionString("main", "captcha_password", $captchaPass);} $cpt->SetCodeCrypt($captchaPass);}?>
<div class="form-popup-request form-hide" id="form-popup-request">
	<form class="form-popup-request__form feedback" method="post">
    	<input type="hidden" name="code" value="request">
		<div class="container">
			<div class="row">
				<?if($formManager == 'Y'):?>
				<div class="col-md-4">
					<div class="forms-manager">
					<img src="<?if($managerPhoto):?><?=$managerPhoto?><?else:?><?=SITE_DIR?>lib/feedback/logo.png<?endif;?>" alt="Менеджер" class="forms-manager__img r52">
						<?if(false):?>
						<img src="<?if($managerPhoto):?><?=$managerPhoto?><?else:?><?=SITE_DIR?>lib/feedback/manager.png<?endif;?>" alt="Менеджер" class="forms-manager__img">
						<?if($managerFio):?>
						<div class="forms-manager__name">
							<?=$managerFio?>
						</div>
						<?endif;?>
						<?if($managerPost):?>
						<div class="forms-manager__post">
							<?=$managerPost?>
						</div>
						<?endif;?>
						<?if($managerPhone || $managerEmail):?>
						<div class="forms-manager__line"></div>
						<?endif;?>
						<?if($managerPhone):?>
						<div class="forms-manager__phone">
							<?=$managerPhone?>
						</div>
						<?endif;?>
						<?if($managerEmail):?>
						<div class="forms-manager__email">
							<a href="mailto:<?=$managerEmail?>" class="forms-manager__email-link"><?=$managerEmail?></a>
						</div>
						<?endif;?>
						<?endif;?>
					</div>
				</div>
				<?endif;?>
				<div class="<?if($formManager=='Y'):?>col-md-8<?else:?>col-12<?endif;?>">
					<div class="form-popup-request__title">
						Оставить заявку
					</div>
					<?if($managerDesc):?>
					<div class="form-popup-request__text">
						<?=$managerDesc?>
					</div>
					<?endif;?>
					<input type="text" name="form_name" placeholder="Ваше имя" maxlength="50" class="form-popup-request__input">
					<input type="text" name="form_phone" placeholder="Ваш номер телефона*" required="required" class="inputmask form-popup-request__input">
					<?if($formEmail=='Y'):?>
					<input type="text" name="form_email" placeholder="Ваш email" maxlength="50" class="form-popup-request__input">
					<?endif;?>
					<textarea name="form_message" placeholder="Комментарий к заявке*" required="required" rows="3" maxlength="500" class="form-popup-request__textarea"></textarea>
					<div class="feedback-file form-popup-request__file">
						<input type="file" name="files[]" accept=".png,.jpg,.jpeg,.tif,.pdf,.doc,.docx,.xls,.xlsx" multiple class="feedback-file__input">
						<div class="feedback-file__box">
							<div class="feedback-file__icon"></div>
							<div class="feedback-file__text">
								Прикрепить свои файлы
							</div>
						</div>
					</div>
					<?if($useCaptcha === "Y"):?>
					<div class="form-captcha">
						<input name="captcha_code" value="<?=htmlspecialchars($cpt->GetCodeCrypt());?>" type="hidden" />
						<img src="/bitrix/tools/captcha.php?captcha_code=<?=htmlspecialchars($cpt->GetCodeCrypt());?>" class="form-captcha__code"/>
						<input id="captcha_word" placeholder="Код*" required="required" name="captcha_word" type="text" class="form-popup-request__input form-captcha__input" />
					</div>
					<?endif;?>
					<div class="feedback-garant__mess-error"></div>
					<div class="feedback-garant form-popup-request__feedback-garant">
						<div class="feedback-garant__box">
							<input type="checkbox" checked="checked" class="feedback-garant__checkbox">
							<label class="feedback-garant__label">
								Cогласие с <a href="<?=SITE_DIR?>kontakty/politika-konfidentsialnosti/" target="_blank" class="feedback-garant__link">политикой конфиденциальности</a>
							</label>
						</div>	
					</div>
					<button type="submit" class="form-popup-request__btn btn-button" >Отправить</button>
					<div class="feedback-send-message"></div>
				</div>
			</div>
		</div>
	</form>
</div>



