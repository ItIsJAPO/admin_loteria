<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/autoloader.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/logger_init.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/session_init.php';

	use plataforma\SysConstants;

	use util\token\TokenHelper;

	$nonce_hash = TokenHelper::generarCodigo(mt_rand());
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv = "content-type" content = "text/html; charset=UTF-8">
        <meta http-equiv = "X-UA-Compatible" content = "IE=edge">

        <meta name = "viewport" content = "width=device-width, initial-scale=1">

		<title> 400 - Peticion invalida </title>

		<meta name = "viewport" content="width=device-width, initial-scale=1.0">

		<meta http-equiv = "X-Content-Security-Policy" content = "script-src 'self' 'nonce-<?= $nonce_hash ?>'; img-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com/css" />

        <meta http-equiv = "Content-Security-Policy" content = "script-src 'self' 'nonce-<?= $nonce_hash ?>'; img-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com/css" />

        <meta http-equiv = "X-WebKit-CSP" content = "script-src 'self' 'nonce-<?= $nonce_hash ?>'; img-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com/css" />

        <link href="/assets/css/font/fonts.css" rel="stylesheet"/>
		<link rel = "stylesheet" type = "text/css" href = "/assets/font-awesome/css/all.css" />
		<link rel = "stylesheet" type = "text/css" href = "/assets/bootstrap-4/css/bootstrap.min.css" />

		<link rel = "shortcut icon" href = "/assets/img/icon.png" />

		<script type = "text/javascript" src = "/assets/js/jquery-3.1.1.js"></script>
	</head>

	<body class = "text-center">
		<div class = "col-lg-6 col-lg-offset-3 col-md-8 col-md-offset-2 col-sm-10 col-sm-offset-1 col-xs-12">
			<div style = "margin-bottom: 20px">
				<img src = "/assets/img/tocajazz.png" width = "20%" />
			</div>

			<div class = "form-group">
				<div class = "alert alert-warning text-center" role = "alert">
					<h3 class = "alert-heading" style = "margin: 0">
						<b class = "text-uppercase"> Peticion invalida </b>
					</h3>
				</div>
			</div>

			<div class = "">
				<a href = "#" class = "btn btn-danger" id = "back_button">
					<i class="fas fa-fw fa-arrow-left"></i> Regresar
				</a>
				&nbsp;
				<?php
				if ( isset($_SESSION[SysConstants::SESS_PARAM_AUTENTICADO]) && ($_SESSION[SysConstants::SESS_PARAM_AUTENTICADO] == 1) ) { ?>
					<a href = "/" class = "btn btn-primary">
						<i class = "fa fa-fw fa-home"></i> Pagina principal
					</a>
					<?php
				} else { ?>
					<a href = "/" class = "btn btn-success">
						<i class = "fas fa-fw fa-sign-in-alt"></i> Iniciar sesion
					</a>
					<?php
				} ?>
			</div>
		</div>

		<script nonce = "<?= $nonce_hash ?>">
			$("#back_button").on("click", function() {
				window.history.back();
			});
		</script>
	</body>
</html>