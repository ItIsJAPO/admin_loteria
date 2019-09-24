<?php
	use plataforma\SysConstants;
	use util\config\Config;
?>
<!DOCTYPE html>
<html id="html-body" lang = "<?= $_SESSION[SysConstants::SESS_PARAM_ISO_639_1] ?>">
	<head>
		<meta http-equiv = "content-type" content = "text/html; charset=UTF-8" />

		<meta http-equiv = "X-UA-Compatible" content = "IE=edge" />

		<?php
        if ( Config::get("over_https") ) { ?>
            <meta http-equiv = "Content-Security-Policy" content = "upgrade-insecure-requests" />
            <?php
        } ?>

		<meta name = "viewport" content = "width=device-width, initial-scale=1" />
		<title> <?= $dataAndView->getData('title_page_login') ?> </title>

        <link rel = "shortcut icon" href = "/assets/img/logo_uac_ms.ico" />

		<link rel = "stylesheet" type = "text/css" href = "/assets/font-awesome/css/all.min.css" />
        <link rel = "stylesheet" type = "text/css" href="/assets/bootstrap-4/css/bootstrap.min.css" >
        <link rel = "stylesheet" type = "text/css" href = "/assets/animatecss/animate.css" />
        <link rel = "stylesheet" type = "text/css" href = "/assets/nprogress/nprogress.css" />
        <link rel = "stylesheet" type = "text/css" href="/assets/css/helper.css" />
        <link rel = "stylesheet" type = "text/css" href="/assets/css/style.css" />
        <link rel = "stylesheet" type = "text/css" href="/assets/css/default.css"/>

        <script type = "text/javascript" src = "/assets/js/jquery-3.1.1.js"></script>
        <script type = "text/javascript" src = "/assets/bootstrap-4/js/bootstrap.min.js"></script>
        <script type = "text/javascript" src = "/assets/js/jquery.blockUI.js"></script>
        <script type = "text/javascript" src = "/assets/nprogress/nprogress.js"></script>
        <script type = "text/javascript" src = "/assets/bootstrap-4/js/popper.min.js"></script>
		<script type = "text/javascript" src = "/assets/bootstrap-notify/bootstrap-notify.min.js"></script>
		<script type = "text/javascript" src = "/assets/js/notify.js"></script>
	</head>
		<?php
			include 'scripts/validations.php';
		?>
	<body>

    <div id = "notify_body" style = "display: none">
        <div data-notify = "container" class = "col-10 col-sm-4  col-md-4 col-lg-3 alert alert-{0} text-left" role="alert" style=" box-shadow: 2px 4px 15px rgba(0, 0, 1, 0.1);padding: 8px 8px">
            <div style = "display: table; width: 100%">
                <div>
                    <span data-notify="title"><font style = "font-size:14px;font-family:sans-serif;font-weight:600;margin-top: 0;text-shadow: -1px 1px 4px #ffff;">{1}</font></span>
                    <span style="display:block;font-size:13px;text-align:unset;line-height: normal; text-shadow: -1px 1px 4px #ffff;width: 100%"  data-notify="message">{2}</span>
                </div>
                <div class = "close_notify_button" style = "cursor: pointer; display: table-cell; vertical-align: text-top; text-align: right; width: 10px">
                    <i class = "fa fa-times"></i>
                </div>
            </div>
        </div>
    </div>