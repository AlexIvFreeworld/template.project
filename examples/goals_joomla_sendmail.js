jQuery(function ($) {
    // ОТПРАВКА ФОРМЫ       .form-submit-toggle
    // ОБРАТНЫЙ ЗВОНОК      .callback-toggle

    document.querySelectorAll(".callback-toggle").forEach(function (el) {
        //console.log(el.textContent);
        el.addEventListener("click", function (i) {
            // console.log(this);
            let formCode = this.textContent;
            // console.log(formCode);
            document.querySelector("form.callback-modal__form-submit").setAttribute("data-id", formCode);
        });
    });


    $('body').on('click', '.callback-toggle', function () {
        //console.log($(this).text());
        $.magnificPopup.open({
            type: 'inline',
            removalDelay: 500,
            mainClass: 'mfp-fade',
            midClick: true,
            items: {
                src: '.callback-modal-toggle'
            }
        });
    });

    $('body').on('click', '.callback-toggle-f', function () {
        $.magnificPopup.open({
            type: 'inline',
            removalDelay: 500,
            mainClass: 'mfp-fade',
            midClick: true,
            items: {
                src: '.callback-modal-toggle-f'
            }
        });
    });

    // ОТПРАВКА ФОРМЫ
    $('.form-submit-toggle').submit(function (e) {
        e.preventDefault();
        var $form = new FormData(this);
        var $formclear = $(this);

        $.ajax({
            type: 'POST',
            url: '/index.php?option=com_request',
            data: $form,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },


            beforeSend: function () {
                $.magnificPopup.open({
                    type: 'inline',
                    removalDelay: 500,
                    mainClass: 'mfp-fade',
                    midClick: true,
                    items: {
                        src: '<div class="callback-modal-message">' +
                            '<div class="callback-modal-message__text"><span class="callback-modal-message__process">Идет отправка сообщения...</span></div>' +
                            '</div>'
                    }
                });
            },

            success: function (response) {
                $lol = JSON.parse(response);
                //console.log(response);
                //console.log($formclear.attr("data-id"));
                if ($lol["status"] == "saveAndSend") {
                    if($formclear.attr("data-id") == "Заказать звонок"){
                        ym(23417368, 'reachGoal', 'CallBack');
                    }
                    if($formclear.attr("data-id") == "Вызвать замерщика"){
                        ym(23417368, 'reachGoal', 'CallFitter');
                    }
                    if($formclear.attr("data-id") == "Получить консультацию"){
                        ym(23417368, 'reachGoal', 'GetConsultation');
                    }
                    if($formclear.attr("data-id") == "Бесплатный замер"){
                        ym(23417368, 'reachGoal', 'FreeMeasuring');
                    }
                    if(!$formclear.attr("data-id")){
                        //console.log("CallFitter");
                        ym(23417368, 'reachGoal', 'CallFitter');
                    }
                    $('.callback-modal-message__text').html('<span class="callback-modal-message__success">Сообщение успешно отправлено!</span>');
                    // close modal message
                    setTimeout(function () {

                        $formclear.trigger("reset");
                        $.magnificPopup.close();
                    }, 3000);
                } else {
                    if ($lol["status"] == "captcha") {
                        $('.callback-modal-message__text').html('<span class="callback-modal-message__error">Отсутствует проверочное поле капчи в форме!</span>');
                        setTimeout(function () {
                            $formclear.trigger("reset");
                            $.magnificPopup.close();
                        }, 3000);
                    } else {
                        $('.callback-modal-message__text').html('<span class="callback-modal-message__error">Ошибка отправки сообщения!</span>');
                        setTimeout(function () {
                            $formclear.trigger("reset");
                            $.magnificPopup.close();
                        }, 3000);
                    }
                }

                (typeof updateCaptcha !== "undefined") && updateCaptcha();
            },

            error: function (response) {
                // dump error

                console.error(response.message);
            },

            // after success or error
            complete: function () { }
        });
    });
});

// подключение капчи/проверка капчи
function runCaptcha(site_key) {
    jQuery.getScript("https://www.google.com/recaptcha/api.js?render=" + site_key, function () {
        window.updateCaptcha = function () {
            grecaptcha.ready(function () {
                grecaptcha.execute(site_key, { action: 'homepage' }).then(function (token) {
                    jQuery(".reCaptcha").val(token);
                });
            });
        };
        updateCaptcha();
    });
}