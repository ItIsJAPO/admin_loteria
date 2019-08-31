<table style="text-align: center ;width: 100%;height: 100%">
    <tr>
        <td>
            <div style="margin-bottom: 30px">
                <h2><b class="text-uppercase">Solicitud de recuperación de contraseña</b></h2>
            </div>

            <div style="margin-bottom: 20px">
                <p>Se ha solicitado el cambio de contraseña por cual motivo se envió el siguiente correo eléctronico</p>
                <p style="font-size: small;font-weight: 600;color: #929292;text-align: justify;padding: 5px">
                    El token internamente tiene un reloj que está en combinación con el reloj del sistema para identificar cuál es el código dinámico del token válido en ese momento.
                    El tiempo de vida del token es de una hora desde la presente solicitud de cambio de contraseña.
                    En caso de un desfase del tiempo en el token, el sistema indica que el token ha expirado y tendra que mandar nuevamente la solicitud de cambio de contraseña.
                </p>
            </div>
        </td>
    </tr>
    <tr >
        <td>
            <a style="FONT-SIZE: 16px; TEXT-DECORATION: none;  BORDER-TOP: #59b07c 11px solid;
                FONT-FAMILY: Helvetica, Helvetica neue,Arial, Verdana, sans-serif; BORDER-RIGHT: #59b07c 20px solid;
                BORDER-BOTTOM: #59b07c 11px solid; COLOR: #ffffff; BORDER-LEFT: #59b07c 20px solid;
                DISPLAY: inline-block; BACKGROUND-COLOR: #59b07c; border-radius: 5px;
                -webkit-border-radius: 5px; -moz-border-radius: 5px"
                href="<?=$link.'?token='.$token?>"
                target="_blank" alias="Button" >Cambiar contraseña</a>
        </td>
    </tr>
</table>
