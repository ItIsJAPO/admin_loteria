<?php

namespace util\facturacionmoderna\logic;

class ContratoRetencionOtro {

    private $idContrato = NULL;
    private $idRetencionOtro = NULL;
    
    public static function getPopulatedInstance( $params ) {
        $model = new ContratoRetencionOtro();
        $model->setFolioFactura(array_key_exists('id_contrato', $params) && isset($params['id_contrato']) && intval($params['id_contrato']) > 0 ? intval($params['id_contrato']) : NULL);
        $model->setIdRetencionOtro(array_key_exists('id_retencion_otro', $params) && isset($params['id_retencion_otro']) && intval($params['id_retencion_otro']) > 0 ? intval($params['id_retencion_otro']) : NULL);
    
        return $model;
    }

    /**
	 * @return the unknown_type
	 */
    public function getIdContrato() {
        return $this->idContrato;
    }

    /**
	 * @param unknown_type $idContrato
	 */
    public function setIdContrato( $idContrato ) {
        $this->idContrato = $idContrato;
        return $this;
    }

    /**
	 * @return the unknown_type
	 */
    public function getIdRetencionOtro() {
        return $this->idRetencionOtro;
    }

    /**
	 * @param unknown_type $idRetencionOtro
	 */
    public function setIdRetencionOtro( $idRetencionOtro ) {
        $this->idRetencionOtro = $idRetencionOtro;
        return $this;
    }
}