<?php use plataforma\SysConstants; ?>

<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-primary">Bitácora</h3> </div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
            <li class="breadcrumb-item active">Bitácora</li>
        </ol>
    </div>
</div>
<div class="container-fluid">
    <div class = "card">
        <div class="card-body">
            <div class = "row">
                <div class = "col-md-12 col-12 col-xs-12">
                    <div class = "form-group">
                        <h3>Filtros de busqueda</h3>
                        <hr>
                    </div>
                </div>
            </div>
            <form id = "filters_form">
                <div class = "row">
                   <div class = "col-12 col-md-4">
                        <label> Fecha inicio </label>
                        <div class = 'form-group input-group' id = "fecha_inicio">
                            <input type = "text" class = "form-control date" />
                            <div class="input-group-append">
                                 <span class="input-group-text"> <i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class = "col-12 col-md-4">
                        <label> Fecha fin </label>
                        <div class = 'input-group' id = "fecha_fin">
                            <input type = "text" class = "form-control date" />
                             <div class="input-group-append">
                                 <span class="input-group-text"> <i class="glyphicon glyphicon-calendar"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class = "col-12 col-md-4">
                        <div class = "form-group">
                            <label> Acciones </label>
                            <select class = "form-control select2" name = "actions">
                                <option value = "all">Todos las acciones</option>
                                <?php
                                if ( !empty($dataAndView->getData('acciones')) ) {
                                    foreach ( $dataAndView->getData('acciones') as $key => $accion ) { ?>
                                        <option value = "<?= $key ?>"><?= $accion ?></option>
                                        <?php
                                    }
                                } ?>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
            <div class = "form-group">
                <button class = "btn btn-success" id = "search_logs_button">
                    <i class = "fas fa-fw fa-search"></i> Buscar
                </button>
            </div>
            <div class = "form-group" style = "margin-top: 50px">
                <h3>Resultados de busqueda</h3>
                <hr>
            </div>
            <div style = "overflow-x: auto;">
                <table class = "table table-bordered" id = "table_log" style = "margin-bottom: 20px !important">
                    <thead>
                        <tr>
                            <th style = "min-width: 140px; width: 140px">Fecha </th>
                            <th>Usuario </th>
                            <th>Acción </th>
                            <th style = "min-width: 120px; width: 120px">IP </th>
                            <th>URL </th>
                            <th style = "min-width: 80px; width: 80px">Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
</div>

<div class = "modal fade" id = "modal_params">
    <div class = "modal-dialog modal-lg" role = "document">
        <div class = "modal-content">
            <div class="modal-header">
                <h4 class = "modal-title">
                    <i class = "fas fa-fw fa-clipboard-list animated rotate"></i>
                    <span id = "title_text">Par&aacute;metros</span>
                </h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class = "modal-body">
                <div class = "row">
                    <div class = "col-12 col-md-6">
                        <div class = "alert alert-info" role = "alert">
                            <h4 class = "alert-heading"> IP </h4>
                            <p id = "log_ip"></p>
                        </div>
                    </div>

                    <div class = "col-12 col-md-6">
                        <div class = "alert alert-info" role = "alert">
                            <h4 class = "alert-heading"> URL </h4>
                            <p id = "log_url"></p>
                        </div>
                    </div>
                </div>

                <div class = "row">
                    <div class = "col-12 col-md-6">
                        <div class = "alert alert-info" role = "alert">
                            <h4 class = "alert-heading"> Usuario </h4>
                            <p id = "log_user"></p>
                        </div>
                    </div>

                    <div class = "col-12 col-md-6">
                        <div class = "alert alert-info" role = "alert">
                            <h4 class = "alert-heading"> Acción </h4>
                            <p id = "log_description"></p>
                        </div>
                    </div>
                </div>

                <div class = "row">
                    <div class = "form-group col-md-12">
                        <label> Get </label>
                        <div style = "overflow-x: auto;">
                            <table class = "table table-bordered custom-table" id = "table_get" style = "margin-bottom: 20px !important">
                                <thead></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class = "row">
                    <div class = "col-md-12">
                        <label> Post </label>
                        <div style = "overflow-x: auto;">
                            <table class = "table table-bordered custom-table" id = "table_post" style = "margin-bottom: 20px !important">
                                <thead></thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class = "modal-footer">
                <button type = "button" class = "btn btn-success" data-dismiss = "modal"><i class = "fas fa-fw fa-thumbs-up"></i> Aceptar</button>
            </div>
        </div>
    </div>
</div>
</div>

<script nonce = "<?= $nonce_hash ?>">
	$(document).ready(function() {
        var logs;
        var table_log = initDataTable("#table_log");

        function buttonShow( log ) {
            var button = $("<a></a>");
            button.attr("href", "#");
            button.attr("log", log.identifier);
            button.attr("data-placement", "top");
            button.attr("data-toggle", "tooltip");
            button.attr("data-title", "Vista detallada");
            button.attr("class", "btn btn-sm btn-info show_params");
            var icon = $("<i></i>").attr("class", "fas fa-eye");
            button.append(icon);
            return button.prop("outerHTML");
        }

        function loadLogs() {
            table_log.clear().draw();
            
            if ( $(logs).length == 0 ) {
                return false;
            }

            $(logs).each(function( _, log ) {
                fila = $("<tr></tr>");

                columna_fecha = $("<td></td>");
                columna_fecha.html(moment(log.created).format('LLLL'));

                columna_user = $("<td></td>");
                columna_user.html(log.user);

                columna_accion = $("<td></td>");
                columna_accion.html(log.description);

                columna_ip = $("<td></td>");
                columna_ip.html(log.ip);

                columna_url = $("<td></td>");
                columna_url.html(log.url);

                columna_acciones = $("<td></td>");
                columna_acciones.html(
                    buttonShow(log)
                );

                fila.append(columna_fecha);
                fila.append(columna_user);
                fila.append(columna_accion);
                fila.append(columna_ip);
                fila.append(columna_url);
                fila.append(columna_acciones);
                
                table_log.row.add(fila).draw();
            });

            $('[data-toggle="tooltip"]').tooltip();
        }

        $(".date").datetimepicker({
            locale: '<?= $_SESSION[SysConstants::SESS_PARAM_ISO_639_1] ?>',
            format: "LLL",
            maxDate: moment()
        });

        $("#search_logs_button").on("click", function() {
            if ( $("#fecha_inicio input").val().length == 0 ) {
                noty.show("error", "Debe especificar una fecha de inicio");
                $("#fecha_inicio input").focus();
                return false;
            }

            if ( $("#fecha_fin input").val().length == 0 ) {
                noty.show("error", "Debe especificar una fecha de fin");
                $("#fecha_fin input").focus();
                return false;
            }

            /* get date from datetimepicker using moment object */
            var end = $("#fecha_fin").data("DateTimePicker").date().format();
            var start = $("#fecha_inicio").data("DateTimePicker").date().format();

            action = $("#filters_form select[name=actions]").val();

            $.ajax({
                type: "POST",
                url: "/bitacora/find_by_filters",
                data: {
                    "end" : end,
                    "start" : start,
                    "action" : action
                },
                success: function( response ) {
                    handleJsonResponse(response, function() {
                        logs = response.logs;
                        loadLogs();
                    });
                }
            });
        });

        $("body").on("click", ".show_params", function( event ) {
            event.preventDefault();

            log = undefined;
            log_id = $(this).attr("log");

            $(logs).each(function( _, logg ) {
                if ( logg.identifier == log_id ) {
                    log = logg;
                    return false;
                }
            });

            if ( log == undefined ) {
                noty.show("danger", "No se pudo editar el log");
                return false;
            }

            $("#log_ip").html(log.ip);
            $("#log_url").html(log.url);
            $("#log_user").html(log.user);
            $("#log_description").html(log.description);

            $("#table_get thead").empty();
            $("#table_get tbody").empty();

            if ( $(log.params_get).length > 0 ) {
                tr_body = $("<tr></tr>");
                tr_thead = $("<tr></tr>");

                for ( var index_get in log.params_get ) {
                    td_body = $("<td></td>");
                    td_body.html(log.params_get[index_get]);

                    td_thead = $("<th></th>");
                    td_thead.html(index_get);

                    $(tr_body).append(td_body);
                    $(tr_thead).append(td_thead);
                }

                $("#table_get tbody").append(tr_body);
                $("#table_get thead").append(tr_thead);
            }

            $("#table_post thead").empty();
            $("#table_post tbody").empty();

            if ( $(log.params_post).length > 0 ) {
                tr_body = $("<tr></tr>");
                tr_thead = $("<tr></tr>");

                for ( var index_post in log.params_post ) {
                    td_body = $("<td></td>");
                    td_body.html(log.params_post[index_post]);

                    td_thead = $("<th></th>");
                    td_thead.html(index_post);

                    $(tr_body).append(td_body);
                    $(tr_thead).append(td_thead);
                }

                $("#table_post tbody").append(tr_body);
                $("#table_post thead").append(tr_thead);
            }

            $("#modal_params").modal('show');
        });
    });
</script>