<?php
?>
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-primary">Adscripciones</h3></div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Inicio</li>
        </ol>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-right">
                        <button id="nueva_adscripcion" class="btn btn-primary"><i class="fa fa-plus"></i> Agregar nuevo</button>
                    </div>
                    <div class="table-sm-responsive">
                        <table class="table table-bordered table-condensed" id="adscripciones_tabla">
                            <thead>
                            <tr>
                                <th scope="col">Nombre</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <th scope="col">Cargando..</th>
                            <th scope="col">Cargando..</th>
                            <th scope="col">Cargando..</th>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="modal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <span class="text-center">
                    <h5 class="modal-title text-center nuevo">
                        Agregar una nueva adscripción
                    </h5>
                     <h5 class="modal-title text-center editar">
                        Editar información de adscripción
                    </h5>
                </span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="padding: 20px 30px">
                <form action="#">
                    <div id="seccion_datos_usuario" class="animated fadeIn">
                        <div class="row col-md-12">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Nombre: </label>
                                    <input name="nombre" id="nombre" placeholder="Nombre de la adscripción"
                                           class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button id="btnCerrarModal" class="btn btn-rounded btn-danger" data-dismiss="modal">
                    <i class="fal fa-times"></i>
                    Cancelar
                </button>
                <button id="btnGuardarModal" class="btn btn-rounded btn-primary nuevo">
                    <i class="fal fa-user-plus"></i>
                    Guardar
                </button>
                <button id="btnActualizarModal" class="btn btn-rounded btn-primary editar">
                    <i class="fal fa-user-edit"></i>
                    Actualizar
                </button>
            </div>
        </div>
    </div>
</div>


<script nonce="<?= $nonce_hash ?>">
    $(function () {
        listarAdscripciones();
    });
    const jBody = $("body");
    const modal = $('#modal');
    const ACCESO_DESACTIVADO = 0;
    const ACCESO_ACTIVO = 1;
    const CREAR = 1;
    const EDITAR = 2;

    const services = {
        getAdscripciones: function () {
            return $.ajax({
                type: 'get',
                url: '/adscripciones/getAdscripciones',
            })
        },
        actualziarAdscripcion: function (formData) {
            return $.ajax({
                type: 'post',
                url: '/adscripciones/actualizarNombre',
                processData: false,
                contentType: false,
                dataType: 'json',
                cache: false,
                data: formData
            })
        },
        guardarAdscripcion: function (formData) {
            return $.ajax({
                type: 'post',
                url: '/adscripciones/nuevaAdscripcion',
                processData: false,
                contentType: false,
                dataType: 'json',
                cache: false,
                data: formData
            })
        },
        changeActivation: function (uuid) {
            return $.ajax({
                type: 'post',
                url: '/adscripciones/cambiarEstado',
                data: {uuid: uuid}
            })
        },

    };

    const dataTableConf = {
        "pageLength": 15,
        "stateSave": true,
        "order": [0, 'asc'],
        aLengthMenu: [
            [15, 50, 75, 100, 200, -1],
            [15, 50, 75, 100, 200, "Todos"]
        ],
        iDisplayLength: -1,
        data: [],
        dom: 'Bifrtpl',
        buttons: {
            buttons: [
                {
                    extend: 'excel',
                    className: 'btn btn-success',
                    text: 'Descargar en Excel'
                }
            ]
        },
    };

    dataTableConf.columns = [
        {data: 'nombre', sClass: "align-middle text-center"},
        {data: null, sClass: "align-middle text-center"},
        {data: null, sClass: "align-middle text-center"},
    ];

    dataTableConf.aoColumnDefs = [
        {
            'aTargets': [1],
            'mRender': function (data) {
                return dataTable.estatus(data);
            }
        },
        {
            'aTargets': [2],
            'mRender': function (data) {
                return dataTable.acciones(data);
            }
        },

    ];

    function validar() {
        let formData = new FormData();
        const nombre = $("#nombre").val();
        if ($.trim(nombre).length === 0 || nombre === "")
            throw new UserException("El nombre de la adscripción es obligatorio.");
        formData.append("nombre", nombre);
        return formData;
    }

    const dataTable = {
        estatus: function (data) {
            let span = $("<span></span>");
            if (data.estatus === 1) {
                span.attr("class", "badge badge-info");
                span.append("Disponible");
            } else {
                span.attr("class", "badge badge-danger");
                span.append("No disponible");
            }
            return span.prop("outerHTML");
        },
        acciones: function (data) {
            let btn = [];
            let button = "";
            let icon = "";
            let span = "";
            //Editar
            button = $("<a></a>");
            button.css('cursor', 'pointer');
            button.attr("class", "btn_editar text-primary");
            button.attr("uuid", data.uuid);
            button.attr("nombre", data.nombre);
            button.attr("data-placement", "top");
            button.attr("data-toggle", "tooltip");
            button.attr("data-title", "Editar adscripción");
            button.attr("title", "Editar adscripción");
            icon = $("<i></i>").attr("class", "fal fa-pencil-alt text-primary fa-lg");
            button.append(icon);
            btn.push(button);
            //Activar/Desactivar
            button = $("<a></a>");
            button.css('cursor', 'pointer');
            button.attr("class", "change-activation text-primary");
            button.attr("uuid", data.uuid);
            button.attr("nombre", data.nombre);
            button.attr("active", data.estatus);
            button.attr("data-placement", "top");
            button.attr("data-toggle", "tooltip");
            (data.estatus === ACCESO_ACTIVO) ? icon = button.attr("data-title", "Desactivar") : button.attr("data-title", "Activar");
            (data.estatus === ACCESO_ACTIVO) ? icon = button.attr("title", "Desactivar") : button.attr("title", "Activar");
            (data.estatus === ACCESO_ACTIVO) ? icon = $("<i></i>").attr("class", "far fa-eye text-danger fa-lg") : icon = $("<i></i>").attr("class", "far fa-eye-slash text-success fa-lg");
            button.append(icon);
            btn.push(button);
            let temp = "";
            btn.forEach(function (valor, indice, array) {
                temp += valor.prop("outerHTML") + " ";
            });
            return temp;
        }
    };

    const listarAdscripciones = function () {
        $.when(services.getAdscripciones()).then(function (response) {
            var conf = $.extend(dataTableConf, {
                data: response
            });
            CustomDataTable.applyCustomStyleTo("#adscripciones_tabla", conf);
            $('[data-toggle="tooltip"]').tooltip();
        });
    };

    jBody.on("click", ".btn_editar", function (event) {
        event.preventDefault();
        accion = EDITAR;
        const id = $(this).attr("uuid");
        const nombre = $(this).attr("nombre");

        $("#btnActualizarModal").attr("uuid", id);
        $("#nombre").val(nombre);
        $(".nuevo").hide();
        $(".editar").show();
        modal.modal({
            keyboard: false,
            backdrop: 'static'
        });
        modal.modal("show");
    });

    jBody.on("click", "#nueva_adscripcion", function (event) {
        event.preventDefault();
        accion = CREAR;
        $("#nombre").val("");
        $(".nuevo").show();
        $(".editar").hide();
        modal.modal({
            keyboard: false,
            backdrop: 'static'
        });
        modal.modal("show");
    });

    jBody.on("click", "#btnActualizarModal", function (event) {
        event.preventDefault();
        const id = $(this).attr("uuid");
        formData = validar();
        formData.append('uuid', id);
        $.when(services.actualziarAdscripcion(formData)).then(function (response) {
            if (response.type === "success") {
                listarAdscripciones();
                jsonNotyfication(response);
                modal.modal("hide");
            } else {
                jsonNotyfication({
                    type: 'danger',
                    message: "(" + response.code + ") " + response.message
                });
            }
        });
    });

    jBody.on("click", ".change-activation", function (event) {
        event.preventDefault();
        let active = parseInt($(this).attr("active"));
        let nombre = $(this).attr("nombre");
        let id = $(this).attr("uuid");
        let msg = "";
        if (active === ACCESO_ACTIVO)
            msg = "¿Esta seguro que desea que no aparezca <strong>" + nombre + "</strong>?";
        else
            msg = "¿Esta seguro que desea que vuelva aparecer <strong>" + nombre + "</strong> ?";
        confirmMessage(msg, function () {
            $.when(services.changeActivation(id)).then(function (response) {
                if (response.type === "success") {
                    jsonNotyfication(response);
                    listarAdscripciones();
                } else {
                    jsonNotyfication({
                        type: 'danger',
                        message: "(" + response.code + ") " + response.message
                    });
                }
            });
        });
    });

    jBody.on("click", "#btnGuardarModal", function (event) {
        event.preventDefault();
        formData = validar();
            $.when(services.guardarAdscripcion(formData)).then(function (response) {
                if (response.type === "success") {
                    jsonNotyfication(response);
                    listarAdscripciones();
                    modal.modal("hide");
                } else {
                    jsonNotyfication({
                        type: 'danger',
                        message: "(" + response.code + ") " + response.message
                    });
                }
            });

    });

</script>