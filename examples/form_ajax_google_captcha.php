<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
  $id = $_GET['id'];
  $type = $_GET['type'];
  CModule::IncludeModule("iblock");
  $obElement = CIBlockElement::GetByID($id);
  if ($arEl = $obElement->GetNext())
?>
  <? if ($type == "opt") {
  $resblock = "optprice__content";
  ?>
    <div class="feedback__title">Запрос оптовой цены для <?= $arEl["NAME"] ?></div>
    <form class="form form_ajax" action="javascript:void(null);" onsubmit="send()">
      <input type="hidden" name="id" value="<?= $arEl["NAME"] ?>">
      <input type="hidden" name="type" value="<?= $type ?>">
      <div class="form__group">
        <input id="name" name="name" class="form__control" type="text" placeholder="Вашe имя" required />
      </div>
      <div class="form__group">
        <input id="email" name="email" class="form__control" type="email" placeholder="Ваш E-mail" required />
      </div>
      <div class="form__group">
        <input id="phone" name="phone" class="form__control" type="text" placeholder="Ваш телефон" required />
      </div>
      <div class="form__group checkbox">
        <input type="checkbox" id="agreement" name="agreement" value="" checked required>
        <label for="agreement">Я принимаю условия <a href="/pk/" target="_blank">пользовательского соглашения</a></label>
      </div>
      <?/*$APPLICATION->IncludeComponent(
			  "bitrix:main.userconsent.request",
			  "",
			  array(
				  "ID" => 1,
				  "IS_CHECKED" => "Y",
				  "AUTO_SAVE" => "Y",
				  "IS_LOADED" => "Y",
				  "REPLACE" => array(
				   'button_caption' => 'Подписаться!',
				   'fields' => array('Email', 'Телефон', 'Имя')
				  ),
			  )
			 );*/ ?>
      <div>
        <input type="submit" value="Заказать" />
      </div>

    </form>
  <?
} elseif ($type == "call") {
  $resblock = "feedback__content";
  ?>
    <div class="feedback__title">Заказать обратный звонок</div>
    <form class="form form_ajax" action="javascript:void(null);" onsubmit="send()">
      <input type="hidden" name="type" value="<?= $type ?>">
      <div class="form__group">
        <input id="name" name="name" class="form__control" type="text" placeholder="Вашe имя" required />
      </div>
      <div class="form__group">
        <input id="phone" name="phone" class="form__control" type="text" placeholder="Ваш телефон" required />
      </div>
      <div class="form__group checkbox">
        <input type="checkbox" id="agreement" name="agreement" value="" checked required>
        <label for="agreement">Я принимаю условия <a href="/pk/" target="_blank">пользовательского соглашения</a></label>
      </div>
      <div>
        <input type="submit" value="Заказать" />
      </div>
    </form>

  <?
} elseif ($type == "cont") {
  $resblock = "mfeedback";
  ?>
    <div class="feedback__title">Обратная связь</div>
    <form class="form form_ajax" action="javascript:void(null);" onsubmit="send()">
      <input type="hidden" name="type" value="<?= $type ?>">
      <div class="form__group">
        <input id="name" name="name" class="form__control" type="text" placeholder="Вашe имя" required />
      </div>
      <div class="form__group">
        <input id="email" name="email" class="form__control" type="email" placeholder="Ваш E-mail" required />
      </div>
      <div class="form__group">
        <textarea id="email" name="text" class="form__control" type="textarea" row="5" placeholder="Сообщение" required></textarea>
      </div>
      <div class="form__group checkbox">
        <input type="checkbox" id="agreement" name="agreement" value="" checked required>
        <label for="agreement">Я принимаю условия <a href="/pk/" target="_blank">пользовательского соглашения</a></label>
      </div>
      <div>
        <input type="submit" value="Заказать" />
      </div>
    </form>
  <?
} elseif ($type == "vac") {
  $resblock = "vac_content";
  ?>
    <div class="vacancy-form__title">Резюме</div>
    <form class="form form_ajax" action="javascript:void(null);" onsubmit="send()" enctype="multipart/form-data">
      <input type="hidden" name="type" value="<?= $type ?>">
      <div class="form__group">
        <label>Ваше имя <span>*</span></label>
        <input class="form__control" type="text" name="name" placeholder="Ваше имя" required="required" />
      </div>
      <div class="form__group">
        <label>Контактный телефон <span>*</span></label>
        <input class="form__control" type="tel" name="phone" placeholder="+7 ( ___ ) ___-__-__" />
      </div>
      <div class="form__group">
        <label>E-mail</label>
        <input class="form__control" name="email" type="email" placeholder="E-mail" />
      </div>
      <div class="form__group">
        <label>Желаемая должность <span>*</span></label>
        <input class="form__control" type="text" name="dolzh" placeholder="Желаемая должность" value="<?= $_GET['vac']; ?>" required="required" />
      </div>
      <div class="form__group">
        <label>Прикрепить файл</label>

        <div class="pseudofileupload">
          <label class="btn blue">Прикрепить файл
            <input type="file" name="file" hidden="hidden" />
          </label>
          <p id="filename"></p>
        </div>
      </div>
      <div class="form__group">
        <label>Сообщение <span>*</span></label>
        <textarea class="form__control" name="text" required="required"></textarea>
      </div>
      <div class="form__group checkbox">
        <input type="checkbox" id="agreement" name="agreement" value="" checked required />
        <label for="agreement">Я принимаю условия <a href="/pk/" target="_blank">пользовательского соглашения</a></label>
      </div>
      <input type="submit" value="Отправить резюме" />
    </form>
    <script>
      $(".pseudofileupload input[type=file]").change(function() {
        var filename = $(this).val().replace(/.*\\/, "");
        $("#filename").html(filename);
      });



      $('input[type="tel"]').mask('+7 ( 999 ) 999-99-99');
    </script>


  <?
} else {
  $resblock = "oneclick__content";
  ?>
    <div class="feedback__title">Купить в один клик <?= $arEl["NAME"] ?></div>
    <form class="form form_ajax" action="javascript:void(null);" onsubmit="send()">
      <input type="hidden" name="id" value="<?= $arEl["NAME"] ?>">
      <div class="form__group">
        <input id="name" name="name" class="form__control" type="text" placeholder="Вашe имя" required />
      </div>
      <div class="form__group">
        <input id="phone" name="phone" class="form__control" type="text" placeholder="Ваш телефон" required />
      </div>
      <div class="form__group checkbox">
        <input type="checkbox" id="agreement" name="agreement" value="" checked required>
        <label for="agreement">Я принимаю условия <a href="/pk/" target="_blank">пользовательского соглашения</a></label>
      </div>
      <div>
        <input type="submit" value="Заказать" onclick="ym(52122730, 'reachGoal', 'v_1_click'); return true;" />
      </div>
    </form>
  <? } ?>


  <script>
    jQuery(function($) {
      $("#phone").mask("+7 (999) 999-9999");
    });




    function send() {
      var $that = $('.form_ajax'),
        formData = new FormData($that.get(0)); // создаем новый экземпляр объекта и передаем ему нашу форму (*)
      grecaptcha.execute('6LcTHJUnAAAAAGFzRyKoWk8kG8I0U7qY8VaR91Rn', {
        action: 'submit'
      }).then(function(token) {
        // Add your logic to submit to your backend server here.
        // console.log(token);
        formData.append("token", token);
        $.ajax({
          type: 'POST',
          url: '/local/templates/IGRODAR/ajax/formsend.php',
          contentType: false, // важно - убираем форматирование данных по умолчанию
          processData: false, // важно - убираем преобразование строк по умолчанию
          data: formData,
          success: function(data) {
            let res = JSON.parse(data);
            //console.log(res);
            if (res[0] == "Y") {
              $('.<?= $resblock ?>').html("Спасибо. Менеджер свяжется с Вами в ближайшее время.");
            } else {
              let erMessage = "";
              for (let er in res) {
                //console.log(res[er]);
                erMessage += '<div>' + res[er] + '</div>';
              }
              $('.<?= $resblock ?>').html(erMessage);
            }
          },
          error: function(xhr, str) {
            alert('Возникла ошибка: ' + xhr.responseCode);
          }
        });
      });
    }
  </script>
<? } ?>