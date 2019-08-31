<?php
    $mantenimiento = $dataAndView->getData('mantenimiento');
    $twwitterInfo = $dataAndView->getData('infoTwitter');
    $facebookInfo = $dataAndView->getData('infoFacebook');
?>
<div class="row page-titles">
    <div class="col-md-5 align-self-center">
        <h3 class="text-primary">Configuración</h3> </div>
    <div class="col-md-7 align-self-center">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Inicio</a></li>
            <li class="breadcrumb-item active">Configuración</li>
        </ol>
    </div>
</div>
<style>
    .user_panel {
        border: 1px solid #e2e2e2;
        border-radius: 6px;
        min-height: 400px;
        height: 400px;
        max-height: 400px;
        box-shadow: 0px 0px 4px 2px #f7f6f6;
        padding: 10px;
    }
    .user_panel:hover {
        transition: 0.5s;
        box-shadow: 0px 0px 10px 2px #e0dfdf;
    }
    .switch {
        font-size: 1rem;
        position: relative;
    }
    .switch input {
        position: absolute;
        height: 1px;
        width: 1px;
        background: none;
        border: 0;
        clip: rect(0 0 0 0);
        clip-path: inset(50%);
        overflow: hidden;
        padding: 0;
    }
    .switch input + label {
        position: relative;
        min-width: calc(calc(2.375rem * .8) * 2);
        border-radius: calc(2.375rem * .8);
        height: calc(2.375rem * .8);
        line-height: calc(2.375rem * .8);
        display: inline-block;
        cursor: pointer;
        outline: none;
        user-select: none;
        vertical-align: middle;
        text-indent: calc(calc(calc(2.375rem * .8) * 2) + .5rem);
    }
    .switch input + label::before,
    .switch input + label::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: calc(calc(2.375rem * .8) * 2);
        bottom: 0;
        display: block;
    }
    .switch input + label::before {
        right: 0;
        background-color: #dee2e6;
        border-radius: calc(2.375rem * .8);
        transition: 0.2s all;
    }
    .switch input + label::after {
        top: 2px;
        left: 2px;
        width: calc(calc(2.375rem * .8) - calc(2px * 2));
        height: calc(calc(2.375rem * .8) - calc(2px * 2));
        border-radius: 50%;
        background-color: white;
        transition: 0.2s all;
    }
    .switch input:checked + label::before {
        background-color: #08d;
    }
    .switch input:checked + label::after {
        margin-left: calc(2.375rem * .8);
    }
    .switch input:focus + label::before {
        outline: none;
        box-shadow: 0 0 0 0.2rem rgba(0, 136, 221, 0.25);
    }
    .switch input:disabled + label {
        color: #868e96;
        cursor: not-allowed;
    }
    .switch input:disabled + label::before {
        background-color: #e9ecef;
    }
    .switch.switch-sm {
        font-size: 0.875rem;
    }
    .switch.switch-sm input + label {
        min-width: calc(calc(1.9375rem * .8) * 2);
        height: calc(1.9375rem * .8);
        line-height: calc(1.9375rem * .8);
        text-indent: calc(calc(calc(1.9375rem * .8) * 2) + .5rem);
    }
    .switch.switch-sm input + label::before {
        width: calc(calc(1.9375rem * .8) * 2);
    }
    .switch.switch-sm input + label::after {
        width: calc(calc(1.9375rem * .8) - calc(2px * 2));
        height: calc(calc(1.9375rem * .8) - calc(2px * 2));
    }
    .switch.switch-sm input:checked + label::after {
        margin-left: calc(1.9375rem * .8);
    }
    .switch.switch-lg {
        font-size: 1.25rem;
    }
    .switch.switch-lg input + label {
        min-width: calc(calc(3rem * .8) * 2);
        height: calc(3rem * .8);
        line-height: calc(3rem * .8);
        text-indent: calc(calc(calc(3rem * .8) * 2) + .5rem);
    }
    .switch.switch-lg input + label::before {
        width: calc(calc(3rem * .8) * 2);
    }
    .switch.switch-lg input + label::after {
        width: calc(calc(3rem * .8) - calc(2px * 2));
        height: calc(calc(3rem * .8) - calc(2px * 2));
    }
    .switch.switch-lg input:checked + label::after {
        margin-left: calc(3rem * .8);
    }
    .switch + .switch {
        margin-left: 1rem;
    }
</style>

<div class="container-fluid">
    <div class = "row">
        <div class = "form-group col-12 col-lg-4 col-md-4 text-center">
            <div class = "card user_panel table_view">
                <div class = "cell_view">
                    <div class = "form-group">
                        <label>Ajustes del sistema</label>
                    </div>
                    <div class = "form-group">
                        <i class="fas fa-3x fa-sliders-h"></i>
                    </div>
                    <div class = "form-group">
                        <label>Poner el sistema en modo mantenimiento</label>
                    </div>
                    <span class="switch">
                      <input type="checkbox"  <?= ($mantenimiento == 1) ? 'checked' : '' ?> class="switch" id="switch-id">
                      <label for="switch-id"></label>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<script nonce = "<?= $nonce_hash ?>">
    $(document).ready(function() {
        services={};

        services.saveConfigFacebook=function(title,url){
            return $.ajax({
                type: "POST",
                url: "/configuration/save_config_facebook",
                data:{
                    title:title,
                    url:url
                }
            });
        };

        services.saveConfigTwitter=function(message,url){
            return $.ajax({
                type: "POST",
                url: "/configuration/save_config_twitter",
                data:{
                    message:message,
                    url:url
                }
            });
        };

        $("#switch-id").on("change", function() {
            $.ajax({
                type: "POST",
                url: "/configuration/maintenance_mode",
                success: function( response ) {
                    handleJsonResponse(response, function() {
                        noty.show('success', response.message);
                    });
                }
            });
        });

        $('body').on('click','#btn-facebook',function (e) {
            e.preventDefault();
            let title=$('#facebookTitle').val();
            let url=$('#facebookUrl').val();

            $.when(services.saveConfigFacebook(title,url))
                .then(function (response) {
                    noty.show(response.type,response.message);
                })
        });

        $('body').on('click','#btn-twitter',function (e) {
            e.preventDefault();
            let message=$('#twitterMessage').val();
            let url=$('#twitterUrl').val();

            $.when(services.saveConfigTwitter(message,url))
                .then(function (response) {
                    noty.show(response.type,response.message);
                })
        });

    });
</script>