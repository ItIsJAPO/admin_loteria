<?php

use plataforma\SysConstants;
use repository\Login;
use util\roles\RolesPolicy;

?>
<div class="header">
    <nav class="navbar top-navbar navbar-expand-md navbar-light">
        <!-- Logo -->
        <div class="navbar-header">
            <a class="navbar-brand" href="/">
                <!-- Logo icon -->
                <b><img src="/assets/img/logo_uac.png" alt="homepage" class="dark-logo" width="200px"/></b>
                <!--End Logo icon -->
            </a>
        </div>
        <!-- End Logo -->
        <div class="navbar-collapse">
            <!-- toggle and nav items -->
            <ul class="navbar-nav mr-auto mt-md-0">
                <!-- This is  -->
                <li class="nav-item"><a class="nav-link nav-toggler hidden-md-up text-muted  "
                                        href="javascript:void(0)"><i class="fas fa-bars"></i></a></li>
                <li class="nav-item m-l-10"><a class="nav-link sidebartoggler hidden-sm-down text-muted  "
                                               href="javascript:void(0)"><i class="fas fa-bars"></i></a></li>
                <!-- Messages -->
                <li>
                    <h3 class="title-nav hidden-sm-down hidden-md-down">Universidad Autónoma de Campeche</h3>
                    <h3 class="title-nav hidden-lg-up">UAC</h3>
                </li>
                <!-- End Messages -->
            </ul>
            <!-- User profile and search -->
            <ul class="navbar-nav my-lg-0">
                <!-- Profile -->
                <li class="nav-item">
                    <a class="nav-link dropdown-toggle text-muted  " href="/editar_perfil" title="Editar perfil">
                        <i class="fal fa-user"></i> <?= $_SESSION[SysConstants::SESS_PARAM_USER_NAME] ?>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/login/logout" class="nav-link dropdown-toggle text-muted" title="Salir">
                        <i class="fas fa-sign-out-alt"></i></a>
                </li>
            </ul>
        </div>
    </nav>
</div>
<!-- End header header -->