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
        <div class="col-lg-4 col-12">
            <div class="card">
                    <div style="text-align: center">
                        <div id="user_perfil_ajax" ></div>
                        <br>
                        <input id="input" name="img_perfil[]" type="file">
                        <h3 style="text-transform: capitalize">
                            <?php
                                $name = explode(" ", $userData[0]["name"]);
                                $last_name = explode(" ", $userData[0]["last_name"]);

                                $full_name  = isset($name[0]) ? $name[0] : '';
                                $full_name .= isset($last_name[0]) ? ' ' . $last_name[0] : '';

                                echo $full_name;
                            ?>
                        </h3>
                    </div>
            </div>
        </div>
        <div class="col-12 col-md-12 col-sm-12 col-lg-8 col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div class="text-left"> 
                        <hr>        
                                <h3><i class="fas fa-1x fa-address-card"></i> Información de perfil</h3>
                        <hr>
                    </div>

                    <div class="row">
                         <div class="col-12 col-md-6 col-lg-6 col-xl-6">
                             <font  style="text-align: right"><span style="color: red">*</span>Nombre</font>
                            <input maxlength="80" type="text" class="form-control form-group" id="names" placeholder="Ingrese nombre(s)" value="<?= $userData[0]["name"] ?>">
                        </div>
                        <div class="col-12 col-md-6 col-lg-6 col-xl-6">
                             <font  style="text-align: right">Apellido</font>
                             <input maxlength="80" type="text" class="form-control form-group" name="last_names" id="last_names" placeholder="Ingrese apellido(s)" value="<?= $userData[0]["last_name"] ?>">
                        </div>
                    </div>
                    <div class="text-left">
                        <hr>         
                           <h3> <i class="fas fa-unlock"></i> Cambiar contraseña</h3>
                        <hr>
                    </div>
                          
                    <div class="row">
                         <div class="col-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 offset-xl-3 offset-lg-3">
                                  <font  style="text-align: right"> <span style="color: red">*</span>Actual contraseña</font>
                                 <input type="Password" class="form-control form-group" name="previous_password" id="previous_password" placeholder="Ingrese contraseña actual">
                                       
                                 <font  style="text-align: right"><span style="color: red">*</span>Nueva contraseña</font>
                                <input type="Password" class="form-control form-group" name="new_password" id="new_password" placeholder="Ingrese nueva contraseña">
                                        
                                <font  style="text-align: right"><span style="color: red">*</span>Confrimar contraseña</font>
                                 <input type="Password" class="form-control form-group" name="confirm_password" id="confirm_password" placeholder="Confirmar contraseña">
                          </div>
                    </div>
                    
                        
                       
                      <div class="text-center">
                         <a class="btn btn-danger" id="cancel-edit"><i class="fas fa-thumbs-down"></i>Cancelar</a>
                          <button class="btn btn-success" id="save_profile"><i class="fas fa-save"></i> Guardar</button>
                     </div>
                      
                    </div>
                </div>
            </div>
        </div>    
    </div>    
<?php include 'js/edit_user_js.php'; ?>