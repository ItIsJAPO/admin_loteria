<?php

include 'autoloader.php';

use modulos\configuration\logic\Logic as ConfigurationLogic;

use plataforma\exception\PageNotFoundException;
use plataforma\exception\IntentionalException;
use plataforma\exception\PermissionException;
use plataforma\MainController;
use plataforma\AccessManager;
use plataforma\DataAndView;
use plataforma\Loader;

use util\config\Config;

/* Cuando se requiera que otro dominio o subdominio se conecte por ajax a este proyecto */

$access_headers = Config::get('header_allow_origin_value');

if ( isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $access_headers)){
    header("Access-Control-Allow-Origin:".$_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, DELETE");
}

ini_set('memory_limit', '512M');
// ********************* init logger *************************
include 'logger_init.php';
// ********************* init session *************************
include 'session_init.php';
// ************************************************************


function findModulo() {
    $modulo = NULL;
    
    try {

        $accessManager = new AccessManager();

        if ( !isset($_SERVER['HTTPS']) && Config::get("over_https") ) {
            $_SERVER['HTTPS'] = 'off';

            if ( ($_SERVER['HTTPS'] !== 'on') || ($_SERVER['SERVER_PORT'] != 443) ) {
                header('Location: ' . Config::get("url_sistema") . substr($_SERVER['REQUEST_URI'], 1));
            }
        }

        $_GET = Loader::getConfigRequest($_GET);
       
        $modulo = $accessManager->verifyAccess($_GET, $_SESSION);

    } catch ( PermissionException $p ) {
        include 'error_pages/403.php';

        return 'exception';
    } catch ( \Exception $e ) {
        include 'error_pages/500.php';

        return 'exception';
    }

    return $modulo;
}

function executeRequest( $modulo ) {
    if ( empty($modulo) ) {
        include "error_pages/404.php";
        return;
    }

    if ( $modulo == 'exception' ) {

        return;
    }

    $configuration = new ConfigurationLogic();

    if ( $configuration->isInMaintenanceMode($modulo) ) {
        include "error_pages/maintenance_mode.php";
        return;
    }

    try {
        
        $controler = new MainController();
        
      	$dataAndView = $controler->performRequest($_FILES,$_POST, $_GET, $_SESSION, $_SERVER, $modulo);

        $datas = $dataAndView->getData();
        $template = $dataAndView->getTemplate();
        
        if ( $template !== null ) {
            if ( $template == 'none' ) {
                echo $datas;
            } else {
                include "templates/" . $template . ".php";
            }
        } else {
            include "templates/default.php";
        }

    } catch ( PageNotFoundException $e ) {
        include "error_pages/404.php";
    } catch ( IntentionalException $e ) {
        Logger()->error($e);
        include 'error_pages/500.php';
    } catch ( \Exception $e ) {
        Logger()->error($e);
        include 'error_pages/500.php';
    }
}

executeRequest(findModulo());