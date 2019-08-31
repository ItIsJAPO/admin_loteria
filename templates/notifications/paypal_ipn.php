<div class = "margin_view" style = "padding: 20px 10% !important">
    <div class = "table_view">
        <div class = "cell_view text-center">
            <h3> <b class = "text-uppercase"> <?= $notification_type ?> </b> </h3>
            
            <br>

            <?php
            if ( isset($requestParams) && !empty($requestParams->getPost()) ) {
                foreach ( $requestParams->getPost() as $key => $value ) { ?>
                    <div class = "form-group">
                        <h4><label><?= $key ?></label></h4>
                        <input type = "text" class = "form-control" value = "<?= $value ?>" readonly />
                    </div>
                    <?php
                }
            } ?>

            <br>

            <div>
                <a href = "[--link_download--]" target = "_blank"><?= _("Descargar archivo json") ?></a>
            </div>
        </div>
    </div>
</div>