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
                <li>
                    <a class="has-arrow  " href="#" aria-expanded="false">
                        <i class="fas fa-sliders-h"></i>
                        <span class="hide-menu">Inscritos</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li id="bitacora">
                            <a href="/bitacora">
                                <i class="fas fa-clipboard"></i> Individual
                            </a>
                        </li>
                        <li id="configuration">
                            <a href="/configuration">
                                <i class="fas fa-cogs"></i> Responsables
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-label">Ajustes</li>
                <li>
                    <a class="has-arrow  " href="#" aria-expanded="false">
                        <i class="fas fa-american-sign-language-interpreting"></i>
                        <span class="hide-menu">Catálogos</span>
                    </a>
                    <ul aria-expanded="false" class="collapse">
                        <li id="use_template">
                            <a href="/adscripciones">
                                <i class="fab fa-firstdraft"></i> Adscripciones</a>
                        </li>
                        <li id="use_template-widget"><a href="/use_template/widget"><i class="fas fa-cubes"></i> Widget</a>
                        </li>
                        <li id="use_template-page_blank"><a href="/use_template/page_blank"><i
                                        class="far fa-window-restore"></i> Página en blanco</a></li>
                        <li id="use_template-typo_graphy"><a href="/use_template/typo_graphy"><i
                                        class="fas fa-font"></i> Tipo grafías</a></li>
                        <li id="use_template-tabs"><a href="/use_template/tabs"><i class="fas fa-table"></i> Tabs</a>
                        </li>
                        <li id="use_template-forms"><a href="/use_template/forms"><i class="fab fa-wpforms"></i>
                                Formularios</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</div>