<div class = "margin_view">
    <table class = "table_view">
        <tr>
            <td class = "cell_view text-center">
                <div style = "margin-bottom: 30px">
                    <h3> <b class = "text-uppercase"> <?= sprintf(_("Cuenta %s"), $accion) ?> </b> </h3>
                </div>

                <div style = "margin-bottom: 30px">
                    <p><?= sprintf(_("Su cuenta ha sido %s"), '<b>' . $accion . '</b>') ?></p>
                </div>

                <div style = "margin-bottom: 30px">
                    <p> <?= _("El motivo es el siguiente:") ?> </p>
                </div>

                <textarea class = "form-control disabled" rows = "4" disabled style = "margin: 0 auto; width: 80%"> <?= $reason ?> </textarea>
            </td>
        <tr>
    </table>
</div>