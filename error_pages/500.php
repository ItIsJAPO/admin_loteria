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

		<title> 500 -Error interno</title>

		<meta name = "viewport" content="width=device-width, initial-scale=1.0">

		<meta http-equiv = "X-Content-Security-Policy" content = "script-src 'self' 'nonce-<?= $nonce_hash ?>'; img-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com/css" />

        <meta http-equiv = "Content-Security-Policy" content = "script-src 'self' 'nonce-<?= $nonce_hash ?>'; img-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com/css" />

        <meta http-equiv = "X-WebKit-CSP" content = "script-src 'self' 'nonce-<?= $nonce_hash ?>'; img-src 'self'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com/css" />

        <link href="/assets/css/font/fonts.css" rel="stylesheet"/>
		<link rel = "stylesheet" type = "text/css" href = "/assets/font-awesome/css/all.css" />
		<link rel = "stylesheet" type = "text/css" href = "/assets/bootstrap-4/css/bootstrap.min.css" />
		<link rel = "stylesheet" type = "text/css" href = "/assets/css/style.css" />
        <link rel = "shortcut icon" href = "/assets/img/logo_uac_ms.ico" />

		<script type = "text/javascript" src = "/assets/js/jquery-3.1.1.js"></script>
	</head>

	<body class = "text-center">
            <div class="error-page" id="wrapper">
                <div class="error-box">
                    <div class="error-body text-center">
                        <h1>500</h1>

                        <h3 class="text-uppercase">Error Interno </h3>
                        <?php
                        if ( isset($e) && is_a($e, 'Exception') ) { ?>
                            <p class="text-muted m-t-30 m-b-30 "> <?= $e->getMessage() ?> </p>
                            <?php
                        } else { ?>
                            <p class="text-muted m-t-30 m-b-30"> Error interno del sistema</p>
                            <?php
                        } ?>
                        <a class="btn btn-danger btn-rounded waves-effect waves-light m-b-40" id = "back_button" href="#">Regresar</a>
                        <?php
                        if ( isset($_SESSION[SysConstants::SESS_PARAM_AUTENTICADO]) && ($_SESSION[SysConstants::SESS_PARAM_AUTENTICADO] == 1) ) { ?>
                            <a class="btn btn-info btn-rounded waves-effect waves-light m-b-40" href="/">Pagina principal</a>
                            <?php
                        } else { ?>
                            <a class="btn btn-info btn-rounded waves-effect waves-light m-b-40" href="/">Iniciar sesión</a>
                            <?php
                        } ?>
                    </div>
                    <footer class="footer text-center"><img src="/assets/img/logo_uac_ms.ico" alt="" width="30px">Universidad Autónoma de Campeche © • <?= date("Y") ?></footer>
                </div>
            </div>

		<script nonce = "<?= $nonce_hash ?>">
			$("#back_button").on("click", function(e) {
			    e.preventDefault();
				window.history.back();
			});
		</script>
	</body>

</html>