<?php

namespace util\facturacionmoderna\logic;

use util\facturacionmoderna\logic\ContratoRetencionOtro;
use util\facturacionmoderna\logic\FacturaRetencionOtro;
use util\facturacionmoderna\logic\RetencionOtro;

use database\Connections;
use database\SimpleDAO;

class FacturaRetencionDAO extends SimpleDAO {

    public function __construct() {
        parent::__construct(NULL);
    }
    
    public function getById( $id, $idUsuario ) {
        $sql = "SELECT * FROM retencion_otro WHERE id_usuario = :id_usuario AND id = :id";
        
        $statement = Connections::getConnection()->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":id_usuario", $idUsuario);
        $statement->bindParam(":id", $id);
        $statement->execute();

        $row = $statement->fetch();
        
        $retencion = NULL;

        if ( !empty($row) ) {
            $retencion = RetencionOtro::getPopulatedInstance($row);
        }
        
        return $retencion;
    }

    public function findContratoRetencionOtroByRetencionOtroId( $id_retencion_otro ) {
        $sql = "SELECT * FROM contrato_retencion_otro WHERE id_retencion_otro = :id_retencion_otro";

        $statement = Connections::getConnection()->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":id_retencion_otro", $id_retencion_otro);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function findFacturaRetencionOtroByRetencionOtroId( $id_retencion_otro ) {
        $sql = "SELECT * FROM factura_retencion_otro WHERE id_retencion_otro = :id_retencion_otro";

        $statement = Connections::getConnection()->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":id_retencion_otro", $id_retencion_otro);
        $statement->execute();

        return $statement->fetchAll();
    }
    
    public function listRetencionOtroByIdUsuario( $idUsuario ) {
        $sql = "SELECT * FROM retencion_otro WHERE id_usuario = :id_usuario";
        
        $statement = Connections::getConnection()->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":id_usuario", $idUsuario);
        $statement->execute();

        $result = $statement->fetchAll();
        
        $retencionOtros = array();

        if ( !empty($result) ) {
            foreach ( $result as $row ) {
                $model = RetencionOtro::getPopulatedInstance($row);
                
                $retencionOtros[] = $model;
            }
        }
        
        return $retencionOtros;
    }
    
    public function listRetencionOtro4Contrato( $idUsuario, $idContrato ) {
        $sql = '
            select
                *
            from retencion_otro ro
                inner join contrato_retencion_otro cro on cro.id_retencion_otro = ro.id
                    and cro.id_contrato = :contrato_id
            where ro.id_usuario = :usuario_id
        ';
        
        $statement = Connections::getConnection()->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":usuario_id", $idUsuario);
        $statement->bindParam(":contrato_id", $idContrato);
        $statement->execute();

        $result = $statement->fetchAll();
        
        $retencionOtros = array();

        if ( !empty($result) ) {
            foreach ( $result as $row ) {
                $model = RetencionOtro::getPopulatedInstance($row);
                
                $retencionOtros[] = $model;
            }
        }
        
        return $retencionOtros;
    }
    
    public function listRetencionOtro4UsuarioAndFlaggedContrato( $idUsuario, $idContrato ) {
        $sql = "
            SELECT
                retencion_otro.*,
                contrato_retencion_otro.id_contrato
            FROM 
                retencion_otro LEFT JOIN contrato_retencion_otro ON
                    contrato_retencion_otro.id_retencion_otro = retencion_otro.id AND
                    contrato_retencion_otro.id_contrato = :id_contrato
            WHERE 
                retencion_otro.id_usuario = :id_usuario
        ";
        
        $statement = Connections::getConnection()->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":id_usuario", $idUsuario);
        $statement->bindParam(":id_contrato", $idContrato);
        $statement->execute();

        $result = $statement->fetchAll();
        
        $retencionOtros = array();

        if ( !empty($result) ) {
            foreach ( $result as $row ) {
                $model = RetencionOtro::getPopulatedInstance($row);
                
                $retencionOtros[] = $model;
            }
        }
        
        return $retencionOtros;
    }
    
    public function addRetencionOtro2Contrato( $idContrato, $idRetencionOtro ) {
        $sql = "INSERT INTO contrato_retencion_otro VALUES ( :id_contrato, :id_retencion_otro )";

        $statement = Connections::getConnection()->prepare($sql);
        $statement->bindParam(":id_retencion_otro", $idRetencionOtro);
        $statement->bindParam(":id_contrato", $idContrato);
        $statement->execute();
    }
    
    public function removeRetencionOtroFromContrato( $idContrato, $idRetencionOtro ) {
        $sql = "DELETE FROM contrato_retencion_otro WHERE id_contrato = :id_contrato AND id_retencion_otro = :id_retencion_otro";

        $statement = Connections::getConnection()->prepare($sql);
        $statement->bindParam(":id_retencion_otro", $idRetencionOtro);
        $statement->bindParam(":id_contrato", $idContrato);
        $statement->execute();
    }

    public function insertRetencionOtro( RetencionOtro &$model ) {
        $sql = "INSERT INTO retencion_otro ( id_usuario, concepto, porcentaje ) VALUES ( :id_usuario, :concepto, :porcentaje )";

        $porcentaje = $model->getPorcentaje();
        $id_usuario = $model->getIdUsuario();
        $concepto = $model->getConcepto();
        
        $statement = Connections::getConnection()->prepare($sql);

        $statement->bindParam(":id_usuario", $id_usuario);
        $statement->bindParam(":porcentaje", $porcentaje);
        $statement->bindParam(":concepto", $concepto);

        $statement->execute();
        
        $model->setId(Connections::getConnection()->lastInsertId());
        
        return $model->getId();
    }

    public function insertFacturaRetencionOtro( FacturaRetencionOtro &$model ) {
        $sql = "INSERT INTO factura_retencion_otro ( folio_factura, id_dueno, id_retencion_otro, monto ) VALUES ( :folio_factura, :id_dueno, :id_retencion_otro, :monto )";

        $folio_factura = $model->getFolioFactura();
        $id_dueno = $model->getIdDueno();
        $id_retencion_otro = $model->getIdRetencionOtro();
        $monto = $model->getMonto();
        
        $statement = Connections::getConnection()->prepare($sql);

        $statement->bindParam(":folio_factura", $folio_factura);
        $statement->bindParam(":id_dueno", $id_dueno);
        $statement->bindParam(":id_retencion_otro", $id_retencion_otro);
        $statement->bindParam(":monto", $monto);

        $statement->execute();
    }

    public function insertContratoRetencionOtro( ContratoRetencionOtro &$model ) {
        $sql = "INSERT INTO contrato_retencion_otro ( id_contrato, id_retencion_otro ) VALUES ( :id_contrato, :id_retencion_otro )";

        $idContrato = $model->getIdContrato();
        $idRetencionOtro = $model->getIdRetencionOtro();

        $statement = Connections::getConnection()->prepare($sql);

        $statement->bindParam(":id_retencion_otro", $idRetencionOtro);
        $statement->bindParam(":id_contrato", $idContrato);

        $statement->execute();
    }
    
    public function updateRetencionOtro( RetencionOtro $model ) {
        $sql = "UPDATE retencion_otro SET concepto = :concepto, porcentaje = :porcentaje WHERE id = :id AND id_usuario = :id_usuario";

        $id = $model->getId();
        $concepto = $model->getConcepto();
        $id_usuario = $model->getIdUsuario();
        $porcentaje = $model->getPorcentaje();
        
        $statement = Connections::getConnection()->prepare($sql);

        $statement->bindParam(":id_usuario", $id_usuario);
        $statement->bindParam(":porcentaje", $porcentaje);
        $statement->bindParam(":concepto", $concepto);
        $statement->bindParam(":id", $id);

        $statement->execute();
    }
    
    public function deleteRetencionOtro( RetencionOtro $model ) {
        $sql = "DELETE FROM retencion_otro WHERE id = :id AND id_usuario = :id_usuario";

        $id = $model->getId();
        $id_usuario = $model->getIdUsuario();
        
        $statement = Connections::getConnection()->prepare($sql);
        
        $statement->bindParam(":id_usuario", $id_usuario);
        $statement->bindParam(":id", $id);

        $statement->execute();
    }
    
    public function deleteFacturaRetencionOtro4Factura( $folio, $id_dueno ) {
        $sql = "DELETE FROM factura_retencion_otro WHERE folio_factura = :folio_factura AND id_dueno = :id_dueno";
        
        $statement = Connections::getConnection()->prepare($sql);
        
        $statement->bindParam(":folio_factura", $folio);
        $statement->bindParam(":id_dueno", $id_dueno);

        $statement->execute();
    }
}