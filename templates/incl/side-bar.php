<?php

use util\roles\RolesPolicy;
use \repository\Memberships;

$uri = $_SERVER['REQUEST_URI'];
$startCourses = $dataAndView->getData('startCourses');
$uri = $_SERVER['REQUEST_URI'];
?>
<script nonce="<?= $nonce_hash ?>">
    $(document).ready(function () {
        var idLi = '';
        var uri = "<?= $uri ?>";

        uri = uri.replace("?", "/");
        uri = uri.replace("=", "");

        var uriParts = uri.split('/');

        if (uri == "/" || uri == "/dashboard") {
            idLi = '#dashboard';
        } else {
            idLi = '#' + uriParts[1] + (uriParts[2] != undefined && uriParts[2].length > 0 && !(uriParts[2].indexOf("?") === 0) ? '-' + uriParts[2] : "");

            if (!$(idLi).length) {
                idLi = '#' + uriParts[1];
            }
        }

        var selector = "#sidebarnav" + ' ' + idLi;

        $(selector).addClass('active-side');

        var idSelector = $(selector).parent("ul");
        var colum = $(idSelector).parent('li');
        $(colum).addClass('active');

    });
</script>

<div class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-devider"></li>

                <li>
                    <a href="/" id="dashboard"><i class="fas fa-user-edit"></i> <span class="hide-menu">Asistencia </span></a>
                </li>
                <li>
                    <a href="/grupos" id="grupos"> <i class="fas fa-users"></i> <span class="hide-menu"> Grupos </span></a>
                </li>
                <li class="nav-label">Ajustes</li>
                <li>
                    <a class="has-arrow  " href="#" aria-expanded="false">
                        <i class="fas fa-american-sign-language-interpreting"></i>
                        <span class="hide-menu">Catálogos</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li id="adscripciones">
                            <a href="/adscripciones">
                                <i class="fab fa-firstdraft"></i> Adscripciones
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</div>