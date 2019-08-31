<div class = "margin_view" style = "padding: 20px 10% !important">
    <div class = "table_view">
        <div class = "cell_view">
            <h3> <b class = "text-uppercase"> <?= $notification_type ?> </b> </h3>
            
            <br>

            <?php
            function printData( $array_data, $margin_left = 0 ) {
                $next_margin = $margin_left + 40;
                $style_margin_left = 'style = "margin-left: ' . $margin_left . 'px"';

                if ( !empty($array_data) ) {
                    foreach ( $array_data as $key => $value ) {
                        if ( is_array($value) ) { ?>
                            <div class = "form-group">
                                <h4><label><?= $key ?></label></h4>
                            </div>
                            <?php
                            printData($value, $next_margin);
                        } else { ?>
                            <div class = "form-group" <?= ($margin_left > 0) ? $style_margin_left : '' ?> >
                                <h4><label><?= $key ?></label></h4>
                                <input type = "text" class = "form-control" value = "<?= $value ?>" readonly />
                            </div>
                            <?php
                        }
                    }
                }
            }

            if ( !empty($data_ipn) ) {
                printData($data_ipn);
            } ?>

            <br>

            <div>
                <a href = "[--link_download--]" target = "_blank"><?= _("Descargar archivo json") ?></a>
            </div>
        </div>
    </div>
</div>