<?php
	include_once $_SERVER['DOCUMENT_ROOT'] . '/autoloader.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/logger_init.php';
	include_once $_SERVER['DOCUMENT_ROOT'] . '/session_init.php';
?>

<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv = "content-type" content = "text/html; charset=UTF-8">
        <meta http-equiv = "X-UA-Compatible" content = "IE=edge">

        <meta name = "viewport" content = "width=device-width, initial-scale=1">

		<title> En mantenimiento</title>

		<meta name="description" content="">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link href="/assets/css/font/fonts.css" rel="stylesheet"/>
		<link rel = "stylesheet" type = "text/css" href = "/assets/font-awesome/css/all.css" />
		<link rel = "stylesheet" type = "text/css" href = "/assets/bootstrap-4/css/bootstrap.min.css" />
		<link rel = "stylesheet" type = "text/css" href = "/assets/css/style.css" />

		<link rel = "shortcut icon" href = "/assets/img/icon.png" />
	</head>

	<body>
    <div class="error-page" id="wrapper">
        <div class="error-box">
                    <div class="error-body text-center">
                        <h1 style="font-size: 50px"><i class="fas fa-2x fa-cogs"></i> EN MANTENIMIENTO</h1>

                        <div class = "alert alert-warning text-center" role = "alert">
                            <h4> Actualmente el sistema se encuentra en mantenimiento, por favor vuelva a intentar acceder mas tarde </h4>
                            <h4> Lamentamos las molestias.</h4>
                        </div>
                    </div>
                <footer class="footer text-center">© 2018  <img src="/assets/img/icarus.png" style="vertical-align: baseline;"> Grupo icarus S.A DE C.V </footer>
            </div>
        </div>
    </body>
</html>