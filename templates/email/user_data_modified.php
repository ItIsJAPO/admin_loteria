<div class = "margin_view">
    <table class = "table_view">
        <tr>
            <td class = "cell_view text-center">
                <div style = "margin-bottom: 30px">
                    <h2> <b class = "text-uppercase"> <?= _("Datos de cuenta de usuario modificados") ?> </b> </h2>
                </div>
                
                <div class = "form-group">
                    <p> <?= _("Los datos de su cuenta de acceso han sido modificados por un administrador, los cambios registrados son los siguientes:") ?> </p>
                </div>

                <br>

                <div class = "form-group text-center">
                    <div style = "margin: 0 auto; width: 60%">
                        <table class = "table-bordered table_view text-center" cellspacing = "0">
                            <thead>
                                <tr>
                                    <th colspan = "2" class = "text-center" style = "padding: 5px;">
                                        <?= _("Nombre del usuario") ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th style = "padding: 5px;"><?= _("Antes") ?></th>
                                    <th style = "padding: 5px;"><?= _("Ahora") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style = "padding: 5px; width: 50%">
                                        <?= $nombre_usuario_before ?>
                                    </td>
                                    <td style = "padding: 5px; width: 50%">
                                        <?= $nombre_usuario ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <br>
                        
                        <table class = "table-bordered table_view text-center" cellspacing = "0">
                            <thead>
                                <tr>
                                    <th colspan = "2" class = "text-center" style = "padding: 5px;">
                                        <?= _("Correo del usuario") ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th style = "padding: 5px;"><?= _("Antes") ?></th>
                                    <th style = "padding: 5px;"><?= _("Ahora") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style = "padding: 5px; width: 50%">
                                        <?= $correo_before ?>
                                    </td>
                                    <td style = "padding: 5px; width: 50%">
                                        <?= $correo ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>

                        <br>
                        
                        <table class = "table-bordered table_view text-center" cellspacing = "0">
                            <thead>
                                <tr>
                                    <th colspan = "2" class = "text-center" style = "padding: 5px;">
                                        <?= _("Estado de la cuenta") ?>
                                    </th>
                                </tr>
                                <tr>
                                    <th style = "padding: 5px;"><?= _("Antes") ?></th>
                                    <th style = "padding: 5px;"><?= _("Ahora") ?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td style = "padding: 5px; width: 50%">
                                        <?= $status_before ?>
                                    </td>
                                    <td style = "padding: 5px; width: 50%">
                                        <?= $status ?>
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