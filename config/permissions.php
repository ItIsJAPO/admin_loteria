; <?php die() ?>

; para todos los usuarios que iniciaron sesion
[permissions.all]
editar_perfil.*=true

; para todos sin iniciar sesion
[permissions.free]
login.*=true
api.*=true

[permissions.Administrador]
*.*=true

[permissions.Instructor]
*.*=true

