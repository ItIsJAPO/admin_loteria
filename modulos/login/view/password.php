<?php
	use plataforma\SysConstants;
	use util\config\Config;
?>

<style>
    .login-form {
        background: #ffffff;
        padding: 10px 10px 10px;
        border-radius: 2px;
    }
    .link{
        font-size: 11px;
        margin-left:5px
    }
    .link:hover{
        text-decoration: underline !important;
    }
    .login-panel {
        background-color: white;
        width: 300px;
        margin: 8% auto;
        border-radius: 0.2em;
        box-shadow: 0 0 14px rgba(140, 140, 140, 0.5);
        text-align: center;
    }
</style>
<div class="unix-login">
    <div class="container-fluid">
        <div class="row ">
            <div class="card animated flipInX login-panel">
                <div class="card-body">
                    <div class="login-form">
                        <h4>Recuperar contraseña</h4>
                        <form id="form_login" method="post">
                                <div class="alert alert-danger alert-dismissible fade show" role="alert" style="padding: 8px">
                                        <span style="font-size: 12px" >Envíar correo de recuperación de contraseña.</span>
                                </div>
                                <form id = "recover_form">
                                    <div class="form-group">
                                        <input class = "form-control" type = "email" name = "email" placeholder = "Correo" />
                                    </div>
                                </form>
                                <button id='recupere' class="btn btn-inverse btn-flat" data-callback = "signIn">Recuperar</button>
                                <div class="register-link m-t-15">
                                      <div class="float-left">
                                          <a  class="link" href="/">Regresar</a>
                                      </div>
                                </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script nonce = "<?= $nonce_hash ?>">
    $(function () {
        $("input[name=email]").focus();

        $('body').on('click','#recupere',function(e){
            e.preventDefault();

            if (correoValido("input[name=email]",false) ) {return false}

            data = new FormData;
            data.append('email', $("input[name=email]").val());

            $.ajax({
                type: "POST",
                url: "/login/send_recover",
                processData: false,
                contentType: false,
                cache: false,
                data: data,
                success: function( response ) {
                    noty.show(response.type,response.message);
                    setTimeout(function () {
                        if (response.type == 'success') {
                            window.location = '/'
                        }
                    }, 3000);
                }
            });
        })
    })
</script>