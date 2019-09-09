<?php
$adscripciones = $dataAndView->getData('adscripciones');
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
                    <div class="row">
                        <div class="col-12 col-sm-6 md-6 col-lg-3 col-xl-3">
                            <label for="">Fecha inicio</label>
                            <input type="text" name="" id="fechaInicio" class="form-control form-group date" placeholder="Día/mes/año HH:mm:ss">
                        </div><div class="col-12 col-sm-6 md-6 col-lg-3 col-xl-3">
                            <label for="">Fecha final</label>
                            <input type="text" name="" id="fechaFinal" class="form-control form-group date"placeholder="Día/mes/año HH:mm:ss">
                        </div>
                        <div class="col-12 col-sm-6 md-6 col-lg-3 col-xl-3">
                            <label for="">Adscripción</label>
                            <select name="" id="tipoAdscripcion" class="form-control form-group select2">
                                <option value="">Todos</option>
                                <?php if(!empty($adscripciones)){
                                    foreach ($adscripciones as $item){?>
                                        <option value="<?=$item->getId()?>"> <?=$item->getNombre()?></option>
                                    <?php }
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 md-6 col-lg-3 col-xl-3">
                            <label for="">Núm. acompañantes</label>
                            <select name="" id="numAcompanantes" class="form-control form-group select2">
                                <option value="">Todos</option>
                                <option value="0">Ningún acompañante</option>
                                <option value="1">1 acompañante</option>
                                <option value="2">2 acompañantes</option>
                                <option value="3">3 acompañantes</option>
                                <option value="4">4 acompañantes</option>
                                <option value="5">5 acompañantes</option>
                                <option value="6">6 acompañantes</option>
                                <option value="7">7 acompañantes</option>
                                <option value="8">8 acompañantes</option>
                                <option value="9">9 acompañantes</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-12 col-lg-12 text-right">
                            <button class="btn btn-primary" id="aplicarFiltro">Aplicar filtro</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
                                    <th class="none">Acompañantes</th>
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
        CustomDateTimePicker.applyCustomStyleTo('.date');
        var  dataService = [];
        var services = {
            getTodosLosParticipantesRegistrados:function(fechaInicio, fechaFinal, tipoAdscripcion, numAcompanantes){
                return $.ajax({
                    type:'post',
                    url:'/grupos/get_todos_los_participantes_registrados',
                    data:{
                        fechaInicio:fechaInicio,
                        fechaFinal:fechaFinal,
                        tipoAdscripcion:tipoAdscripcion,
                        numAcompanantes:numAcompanantes
                    }
                })
            },
            getParticipantesPorLiderId:function (id_lider) {
                return $.ajax({
                    type:'post',
                    url:'/grupos/get_participantes_por_lider_id',
                    data:{
                        id_lider:id_lider
                    }
                })
            }

        };

    var  dataTableConfig = {
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
             {data: 'acompanantes_nombres', sClass:"text-center"},
             {data: 'fecha_creacion', sClass:"text-center"},
             {data: null, sClass:"text-center"},
             {data: null, sClass:"text-center"},
         ],
         aoColumnDefs : [
                 {
                  'aTargets': [11],
                  'mRender': function (data) {
                   return estatusAsistencia(data)}

                 },{
                  'aTargets': [12],
                  'mRender': function (data) {
                   return btnParticipantes(data)+' '+btnAsistencia(data)}

                 },
             ]
         };

          function btnParticipantes(data) {
              if(data.acompanantes > 0) {
                  var button = $("<a></a>");
                  button.attr("href", "#");
                  button.attr("data-toggle", "tooltip");
                  button.attr("data-placement", "top");
                  button.attr("title", "Ver grupo de participantes");
                  var icon = $("<i></i>").attr("class", "fas fa-users-class text-info fa-lg accion-participantes");
                  icon.attr("id-db", data.id);
                  button.append(icon);
                  return button.prop("outerHTML");
              }else{
                  return '';
              }
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
          function tableAll(fechaInicio, fechaFinal, tipoAdscripcion, numAcompanantes) {
              $.when(services.getTodosLosParticipantesRegistrados(fechaInicio, fechaFinal, tipoAdscripcion, numAcompanantes)).then(function(response){
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

     $('body').on('click','#aplicarFiltro',function (e) {
         e.preventDefault();

        if ($('#fechaInicio').val() != ''){
            if (vacio('#fechaFinal','El campo fecha final')){return false}
        }
        if ($('#fechaFinal').val() != ''){
            if (vacio('#fechaInicio','El campo fecha inicio')){return false}
        }
        var fechaInicio = ($('#fechaInicio').val() != '')?$('#fechaInicio').data("DateTimePicker").date().format() : '';
        var fechaFinal =  ($('#fechaFinal').val() != '' )?$('#fechaFinal').data("DateTimePicker").date().format() : '';
        var tipoAdscripcion = $('#tipoAdscripcion').val();
        var numAcompanantes = $('#numAcompanantes').val();
         tableAll(fechaInicio, fechaFinal, tipoAdscripcion, numAcompanantes)
     })

    });
</script>