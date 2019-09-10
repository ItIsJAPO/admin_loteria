<script nonce="<?= $nonce_hash ?>">
    function correoValido(selector, show_input_addon) {
        if (!/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test($(selector).val())) {
            if (show_input_addon) {
                if ($(selector).parent().find(".valid_email").length > 0) {
                    $(selector).parent().find(".valid_email").remove();
                }

                $(selector).after('<span style="color: red" class = "input-group-addon response_validation valid_email">Correo inv&aacute;lido</span>');
            } else {
                noty.show("danger", "Debe ingresar un correo valido");
                return true;
            }

            return false;
        }

        if (show_input_addon) {
            if ($(selector).parent().find(".valid_email").length > 0) {
                $(selector).parent().find(".valid_email").remove();
            }

            $(selector).after('<span style="color:green" class = "input-group-addon response_validation valid_email">Correo v&aacute;lido</span>');
        }

        return false;
    }

    function vacio(selector, message, view) {
        view = (view == null) ? true : view;
        if ($.trim($(selector).val()).length == 0 || $(selector).val() == "") {
            if ($(selector).hasClass('select2')) {

                $(selector).select2('focus');
                $(selector).select2('open');
                if (view) {
                    noty.show("danger", message + " es obligatorio");
                }
                return true
            }
            if (view) {
                noty.show("danger", message + " es obligatorio");
            }
            $(selector).focus();
            return true
        }
        return false
    }

    function passwordHasBlankSpace(selector) {
        password = $.trim($(selector).val());

        if (/[\s]/.test(password)) {
            noty.show("danger", "<?= _("La_contrasena_no_puede_contener_espacios_en_blanco") ?>");
            return true;
        }

        return false;
    }


    function validatePassword(selector_pass) {
        password = $.trim($(selector_pass).val());

        if (passwordHasBlankSpace(selector_pass)) {
            return false;
        }

        number = 0;
        special = 0;
        characters = 0;
        upperLetter = 0;
        lowerLetter = 0;

        if (password.length >= 8) {
            characters = 1;
        }

        if (/[a-z]/.test(password)) {
            lowerLetter = 1;
        }

        if (/[A-Z]/.test(password)) {
            upperLetter = 1;
        }

        if (/[0-9]/.test(password)) {
            number = 1;
        }

        if (/(.*[!,@,#,$,%,^,&,*,?,_,~])/.test(password)) {
            special = 1;
        }

        score = ((characters + lowerLetter + upperLetter + number + special) * 100) / 5;

        switch (score) {
            case 100:
                addMessage(selector_pass, "Segura", 'green-background-color');
                break;
            case 80:
                addMessage(selector_pass, "Media", 'orange-background-color');
                break;
            case 60:
            case 40:
                addMessage(selector_pass, "Debil", 'red-background-color');
                break;
            default:
                addMessage(selector_pass, "Muy_debil", 'red-background-color');
                break;
        }

        return score;
    }

    function addMessage(selector, message, classname) {
        if ($(selector).parent().find(".pass_strength").length > 0) {
            $(selector).parent().find(".pass_strength").remove();
        }

        $(selector).after('<span class = "input-group-addon response_validation pass_strength white-color ' + classname + '">' + message + '</span>');
    }
</script>