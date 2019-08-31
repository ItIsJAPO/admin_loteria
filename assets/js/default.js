$.fn.modal.Constructor.prototype._enforceFocus = function() {};
$.fn.select2.defaults.set("theme", "bootstrap4");

/**
 * Usa jsonNotyfication para mostrar notificaciones al usuario en formato por default por error.
 * @param aviso Este llevará el cuerpo del error.
 * @param type Este lleva por default 'danger'. Puede ser cambiado sobreecribiendo el párameto.
 * @constructor
 */
function UserException(aviso, type = 'danger') {
    this.aviso = aviso;
    this.type = "UserException";
}

UserException.prototype.toString = function () {
    return jsonNotyfication({
        type: 'danger',
        message: this.aviso
    })
};

$.fn.extend({
    animateCss : function(animationName, complete) {
        var self = $(this);
        var animationEnd = 'webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend';
        var animateCssClass = ["animated", "bounce", "flash", "pulse", "rubberBand", "shake", "headShake", "swing", "tada", "wobble", "jello", "bounceIn", "bounceInDown", "bounceInLeft", "bounceInRight", "bounceInUp", "bounceOut", "bounceOutDown", "bounceOutLeft", "bounceOutRight", "bounceOutUp", "fadeIn", "fadeInDown", "fadeInDownBig", "fadeInLeft", "fadeInLeftBig", "fadeInRight", "fadeInRightBig", "fadeInUp", "fadeInUpBig", "fadeOut", "fadeOutDown", "fadeOutDownBig", "fadeOutLeft", "fadeOutLeftBig", "fadeOutRight", "fadeOutRightBig", "fadeOutUp", "fadeOutUpBig", "flipInX", "flipInY", "flipOutX", "flipOutY", "lightSpeedIn", "lightSpeedOut", "rotateIn", "rotateInDownLeft", "rotateInDownRight", "rotateInUpLeft", "rotateInUpRight", "rotateOut", "rotateOutDownLeft", "rotateOutDownRight", "rotateOutUpLeft", "rotateOutUpRight", "hinge", "rollIn", "rollOut", "zoomIn", "zoomInDown", "zoomInLeft", "zoomInRight", "zoomInUp", "zoomOut", "zoomOutDown", "zoomOutLeft", "zoomOutRight", "zoomOutUp", "slideInDown", "slideInLeft", "slideInRight", "slideInUp", "slideOutDown", "slideOutLeft", "slideOutRight", "slideOutUp"];
        $.each(animateCssClass, function(_, c){
            if(self.hasClass(c)){
                self.removeClass(c);
            }
        });
        self.addClass('animated ' + animationName).one(animationEnd, function() {
            self.removeClass('animated ' + animationName);
            if (complete)
                complete();
        });
    }
});

var CustomDateTimePicker = {
    translateTooltips: {
        today: 'Todos los días',
        clear: 'limpiar selección',
        close: 'Salir',
        selectMonth: 'Seleccione un mes',
        prevMonth: 'Mes anterior',
        nextMonth: 'Mes siguiente',
        selectYear: 'Selecinar año',
        prevYear: 'Anterior año',
        nextYear: 'Siguiente año',
        selectDecade: 'Selecione decada',
        prevDecade: 'Anterior Decada',
        nextDecade: 'Siguiente Decada',
        prevCentury: 'Anterior siglo',
        nextCentury: 'Siguiente siglo',
        pickHour: 'Horas',
        incrementHour: 'Incrementar hora',
        decrementHour: 'Decrementar hora',
        pickMinute: 'Minutos',
        incrementMinute: 'Incrementar minutos',
        decrementMinute: 'Decrementar minutos',
        pickSecond: 'Segundos',
        incrementSecond: 'Incrementar segundos',
        decrementSecond: 'Decrementar segundos',
        togglePeriod: 'Periodo',
        selectTime: 'Seleccione el tiempo'
    },
    applyCustomStyleTo : function(selector, customOptions) {
        var options = {
            locale:'es',
            format:'DD/MM/YYYY HH:mm:ss',
            tooltips:CustomDateTimePicker.translateTooltips,
        };
        var mergedOptions = options;
        if (customOptions) {
            mergedOptions = $.extend(options, customOptions);
        }
        return $(selector).datetimepicker(mergedOptions);
    },
}

var CustomDataTable = {
    language : {
        "sProcessing" : "Espere...",
        "sLengthMenu" : "_MENU_ Registros por página",
        "sZeroRecords" : "No se encontraron resultados",
        "sEmptyTable" : "Ningún dato disponible en esta tabla",
        "sInfo" : "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty" : "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered" : "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix" : "",
        "sSearch" : "Buscar:",
        "sUrl" : "",
        "sInfoThousands" : ",",
        "sLoadingRecords" : "Cargando...",
        "oPaginate" : {
            "sFirst" : "Primero",
            "sLast" : "Último",
            "sNext" : "Siguiente",
            "sPrevious" : "Anterior"
        },
        "oAria" : {
            "sSortAscending" : ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending" : ": Activar para ordenar la columna de manera descendente"
        },
        buttons: {
            copyTitle: 'El contenido de la tabla ha sido copiado',
            copySuccess: {
                _: '%d Lineas copiadas',
                1: '1 Linea copiada'
            }
        }
    },
    applyCustomStyleTo: function (selector, customOptions) {
        selectortables = selector;
        var options = {
            "bFilter": true,
            "bSearchable": true,
            "destroy": true,
            "responsive": true,
            "language": CustomDataTable.language,
            "dom": 'if<"custom-table"t><"custom-table-bottom filtrosPag pagiNation"rlp><"clear">',
        };
        var mergedOptions = options;
        if (customOptions) {
            mergedOptions = $.extend(options, customOptions);
        }
        $(selector).DataTable().on('draw', function () {
            $('[data-toggle="tooltip"]').tooltip();
        });
        return $(selector).DataTable(mergedOptions);
    },
    applyCustomStyleToNoSearch : function(selector, customOptions) {
        var options = {
            "bFilter" : false,
            "bSearchable" : false,
            "destroy": true,
            "responsive": true,
            "language" : CustomDataTable.language,
            "bPaginate" : false,
            "bInfo" : false
        };
        var mergedOptions = options;
        if (customOptions) {
            mergedOptions = $.extend(options, customOptions);
        }
        return $(selector).DataTable(mergedOptions);
    },
    applyCustomStyleToExportExcel : function(selector, customOptions) {
        var options = {
            "bFilter" : true,
            "bSearchable" : true,
            "destroy": true,
            "responsive": true,
            "language" : CustomDataTable.language,
            "bPaginate" : true,
            "bInfo" : true,
            "dom": 'Bfrtip',
            "buttons": [{"extend": 'copy',
                "text": 'Copiar'},
                {"extend": 'csv',
                    "text": 'Exportar a CSV'}
            ]
        };
        var mergedOptions = options;
        if (customOptions) {
            mergedOptions = $.extend(options, customOptions);
        }
        return $(selector).DataTable(mergedOptions);
    }
};

function initDataTable( table, options ) {
    if ( options ) {
        datatable_object = CustomDataTable.applyCustomStyleTo(table, options);
    } else {
        datatable_object = CustomDataTable.applyCustomStyleTo(table);
    }

    $("body").on("click", ".sorting_column", function() {
        sorting_element = $(this);
        th = $(sorting_element).parent();

        sorting = $(sorting_element).attr("sorting");

        $(table).find(".sorting_column i").removeAttr("class").addClass('fas fa-sort');

        switch ( sorting ) {
            case 'asc':
                sorting = 'desc';
                $(sorting_element).find("i").removeClass('fa-sort').addClass('fa-sort-down');
                break;
            case 'desc':
                sorting = 'asc';
                $(sorting_element).find("i").removeClass('fa-sort').addClass('fa-sort-up');
                break;
            default:
                sorting = 'asc';
                $(sorting_element).find("i").removeClass('fa-sort').addClass('fa-sort-up');
                break;
        }

        $(sorting_element).attr("sorting", sorting);

        datatable_object.order([$(table).find("th").index(th), sorting]).draw();
    });

    $('body').on('draw.dt', table, function() {
        $('[data-toggle="tooltip"]').tooltip();
        $(".cell_price").autoNumeric('init', {aSign: '$ '});
    });

    return datatable_object;
}

function initSelect2( selector ) {
    $(selector).each(function( _, elemento ) {
        if ( $(elemento).find("option").length > 1 ) {
            $(elemento).select2({
                language: 'iso_639_1'
            });
        }
    });
}


function confirmMessage( msg, functionCallback ) {
    return bootbox.dialog({
        message: msg,
        title: "Confirmación",
        size: "small",
        buttons: {
            danger: {
                label: '<i class = "fas fa-fw fa-thumbs-down"></i> No',
                className: "btn-danger btn-rounded"
            },
            success: {
                label: '<i class = "fas fa-fw fa-thumbs-up"></i> Si',
                className: "btn-success btn-rounded",
                callback: functionCallback
            }
        }
    });
}

function handleJsonResponse( response, onSuccess, onFailure ) {
    if ( response.success ) {
        onSuccess();
    } else {
        if ( onFailure ) {
            onFailure();
        }

        if ( response.message !== undefined ) {
            noty.show("danger", response.message);
        } else {
            noty.show("danger", response);
        }
    }
}

function jsonNotyfication(json){
    try {
        if (json.type == '' || json.type == undefined) {
            noty.show('danger', 'Formato de json no válido');
        } else {
            noty.show(json.type, json.message);
        }
    }catch (e) {
        console.log(e.message);
    }

}

function showLoadingModal() {
    $.blockUI({
        overlayCSS: {
            backgroundColor: 'rgba(211,206,209,0.24)'} ,
        css: {
            filter: 'blur(3px)',
            border: 'none',
            padding: '15px',
            backgroundColor: 'none'
        },
        message: $("<span></span>").html(),
        baseZ: 2000
    });
    $('.page-wrapper .container-fluid').css({'filter':'blur(0.6px)'});
    $('.page-wrapper .page-titles').css({'filter':'blur(0.6px)'});
    NProgress.start();
}

function hideLoadingModal() {
    $.unblockUI();
    NProgress.done();
    $('.page-wrapper .container-fluid').removeAttr('style');
    $('.page-wrapper .page-titles').removeAttr('style');
}

$(document).ajaxStart(function () {
    showLoadingModal();
});


$(document).ajaxError(function( event, jQXHR, setting, throw_error ) {
    switch( jQXHR.status ) {
        case 400:
            noty.show('warning','La solicitud contiene sintaxis errónea');
            break;
        case 404:
            noty.show('warning','Recurso no encontrado');
            break;
        case 405:
            noty.show('danger','Método de solicitud no soportado');
            break;
        case 406:
            noty.show('danger','El servidor no es capaz de devolver los datos');
            break;
        case 407:
            noty.show('danger','Proxy autenticación requerida');
            break;
        case 408:
            noty.show('danger','El cliente falló al continuar la petición');
            break;
        case 409:
            noty.show('danger','La solicitud no pudo ser procesada debido a un conflicto');
            break;
        case 411:
            noty.show('danger','El servidor rechazo su petición');
            break;
        case 413:
            noty.show('danger','La petición del navegador es demasiado grande');
            break;
        case 423:
            noty.show('danger','El recurso está bloqueado');
            break;
        case 429:
            noty.show('danger','Hay muchas conexiones desde esta dirección de internet');
            break;
        case 431:
            noty.show('danger','Las cabeceras de la petición es demasiado grande');
            break;
    }
});

$(document).ajaxStop(function() {
    hideLoadingModal();
});

var introDefault={
    config :{
        nextLabel:'Siguiente',
        prevLabel:'Anterior',
        skipLabel:'Salir',
        doneLabel:'Finalizar',
        keyboardNavigation:true,
        buttonClass:'btn btn-primary btn-xs btn-rounded'
    },
    init:function(){
        var intro = introJs();
        intro.setOptions(this.config);
        intro.start();
    },
}



var onlineSystem = {

    checkingTime: 1700,
    timeMessage: 2000,
    mostrarMensaje: true,
    message : "Se ha perdido la conexión con la sesión debido a un error de red desconocido si desea navegar sin conexión dar click en aceptar.",
    messageStatusOn :'<i class="fa fa-check-circle"></i> Conexión restablecida correctamente.',
    messageStatusOff :'  <i class="fas fa-circle-notch fa-spin" ></i> Se perdio su conexión a internet..',

    noClick: function() {
        if (onlineSystem.mostrarMensaje) {
            alert(onlineSystem.message);
        }
    },

    close:function(){
        $('body').on('click','#connectionMessageClose',function (e) {
            e.preventDefault();
            $('#connectionMessage').removeClass('show');
        })
    },

    goOnline: function() {
        $('#connectionMessage').removeClass('toast-danger');
        $('#connectionMessage').addClass('toast-success');
        $('#messageStatus').html(onlineSystem.messageStatusOn);
        setTimeout(function () {
            $('#content-noty').hide('fade');
        },onlineSystem.timeMessage);

        /* Activar click en el sistema */
        document.onmousedown = false;
    },

    goOffline: function() {
        $('#connectionMessage').addClass('toast-danger');
        $('#connectionMessage').removeClass('toast-success');
        $('#messageStatus').html(onlineSystem.messageStatusOff);
        $('#content-noty').show('fade');
        document.onmousedown = onlineSystem.noClick;
    },

    run: function () {
        setInterval(function () {
            if(navigator.onLine) {
                onlineSystem.goOnline();
            } else {
                onlineSystem.goOffline();
            }
        },this.checkingTime );

        onlineSystem.close();
    }

};

onlineSystem.run();


$(document).ready(function () {
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    });

    $('body').on('click', 'div[data-toggle^=toggle]', function(e) {
        e.preventDefault();
        var $checkbox = $(this).find('input[type=checkbox]');
        $($checkbox).bootstrapToggle('toggle')
    })
});
moment.locale('es');

var NotificationService = {
    next : 0,
    notificationsTotal : 0,
    lastCreatedDate : null,
    lastNotificationId : 0,
    stopService : false,
    requestDelay : 2000,
    different : 1000,
    newNotificationListener:function () {

    },

    checkForUpdates :function(){
        return $.ajax({
            global: false,
            url: '/notificaciones/check_for_updates',
            statusCode: {
                401: function() { console.log('stop service'); self.stopService = true; }
            }
        });
    },

    getNotReadedList:function() {
        var next = this.next;
        return $.ajax({
            url: '/notificaciones/get_list?next=' + next,
            statusCode: {
                401: function() { console.log('stop service'); self.stopService = true; }
            }
        });
    },

    getNews : function() {
        return $.ajax({
            global: false,
            url: '/notificaciones/get_news',
            statusCode: {
                401: function() { console.log('stop service'); self.stopService = true; }
            }
        });
    },

    markAsReadedUseList : function(notificationList){
        return $.ajax({
            type:'get',
            url: '/notificaciones/mark_as_readed',
            data:{
                l:JSON.stringify(notificationList)
            }
        });
    },

    markAsReadedUseDate : function(date){
        return $.ajax({
            url: '/notificaciones/mark_as_readed?d=' + date
        });
    },

    nextNotReadedList :function() {
        var self = this;
        return $.when(this.getNotReadedList()).then(function(notificationList){
            if(notificationList) {
                var keys = Object.keys(notificationList);
                if(keys.length > 0) {
                    var lastItem = notificationList[keys[keys.length - 1]];
                    var lastNotification = lastItem[lastItem.length - 1];
                    self.next = lastNotification.id;
                }
            }
            return notificationList;
        });
    },

    listenForChanges : function () {

        $.when(this.checkForUpdates()).then(function (result) {

            if (!result || !result.hasOwnProperty('last_created_date') || !result.hasOwnProperty('total')) {
                throw new Error("Mala respuesta, no se puede determinar si hay nuevas notificaciones");
            }

            var lastCreatedDate = null;

            if (result.last_created_date != null) {
                lastCreatedDate = moment(result.last_created_date, 'YYYY-MM-DD HH:mm:ss');

                if (!lastCreatedDate.isValid()) {
                    throw new Error("FECHA INVALIDA");
                }

            }
            var totalnoty = parseInt(result.total);


            setTimeout(function(){
                $.when(NotificationService.checkForUpdates()).then(function (newNotifications) {
                    var newTotal = parseInt(newNotifications.total);

                    if(newTotal > totalnoty){
                        $('#notyTimbre').animateCss('tada');
                        $('#notifications').animateCss('tada');
                    }
                    if (totalnoty > 0) {
                        $('#notifications').show('slow');
                        $('#notifications').html(totalnoty);

                    }else{
                        var tmplNotificationEmpty = $("#notification-empty-template").html();
                        $('#notifications').hide('slow');
                        $('#notifications').html(totalnoty)

                        /*Mostrar mensaje de no hay notificaciones por el momento */
                        $("#show-more-notifications-btn").prop("disabled", true);
                        $("#container-all-notifications").html(tmplNotificationEmpty);
                    }

                });
            }, 2000);


        });

    },

    send : function(idNoty) {
        var data = [];
        data.push(idNoty);
        return $.when(this.markAsReadedUseList(data))
    },

    markAsReadedUseDateList: function(date) {
        return $.when(this.markAsReadedUseDate(date));
    },


};


