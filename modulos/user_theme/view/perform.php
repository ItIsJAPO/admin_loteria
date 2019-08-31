<?php
use plataforma\SysConstants;
$themes = $dataAndView->getData('themes');
$themeUser = $_SESSION[SysConstants::SESS_PARAM_USER_THEME];
?>
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-primary">Cambiar de tema</h3> </div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="javascript:void(0)">Inicio</a></li>
            <li class="breadcrumb-item active">Cambiar de tema</li>
        </ol>
    </div>
</div>
<!-- End Bread crumb -->
<!-- Container fluid  -->
<div class="container-fluid">
    <!-- Start Page Content -->
    <div class="row justify-content-center">
        <div class="col-12  col-lg-8  col-xl-8 col-md-8 align-self-center">
            <div class="card">
                <div class="card-title">
                    Tema del sistema
                </div>
                <div class="card-body">
                    <form action="/user_theme/new_theme" method="post">
                        <label for=""> Seleccione un tema</label>
                        <select name="theme" id="" class="form-control form-group" required title="Temas del sistema">
                            <option value="">Seleccione una opción</option>
                            <?php foreach ($themes as $itemTheme){?>
                                <option  <?= ($itemTheme['id'] == $themeUser)?'selected':'' ?> value="<?=$itemTheme['id']?>"> <?=$itemTheme['description']?></option>
                                 <?php
                            }?>
                        </select>
                       <div class="text-right">
                           <button type="submit" class="btn btn-primary m-t-5"><i class="fas fa-paint-roller"></i> Aplicar tema</button>
                       </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <!-- End PAge Content -->
</div>
<!-- End Container fluid  -->