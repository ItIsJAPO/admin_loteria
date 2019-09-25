$(function () {
    var dominio = 'https://admin.loteriauac2019.mx';
    var token = null;
    var services = {
        getUniversidad: function () {
            return $.ajax({
                type: 'get',
                url: dominio + '/api/getAdscripciones',
            })
        }
    };

    function captcha() {
        grecaptcha.ready(function () {
            grecaptcha.execute('6LfyTbYUAAAAACXuzHxeIOu-lWX3SBeyP2oocu_P', {action: 'homepage'}).then(function (response) {
                token = response;
            });
        });
    }

    captcha();

    function universidadesSelect() {
        $.when(services.getUniversidad()).then(function (response) {
            $('#universidadOEscuela').empty();
            var list = '';
            list += '<option value="">Seleccione una opción</option>';
            $(response).each(function (_, value) {
                list += '<option value="' + value.uuid + '">' + value.nombre + '</option>'
            });
            $('#universidadOEscuela').html(list);
            $('#universidadOEscuela').select2();
        })
    }

    universidadesSelect();

    $('body').on('click', '#universitario1', function (e) {
        if ($(this).is(':checked')) {
            $('#esUniversitario').show();
        }
    });
    $('body').on('click', '#universitario2', function (e) {
        if ($(this).is(':checked')) {
            $('#esUniversitario').hide();
        }
    });

    $('body').on('change', '#numeroDePersonas', function (e) {
        e.preventDefault();
        var total = $(this).val();
        var personas = '';
        for (var i = 1; i <= total; i++) {
            personas += '<div class="col-12 col-sm-6 col-md-6 col-lg-6">';
            personas += '<label for=""><span class="text-danger">*</span> Nombre de acompañante ' + i + '</label>';
            personas += '<input type="text" class="form-control form-group form-lg" placeholder="Ingrese nombre del acompañante" name="nombreAcompanante" tipo="nombre" id="nombre' + i + '" >';
            personas += '</div>';
            personas += '<div class="col-12 col-sm-6 col-md-6 col-lg-6">';
            personas += '<label for=""><span class="text-danger">*</span> Edad</label>';
            personas += '<input type="number" class="form-control form-group form-lg" min="10" placeholder="Ingrese edad del acompañante" name="edadAcompanante" tipo="edad" id="edad' + i + '" >';
            personas += '</div>';
        }
        $('#divCapturaPersonas').html(personas);
    });

    $('body').on('click', '#registrar', function (e) {
        e.preventDefault();
        captcha();
        if (vacio('input[name="nombre"]', 'El campo nombre')) {
            return false
        }
        if (vacio('input[name="edad"]', 'El campo edad')) {
            return false
        }
        if (vacio('input[name="direccion"]', 'El campo dirección')) {
            return false
        }
        if (vacio('input[name="telefono"]', 'El campo teléfono')) {
            return false
        }
        if (vacio('input[name="correo"]', 'El correo electrónico')) {
            return false
        }
        if (correoValido('input[name="correo"]', false)) {
            return false
        }
        if ($('#universitario1').is(':checked') == false && $('#universitario2').is(':checked') == false) {
            noty.show('danger', 'El campo pertenece a una universidad es obligatorio');
            return false;
        }
        if ($('#universitario1').is(':checked')) {
            if (vacio('#universidadOEscuela', 'El campo escuela o facultad')) {
                return false;
            }
        }
        var envio = true;
        if ( $('#numeroDePersonas').val() > 0) {
            var container = $('#divCapturaPersonas').children();
            $(container).each(function (_, value) {
                var tipo = $(value).children('input').attr('tipo');
                if (vacio($(value).children('input'), 'El campo ' + tipo + ' de acompañante')) {
                    envio = true;
                    return false;
                }else{
                    envio = false
                }
            });
        }
        /*validación del formulario*/
        if ( $('#numeroDePersonas').val() > 0 && envio) {
            return false
        }

        const SI = 1, NO = 2;
        var esUniversitarioRadio = null;
        var tipoUniversitario = null;
        var adscriptos = [];
        var formData = new FormData();

        if ($('input:radio[name=universitario]:checked').val() == SI) {
            esUniversitarioRadio = SI;
            tipoUniversitario = $('input:radio[name=radioUnivresidad]:checked').val();
        } else {
            esUniversitarioRadio = NO;
        }
        var numeroDePersonas = $('#numeroDePersonas').val() == '' ? 0 : $('#numeroDePersonas').val();

        for (var n = 1; n <= numeroDePersonas; n++) {
            var values = {nombre: $("#nombre" + n).val(), edad: $("#edad" + n).val()};
            adscriptos.push(values);
        }

        formData.append('nombre', $('input[name="nombre"]').val());
        formData.append('edad', $('input[name="edad"]').val());
        formData.append('direccion', $('input[name="direccion"]').val());
        formData.append('telefono', $('input[name="telefono"]').val());
        formData.append('correo', $('input[name="correo"]').val());
        formData.append('esUniversitario', esUniversitarioRadio);
        formData.append('tipoUniversitario', tipoUniversitario);
        formData.append('universidadOEscuela', $('#universidadOEscuela').val());
        formData.append('numeroDePersonas', numeroDePersonas);
        formData.append('adscripciones', JSON.stringify(adscriptos));
        formData.append('token', token);
        $("#registrar").attr("disabled", "disabled");
        $.ajax({
            type: 'post',
            url: dominio + '/api/nuevoRegistro',
            cache: false,
            processData: false,
            contentType: false,
            data: formData,
            success: function (response) {
                noty.show(response.type, response.message);
                if (response.type == 'success') {

                    $('#universidadOEscuela').val('').trigger('change');
                    $('#numeroDePersonas').val('').change();
                    $('#esUniversitario').hide();
                    $('#formRegistro')[0].reset();
                    $('#contenido').html(response.mensaje);
                    $('#responseRegister').modal();
                }
            }
        }).always(
            function () {
                $("#registrar").removeAttr("disabled");
            }
        );
    })
});