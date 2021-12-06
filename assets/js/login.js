$(document).ready(function ($) {
    $(function () {
        //show password
        $('.icon-eye-hide').on('click', function () {

            $(this).toggleClass('icon-eye');
            let password = $("[name=password]");
            if (password.attr('type') === "password") {
                password.attr('type', 'text');
            } else {
                password.attr('type', 'password');
            }

        });

        $(".rowTitle").hover(function () {
            // over
            $(".slider", this).toggleClass("hide");
        }, function () {
            // out
            $(".slider", this).toggleClass("hide");
        });

        // Flash message
        $(function () {
            setTimeout(function () {
                $('#flashMessage').fadeOut('fast');
            }, 5000)
        })

        // Tooltips Initialization
        $(function () {
            $('[data-bs-toggle="tooltip"]').tooltip()
        })

        //GESTION DU PASSWORD 1
        function checkPassword1() {
            var progress = 0;
            // set password variable
            var pswd = $('input[name="forget_password[new_password][first]"]').val();
            //validate the length
            if (pswd.length >= 12 && pswd.length <= 32) {
                progress = progress + 20;
                $('#length').removeClass('invalid').addClass('valid');
            } else {
                $('#length').removeClass('valid').addClass('invalid');
            }
            //Validate lower character
            if (pswd.match(/[a-z]/)) {
                progress = progress + 20;
                $('#letter').removeClass('invalid').addClass('valid');
            } else {
                $('#letter').removeClass('valid').addClass('invalid');
            }
            //Validate special character
            if (pswd.match(/[!"#$£€%&'()*+,-.:;<=>?@[\]^_`{|}~/\\\\]/g)) {
                progress = progress + 20;
                $('#special').removeClass('invalid').addClass('valid');
            } else {
                $('#special').removeClass('valid').addClass('invalid');
            }
            //validate capital letter
            if (pswd.match(/[A-Z]/)) {
                progress = progress + 20;
                $('#capital').removeClass('invalid').addClass('valid');
            } else {
                $('#capital').removeClass('valid').addClass('invalid');
            }
            //validate number
            if (pswd.match(/\d/)) {
                progress = progress + 20;
                $('#number').removeClass('invalid').addClass('valid');
            } else {
                $('#number').removeClass('valid').addClass('invalid');
            }

            $('.progress-bar-password').attr('aria-valuenow', progress).css('width', progress + '%');
            if (progress === 100) {
                // $('.password-explanation').removeClass('msg-error');
                $('.pswd_info').addClass('d-none');
                $('#forget_password_submit').prop('disabled', false).removeClass('disabled');
            } else {
                $('#forget_password_submit').prop('disabled', true).addClass('disabled');
            }
            return progress;
        }

        //On vérifie que le password rempli bien les conditions (sinon message explicatif)
        progress = $('input[name="forget_password[new_password][first]"]').keyup(function () {
            checkPassword1()
        }).focus(function () {
            $('.pswd_info').removeClass('d-none');
        }).blur(function () {
            $('.pswd_info').addClass('d-none');

            return progress;
        });

        //GESTION DU PASSWORD 2
        function checkPassword2() {
            var progress2 = 0;
            // set password variable
            var pswd = $('#validate_register_validate_password_first').val();
            //validate the length
            if (pswd.length >= 12 && pswd.length <= 32) {
                progress2 = progress2 + 20;
                $('#length').removeClass('invalid').addClass('valid');
            } else {
                $('#length').removeClass('valid').addClass('invalid');
            }
            //Validate lower character
            if (pswd.match(/[a-z]/)) {
                progress2 = progress2 + 20;
                $('#letter').removeClass('invalid').addClass('valid');
            } else {
                $('#letter').removeClass('valid').addClass('invalid');
            }
            //Validate special character
            if (pswd.match(/[!"#$£€%&'()*+,-.:;<=>?@[\]^_`{|}~/\\\\]/g)) {
                progress2 = progress2 + 20;
                $('#special').removeClass('invalid').addClass('valid');
            } else {
                $('#special').removeClass('valid').addClass('invalid');
            }
            //validate capital letter
            if (pswd.match(/[A-Z]/)) {
                progress2 = progress2 + 20;
                $('#capital').removeClass('invalid').addClass('valid');
            } else {
                $('#capital').removeClass('valid').addClass('invalid');
            }
            //validate number
            if (pswd.match(/\d/)) {
                progress2 = progress2 + 20;
                $('#number').removeClass('invalid').addClass('valid');
            } else {
                $('#number').removeClass('valid').addClass('invalid');
            }

            $('.progress-bar-password').attr('aria-valuenow', progress2).css('width', progress2 + '%');
            if (progress2 === 100) {
                // $('.password-explanation').removeClass('msg-error');
                $('.pswd_info').addClass('d-none');
                $('#forget_password_submit').prop('disabled', false).removeClass('disabled');
            } else {
                $('#forget_password_submit').prop('disabled', true).addClass('disabled');
            }
            return progress2;
        }

        //On vérifie que le password rempli bien les conditions (sinon message explicatif)
        progress2 = $('#validate_register_validate_password_first').keyup(function () {
            checkPassword2()
        }).focus(function () {
            $('.pswd_info').removeClass('d-none');
        }).blur(function () {
            $('.pswd_info').addClass('d-none');

            return progress2;
        });

        // Select2 
        $('.select-tags').select2({
            placeholder: 'Tapez une ou plusieurs lettres'
        });
        $('.select-product').select2({
            placeholder: 'Tapez une ou plusieurs lettres'
        });

        // [ Focus Contact2 ]
        $('.input2').each(function () {
            $(this).on('blur', function () {
                if ($(this).val().trim() != "") {
                    $(this).addClass('has-val');
                } else {
                    $(this).removeClass('has-val');
                }
            })
        })
    });
});