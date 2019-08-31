<?php

namespace util\date;

class ParseDate {

    private static $DIAS = array(
        '1' => 'Lunes',
        '2' => 'Martes',
        '3' => 'Miércoles',
        '4' => 'Jueves',
        '5' => 'Viernes',
        '6' => 'Sábado',
        '7' => 'Domingo'
    );
    
    private static $MESES = array(
        '01' => 'Enero',
        '02' => 'Febrero',
        '03' => 'Marzo',
        '04' => 'Abril',
        '05' => 'Mayo',
        '06' => 'Junio',
        '07' => 'Julio',
        '08' => 'Agosto',
        '09' => 'Septiembre',
        '10' => 'Octubre',
        '11' => 'Noviembre',
        '12' => 'Diciembre'
    );

    public static function getFormatDate( $format, $date = "now" ) {
        if ( $date == "now" ) {
            $now = new \DateTime();
        } else {
            $now = new \DateTime($date);
        }

        return $now->format($format);
    }

    public static function getFullDate( $date = "now" ) {
        if ( $date == "now" ) {
            $now = new \DateTime();
        } else {
            $now = new \DateTime($date);
        }
        
        $dias  = self::$DIAS;
        $meses = self::$MESES;

        $parts = explode(" ", $now->format('N d m Y'));
        $dia   = str_ireplace($parts[0], $dias[$parts[0]], $parts[0]);
        $mes   = str_ireplace($parts[2], $meses[$parts[2]], $parts[2]);
        
        return $dia . ", " . $parts[1] . " de " . $mes . " de " . $parts[3];
    }

    public static function getOnlyDate( $date = "now" ) {
        if ( $date == "now" ) {
            $now = new \DateTime();
        } else {
            $now = new \DateTime($date);
        }
        
        $meses = self::$MESES;

        $parts = explode(" ", $now->format('N d m Y'));
        $mes   = str_ireplace($parts[2], $meses[$parts[2]], $parts[2]);
        
        return $parts[1] . " de " . $mes . " de " . $parts[3];
    }

    public static function validDate( $date, $format = 'd-m-Y H:i:s' ) {
        $d = \DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) == $date;
    }
}