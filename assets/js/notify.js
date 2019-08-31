function Noty() {
    var objects_index = 0;
    var objects = new Array();
    var template_body = $("#notify_body").html();

    $("#notify_body").remove();

    $("body").on("click", ".close_notify_button", function() {
        index = parseInt($(this).attr("index"));

        objects[index].close();
    });

    var showNotification = function( tipo, msg ) {
        switch ( tipo ) {
            case 'success':
                title = 'OPERACION EXITOSA';
                break;
            case 'danger':
                title = 'ERROR';
                break;
            case 'info':
                title = 'NOTIFICACION';
                break;
            case 'warning':
                title = 'ADVERTENCIA';
                break;
        }

        objects[objects_index] = $.notify({
            title: title,
            message: msg,
        },{
            newest_on_top: true,
            showProgressbar: true,
            delay: 4000,
            type: tipo,
            animate: {
                enter: 'animated fadeInRight',
                exit: 'animated fadeOutRight'
            },
            placement: {
                from: "top",
                align: "right"
            },
            template: template_body
        });

        $("[data-notify=container]").css("z-index", "10000");

        $(objects[objects_index].$ele).find(".close_notify_button").attr("index", objects_index);

        objects_index++;
    }

    this.show = function( type, message ) {
        showNotification(type, message);
    }
}

var noty;

$(document).ready(function() {
    noty = new Noty();
});