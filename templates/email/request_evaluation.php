<div class = "margin_view">
    <table class = "table_view">
        <tr>
            <td class = "cell_view text-center">
                <div style = "margin-bottom: 30px">
                    <h3> <b class = "text-uppercase"> <?= sprintf(_("Nueva solicitud de la evaluacion: %s"), $evaluacion) ?> </b> </h3>
                </div>
                
                <div style = "margin-bottom: 30px">
                    <p> 
                        <?= sprintf(
                            _("El usuario: %s ha presentado y enviado la evaluacion: %s del curso: %s"),
                            '<b>' . $nombre_usuario . '</b>', '<b>' . $evaluacion . '</b>', '<b>' . $curso . '</b>'
                        ) ?>
                    </p>
                </div>

                <a class = "btn btn-success" href = "<?= $url_calificar ?>"><?= _('Calificar evaluacion') ?></a>
            </td>
        </tr>
    </table>
</div>