<?php

namespace view;

use plataforma\view\json\JsonSerializable;

class ViewElements {

    /**
     * Convert an object or array into his
     * json representation
     * @return A string representation of the object or array
     */
    public static function toJSON( $object ) {
        if ( $object === NULL ) {
            return json_encode(NULL);
        }

        return json_encode(ViewElements::toArray($object));
    }

    private static function toArray( $object ) {
        $arrayOutput = NULL;

        if ( $object instanceof JsonSerializable ) {
            $arrayOutput = $object->jsonSerialize();
        } else if ( is_array($object) and count($object) == 0 ) {
            $arrayOutput = $object;
        } else if ( is_array($object) ) {
            $arrayOutput = array();

            foreach ( $object as $itemKey => $itemValue ) {
                if ( $itemValue instanceof JsonSerializable ) {
                    $arrayOutput[$itemKey] = $itemValue->jsonSerialize();
                } else if ( is_array($itemValue) ) {
                    $arrayOutput[$itemKey] = ViewElements::toArray($itemValue);
                } else {
                    // Maybe is a primitive 'Integer, Boolean, String, Float, ...'
                    $arrayOutput[$itemKey] = ViewElements::processDataType($itemValue);
                }
            }
        } else {
            // Maybe is a primitive 'Integer, Boolean, String, Float, ...'
            $arrayOutput = ViewElements::processDataType($object);
        }
        
        return $arrayOutput;
    }

    private static function processDataType( $value ) {
        if ( is_int($value) ) {
            $number = explode(".", $value);

            if ( is_array($number) && isset($number[1]) ) {
                return filter_var($value, FILTER_VALIDATE_FLOAT);
            } else {
                return filter_var($value, FILTER_VALIDATE_INT);
            }
        } else {
            return $value;
        }
    }
}