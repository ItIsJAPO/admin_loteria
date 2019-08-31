<?php

namespace util\facturacionmoderna\logic;

use util\view\JsonSerializable;

class RetencionOtro implements JsonSerializable {

    private $id = NULL;
    private $idUsuario = NULL;
    private $concepto = NULL;
    private $porcentaje = NULL;
    
    private $idContrato = NULL;
    
    private $idIdContrato = NULL;

    public static function getPopulatedInstance( $params ) {
        $model = new RetencionOtro();
        $model->setId(array_key_exists('id', $params) && isset($params['id']) && intval($params['id']) > 0 ? intval($params['id']) : NULL);
        $model->setIdUsuario(array_key_exists('id_usuario', $params) && isset($params['id_usuario']) && intval($params['id_usuario']) > 0 ? intval($params['id_usuario']) : NULL);
        $model->setConcepto(array_key_exists('concepto', $params) && isset($params['concepto']) && trim($params['concepto']) != "" ? trim($params['concepto']) : NULL);
        $model->setPorcentaje(array_key_exists('porcentaje', $params) && isset($params['porcentaje']) && doubleval($params['porcentaje']) > 0 ? doubleval($params['porcentaje']) : NULL);
        
        $model->setIdContrato(array_key_exists('id_contrato', $params) && isset($params['id_contrato']) && intval($params['id_contrato']) > 0 ? intval($params['id_contrato']) : NULL);
        
        return $model;
    }

    /**
	 * @return int
	 */
    public function getId(){
        return $this->id;
    }

    /**
	 * @param int $id
	 */
    public function setId( $id ){
        $this->id = $id;

        if ( $this->id == NULL && $this->idContrato )
            $this->idIdContrato = "0_0";
        else if ( $this->id == NULL )
            $this->idIdContrato = "0_" . $this->idContrato;
        else if ( $this->idContrato == NULL )
            $this->idIdContrato = $this->id . "_0";
        else
            $this->idIdContrato = $this->id . "_" . $this->idContrato;
        
        return $this;
    }

    /**
	 * @return int
	 */
    public function getIdUsuario(){
        return $this->idUsuario;
    }

    /**
	 * @param int $idUsuario
	 */
    public function setIdUsuario( $idUsuario ){
        $this->idUsuario = $idUsuario;
        return $this;
    }

    /**
	 * @return string
	 */
    public function getConcepto(){
        return $this->concepto;
    }

    /**
	 * @param string $concepto
	 */
    public function setConcepto( $concepto ){
        $this->concepto = $concepto;
        return $this;
    }

    /**
	 * @return double
	 */
    public function getPorcentaje(){
        return $this->porcentaje;
    }

    /**
	 * @param double $porcentaje
	 */
    public function setPorcentaje( $porcentaje ){
        $this->porcentaje = $porcentaje;
        return $this;
    }

    /**
	 * @return the unknown_type
	 */
    public function getIdContrato(){
        return $this->idContrato;
    }

    /**
	 * @param unknown_type $idContrato
	 */
    public function setIdContrato( $idContrato ){
        $this->idContrato = $idContrato;
        
        if ( $this->id == NULL && $this->idContrato )
            $this->idIdContrato = "0_0";
        else if ( $this->id == NULL )
            $this->idIdContrato = "0_" . $this->idContrato;
        else if ( $this->idContrato == NULL )
            $this->idIdContrato = $this->id . "_0";
        else
            $this->idIdContrato = $this->id . "_" . $this->idContrato;
        
        return $this;
    }

    public function jsonSerialize() {
        return get_object_vars($this);
    }
}