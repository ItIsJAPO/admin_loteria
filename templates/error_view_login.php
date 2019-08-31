<?php
use util\token\TokenHelper;
$nonce_hash = TokenHelper::generarCodigo(mt_rand());
include 'incl/topLogin.php';
?>
    <style>
        .border-card{
            border: solid 5px #F15757;
            border-radius: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.16);
            margin: 8% auto;
        }
        .card-header{
            background:#F15757;
            padding: 30px;
            font-weight: bolder;
        }
        .icons{
            float: right;
            background: white;
            padding: 10px;
            margin: 5px;
            border-radius: 50%;
            width: 5px;
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
    </style>
   <div class="container">
       <div class="row justify-content-md-center">
          <div class="col-12 col-md-10 col-lg-10 col-xl-10">
              <div class="card p-0 border-card animated zoomIn">
                  <h1 class="card-header text-white text-center pt-0">
                      <i class="icons"></i>
                      <i class="icons"></i>
                      <i class="icons"></i>
                      <br>
                      <i class="fas fa-exclamation-triangle fa-2x"></i>
                      <br>
                      Ha ocurrido un error
                  </h1>
                  <div class="card-body p-20 text-center">
                      <h5 class="card-title">Mensaje interno</h5>
                      <h3> <?= $dataAndView->getData('message') ?> </h3>
                       <a class="btn btn-secondary btn-rounded waves-effect waves-light " href="/">Iniciar sesión</a>
                  </div>
              </div>
          </div>
       </div>
   </div>

    <script nonce="<?= $nonce_hash ?>">
        $(document).ready(function () {
            $("#back_button").on("click", function () {
                window.history.back();
            });
        });
    </script>

<?php include 'incl/bottomLogin.php'; ?>