<div class = "margin_view">
    <table class = "table_view">
        <tr>
            <td class = "cell_view text-center">
                <div style = "margin-bottom: 30px">
                    <h2> <b class = "text-uppercase"> <?= _("Cancelacion de membresia existosa") ?> </b> </h2>
                </div>

                <div class = "form-group">
                    <p>
                        <?= sprintf(
                            _("Se ha realizado la cancelacion de la membresia: %s del usuario: %s al curso: %s exitosamente"),
                            '<b> ' . $membresia . '</b>', '<b> ' . $usuario . '</b>', '<b> ' . $curso . '</b>'
                        ) ?> </p>
                </div>
            </td>
        </tr>
    </table>
</div>