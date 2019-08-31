<div class = "margin_view">
    <table class = "table_view">
        <tr>
            <td class = "cell_view text-center">
                <div style = "margin-bottom: 30px">
                    <h2> <b class = "text-uppercase"> <?= _("Pago de suscripcion erroneo") ?> </b> </h2>
                </div>
                
                <div class = "form-group">
                    <p>
                        <?=
                            sprintf(
                                _("El pago de su suscripcion al curso: %s ha fallado"),
                                '<b>' . $course . '</b>'
                            )
                        ?>
                    </p>
                </div>

                <br>

                <div class = "form-group text-center">
                    <div style = "margin: 0 auto; width: 60%">
                        <table class = "table-bordered table_view text-center" cellspacing = "0">
                            <thead>
                                <tr>
                                    <th colspan = "3" class = "text-center" style = "padding: 5px;">
                                        <?= _("Suscripcion") ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th style = "padding: 5px;"><?= _("Curso") ?></th>
                                    <th style = "padding: 5px;"><?= _("Ciclos") ?></th>
                                    <th style = "padding: 5px;"><?= _("Frecuencia") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style = "padding: 5px; width: 33%">
                                        <?= $course ?>
                                    </td>
                                    <td style = "padding: 5px; width: 33%">
                                        <?= _("Mensuales") ?>
                                    </td>
                                    <td style = "padding: 5px; width: 33%">
                                        <?= $frequency ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <br>

                        <table class = "table-bordered table_view text-center" cellspacing = "0">
                            <thead>
                                <tr>
                                    <th colspan = "3" class = "text-center" style = "padding: 5px;">
                                        <?= _("Ciclo de tiempo pagado") ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th style = "padding: 5px;"><?= _("Fecha inicio") ?></th>
                                    <th style = "padding: 5px;"><?= _("Fecha fin") ?></th>
                                    <th style = "padding: 5px;"><?= _("Precio") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style = "padding: 5px; width: 33%">
                                        <?= $start_date ?>
                                    </td>
                                    <td style = "padding: 5px; width: 33%">
                                        <?= $end_date ?>
                                    </td>
                                    <td style = "padding: 5px; width: 33%">
                                        $ <?= number_format($price, 2) ?> USD
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </td>
        </tr>
    </table>
</div>