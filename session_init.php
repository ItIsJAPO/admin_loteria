<?php
use plataforma\exception\IntentionalException;
use plataforma\session\SessionLoader;
use plataforma\SysConstants;
use util\token\TokenHelper;
use util\config\Config;

$session_domain = Config::get('session_domain');
$session_timeout = Config::get('session_timeout') * 60;

date_default_timezone_set('America/Mexico_City');

ini_set('suhosin.cookie.cryptdocroot', 0);
ini_set('suhosin.session.cryptdocroot', 0);
ini_set('session.cookie_domain', $session_domain);

session_name("loteria_uacam");
session_start();

if ( !isset($_SESSION[SysConstants::SESS_PARAM_REMEMBER_SESSION]) || ($_SESSION[SysConstants::SESS_PARAM_REMEMBER_SESSION] === 0) ) {
    unset($_SESSION['last_session_update']);
    $_SESSION['last_activity'] = time(); // update last activity time stamp

    ini_set('session.gc_maxlifetime', 3600);
    ini_set('session.cookie_lifetime', 3600);
    setcookie("loteria_uacam", "PpAx9P9qqSRApSa4uOrtTOR2CPWj0byiV433Gvz1Hl8", time() + 3600, '/', $session_domain, true, true);
} else {
    unset($_SESSION['last_activity']);
    $_SESSION['last_session_update'] = time(); // update time stamp
    ini_set('session.gc_maxlifetime', 99999915);
    ini_set('session.cookie_lifetime', 99999915);
    setcookie("loteria_uacam", "PpAx9P9qqSRApSa4uOrtTOR2CPWj0byiV433Gvz1Hl8", time() + 99999915, '/', $session_domain, true, true);
}

$availableLocales = array("es_MX");

$defaultLocale = Config::get('default_lang');

$langHeader = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) && (trim($_SERVER['HTTP_ACCEPT_LANGUAGE']) != "") ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : "";
$country = substr($langHeader, 3, 2);
$lang = substr($langHeader, 0, 2);

$locale = $lang . "_" . strtoupper($country);

if ( !in_array($locale, $availableLocales) ) {
    // try to find the language independently of the country
    $locFound = false;

    foreach ( $availableLocales as $loc ) {
        if ( substr($loc, 0, 2) == substr($locale, 0, 2) ) {
            $locFound = true;
            $locale = $loc;
            break;
        }
    }

    if ( !$locFound ) {
        $locale = $defaultLocale;
    }
}

$domain = 'messages';

putenv("LC_ALL=$locale");
setlocale(LC_ALL, $locale);
bindtextdomain($domain, './locale');
textdomain($domain);
bind_textdomain_codeset($domain, 'UTF-8');

$_SESSION[SysConstants::SESS_PARAM_USER_LOCALE] = strtolower(substr($locale, 3, 2)); // e.g.: mx
$_SESSION[SysConstants::SESS_PARAM_ISO_639_1] = strtolower(substr($locale, 0, 2)); // e.g.: es
$_SESSION[SysConstants::SESS_PARAM_USER_LANG_CAT] = strtolower($locale); // e.g.: es_mx
$_SESSION[SysConstants::SESS_PARAM_USER_LANG] = $locale; // e.g.: es_MX

// **************** session time out *************************
if ( isset($_SESSION[SysConstants::SESS_PARAM_AUTENTICADO]) && ($_SESSION[SysConstants::SESS_PARAM_AUTENTICADO] == 1) ) {
    if ( isset($_SESSION['last_activity']) && ((time() - $_SESSION['last_activity']) > 3600) ) {
        session_unset(); // unset $_SESSION variable for the run-time
        session_destroy(); // destroy session data in storage
    }

    if ( isset($_SESSION['last_session_update']) && ( ( time() - $_SESSION['last_session_update'] ) > $session_timeout ) ) {
        try {
            SessionLoader::reloadSession();
        } catch ( IntentionalException $e ) {
            include 'error_pages/500.php';
            exit;
        } catch ( \Exception $e ) {
            Logger()->error($e);
            include 'error_pages/500.php';
            exit;
        }
    }
}
