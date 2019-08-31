<?php

namespace util\facturacionmoderna\logic;

class FacturaRetencionOtro {

    private $folioFactura = NULL;
    private $idDueno = NULL;
    private $idRetencionOtro = NULL;
    private $monto = NULL;
    
    public static function getPopulatedInstance( $params ) {
        $model = new FacturaRetencionOtro();
        $model->setFolioFactura(array_key_exists('folio_factura', $params) && isset($params['folio_factura']) && intval($params['folio_factura']) > 0 ? intval($params['folio_factura']) : NULL);
        $model->setIdDueno(array_key_exists('id_dueno', $params) && isset($params['id_dueno']) && intval($params['id_dueno']) > 0 ? intval($params['id_dueno']) : NULL);
        $model->setIdRetencionOtro(array_key_exists('id_retencion_otro', $params) && isset($params['id_retencion_otro']) && intval($params['id_retencion_otro']) > 0 ? intval($params['id_retencion_otro']) : NULL);
        $model->setMonto(array_key_exists('monto', $params) && isset($params['monto']) && doubleval($params['monto']) > 0 ? doubleval($params['monto']) : NULL);
        
        return $model;
    }

    /**
	 * @return the unknown_type
	 */
    public function getFolioFactura(){
        return $this->folioFactura;
    }

    /**
	 * @param unknown_type $folioFactura
	 */
    public function setFolioFactura( $folioFactura ){
        $this->folioFactura = $folioFactura;
        return $this;
    }

    /**
	 * @return the unknown_type
	 */
    public function getIdDueno(){
        return $this->idDueno;
    }

    /**
	 * @param unknown_type $idDueno
	 */
    public function setIdDueno( $idDueno ){
        $this->idDueno = $idDueno;
        return $this;
    }

    /**
	 * @return the unknown_type
	 */
    public function getIdRetencionOtro(){
        return $this->idRetencionOtro;
    }

    /**
	 * @param unknown_type $idRetencionOtro
	 */
    public function setIdRetencionOtro( $idRetencionOtro ){
        $this->idRetencionOtro = $idRetencionOtro;
        return $this;
    }

    /**
	 * @return the unknown_type
	 */
    public function getMonto(){
        return $this->monto;
    }

    /**
	 * @param unknown_type $monto
	 */
    public function setMonto( $monto ){
        $this->monto = $monto;
        return $this;
    }
    
    public function jsonSerialize() {
        return get_object_vars($this);
    }
}