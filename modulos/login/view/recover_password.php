<?php
$token = $dataAndView->getData('token');
?>
<style>
	.login-form {
		background: #ffffff;
		padding: 10px 10px 10px;
		border-radius: 2px;
	}
	.login-panel {
		background-color: white;
		width: 300px;
		margin: 8% auto;
		border-radius: 0.2em;
		box-shadow: 0 0 14px rgba(140, 140, 140, 0.5);
		text-align: center;
	}
    .response_validation {
        text-outline: 0;
        position: absolute;
        font-size: small;
        text-align: left;
        margin-left: 0;
        display: block;
    }
</style>
<div class="unix-login">
	<div class="container-fluid">
		<div class="row ">
			<div class="card animated flipInX login-panel">
				<div class="card-body">
					<div class="login-form">
						<h4 class="mb-1">Restaurar contraseña</h4>
						<form id="form_login" method="post">
                            <div class="alert alert-info alert-dismissible fade show" role="alert" style="padding: 8px">
                                <span style="font-size: 12px">
                                  Por cuestiones de seguridad. Nunca divulgues tus contraseñas ni códigos de verificación.
                                  <strong>Ingrese una contraseña mínima de 8 carácteres y máximo 12, con una letra de (a-z) con al menos un caracter no alfanumérico ($ % & /) y un número </strong>
                                </span>
                            </div>
							<form id="recover_form">
								<div class="form-group" style="margin-bottom: 18px">
									<input type="hidden" style="display: none" name="token" value="<?= $token ?>"/>
									<input class="form-control" type="password" name="password" placeholder="Nueva contraseña"/>
								</div>
								<div class="form-group">
									<input class="form-control" type="password" name="confirmPassword" placeholder="Confirmar contraseña"/>
								</div>
							</form>
							<button id='recupere' class="btn btn-info btn-flat" data-callback="signIn">Restaurar contraseña</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script nonce="<?= $nonce_hash ?>">
    $(function () {
        $("input[name=password]").focus();

        $('body').on('click', '#recupere', function (e) {
            e.preventDefault();

            if($("input[name=password]").val().length < 8){
                noty.show('danger','La contraseña el minimo permitido son de 5 de caracteres.');
                $("input[name=password]").focus();
                return false
            }

            if (vacio("input[name=password]", 'Nueva contraseña')) {return false}

            validatePassword('input[name=password]');

            if($("input[name=password]").val() != $("input[name=confirmPassword]").val()){
                noty.show('danger','Las contraseñas no coninciden');
                $("input[name=confirmPassword]").focus();
                return false
            }

            data = new FormData;
            data.append('token', $("input[name=token]").val());
            data.append('password', $("input[name=password]").val());
            data.append('confirmPassword', $("input[name=confirmPassword]").val());

            $.ajax({
                type: "POST",
                url: "/login/save_password",
                processData: false,
                contentType: false,
                cache: false,
                data: data,
                success: function (response) {
                    noty.show(response.type, response.message);
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