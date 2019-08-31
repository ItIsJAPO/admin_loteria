<?php

namespace util\facturacionmoderna\logic;

class Retencion {

    private $id = NULL;
    private $nombreImpuesto = NULL;
    private $valorImpuesto = NULL;
    private $porcentajeImpuesto = NULL;

    /**
	 * @return the unknown_type
	 */
    public function getId(){
        return $this->id;
    }

    /**
	 * @param unknown_type $id
	 */
    public function setId( $id ){
        $this->id = $id;
        return $this;
    } 

    /**
	 * @return the unknown_type
	 */
    public function getNombreImpuesto(){
        return $this->nombreImpuesto;
    }

    /**
	 * @param unknown_type $nombreImpuesto
	 */
    public function setNombreImpuesto( $nombreImpuesto ){
        $this->nombreImpuesto = $nombreImpuesto;
        return $this;
    }

    /**
	 * @return the unknown_type
	 */
    public function getValorImpuesto(){
        return $this->valorImpuesto;
    }

    /**
	 * @param unknown_type $valorImpuesto
	 */
    public function setValorImpuesto( $valorImpuesto ){
        $this->valorImpuesto = $valorImpuesto;
        return $this;
    }

    /**
	 * @return the unknown_type
	 */
    public function getPorcentajeImpuesto(){
        return $this->porcentajeImpuesto;
    }

    /**
	 * @param unknown_type $porcentajeImpuesto
	 */
    public function setPorcentajeImpuesto( $porcentajeImpuesto ){
        $this->porcentajeImpuesto = $porcentajeImpuesto;
        return $this;
    }
    
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}