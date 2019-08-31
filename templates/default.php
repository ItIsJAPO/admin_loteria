<?php

use util\token\TokenHelper;

$nonce_hash = TokenHelper::generarCodigo(mt_rand());

include 'incl/top.php';
include $dataAndView->getView();
include 'incl/footer.php';