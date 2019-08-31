<?php

namespace util\file;

class File {

    public function fileGetContents( $ruta = "" ) {
        if ( !file_exists($ruta) ) {
            return "";
        }
        
        return file_get_contents($ruta);
    }

    public function fileGetReplaceContents( $ruta, $replace ) {
        $content = $this->fileGetContents($ruta);
        
        foreach ( $replace as $k => $v ) {
            $content = str_replace("[--$k--]", $v, $content);
        }
        
        return $content;
    }

    public function getContentReplaced( $content, $replace ) {
        foreach ( $replace as $k => $v ) {
            $content = str_replace("[--$k--]", $v, $content);
        }
        
        return $content;
    }

    public function fileGetReplaceContentsDeleteEmpty( $ruta, $replace ) {
        $content = $this->fileGetContents($ruta);
        
        foreach ( $replace as $k => $v ) {
            $content = str_replace("[--$k--]", $v, $content);
        }
        
        $content = preg_replace('/\[\-\-[A-Za-z0-9_]+\-\-\]/m', '', $content);
        
        return $content;
    }

    public function getContentForEmail( $template, $data ) {
        ob_start();
        
        extract($data);

        include 'templates/email/top.php';
        include 'templates/email/' . $template . '.php';
        include 'templates/email/bottom.php';
        
        return ob_get_clean();
    }
}