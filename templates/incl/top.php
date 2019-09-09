<?php
    use plataforma\SysConstants;
    use repository\Login;
    use util\config\Config;
?>
<!DOCTYPE html>
<html lang = "<?= $_SESSION[SysConstants::SESS_PARAM_ISO_639_1] ?>">
    <head>
        <meta http-equiv = "content-type" content = "text/html; charset=UTF-8" />
        <meta http-equiv = "X-UA-Compatible" content = "IE=edge" />

        <?php
        if ( Config::get("over_https") ) { ?>
            <meta http-equiv = "Content-Security-Policy" content = "upgrade-insecure-requests" />
            <?php
        } ?>

        <meta http-equiv = "X-Content-Security-Policy" content = "script-src 'self' 'nonce-<?= $nonce_hash ?>' https://code.highcharts.com/highcharts.js http://fast.wistia.net; img-src data: 'self' http://fast.wistia.net http://embed.wistia.com; style-src 'self' 'unsafe-inline' " />

        <meta http-equiv = "Content-Security-Policy" content = "script-src 'self' 'nonce-<?= $nonce_hash ?>' https://code.highcharts.com/highcharts.js http://fast.wistia.net/; img-src data: 'self' http://fast.wistia.net http://embed.wistia.com; style-src 'self' 'unsafe-inline' " />

        <meta http-equiv = "X-WebKit-CSP" content = "script-src 'self' 'nonce-<?= $nonce_hash ?>' https://code.highcharts.com/highcharts.js http://fast.wistia.net; img-src data: 'self' http://fast.wistia.net http://embed.wistia.com; style-src 'self' 'unsafe-inline' " />

        <meta content = "width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name = "viewport" />

        <title> UACAM </title>

        <link rel = "shortcut icon" href = "/assets/img/logo_uac_ms.ico" />
        <!-- Bootstrap Core CSS -->
         <link href="/assets/select2/css/select2-bootstrap4.css" rel="stylesheet">
        <link href="/assets/bootstrap-4/css/bootstrap.css" rel="stylesheet">

        <!--  ====  css template customer===-->
        <link href="/assets/css/lib/owl.carousel.min.css" rel="stylesheet" />
        <link href="/assets/css/lib/owl.theme.default.min.css" rel="stylesheet" />

        <link rel = "stylesheet" type = "text/css" href = "/assets/DataTables/DataTables-1.10.18/css/dataTables.bootstrap4.css" />
        <link rel = "stylesheet" type = "text/css" href = "/assets/DataTables/Responsive-2.2.2/css/responsive.bootstrap4.min.css" />
        <link rel = "stylesheet" type = "text/css" href = "/assets/DataTables/Buttons-1.5.2/css/buttons.bootstrap4.min.css" />
        <link rel = "stylesheet" type = "text/css" href = "/assets/datetimepicker/build/css/bootstrap-datetimepicker.css" />
        <link rel = "stylesheet" type = "text/css" href = "/assets/bootstrap-fileinput/themes/explorer-fa/theme.css" />
        <link rel = "stylesheet" type = "text/css" href = "/assets/bootstrap-fileinput/css/fileinput.css" />
        <link rel = "stylesheet" type = "text/css" href = "/assets/bootstrap-toggle/bootstrap-toggle.css" />
        <link rel = "stylesheet" type = "text/css" href = "/assets/font-awesome/css/all.min.css" />
        <link rel = "stylesheet" type = "text/css" href = "/assets/lightbox/css/lightbox.min.css" />
        <link rel = "stylesheet" type = "text/css" href = "/assets/select2/css/select2.min.css" />
        <link rel = "stylesheet" type = "text/css" href = "/assets/animatecss/animate.css" />
        <link rel = "stylesheet" type = "text/css" href = "/assets/nprogress/nprogress.css" />
         <!-- Custom CSS -->
        <link rel = "stylesheet" type = "text/css" href="/assets/css/helper.css" />

        <!--themes user  -->
        <link rel = "stylesheet" type = "text/css" href="/assets/css/style.css" />
        <link rel = "stylesheet" type = "text/css" href ="/assets/css/widget.css" />
        <link rel = "stylesheet" type = "text/css" href ="/assets/css/default.css" />

        <script type = "text/javascript" src = "/assets/js/jquery-3.1.1.js"></script>
        <script type = "text/javascript" src = "/assets/js/jquery.blockUI.js"></script>
        <script type = "text/javascript" src = "/assets/nprogress/nprogress.js"></script>
        <script type = "text/javascript" src = "/assets/highchart/highcharts.js"></script>
        <script type = "text/javascript" src = "/assets/select2/js/select2.min.js"></script>
        <script type = "text/javascript" src = "/assets/bootstrap-4/js/popper.min.js"></script>
        <script type = "text/javascript" src = "/assets/autoNumeric/autoNumeric-min.js"></script>
        <script type = "text/javascript" src = "/assets/bootstrap-4/js/bootstrap.min.js"></script>
        <script type = "text/javascript" src = "/assets/bootstrap-toggle/bootstrap-toggle.js"></script>
        <script type = "text/javascript" src = "/assets/js/js-custom/lib/bootbox/bootbox.min.js"></script>
        <script type = "text/javascript" src = "/assets/form-validation/jquery.validate.min.js"></script>
        <script type = "text/javascript" src = "/assets/bootstrap-notify/bootstrap-notify.min.js"></script>
        <script type = "text/javascript" src = "/assets/js/js-custom/lib/plugin%20js/jquery.mask.min.js"></script>
        <script type = "text/javascript" src = "/assets/DataTables/DataTables-1.10.18/js/jquery.dataTables.js"></script>
        <script type = "text/javascript" src = "/assets/DataTables/DataTables-1.10.18/js/dataTables.bootstrap.js"></script>
        <script type = "text/javascript" src = "/assets/DataTables/Responsive-2.2.2/js/dataTables.responsive.min.js"></script>
        <script type = "text/javascript" src = "/assets/DataTables/Buttons-1.5.2/js/dataTables.buttons.min.js"></script>
        <script type = "text/javascript" src = "/assets/DataTables/Buttons-1.5.2/js/html5.js"></script>
        <script type = "text/javascript" src = "/assets/DataTables/Buttons-1.5.2/js/buttons.print.js"></script>
        <script type = "text/javascript" src = "/assets/DataTables/JSZip-2.5.0/jszip.js"></script>
        <script type = "text/javascript" src = "/assets/select2/js/i18n/<?= $_SESSION[SysConstants::SESS_PARAM_ISO_639_1] ?>.js"></script>
        <script type = "text/javascript" src = "/assets/js/js-custom/lib/moment/moment.js"></script>
        <script type = "text/javascript" src = "/assets/js/js-custom/lib/moment/moment-with-locales.js"></script>
        <script type = "text/javascript" src = "/assets/datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
        <script type = "text/javascript" src = "/assets/bootstrap-toggle/bootstrap-toggle.js"></script>
        <script type = "text/javascript" src = "/assets/bootstrap-fileinput/js/fileinput.js"></script>
        <script type = "text/javascript" src = "/assets/bootstrap-fileinput/js/locales/es.js"></script>
        <script type = "text/javascript" src = "/assets/bootstrap-fileinput/themes/explorer-fas/theme.js"></script>
        <script type = "text/javascript" src = "/assets/bootstrap-fileinput/themes/fas/theme.js"></script>

<!--        init default js-->
        <script type = "text/javascript" src = "/assets/js/default.js"></script>
        <script type = "text/javascript" src = "/assets/js/notify.js"></script>
<!--        end default js-->
    </head>
        <?php
            include 'scripts/validations.php';
        ?>
    <body class="fix-header fix-sidebar">
        <!-- Main wrapper  -->
        <div id="main-wrapper">
            <!-- header header  -->
            <?php include "nav-bar.php"; ?>
            <!-- Left Sidebar  -->
            <?php include 'side-bar.php'; ?>
            <!-- Page wrapper  -->
            <div class="page-wrapper">

