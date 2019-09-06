<?php
?>
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-primary">Grupos</h3> </div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item active">Grupos</li>
        </ol>
    </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-sm-responsive">
                        <table class="table table-bordered table-condensed" id="tablaParticipantes">
                            <thead>
                                <tr>
                                    <th class="all">Folio</th>
                                    <th class="all">Nombre</th>
                                    <th class="all">Teléfono</th>
                                    <th class="none">Edad</th>
                                    <th class="none">Dirección</th>
                                    <th class="none">Correo</th>
                                    <th class="all">Núm. de acompañantes</th>
                                    <th class="desktop">Oficio</th>
                                    <th class="desktop">Adscripción</th>
                                    <th class="desktop">Registro</th>
                                    <th class="desktop">Asistencia</th>
                                    <th class="all">Acciones</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script nonce = "<?= $nonce_hash ?>">
    $(function () {
        var  dataService = [];
        var services = {
            getTodosLosParticipantesRegistrados:function(){
            return $.ajax({
                type:'get',
                url:'/grupos/get_todos_los_participantes_registrados'
            })
            }
        };

    var  dataTableConfig = {
         "stateSave": true,
         data: [],
         columns : [
             {data: 'name_column', sClass:"text-center"},
             {data: 'name_column', sClass:"text-center"},
             {data: 'name_column', sClass:"text-center"},
             {data: 'name_column', sClass:"text-center"},
             {data: 'name_column', sClass:"text-center"},
             {data: 'name_column', sClass:"text-center"},
             {data: 'name_column', sClass:"text-center"},
             {data: 'name_column', sClass:"text-center"},
             {data: 'name_column', sClass:"text-center"},
             {data: 'name_column', sClass:"text-center"},
             {data: 'name_column', sClass:"text-center"},
             {data: null, sClass:"text-center"},
         ],
         aoColumnDefs : [
                 {
                  'aTargets': [1],
                  'mRender': function (data) {
                   return btnEdit(data)+' '+btnDelete(data)}

                 },
             ]
         };

          function btnEdit(data) {
              var button = $("<a></a>");
              button.attr("href", "#");
              button.attr("data-toggle", "tooltip");
              button.attr("data-placement", "top");
              button.attr("title", "Editar");
              button.attr("id-db",data.id);
              button.attr("class", "btn btn-primary btn-xs btn-edit");
              var icon = $("<i></i>").attr("class", "fa fa-edit");
              button.append(icon);
              return button.prop("outerHTML");
          }
          function btnDelete(data) {
              var button = $("<a></a>");
              button.attr("href", "#");
              button.attr("data-toggle", "tooltip");
              button.attr("data-placement", "top");
              button.attr("title", "Eliminar");
              button.attr("id-db",data.id);
              button.attr("class", "btn btn-danger btn-xs btn-delete");
              var icon = $("<i></i>").attr("class", "fa fa-trash");
              button.append(icon);
              return button.prop("outerHTML");
          }

          function tableAll() {
              $.when(services.getTodosLosParticipantesRegistrados()).then(function(response){
                         dataService = response;
                  var conf = $.extend( dataTableConfig, {data: response});
                  CustomDataTable.applyCustomStyleTo("#tablaParticipantes",conf);
                  $('[data-toggle="tooltip"]').tooltip();
              });
          }

          tableAll();
    });
</script>