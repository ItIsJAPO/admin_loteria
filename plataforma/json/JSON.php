<?php

namespace plataforma\json;

class JSON {

	/* Recibe una clase */
	public static function classToJSON( $class ) {
		$json = array();

		if ( !$class ) {
			return $json;
		}
		
		$reflectionClass = new \ReflectionClass($class);
        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE);
        
        if ( count($properties) > 0 ) {
	        foreach ( $properties as $property ) {
	        	$property->setAccessible(true);

	        	$json[$property->getName()] = $property->getValue($class);
	        }
	    }

	    return $json;
	}

	/* Recibe array de clases */
	public static function arrayToJSON( $classes ) {
		$json = array();
		
		if ( is_array($classes) ) {
			if ( count($classes) > 0 ) {
				foreach ( $classes as $class ) {
					$json[] = self::classToJSON($class);
				}
			}
		}

	    return $json;
	}
}