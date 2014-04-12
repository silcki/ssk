var VALIDATE_FEEDBACK_CAPTCHA = '/ajax/validatecaptcha/';

function reloadCaptcha(){
    var rndval = new Date().getTime();
    $('#capcha').html('<img src="/captcha/getImage.php?f='+rndval+'" width="160" height="47" border="0" alt="" />');
};

$(document).ready(function() {
//    $('#feedbackk').ajaxForm({
//        beforeSubmit: function(a,f,o) {
//            return formStatus;
//        },
//        success: function(data) {
//            alert(1);
//            var dateJSON = {};
//            try
//            {
//                dateJSON = $.parseJSON(data);
//            }
//            catch(e)
//            {
//                dateJSON['text'] = data;
//            }

//            message =   '<div class="okhold-false">'+ dateJSON['text'] +'</div>';
//            if (dateJSON['result']){
//                form.reset();
//                message = '<div class="okhold">'+ dateJSON['text'] +'</div>';
//            }
//            else {
//                form.captcha.value = '';
//            }

//            reloadCaptcha();
//            $.fancybox(dateJSON['text']);
//            $('.phoneback_back').remove();
//        }
//    });

    $("#feedbackk").validate({
        errorLabelContainer: $("#feedbackk div.errhold"),
        submitHandler: function(form) {
            $(form).parent().append('<div class="phoneback_back"><div class="phoneback_back_loader">&nbsp;</div></div>');
            $('#feedbackk').ajaxSubmit({
                success: function(data) {
                    var dateJSON = {};
                    try
                    {
                        dateJSON = $.parseJSON(data);
                    }
                    catch(e)
                    {
                        dateJSON['text'] = data;
                    }

                    message =   '<div class="okhold-false">'+ dateJSON['text'] +'</div>';
                    if (dateJSON['result']){
                        form.reset();
                        message = '<div class="okhold">'+ dateJSON['text'] +'</div>';
                    }
                    else {
                        form.captcha.value = '';
                    }

                    reloadCaptcha();
                    $.fancybox(dateJSON['text']);
                    $('.phoneback_back').remove();
                }
            });
        },
        invalidHandler: function(form, validator) {
            if ('captcha' == validator.invalidElements().attr('id')){
                reloadCaptcha();
                validator.invalidElements().val('');
                validator.invalidElements().focus();
            }
        },

        rules: {
            captcha: {
                required: true,
                remote:  {
                    url: VALIDATE_FEEDBACK_CAPTCHA,
                    type: "post"
                }
            }

            ,
            name: {
                required: true,
                minlength: 2
            },
            telmob: {
                required: true,
                minlength: 2
            }
        },
        messages: {
            name: "Поле Имя пустое",
            telmob: "Поле Телефон пустое",
            captcha: "Укажиет правильные символы на картинке."
        },
        onkeyup: false
    });
});