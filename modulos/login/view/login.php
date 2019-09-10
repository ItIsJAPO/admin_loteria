<style>
    .login-form {
        background: #ffffff;
        padding: 10px 10px 10px;
        border-radius: 2px;
    }
    .link{
        font-size: 11px;
        margin-left:5px
    }
    .link:hover{
        text-decoration: underline !important;
    }
    .login-panel {
        background-color: white;
        width: 300px;
        margin: 8% auto;
        border-radius: 0.2em;
        box-shadow: 0 0 14px rgba(140, 140, 140, 0.5);
        text-align: center;
    }
    .checkbox_item {
        margin: 5px;
        vertical-align: middle;
    }
    .response_validation{
       position: absolute;
       font-size: small;
       text-align: left;
       margin-left: 0;
       display: block;
   }
    .form-group {
        margin-bottom: 16px !important;
    }
</style>
<div class="unix-login">
    <div class="container-fluid">
        <div class="row ">
            <div class="card login-panel animated flipInX">
                <center> <img src="/assets/img/logo-login.png" alt="EQUIMAR" width="220px" ></center>
                <div class="card-body ">
                    <div class="login-form">
<!--                        <h4>Inicio de sesión</h4>-->
                        <form id="form_login" method="post">
                                <div class="form-group">
                                    <input class = "form-control" type = "email" name = "email" placeholder = "Correo" />
                                </div>
                                <div class="form-group">
                                    <input type="password" class="form-control" name = "password" placeholder = "Contraseña">
                                </div>
                                <button id = "sign_in_tocajazz" class="btn btn-info btn-flat">Iniciar sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="application/javascript" src="/assets/js/default_login.js"></script>