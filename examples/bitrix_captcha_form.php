<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
include_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/classes/general/captcha.php");
$cpt = new CCaptcha();
$captchaPass = COption::GetOptionString("main", "captcha_password", "");
if (strlen($captchaPass) <= 0) {
	$captchaPass = randString(10);
	COption::SetOptionString("main", "captcha_password", $captchaPass);
}
$cpt->SetCodeCrypt($captchaPass);
$APPLICATION->SetTitle("");
$services = array(
	array(
		"repairinstr",
		"Ремонт и поверка средств измерений",
		array(
			"Ремонт и поверка средств измерений",
		),
	),

	array(
		"hidraulic",
		"Гидравлические расчёты и испытания",
		array(
			"Гидравлические расчёты и испытания",
		),
	),
	array(
		"rent",
		"Услуги аренды",
		array(
			"Аренда транспорта и спецтехники",
			"Аренда мест и площадей",
			"Аренда сооружений для размещения рекламы"
		),
	),
	array(
		"approvals",
		"Согласования",
		array(
			'Проверка предоставленной Заказчиком выдержки из топографического плана города Нижнего Новгорода в на предмет соответствия фактическому наличию, расположению и техническому описанию инженерных сетей, находящихся в эксплуатации АО "Теплоэнерго", в объеме 1 лист формата А4',
			'Проверка предоставленной Заказчиком выдержки из топографического плана города Нижнего Новгорода в на предмет соответствия фактическому наличию, расположению и техническому описанию инженерных сетей, находящихся в эксплуатации АО "Теплоэнерго", в объеме 1 лист формата А4',
			'Рассмотрение для выдачи согласования проекта производства земляных работ',
			'Подготовка дубликата проекта по заявке правообладателя земельного участка, здания, сооружения',
			'Подготовка скан-версии проекта (листов форматов А0-А4) по заявке правообладателя земельного участка, здания, сооружения',
			'Рассмотрение с выдачей заключения по заявкам сторонних организаций проектной документации переустройства (изменения трассировки) тепловых сетей, расположенных на территории г.Нижнего Новгорода в зоне действия ЕТО',
		),
	),
	array(
		"nondestrtest",
		"Работы по неразрушающему контролю",
		array(
			'Ультразвуковой контроль сварных соединений (кольцевой шов)',
			'Ультразвуковой контроль сварных соединений (продольный шов)',
			'Визуально-измерительный контроль сварных соединений',
			'Измерение толщины металла ультразвуковым способом',
			'Поиск места повреждения на трубопроводе теплотрассы с поверхности грунта',
			'Поиск места повреждения на трубопроводе теплотрассы корреляционным методом',
			'Капиллярный контроль (цветной метод)',
		),
	),
	array(
		"eleclab",
		"Электротехническая лаборатория",
		array(
			'Испытания средств защиты из диэлектрической резины.',
			'Испытания изолирующих штанг и клещей, указателей напряжения, электроизмерительных клещей.',
			'Испытания слесарно-монтажного инструмента с изолирующими рукоятками.',
			'Измерения сопротивления заземляющих устройств, заземлителей, определение удельного сопротивления грунта.',
			'Измерения сопротивления петли «фаза-нуль».',
			'Измерения сопротивления изоляции электрооборудования.',
			'Проверка наличия цепи между заземлителями и заземляемыми элементами.',
			'Испытания повышенным напряжением изоляции электрооборудования и кабельных линий.',
			'Определение места повреждения, прожиг и «трассировка» кабельных линий.',
			'Временное подключение подрядной организации к электросетям АО "Теплоэнерго" на время проведения работ на объектах АО "Теплоэнерго"',
			'Отключение после временного подключения подрядной организации к электросетям АО "Теплоэнерго" на время проведения работ на объектах АО "Теплоэнерго"',
			'Рассмотрение с выдачей заключения по заявкам сторонних организаций проектной документации переустройства (изменения трассировки) электрических сетей АО "Теплоэнерго"',
		),
	),
	array(
		"engcomm",
		"Инженерные коммуникации",
		array(
			'Проектирование',
			'Строительство',
			'Ремонт',
		),
	),
	array(
		"heatnet",
		"Тепловые сети",
		array(
			'Проектирование',
			'Монтаж',
			'Ремонт',
		),
	),
	array(
		"heatpoint",
		"Тепловые пункты (ИТП, ЦТП, БМТП)",
		array(
			'Проектирование',
			'Монтаж',
			'Обслуживание',
		),
	),
	array(
		"boiler",
		"Котельные",
		array(
			"Проектирование",
			"Строительство",
			"Ремонт",
			"Обслуживание",
		),
	),
	array(
		"lab",
		"Лабораторные исследования",
		array(
			"Лабораторные исследования воды питьевой, природной, сточной, производственной",
			"Лабораторные исследования атмосферного воздуха",
			"Лабораторные исследования воздуха рабочей зоны",
			"Лабораторные исследования почвы, отходов",
			"Инструментальные  измерения физических факторов"
		),
	),
	array(
		"thermmal",
		"Тепловизионные обследования",
		array(
			"Тепловизионные обследования",
		),
	),
	array(
		"relland",
		"Освобождение земельного участка",
		array(
			"Освобождение земельного участка",
		),
	),
);

$arPerson = array(
	array("1", "Физическое лицо"),
	array("2", "Юридическое лицо"),
	array("3", "Индивидуальный предприниматель"),
);


?>
<div class="modal-form" id="modal-ask">
	<div class="close-modal">
		<a href="#">
			<img src="<?= SITE_TEMPLATE_PATH ?>/img/close_modal.svg" alt="">
		</a>
	</div>
	<div class="modal-form-content">
		<p class="modal-form-content__title">Подать заявку</p>
		<form id="form-apply" name="form-apply" data-ajax="true" method="POST" action="/ajax/form_send_apply.php" enctype="multipart/form-data">
			<input type="radio" name="radio-ask" value="4" id="radio-ask3" checked hidden>
			<div class="modal-form-block">
				<div class="row justify-content-between">
					<div class="col-md-5">
						<div class="modal-form-block__name">
							<p>Заявитель</p>
						</div>
					</div>
					<div class="col-md-7">
						<div class="modal-form-block__data">
							<? foreach ($arPerson as $key => $person) : ?>
								<label class="radio" for="person<?= $key + 1 ?>" data-person="<?= $person[0] ?>">
									<input type="radio" name="person" value="<?= $person[1] ?>" id="person<?= $key + 1 ?>" checked="" hidden="">
									<div class="radio__text person">
										<?= $person[1] ?>
									</div>
								</label>
							<? endforeach; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-form-block">
				<div class="row justify-content-between">
					<div class="col-md-5">
						<div class="modal-form-block__name">
							<p>Услуга</p>
						</div>
					</div>
					<div class="col-md-7">
						<div class="modal-form-block__data">
							<? foreach ($services as $key => $service) : ?>
								<label class="radio services" for="service<?= $key + 1 ?>" data-service="<?= $service[0] ?>">
									<input type="radio" name="service" value="<?= $service[1] ?>" id="service<?= $key + 1 ?>" checked="" hidden="">
									<div class="radio__text services">
										<?= $service[1] ?>
									</div>
								</label>
								<? if (count($service[2]) > 1) : ?>
									<div class="modal-form-block services hide-r52" id="<?= $service[0] ?>">
										<div class="row justify-content-between" style="padding-left:20px;">
											<div class="col-md-7">
												<div class="modal-form-block__data">
													<? foreach ($service[2] as $key => $subserv) : ?>
														<div class="custom-checkbox-container">
															<input type="checkbox" class="custom-checkbox" name="<?= $service[0] ?>[]" value="<?= $subserv ?>">
															<label for="rent"><?= $subserv ?></label>
														</div>
													<? endforeach; ?>
												</div>
											</div>
										</div>
									</div>
								<? endif; ?>
							<? endforeach; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-form-block" id="company">
				<div class="row justify-content-between">
					<div class="col-md-5">
						<div class="modal-form-block__name">
							<p>Полное наименование организации</p>
						</div>
					</div>
					<div class="col-md-7">
						<div class="modal-form-block__data">
							<input type="text" name="fio-ask" id="fio-ask" class="form-data company">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-form-block">
				<div class="row justify-content-between">
					<div class="col-md-5">
						<div class="modal-form-block__name">
							<p>Контактное лицо</p>
						</div>
					</div>
					<div class="col-md-7">
						<div class="modal-form-block__data">
							<input type="text" name="fio-ask2" id="fio-ask2" class="form-data">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-form-block">
				<div class="row justify-content-between">
					<div class="col-md-5">
						<div class="modal-form-block__name">
							<p>Контактный телефон</p>
						</div>
					</div>
					<div class="col-md-7">
						<div class="modal-form-block__data">
							<input type="text" name="phone-ask" id="phone-ask" class="form-data" data-phone="true" pattern="^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$" placeholder="+7 (___)___-__-__">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-form-block">
				<div class="row justify-content-between">
					<div class="col-md-5">
						<div class="modal-form-block__name">
							<p>Электронная почта</p>
						</div>
					</div>
					<div class="col-md-7">
						<div class="modal-form-block__data">
							<input type="text" name="email-ask" id="email-ask" pattern="^[a-zA-Z0-9.!#$%&*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$" class="form-data">
						</div>
					</div>
				</div>
			</div>

			<div class="modal-form-block">
				<div class="row justify-content-between">
					<div class="col-md-5">
						<div class="modal-form-block__name">
							<p>Комментарий</p>
						</div>
					</div>
					<div class="col-md-7">
						<div class="modal-form-block__data">
							<textarea name="message-ask" id="message-ask"></textarea>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-form-block">
				<div class="row justify-content-between">
					<div class="col-md-5">
						<div class="modal-form-block__name">
							<p>Добавить файл</p>
						</div>
					</div>
					<div class="col-md-7">
						<div class="modal-form-block__data">
							<label for="file-warning" class="file-upload">
								<input type="file" name="file-warning" id="file-warning" hidden>
								<span class="file-upload__button">Выберите файл</span>
								<span class="file-upload__name">Файл не выбран</span>
							</label>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-form-block modal-form-block__error">Не заполнены обязательные поля</div>
			<div class="modal-form-block">
				<div id="captcha-error"></div>
				<input name="captcha_code" value="<?= htmlspecialchars($cpt->GetCodeCrypt()); ?>" type="hidden">
				<input id="captcha_word" name="captcha_word" type="text">
				<img src="/bitrix/tools/captcha.php?captcha_code=<?= htmlspecialchars($cpt->GetCodeCrypt()); ?>">
			</div>
			<div class="modal-form-block">
				<div class="row">
					<div class="col">
						<!-- <input type="submit" class="button" name="submit-ask" id="submit-ask" value="Отправить"> -->
						<input type="submit" class="button" value="Отправить">
					</div>
				</div>
			</div>
			<div class="modal-form-block">
				<div class="row">
					<div class="col">
						<label class="checkbox">
							<input type="checkbox" name="check-ask" id="check-ask" checked hidden>
							<div class="checkbox__text">Вы соглашаетесь c <a href="http://teploenergo-nn.ru/potrebitelyam/obrabotka-personalnykh-dannykh/" target="_blank">условиями обработки персональных данных</a></div>
						</label>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>