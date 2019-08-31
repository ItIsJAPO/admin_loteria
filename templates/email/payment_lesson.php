<div class = "margin_view">
    <table class = "table_view">
        <tr>
            <td class = "cell_view text-center">
                <div style = "margin-bottom: 30px">
                    <h2> <b class = "text-uppercase"> <?= _("Pago de leccion exitoso") ?> </b> </h2>
                </div>

                <div style = "margin-bottom: 30px">
                    <p> <?= _("Su pago ha sido registrado exitosamente. Ahora puede acceder a su leccion sin tener que esperar.") ?> </p>
                </div>

                <div style = "margin-bottom: 30px" class = "text-center">
                    <div style = "margin: 0 auto; width: 60%">
                        <table class = "table-bordered table_view text-center" cellspacing = "0">
                            <thead>
                                <tr>
                                    <th style = "padding: 5px; width: 33%"><?= _("Curso") ?></th>
                                    <th style = "padding: 5px; width: 33%"><?= _("Leccion") ?></th>
                                    <th style = "padding: 5px; width: 33%"><?= _("Precio") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style = "padding: 5px;">
                                        <?= $curso ?>
                                    </td>
                                    <td style = "padding: 5px;">
                                        <?= $nombre_leccion ?>
                                    </td>
                                    <td style = "padding: 5px;">
                                        <?= $precio_leccion ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <a class = "btn btn-success" href = "<?= $url_leccion ?>"><?= _('Ir a lección') ?></a>
            </td>
        </tr>
    </table>
</div>