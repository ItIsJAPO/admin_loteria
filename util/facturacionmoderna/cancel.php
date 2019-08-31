<?php

use util\facturacionmoderna\FacturacionModerna;

chdir('../');

include '__init.php';

$parametros = array(
    'emisorRFC' => "AVR130708I2A",
    'UserID' => "AVR130708I2A",
    'UserPass' => "f87b4bf91dcce3b11f62ee509c0e97b35a216a6f"
);

$fm = new FacturacionModerna("https://t1.facturacionmoderna.com/timbrado/wsdl", $parametros, 0);

$fm->cancelar('4E8FD706-B5E1-11E7-9A27-B740C6C8A305');