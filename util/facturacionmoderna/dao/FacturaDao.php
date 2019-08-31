<?php

namespace util\facturacionmoderna\dao;

use util\facturacionmoderna\logic\Factura;
use modulos\contrato\logic\Pago;
use util\arrays\ArrayTools;
use modulos\log\LogLogic;
use database\Connections;
use database\SimpleDAO;

class FacturaDao extends SimpleDAO {

    public function insert( Factura $model ) {
        $sql  = 'INSERT INTO factura ( folio, id_dueno, id_usuario, num_pago, id_contrato, concepto, fecha, total, importe, iva, iva_ret, isr_ret, uid, estatus, enviada )';
        $sql .= ' VALUES ( :folio, :id_dueno, :id_usuario, :num_pago, :id_contrato, :concepto, :fecha, :total, :importe, :iva, :iva_ret, :isr_ret, :uid, :estatus, :enviada )';
        
        $statement = Connections::getConnection()->prepare($sql);
                
        $fecha = str_replace("T", " ", $model->getFecha());
        
        $folio = $model->getFolio();
        $idDueno = $model->getIdDueno();
        $idUsuario = $model->getIdUsuario();
        $idContrato = $model->getIdContrato();
        $noPago = $model->getNoPago();
        $concepto = $model->getConceptoPago();
        $fecha = $fecha;
        $total = $model->getTotal();
        $importe = $model->getImporte();
        $iva = $model->getImporteIva();
        $ivaRet = $model->getImporteIvaRet();
        $isrRet = $model->getImporteIsrRet();
        $uid = $model->getUid();
        $estatus = $model->getEstatus();
        $enviada = $model->getEnviada();
        
        $statement->bindParam(":folio", $folio);
        $statement->bindParam(":id_dueno", $idDueno);
        $statement->bindParam(":id_usuario", $idUsuario);
        $statement->bindParam(":num_pago", $noPago);
        $statement->bindParam(":id_contrato", $idContrato);
        $statement->bindParam(":concepto", $concepto);
        $statement->bindParam(":fecha", $fecha);
        $statement->bindParam(":total", $total);
        $statement->bindParam(":importe", $importe);
        $statement->bindParam(":iva", $iva);
        $statement->bindParam(":iva_ret", $ivaRet);
        $statement->bindParam(":isr_ret", $isrRet);
        $statement->bindParam(":uid", $uid);
        $statement->bindParam(":estatus", $estatus);
        $statement->bindParam(":enviada", $enviada);
        
        $statement->execute();
        
        return true;
    }

    public function findFacturaForPago( $concepto, $id_contrato, $num_pago ) {
        $sql = "SELECT * FROM factura WHERE id_contrato = :id_contrato and num_pago = :num_pago and concepto = :concepto";
        
        $statement = Connections::getConnection()->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":id_contrato", $id_contrato);
        $statement->bindParam(":concepto", $iconcepto);
        $statement->bindParam(":num_pago", $num_pago);
        $statement->execute();
        
        $row = $statement->fetch();
        
        $factura = NULL;

        if ( !empty($row) ) {
            $factura = new Factura();
            $factura->setEstatus($row['estatus']);
        }
        
        return $factura;
    }

    public function getFolioByUid( $uid ) {
        $sql = "SELECT folio FROM factura WHERE uid = :uid";
        
        $statement = Connections::getConnection()->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":uid", $uid);
        $statement->execute();
        
        $row = $statement->fetch();
        
        return !$row ? NULL : $row['folio'];
    }
    
    public function getByUid( $uid, $idUsuario ) {
        $sql = "SELECT 
                        f.folio,
                        f.id_dueno,
                        f.id_usuario,
                        f.id_contrato,
                        f.observaciones,
                        f.estatus,
                        f.enviada,
                        i.razon_social AS rs_inq,
                        i.nombre AS nombre_inq,
                        i.paterno AS pat_inq,
                        i.materno AS mat_inq,
                        i.correo,
                        d.razon_social,
                        d.rfc
                    FROM 
                        factura f,
                        contrato c,
                        inquilino i,
                        dueno d
                    WHERE
                        c.id = f.id_contrato AND
                        i.id = c.id_inquilino AND
                        d.id = f.id_dueno AND
                        f.uid = :uid AND 
                        f.id_usuario = :id_usuario";
        
        $statement = Connections::getConnection()->prepare($sql);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":id_usuario", $idUsuario);
        $statement->bindParam(":uid", $uid);
        $statement->execute();
        
        $row = $statement->fetch();
        
        $factura = NULL;

        if ( !empty($row) ) {
            $factura = new Factura();
            $factura->setFolio($row['folio']);
            $factura->setIdDueno($row['id_dueno']);
            $factura->setIdUsuario($row['id_usuario']);
            $factura->setIdContrato($row['id_contrato']);
            $factura->setObservaciones($row['observaciones']);
            $factura->setEstatus($row['estatus']);
            $factura->setEnviada($row['enviada']);
            $factura->setNombreR($row['rs_inq']);
            $factura->setNombre($row['razon_social']);
            $factura->setEmail($row['correo']);
            $factura->setRfc($row['rfc']);
            $nombreInqui = $row['nombre_inq'] . " " . $row['pat_inq'] . " " . $row['mat_inq'];
            $factura->setInquiNombreComplet(trim($nombreInqui));
        }
        
        return $factura;
    }

    public function updateCancel( Factura $factura, $obsevaciones = NULL ) {
        $sql = "UPDATE factura SET estatus = :estatus, observaciones = :observaciones, fecha_cancelada = now() WHERE uid = :uid";
        
        $uid = $factura->getUid();
        $estatus = Factura::ESTATUS_CANCELADA;
        
        $statement = Connections::getConnection()->prepare($sql);
        $statement->bindParam(":observaciones", $observaciones);
        $statement->bindParam(":estatus", $estatus);
        $statement->bindParam(":uid", $uid);
        
        $statement->execute();
    }

    public function updateEstatus( $uid, $newEstatus ) {
        $sql = "UPDATE factura SET estatus = :estatus WHERE uid = :uid";
        
        $statement = Connections::getConnection()->prepare($sql);
        $statement->bindParam(":estatus", $newEstatus);
        $statement->bindParam(":uid", $uid);
        
        $statement->execute();
    }

    public function getFolio( $idDueno ) {
        $query = "SELECT MAX(folio) + 1 AS folio FROM factura WHERE id_dueno = :id_dueno";
                        
        $statement = Connections::getConnection()->prepare($query);
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        $statement->bindParam(":id_dueno", $idDueno);
        
        $statement->execute();
        
        $row = $statement->fetch();
        
        $folio = 1;

        if ( !empty($row) ) {
            if ( !$row['folio'] == NULL ) {
                $folio = $row['folio'];
            }
        }
        
        return $folio;
    }
}