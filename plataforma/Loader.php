<?php

namespace plataforma;

class Loader {

    public static function getConfigRequest( &$get ) {
        $urlConfig = array(
            'acciones' => 2,
            'modulo' => 1,
            'id' => 3
        );

        $request = $_SERVER['REQUEST_URI'];

        $separate = explode("?", $request);
        $options = explode("/", $separate[0]);
        
        $configRequest = array();

        if ( is_array($urlConfig) && (count($urlConfig) > 0) ) {
            foreach ( $urlConfig as $key => $value ) {
                $configRequest[$key] = isset($options[$value]) ? $options[$value] : "";
            }
        }

        //$extraUrl = "";
        $extraUrlSplitted = array();
        $sizeConfigured = count($urlConfig);

        if ( count($options) > $sizeConfigured ) {
            for ( $i = $sizeConfigured + 1; $i < count($options); $i++ ) {
                //$extraUrl .= "/" . $options[$i];
                $extraUrlSplitted[] = $options[$i];
            }
        }

        //$configRequest['extra_url'] = $extraUrl;
        $configRequest['extra_url_splitted'] = $extraUrlSplitted;
        
        return array_merge($configRequest, $get);
    }
}