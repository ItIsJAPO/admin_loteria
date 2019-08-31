<div class = "margin_view">
    <table class = "table_view">
        <tr>
            <td class = "cell_view text-center">
                <div style = "margin-bottom: 30px">
                    <h2> <b class = "text-uppercase"> <?= sprintf(_("Cancelacion de %s de %s"), $method, $pasarela) ?> </b> </h2>
                </div>

                <div style = "margin-bottom: 30px">
                    <p> <?= sprintf(_("El usuario registrado con el correo: %s ha cancelado su %s"), '<b> ' . $email . '</b>', $method) ?> </p>
                </div>

                <div style = "margin-bottom: 20px">
                    <p> <?= _("Motivo de la cancelacion especificada por el usuario:") ?> </p>
                </div>

                <textarea class = "form-control disabled" rows = "4" disabled style = "margin: 0 auto; width: 80%"> <?= $reason ?> </textarea>
            </td>
        </tr>
    </table>
</div>