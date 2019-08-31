<?php

namespace util\arrays;

class ArrayTools {

    public static function getDeep( $array, $searchKey ) {
        if ( !is_array($array) ) {
            throw new \Exception("param 1 must be array", 101);
        }
        
        $ret = NULL;

        foreach ( $array as $key => $value ) {
            if ( $key == $searchKey ) {
                $ret = $value;
            } else if ( is_array($value) ) {
                $ret = ArrayTools::getDeep($value, $searchKey);
            }
            
            if ( $ret != NULL ) {
                break;
            }
        }
        
        return $ret;
    }

    public static function arrayToString( $array, $lineBreak = "\n" ) {
        if ( $array == NULL ) {
            return "";
        }
        
        if ( !is_array($array) ) {
            return $array;
        }
        
        $str = "start[" . $lineBreak;

        foreach ( $array as $key => $value ) {
            if ( is_array($value) ) {
                $str .= $key . "=>[" . ArrayTools::arrayToString($value, $lineBreak) . "]" . $lineBreak;
            } else if ( $value === NULL ) {
                $str .= $key . "=>[IS_NULL]" . $lineBreak;
            } else {
                $str .= $key . "=>[" . $value . "]" . $lineBreak;
            }
        }
        
        return $str . $lineBreak . "]end";
    }
}