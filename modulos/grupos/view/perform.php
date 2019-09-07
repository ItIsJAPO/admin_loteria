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
                                    <th class="desktop">Oficio</th>
                                    <th class="desktop">Adscripción</th>
                                    <th class="all">Núm. de acompañantes</th>
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
<?php include 'modal/modal_participantes.php'?>
<script nonce = "<?= $nonce_hash ?>">
    $(function () {
        var  dataService = [];
        var services = {
            getTodosLosParticipantesRegistrados:function(){
                return $.ajax({
                    type:'get',
                    url:'/grupos/get_todos_los_participantes_registrados'
                })
            },
            getParticipantesPorLiderId:function (id_lider) {
                return $.ajax({
                    type:'Post',
                    url:'/grupos/get_participantes_por_lider_id',
                    data:{
                        id_lider:id_lider
                    }
                })
            }

        };

    var  dataTableConfig = {
         "stateSave": true,
         data: [],
         columns : [
             {data: 'id', sClass:"text-center"},
             {data: 'nombre', sClass:"text-center"},
             {data: 'telefono', sClass:"text-center"},
             {data: 'edad', sClass:"text-center"},
             {data: 'direccion', sClass:"text-center"},
             {data: 'email', sClass:"text-center"},
             {data: 'tipo_universitario', sClass:"text-center"},
             {data: 'tipo_adscripcion', sClass:"text-center"},
             {data: 'acompanantes', sClass:"text-center"},
             {data: 'fecha_creacion', sClass:"text-center"},
             {data: null, sClass:"text-center"},
             {data: null, sClass:"text-center"},
         ],
         aoColumnDefs : [
                 {
                  'aTargets': [10],
                  'mRender': function (data) {
                   return estatusAsistencia(data)}

                 },{
                  'aTargets': [11],
                  'mRender': function (data) {
                   return btnParticipantes(data)+' '+btnAsistencia(data)}

                 },
             ]
         };

          function btnParticipantes(data) {
              var button = $("<a></a>");
              button.attr("href", "#");
              button.attr("data-toggle", "tooltip");
              button.attr("data-placement", "top");
              button.attr("title", "Ver grupo de participantes");
              var icon = $("<i></i>").attr("class", "fas fa-users-class text-info fa-lg accion-participantes");
              icon.attr("id-db",data.id);
              button.append(icon);
              return button.prop("outerHTML");
          }
          function estatusAsistencia(data) {
              var span = $("<span></span>");
              if(parseInt(data.asistencia) == 0){
                  span.attr('class','badge badge-danger');
                  span.html('Sin asistencia');
              }else{
                  span.attr('class','badge badge-success');
                  span.html('Verificado');
              }
              return span.prop("outerHTML");
          }
          function btnAsistencia(data) {
              var button = $("<a></a>");
              button.attr("href", "#");
              button.attr("data-toggle", "tooltip");
              button.attr("data-placement", "top");
              if(data.asistencia == 0){
                  button.attr("title", "Marcar  asistencia");
                  var icon = $("<i></i>").attr("class", "fas fa-check-circle text-success fa-lg accion-asistencia");
              }else{
                  button.attr("title", "Marcar como sin asistir");
                  var icon = $("<i></i>").attr("class", "fas fa-times-circle text-danger fa-lg accion-asistencia");
              }
              icon.attr('status',data.asistencia);
              icon.attr('name-user',data.nombre);
              icon.attr("id-db",data.id);
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
          
          $('body').on('click','.accion-asistencia',function (e) {
              e.preventDefault();
              var nombre = $(this).attr('name-user');
              var estatus = $(this).attr('status');
              var id = $(this).attr('id-db');
              var idLider =  $('#modalParticipantes').attr('id-lider');
              confirmMessage('Realmente desea cambiar el estatus de asistencia de '+nombre,function () {
                  $.ajax({
                      type:'post',
                      url:'/grupos/cambiar_estatus_de_participante',
                      data:{
                          id:id
                      },
                      success:function (response) {
                          jsonNotyfication(response);
                          tableAll();
                          if(idLider != '') {
                              tableAsistencia(idLider);
                          }
                      }
                  })
              })
          });

        $('body').on('click','.accion-participantes',function (e) {
            e.preventDefault();
            var id = $(this).attr('id-db');
            $('#modalParticipantes').modal();
            $('#modalParticipantes').attr('id-lider',id);
            tableAsistencia(id);
        });

    var  dataTableConfigAsistencia = {
        "stateSave": true,
        data: [],
        columns : [
            {data: 'id', sClass:"text-center"},
            {data: 'nombre', sClass:"text-center"},
            {data: 'edad', sClass:"text-center"},
            {data: null, sClass:"text-center"},
            {data: null, sClass:"text-center"},
        ],
        aoColumnDefs : [
            {
                'aTargets': [3],
                'mRender': function (data) {
                    return estatusAsistencia(data)}

            },{
                'aTargets': [4],
                'mRender': function (data) {
                    return btnAsistencia(data)}
            },
            ]
        };

         function tableAsistencia(id_lider) {
             $.when(services.getParticipantesPorLiderId(id_lider)).then(function(response){
                 var conf = $.extend( dataTableConfigAsistencia, {data: response});
                 CustomDataTable.applyCustomStyleTo("#tablaAsistencia",conf);
                 $('[data-toggle="tooltip"]').tooltip();
             });
         }


    });
</script>