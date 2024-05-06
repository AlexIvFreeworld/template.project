$(document).ready(function(){
    
    $('.feedback-file__box').click(function(){
        $(this).closest('form').find('input[type=file]').click();
    });

    $('input[type=file]').change(function(){
        var form = $(this).closest('form');
        var count = form.find("input:file")[0].files.length;
        form.find('.feedback-file__text').text('Выбрано '+count+' файл(ов)');
    });

    /* FORM */

    $(".feedback").submit(function(event){
        event.preventDefault();
        var fb = $(this);
        if(fb.find('.feedback-garant__checkbox').prop('checked')){
            var formData = new FormData(this);
            $.ajax({
                url: "/lib/feedback/mail-form.php",
                type: "post",
                data: formData,
                success: function(data) {
                    data = data.split("#");
                    if (data[0] == "error") {
                        fb.find(".feedback-garant__mess-error").html(data[1]);
                    } else {
                        fb.find('.feedback-garant').html('<div class="feedback-send-message">'+data[1]+'</div>');
                        fb.find('button[type="submit"]').prop('disabled', true);
                        console.log(formData.getAll("code")[0]);
                        let code = formData.getAll("code")[0];
                        if(code == "catalog"){
                            ym(96979283, 'reachGoal', 'OrderGoods');
                        }
                        if(code == "contacts"){
                            ym(96979283, 'reachGoal', 'AskQuestionSpecialist');
                        }
                        if(code == "request"){
                            ym(96979283, 'reachGoal', 'LeaveRequest');
                        }
                        if(code == "callback"){
                            ym(96979283, 'reachGoal', 'OrderCall');
                        }
                    }
                },
                error: function(){
                    alert("Ваша заявка не отправлена! Попробуйте еще раз");
                },
                cache: false,
                contentType: false,
                processData: false
            });
            $(this).find(".feedback-garant__mess-error").html("");
        } else {
            $(this).find(".feedback-garant__mess-error").html("Необходимо дать согласие");
        }
    });
});
