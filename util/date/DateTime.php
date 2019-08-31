<?php

namespace util\date;

class DateTime extends \DateTime {

    private $monthAbr = array();

    public  function format( $format ) {
        $posM = strpos($format, 'M');

        if ( $posM === false ) {
            return parent::format($format);
        }
        
        $month = intval(parent::format('n')) - 1;
        $monthSpanish = $this->threeLetterMonth($month);
        $format = str_replace('M', '?_-_-_?', $format);
        $formatted = parent::format($format);
        
        $formatted = str_replace('?_-_-_?', $monthSpanish, $formatted);
        
        return $formatted;
    }

    private function threeLetterMonth( $numeric ) {
        $this->monthAbr["es"] = array(
            'Ene',
            'Feb',
            'Mar',
            'Abr',
            'May',
            'Jun',
            'Jul',
            'Ago',
            'Sep',
            'Oct',
            'Nov',
            'Dic'
        );

        return $this->monthAbr['es'][$numeric];
    }
}