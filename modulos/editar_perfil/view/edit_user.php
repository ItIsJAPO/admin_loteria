<?php
use repository\Login;
use util\roles\RolesPolicy;
$userData = $dataAndView->getData("userData"); 
?>

<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-primary">Editar perfil</h3> </div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item active">Editar perfil</li>
        </ol>
    </div>
</div>
<div class="container-fluid">
   <div class="row">
        <div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-left">
                           <h3> <i class="fas fa-unlock"></i> Cambiar contraseña</h3>
                        <hr>
                    </div>
                    <div class="row">
                        <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                            <font  style="text-align: right"><span style="color: red">*</span>Nueva contraseña</font>
                            <input type="Password" class="form-control form-group" name="new_password" id="new_password" placeholder="Ingrese nueva contraseña">

                        </div>
                         <div class="col-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                                <font  style="text-align: right"><span style="color: red">*</span>Confrimar contraseña</font>
                                 <input type="Password" class="form-control form-group" name="confirm_password" id="confirm_password" placeholder="Confirmar contraseña">
                          </div>
                    </div>

                      <div class="text-right">
                         <a class="btn btn-secondary" id="cancel-edit"><i class="fa fa-times"></i> Cancelar</a>
                          <button class="btn btn-success" id="save_profile"><i class="fas fa-save"></i> Guardar</button>
                     </div>
                    </div>
                </div>
            </div>
        </div>    
    </div>    
<?php include 'js/edit_user_js.php'; ?>