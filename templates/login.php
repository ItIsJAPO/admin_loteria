<?php

use util\token\TokenHelper;

$nonce_hash = TokenHelper::generarCodigo(mt_rand());

include 'incl/topLogin.php';
include $dataAndView->getView();
include 'incl/bottomLogin.php';