
function showLoadingModal() {
    $.blockUI({
        css: {
            border: 'none',
            padding: '15px',
            backgroundColor: 'none'
        },
        message : $("<span></span>").html(),
        baseZ: 2000
    });
    NProgress.start();
}

function hideLoadingModal() {
	$.unblockUI();
	NProgress.done();
}

$(document).ajaxStart(function() {
	showLoadingModal();
});

$(document).ajaxStop(function() {
	hideLoadingModal();
});

function sendData( data ) {
    $.ajax({
        type: "POST",
        url: "/login/login",
        data: data,
        processData: false,
        contentType: false,
        cache: false,
        success: function( response ) {
            if ( response.success ) {
                window.location = '/' + response.page;
            } else {
                if (response.message !== undefined) {
                    error_message = response.message;
                } else {
                    error_message = response;
                }

                if (parseInt(response.code) == 1000) {
                    noty.show("danger", error_message);
                }else{
                    noty.show("danger", error_message);
                }
            }
        }
    });
}

function signIn() {
    data = new FormData($("#form_login").get(0));
    sendData(data);
}

$(document).ready(function() {

    $('body').on("click","#sign_in_tocajazz", function(e) {
        e.preventDefault();
        signIn()
    });

    $("#form_login input[name=email]").focusout(function() {
        correoValido(this, true);
    });

});