<?php

use util\token\TokenHelper;

$nonce_hash = TokenHelper::generarCodigo(mt_rand());

if ( $dataAndView->getView() && file_exists($dataAndView->getView()) ) {
	include $dataAndView->getView();
}