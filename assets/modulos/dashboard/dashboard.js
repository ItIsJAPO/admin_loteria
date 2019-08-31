$(function () {
    var services = {};
    var dataServices;

    services.getAllFacturas = function(){
        return $.ajax({
            type:'post',
            url:'/dashboard/get_all_billing'
        })
    };
    services.getClientById = function(id_client){
        return $.ajax({
            type:'post',
            url:'/dashboard/get_client_by_id',
            data:{
                id:id_client
            }
        })
    };
    services.getBoatById= function(id_boat){
        return $.ajax({
            type:'post',
            url:'/dashboard/get_boat_by_id',
            data:{
                id:id_boat
            }
        })
    };
    services.dataDivisas= function(id){
        return $.ajax({
            type:'post',
            url:'/dashboard/data_divisas',
            data:{
                id_divisa:id
            }
        })
    };

    var  dataTableConfig = {
        bStateSave: false,
        select:true,
        paging:false,
        data: []
    };
    dataTableConfig.columns = [
        {data: 'name_service', sClass:"text-center"},
        {data: 'name_rate', sClass:"text-center"},
        {data: 'name_boat', sClass:"text-center"},
        {data: 'uab', sClass:"text-center"},
        {data: 'muelle', sClass:"text-center"},
        {data: null,  width: "200x!important",sClass:"text-center"},
        {data: 'service_time', sClass:"text-center"},
        {data: 'time_dif', sClass:"text-center"},
        {data: 'type_change', sClass:"text-center"},
        {data: 'foreign_rate', sClass:"text-center"},
        {data: 'subtotal', sClass:"text-center"},
        {data: 'current_exchange_rate', sClass:"text-center"},
        {data: 'name_tugboats', sClass:"text-center"},
        {data: 'discounts', sClass:"text-center"},
        {data: 'surcharge', sClass:"text-center"},
        {data: null, sClass:"text-center"},
        {data: 'service_concept', sClass:"text-center"},
        {data: null, sClass:"text-center"},
    ];
    dataTableConfig.aoColumnDefs = [
        {
            'aTargets': [5],
            'mRender': function (data) {
                return dateService(data)}
        },
        {
            'aTargets': [15],
            'mRender': function (data) {
                return dateCreate(data)}
        },
        {
            'aTargets': [17],
            'mRender': function (data) {
                return btnEditConcept(data)+' ' +btnDelete(data)}
        }
    ];
    function dateService(data) {
        var service = moment(data.date_service);
        service.locale('es');
        return  service.format('LL');
    }
    function dateCreate(data) {
        var day = moment(data.creation_date);
        day.locale('es');
      return  day.format('LLLL');
    }

    function btnDelete(data) {
        var button = $("<a></a>");
        button.attr("href", "#");
        button.attr("id-db", data.id);
        button.attr("data-placement", "top");
        button.attr("data-toggle", "tooltip");
        button.attr("data-title", "Eliminar");
        button.attr("title", "Eliminar");
        button.attr("class", "btn btn-danger btn-xs delete-service");
        var icon = $("<i></i>").attr("class", "fa fa-trash");
        button.append(icon);
        return button.prop("outerHTML");
    }
    function btnEditConcept(data) {
        var button = $("<a></a>");
        button.attr("href", "#");
        button.attr("id-db", data.id);
        button.attr("data-placement", "top");
        button.attr("data-toggle", "tooltip");
        button.attr("data-title", "Editar concepto");
        button.attr("title", "Editar concepto");
        button.attr("class", "btn btn-primary btn-xs edit-concept");
        var icon = $("<i></i>").attr("class", "fas fa-pencil-alt");
        button.append(icon);
        return button.prop("outerHTML");
    }
var table = {};
    function tableAll () {
        $.when(services.getAllFacturas())
            .then(function(response){
                dataServices = response;
                (response.length >= 1)? $('#saveCapturedServices').removeAttr('disabled') : $('#saveCapturedServices').attr('disabled','disabled');

                var conf = $.extend( dataTableConfig, {data: response});
               table = CustomDataTable.applyCustomStyleTo("#tablaFacturas",conf);
                $('[data-toggle="tooltip"]').tooltip();

                table.on( 'select', function ( e, dt, type, indexes ) {
                    var rowData = table.rows( indexes ).data().toArray();
                    $('#subtotal').val(rowData[0].subtotal);
                    $('#iva').val(rowData[0].iva);
                    $('#total').val(rowData[0].last_price);
                } )
            });
    };

    tableAll();


    $('body').on('change','#clients',function (e) {
        e.preventDefault();
        var id = $(this).val();
        if(id == null){
            $('#clientRfc').val('');
            $('#clientPais').val('');
            $('#clienEmail').val('');
            $('#clientMunicipio').val('');
            $('#clientRfc').removeAttr( "title" );
            $('#clientPais').removeAttr( "title" );
            $('#clientMunicipio').removeAttr( "title" );

        }else {
            $.when(services.getClientById(id)).then(function (r) {
                $('#clientRfc').val(r.rfc);
                $('#clientPais').val(r.pais);
                $('#clientMunicipio').val(r.municipio);
                $('#clientTypeChange').val(r.money_exchange_rate).trigger('change');
                $('#clienEmail').val(r.email);
                //agregar attr titulo
                $('#clientRfc').attr('title',r.rfc);
                $('#clientPais').attr('title',r.pais);
                $('#clientMunicipio').attr('title',r.municipio);
            })
        }
    })

    $('body').on('change','#boats',function (e) {
        e.preventDefault();
        var id = $(this).val();
        if(id == null){
            $('#boatsUab').val('');
            $('#boatsEslora').val('');
            $('#boatsTypeLoad').val('');

            $('#boatsUab').removeAttr( "title" );
            $('#boatsEslora').removeAttr( "title" );
            $('#boatsTypeLoad').removeAttr( "title" );

        }else {
            $.when(services.getBoatById(id)).then(function (r) {
                $('#boatsUab').val(r.uab);
                $('#boatsEslora').val(r.length);
                $('#boatsTypeLoad').val(r.name_type_load);
                //agregar attr titulo
                $('#boatsUab').attr('title',r.uab);
                $('#boatsEslora').attr('title',r.length);
                $('#boatsTypeLoad').attr('title',r.name_type_load);

            })
        }
    });
    //sin tasa de cambio
    $('#sinCambio').click(function (e) {
        e.preventDefault();
        $('#clientMoney').val('');
    });

    $('body').on('change','#clientTypeChange',function (e) {
        e.preventDefault();
        var id = $(this).val();
        if(id == null){
            $('#clientMoney').val('');
        }else {
            $.when(services.dataDivisas(id)).then(function (r) {
                $('#clientMoney').val(r.cambio);
            })
        }
    })

    //modal aplicar descuentos o sobre cuotas a una enbarcación
    var dataDiscounts=new Array();

    var  discountsConfig = {
            "bStateSave": false,
            "searching": false,
            'info':false,
            data: []
        };

        discountsConfig .columns = [
            {data: 'rode', sClass:"text-center"},
            {data: 'type_action', sClass:"text-center"},
            {data: 'type_rode', sClass:"text-center"},
            {data: 'reason', sClass:"text-center"},
            {data: null, sClass:"text-center"},
        ];
        discountsConfig .aoColumnDefs = [
            {
                'aTargets': [4],
                'mRender': function (data) {
                    return btnRemove(data)}

            },
        ];

        function btnRemove(data) {
            var button = $("<a></a>");
            button.attr("href", "#");
            button.attr("data-placement", "top");
            button.attr("id-data", data.id);
            button.attr("data-toggle", "tooltip");
            button.attr("data-title", "Eliminar");
            button.attr("title", "Eliminar");
            button.attr("class", "btn btn-xs btn-danger delete-action");
            var icon = $("<i></i>").attr("class", "fa fa-trash");
            button.append(icon);
            return button.prop("outerHTML");
        }

        function tableDiscounts () {
            var newData = new Array()

            $.each( $(dataDiscounts), function( key, value ) {
             newData.push({id:key,
                     rode:value.rode,
                     type_action:value.type_action==1?'Descuento':'Sobrecargo',
                     type_rode:value.type_rode==1?'Cantidad':'Porcentaje',
                     reason:value.reason
                    });
            });
            var conf = $.extend( discountsConfig , {data: newData});
            CustomDataTable.applyCustomStyleTo("#tableDiscounts",conf);
            $('[data-toggle="tooltip"]').tooltip();
        };

        tableDiscounts();

    $('body').on('click','#descuentos',function (e) {
        e.preventDefault();
        $('#ModalDiscounts').modal();
    });

    //accion de botón de guardar en el modal
    $('body').on('click','#save',function (e) {
        e.preventDefault();
        var check_action = $("#buttonOption1").is(":checked")?1:2;
        var tipo_cantidad = $("#descuentoRadio1").is(":checked")?1:2;
        var monto = $('#monto').val();
        var motivo =$('#motivo').val();

        if(vacio('#monto','El monto')){
            return false;
        }

        if(vacio('#motivo','El campo motivo')){
            return false;
        }

        if(tipo_cantidad == 2 && monto >= 100){
            noty.show('danger','El porcentaje de descuento no puede ser mayor al cien porciento');
            return false
        }

        dataDiscounts.push({rode:monto,type_action:check_action,type_rode:tipo_cantidad,reason:motivo});
        tableDiscounts();
        $('#ModalDiscounts').modal('hide');
    });

    //eliminar un dato de la tabla
    $('body').on('click','.delete-action',function (e) {
        e.preventDefault();
        var id = $(this).attr('id-data');
        confirmMessage('¿Realmente deseas eliminar un reguistro?',function () {
                    dataDiscounts.splice(id, 1);
                    tableDiscounts();
        })
    });

    //evento al cerrace el modal limpiar el formulario

    $("#ModalDiscounts").on('hidden.bs.modal', function () {
        $("#btnOption1").trigger("click");
        $("#descuentoRadio1").trigger("click");
        $('#monto').val('');
        $('#motivo').val('');
    });

    $('#btnOption2').click(function () {
       $('#tipoAjuste').html('Sobrecargo');
       $('#monto').attr('placeholder','Monto de sobrecargo')
    });

    $('#btnOption1').click(function () {
       $('#tipoAjuste').html('Descuento');
       $('#monto').attr('placeholder','Monto de descuento')
    });

    // captura del formularaio para guardar la información de la factura.

    services.saveDataFacture= function(formData){
        return $.ajax({
            type:'post',
            url:'/dashboard/save_data_billing',
            cache:false,
            processData: false,
            contentType: false ,
            data:formData
        })
    };
    //select remolcador uno
    $('body').on('change','#remolcadorUno',function () {
        var remolcador = $(this).val();

        if($('#remolcadorDos').val() == remolcador || $('#remolcadorTres').val() == remolcador){
            noty.show('danger','El remolcador que selecciono esta ciendo utilizado');
        }

        if(remolcador == null || remolcador =='' || remolcador == undefined){
            $('#remolcadorDos').val('').trigger('change');
            $('#remolcadorTres').val('').trigger('change');
            $('#remolcadorDos').attr('disabled','disabled');
            $('#remolcadorTres').attr('disabled','disabled');
        }else{
            $('#remolcadorDos').removeAttr('disabled');
        }
    });
    //select remolcador dos
    $('body').on('change','#remolcadorDos',function () {
        var remolcador = $(this).val();

        if($('#remolcadorUno').val() == remolcador || $('#remolcadorTres').val() == remolcador){
            noty.show('danger','El remolcador que selecciono esta ciendo utilizado');
        }

        if(remolcador == null || remolcador =='' || remolcador == undefined){
            $('#remolcadorTres').val('').trigger('change');
            $('#remolcadorTres').attr('disabled','disabled');
        }else{
            $('#remolcadorTres').removeAttr('disabled');
        }
    });
    $('body').on('change','#remolcadorTres',function () {
        var remolcador = $(this).val();

        if($('#remolcadorUno').val() == remolcador || $('#remolcadorDos').val() == remolcador){
            noty.show('danger','El remolcador que selecciono esta ciendo utilizado');
        }
    });


    //limpiar formulario de captura de factura
    $('body').on('click','#clearForm',function () {
        dataDiscounts=[];
        tableDiscounts();
        $('#remolcadorTres').attr('disabled','disabled');
        $('#remolcadorDos').attr('disabled','disabled');
        $('#wayToPay').val('').trigger('change');
        $('#trafico').val('').trigger('change');
        $('#clients').val('').trigger('change');
        $('#boats').val('').trigger('change');
        $('#clientTypeChange').val('');
        $('#serviceConcept').val('');
        $('#clientMunicipio').val('');
        $('#remolcadorTres').val('');
        $('#desplazamiento').val('');
        $('#remolcadorUno').val('');
        $('#remolcadorDos').val('');
        $('#boatsTypeLoad').val('');
        $('#dateBilling').val('');
        $('#boatsEslora').val('');
        $('#dateService').val('');
        $('#clientPais').val('');
        $('#numberTrip').val('');
        $('#clienEmail').val('');
        $('#clientRfc').val('');
        $('#hourStart').val('');
        $('#services').val('');
        $('#boatsUab').val('');
        $('#subtotal').val('');
        $('#hourEnd').val('');
        $('#calado').val('');
        $('#total').val('');
        $('#docks').val('');
        $('#rate').val('');
        $('#iva').val('');
    });


    $('body').on('click','#aplicarFactura',function (e) {
        e.preventDefault();

        if(vacio('#clients','seleccione un cliente')){return false;}
        if(vacio('#clientTypeChange','Tipo de cambio')){return false;}
        if(vacio('#wayToPay','Forma de pago')){return false;}
        if(vacio('#remolcadorUno','Un remolcador')){return false;}
        if(vacio('#boats','Un barco')){return false;}
        if(vacio('#numberTrip','Número de viaje')){return false;}
        if(vacio('#trafico','El tráfico')){return false;}
        if(vacio('#services','Un servicio')){return false;}
        if(vacio('#docks','El muelle')){return false;}
        if(vacio('#rate','Una tarifa de cobro')){return false;}
        if(vacio('#dateService','Fecha del servicio')){return false;}
        if(vacio('#hourStart','hora de inicio')){return false;}
        if(vacio('#hourEnd','hora de finalización')){return false;}

        var dataTug = new Array();
        if($('#remolcadorUno').val()!= '' && $('#remolcadorDos').val() != '' && $('#remolcadorTres').val() != ''){
            dataTug=[$('#remolcadorUno').val(),$('#remolcadorDos').val(),$('#remolcadorTres').val()];
        }else if($('#remolcadorUno').val()!= '' && $('#remolcadorDos').val() != '') {
            dataTug = [$('#remolcadorUno').val(), $('#remolcadorDos').val()];
        }else if($('#remolcadorUno').val()!=''){
            dataTug=[$('#remolcadorUno').val()];
        }
        var formData = new FormData();
        formData.append('desplazamiento',$('#desplazamiento').val());
        formData.append('typeChange',$('#clientTypeChange').val());
        formData.append('dateBilling',$('#dateBilling').val());
        formData.append('dateService',$('#dateService').val());
        formData.append('numberTrip',$('#numberTrip').val());
        formData.append('hourStart',$('#hourStart').val());
        formData.append('wayToPay',$('#wayToPay').val());
        formData.append('services',$('#services').val());
        formData.append('datas',JSON.stringify(dataDiscounts));
        formData.append('trafico',$('#trafico').val());
        formData.append('hourEnd',$('#hourEnd').val());
        formData.append('client',$('#clients').val());
        formData.append('calado',$('#calado').val());
        formData.append('docks',$('#docks').val());
        formData.append('boat',$('#boats').val());
        formData.append('rate',$('#rate').val());
        formData.append('tugs',dataTug);

        $.when(services.saveDataFacture(formData)).then(function (response) {
            jsonNotyfication(response);
            tableAll();
        });
        
    });
    
//    delete action confirm 
    $('body').on('click','.delete-service',function (e) {
        e.preventDefault();
        var id = $(this).attr('id-db');
        confirmMessage('¿Realmente deseas eliminar la captura del servicio?',function () {
           $.ajax({
               type:'post',
               url:'/dashboard/delete_service_by_id',
               data:{id:id},
               success:function (response) {
                   jsonNotyfication(response);
                   tableAll();
               }
           })
        })
    });

//    botón de editar concepto del servicio;
    $('body').on('click','.edit-concept',function (e) {
        e.preventDefault();
        var id = $(this).attr('id-db');
        $('#saveConcept').attr('id-db',id);
        $('#modalEditConcept').modal();
        $.each( $(dataServices), function( _, value ) {
             if(value.id == id){
                 $('#conpetTextarea').val(value.service_concept);
             }
        });
    })
    //update concept of service;
    $('body').on('click','#saveConcept',function (e) {
        e.preventDefault();
        var id = $(this).attr('id-db');
        var concepto = $('#conpetTextarea').val();

        $.ajax({
            type:'post',
            url:'/dashboard/update_concept_service_by_id',
            data:{
                id:id,
                concept:concepto,
            },
            success:function (r) {
                $('#modalEditConcept').modal('hide');
                jsonNotyfication(r);
                tableAll();
            }
        });
    });
    //Guardar información de captura del servicio.
    $('body').on('click','#saveCapturedServices',function (e) {
        e.preventDefault();
        $.ajax({
            type:'post',
            url:'/dashboard/save_tow_service_capture',
            success:function (r) {
                jsonNotyfication(r);
                tableAll();
            }
        })
    })

});