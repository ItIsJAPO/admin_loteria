<?php
?>
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-primary">Dashboard</h3></div>
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
                    <div class="table-sm-responsive">
                        <table class="table table-bordered table-condensed" id="table">
                            <thead>
                            <tr>
                                <th scope="col"  class="all">Folio</th>
                                <th scope="col" class="all">Nombre</th>
                                <th scope="col" class="all">Asistencia</th>
                                <th scope="col"># de acompañastes</th>
                                <th scope="col" class="all">Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Cargando...</td>
                                <td>Cargando...</td>
                                <td>Cargando...</td>
                                <td>Cargando...</td>
                                <td>Cargando...</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php include 'modal/modal_participantes.php' ?>
<script nonce="<?= $nonce_hash ?>">
    $(function () {

        var services = {
            getListaInscritos: function () {
                return $.ajax({
                    type: 'get',
                    url: '/inscripciones/getAsistencia',
                })
            },
            getParticipantesPorLiderId: function (id_lider) {
                return $.ajax({
                    type: 'post',
                    url: '/grupos/get_participantes_por_lider_id',
                    data: {
                        id_lider: id_lider
                    }
                })
            }
        };

        var dataTableConfigInscripciones = {
            "pageLength": 25,
            "stateSave": true,
            "order": [0, 'asc'],
            aLengthMenu: [
                [25, 50, 100, 200, -1],
                [25, 50, 100, 200, "Todos"]
            ],
            iDisplayLength: -1,
            data: [],
            dom: 'Bifrtpl',
            buttons: {
                buttons: [
                    {
                        extend: 'excel',
                        className: 'btn btn-success',
                        text: '<i class="fas fa-file-excel"></i> Descargar en Excel'
                    }
                ]
            },
            columns: [
                {data: 'folio', sClass: "text-center"},
                {data: 'nombre', sClass: "text-center"},
                {data: null, sClass: "text-center"},
                {data: 'asistentes', sClass: "text-center"},
                {data: null, sClass: "text-center"},
            ],
            aoColumnDefs: [
                {
                    'aTargets': [2],
                    'mRender': function (data) {
                        return estatusAsistencia(data)
                    }

                }, {
                    'aTargets': [4],
                    'mRender': function (data) {
                        return btnParticipantes(data) + ' ' + btnAsistencia(data)
                    }
                },
            ]
        };

        var dataTableConfigAsistencia = {
            "stateSave": true,
            data: [],
            columns: [
                {data: 'id', sClass: "text-center"},
                {data: 'nombre', sClass: "text-center"},
                {data: 'edad', sClass: "text-center"},
                {data: null, sClass: "text-center"},
                {data: null, sClass: "text-center"},
            ],
            aoColumnDefs: [
                {
                    'aTargets': [3],
                    'mRender': function (data) {
                        return estatusAsistencia(data)
                    }

                }, {
                    'aTargets': [4],
                    'mRender': function (data) {
                        return btnAsistencia(data)
                    }
                },
            ]
        };


        function estatusAsistencia(data) {
            var span = $("<span></span>");
            if (parseInt(data.asistencia) === 0) {
                span.attr('class', 'badge badge-danger');
                span.html('Sin asistencia');
            } else {
                span.attr('class', 'badge badge-success');
                span.html('Verificado');
            }
            return span.prop("outerHTML");
        }

        function btnAsistencia(data) {
            var button = $("<a></a>");
            button.attr("href", "#");
            button.attr("data-toggle", "tooltip");
            button.attr("data-placement", "top");
            if (parseInt(data.asistencia) === 0) {
                button.attr("title", "Marcar  asistencia");
                var icon = $("<i></i>").attr("class", "fas fa-check-circle text-success fa-lg accion-asistencia");
            } else {
                button.attr("title", "Marcar como sin asistir");
                var icon = $("<i></i>").attr("class", "fas fa-times-circle text-danger fa-lg accion-asistencia");
            }
            icon.attr('status', data.asistencia);
            icon.attr('name-user', data.nombre);
            icon.attr("id-db", (data.folio === undefined) ? data.id : data.folio);
            button.append(icon);
            return button.prop("outerHTML");
        }

        function btnParticipantes(data) {
            if (data.asistentes > 0) {
                var button = $("<a></a>");
                button.attr("href", "#");
                button.attr("data-toggle", "tooltip");
                button.attr("data-placement", "top");
                button.attr("title", "Ver grupo de participantes");
                var icon = $("<i></i>").attr("class", "fas fa-users-class text-info fa-lg accion-participantes");
                icon.attr("id-db", data.id_lider);
                button.append(icon);
                return button.prop("outerHTML");
            } else {
                return '';
            }
        }

        function tableAll() {
            $.when(services.getListaInscritos()).then(function (response) {
                dataService = response;
                var conf = $.extend(dataTableConfigInscripciones, {data: response});
                CustomDataTable.applyCustomStyleTo("#table", conf);
                $('[data-toggle="tooltip"]').tooltip();
            });
        }

        function tableAsistencia(id_lider) {
            $.when(services.getParticipantesPorLiderId(id_lider)).then(function (response) {
                var conf = $.extend(dataTableConfigAsistencia, {data: response});
                CustomDataTable.applyCustomStyleTo("#tablaAsistencia", conf);
                $('[data-toggle="tooltip"]').tooltip();
            });
        }

        tableAll();

        $('body').on('click', '.accion-participantes', function (e) {
            e.preventDefault();
            var id = $(this).attr('id-db');
            $('#modalParticipantes').modal();
            $('#modalParticipantes').attr('id-lider', id);
            tableAsistencia(id);
        });

        $('body').on('click', '.accion-asistencia', function (e) {
            e.preventDefault();
            var nombre = $(this).attr('name-user');
            var estatus = $(this).attr('status');
            var id = $(this).attr('id-db');
            var idLider = $('#modalParticipantes').attr('id-lider');
            confirmMessage('Realmente desea cambiar el estatus de asistencia de ' + nombre, function () {
                $.ajax({
                    type: 'post',
                    url: '/grupos/cambiar_estatus_de_participante',
                    data: {
                        id: id
                    },
                    success: function (response) {
                        jsonNotyfication(response);
                        tableAll();
                        if (idLider != '') {
                            tableAsistencia(idLider);
                        }
                    }
                })
            })
        });

    });


</script>