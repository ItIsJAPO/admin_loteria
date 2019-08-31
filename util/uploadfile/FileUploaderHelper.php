<?php

namespace util\uploadfile;

use plataforma\exception\IntentionalException;

use util\image\ImageResizer;

use util\config\Config;

class FileUploaderHelper {

    const PROFILE_PICTURES = "profile_pictures";
    const INVOICES_OF_CLIENTS= 'invoices_of_clients';

    private static $ERRORS = array(
        1 => 'El tamaño del archivo excede el tamaño permitido en este servidor.',
        2 => 'El tamaño del archivo excede el tamaño permitido.',
        3 => 'No se pudo subir el archivo por completo.',
        4 => 'No se pudo subir ningun archivo.',
        6 => 'Falta un directorio temporal.',
        7 => 'No se pudo escribir en el disco duro.',
        8 => 'Una extension de PHP interumpe el proceso de subir el archivo.',
        'post_max_size' => 'El tamaño del archivo excede el tamaño permitido en este servidor.',
        'max_file_size' => 'Archivo demasiado grande.',
        'min_file_size' => 'Archivo demasiado pequeño.',
        'accept_file_types' => 'Tipo de archivo no permitido.',
        'max_number_of_files' => 'Ya no puede subir mas archivos.'
    );

    private static $PHP_MIME_TYPES = array(
        "text/php",
        "text/x-php",
        "application/php",
        "application/x-php",
        "application/x-httpd-php",
        "application/x-httpd-php-source"
    );

    private static $EXE_MIME_TYPES = array(
        "application/x-msdownload",
        "application/x-ms-installer",
        "application/x-elf",
        "application/octet-stream",
        "application/x-msi",
        "application/x-msdos-program",
        "application/vnd.apple.installer+xml",
        "application/javascript",
        "application/json"
    );

    private static $LINK_MIME_TYPES = array(
        "application/x-ms-shortcut"
    );

    public static $COMPRESSED_FILE_MIME_TYPES = array(
        "application/x-bzip2",
        "application/zip",
        "application/x-rar-compressed",
        "application/octet-stream",
        "application/x-gzip",
        "application/gzip",
        "application/x-tar",
        "application/tar",
        "application/tar+gzip",
        "application/x-7z-compressed",
        "application/x-bzip",
        "application/x-gtar"
    );

    private static $CSV_MIME_TYPES = array(
        "text/csv",
        "application/csv",
        "application/vnd.ms-excel",
        "text/plain"
    );

    public static function isNotAllowed( $file, $mimeType = null, $extension = null ) {
        return self::isLink($file, $mimeType, $extension) || self::isExecutable($file, $mimeType, $extension) || self::isCompressedFile($file, $mimeType, $extension);
    }

    public static function isLink( $file, $mimeType = null, $extension = null ) {
        $ext = is_null($extension) ? pathinfo($file, PATHINFO_EXTENSION) : $extension;
        $type = is_null($mimeType) ? self::getMimeType($file) : $mimeType;
        $isLink = array_search($type, self::$LINK_MIME_TYPES);

        return is_link($file) || $ext == 'lnk' || ( $isLink !== false );
    }

    public static function isExecutable( $file, $mimeType = null, $extension = null ) {
        $ext = is_null($extension) ? pathinfo($file, PATHINFO_EXTENSION) : $extension;
        $type = is_null($mimeType) ? self::getMimeType($file) : $mimeType;
        $isPHPFile = array_search($type, self::$PHP_MIME_TYPES);
        $isEXE = array_search($type, self::$EXE_MIME_TYPES);

        return is_executable($file) || ( $isPHPFile !== false ) || ( $isEXE !== false ) || $ext == "php" || $ext == "exe" || $ext == "bat" || $ext == "cmd" || $ext == "btm" || $ext == "msi";
    }

    public static function isCompressedFile( $file, $mimeType = null, $extension = null ) {
        $ext = is_null($extension) ? pathinfo($file, PATHINFO_EXTENSION) : $extension;
        $type = is_null($mimeType) ? self::getMimeType($file) : $mimeType;
        $isCompressedFile = array_search($type, self::$COMPRESSED_FILE_MIME_TYPES);

        return ( $isCompressedFile !== false ) || $ext == 'rar' || $ext == 'zip' || $ext == '7z' || $ext == 'gz';
    }

    public static function isCSV( $file, $mimeType = null, $extension = null ) {
        $ext = is_null($extension) ? pathinfo($file, PATHINFO_EXTENSION) : $extension;
        $type = is_null($mimeType) ? self::getMimeType($file) : $mimeType;
        $isCSV = array_search($type, self::$CSV_MIME_TYPES);

        return ( $isCSV !== false ) || $ext == 'csv';
    }
    
    public static function decodeError( $constant ) {
        $errorMessages = self::$ERRORS;

        if ( isset($errorMessages[$constant]) ) {
            return $errorMessages[$constant];
        }

        return "Error desconocido";
    }

    public static function pathOfPrivateDirectoryOf( $path = "" ) {
        $systemPath = Config::get("private_files_folder");

        if ( $path ) {
            $directoryOfUploads = $systemPath . "/" . $path . "/";
            return $directoryOfUploads;
        }

        return $systemPath . "/";
    }

    public static function pathOfDirectoryOf( $path ) {
        $systemPath = Config::get('path_sistema');
        $directoryOfUploads = $systemPath . "/" . $path . "/";

        return $directoryOfUploads;
    }

    public static function createDirectoryIfNotExists( $path , $recursive = true ) {
        if ( !is_dir($path) ) {
            mkdir($path, 0777, $recursive);
        }
    }

    public static function deleteFile( $path ) {
        if ( is_file($path) ) {
            return unlink($path);
        }

        return false;
    }
    
    public static function deleteAllFilesIn( $path ) {
        if ( is_dir($path) ) {
            if ( $dir = opendir($path) ) {
                while ( $archivo = readdir($dir) ) { 

                    $filePath = $path . "/" . $archivo;
                    
                    if ( is_file($filePath) ) {
                        unlink($filePath);
                    }
                }
            }
        }
    }

    public static function deleteAllFilesWithExtension( $absolutePath, $extension ) {
        foreach ( glob($absolutePath . "*." . $extension) as $pdf ) {
            self::deleteFile($pdf);
        }
    }

    public static function getUploadMaxFileSize() {
        return ini_get("upload_max_filesize");
    }

    public static function isExcelFile( $path ) {
        $accepted = array(
            'Excel2007',
            'Excel5',
            'Excel2003XML',
            'CSV'
        );
        
        set_include_path(get_include_path() . PATH_SEPARATOR . Config::get("path_sistema") . "/util/phpexcel");
        
        $reader = \PHPExcel_IOFactory::identify($path);
        
        return in_array($reader, $accepted);
    }
    
    public static function getMimeType( $file ) {
        $type = "unknow";

        if ( !function_exists("finfo_open") ) {
            if ( !function_exists("mime_content_type") ) {
                return $type;
            } else {
                return mime_content_type($file);
            }
        } else {
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $type = finfo_file($fileInfo, $file);
            finfo_close($fileInfo);
        }

        return $type;
    }
    
    public static function isPDF( $file ) {
        return self::compareMimeType($file, "application/pdf");
    }
    
    public static function isXML( $file ) {
        $isValid = false;

        try {
            $isValid = simplexml_load_file($file);
        } catch ( \Exception $e ) {
            Logger()->error($e);
        }

        return $isValid;
    }
    
    public static function isImage( $file ) {
        $mimeType = self::getMimeType($file);

        return strpos($mimeType, "image/") === 0;
    }
    
    public static function compareMimeType( $file, $expectedMimeType ) {
        $type = self::getMimeType($file);

        if ( strcasecmp($type, $expectedMimeType) == 0 ) {
            return true;
        }

        return false;
    }

    public static function copyImage( $tmp_file, $file, $resize = false, $width = 0, $height = 0 ) {
        if ( $resize ) {
            $data_image = getimagesize($tmp_file);
            $image_resizer = new ImageResizer($tmp_file);

            $image_resizer->setMaxWidthAndHeight($width, $height);

            if ( is_array($data_image) ) {
                if ( ($data_image[0] > $width) || ($data_image[1] > $height) ) {
                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                    if ( ($extension == "png") || ($extension == "jpeg") ) {
                        return $image_resizer->resizeAndSaveIn($file);
                    } else {
                        return rename($tmp_file, $file);
                    }
                } else {
                    return rename($tmp_file, $file);
                }
            } else {
                return rename($tmp_file, $file);
            }
        } else {
            return rename($tmp_file, $file);
        }

        return false;
    }

    public static function moveImage( $tmp_file, $file, $resize = false, $width = 0, $height = 0 ) {
        if ( $resize ) {
            $data_image = getimagesize($tmp_file);
            $image_resizer = new ImageResizer($tmp_file);

            $image_resizer->setMaxWidthAndHeight($width, $height);

            if ( is_array($data_image) ) {
                if ( ($data_image[0] > $width) || ($data_image[1] > $height) ) {
                    $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));

                    if ( ($extension == "png") || ($extension == "jpeg") ) {
                        return $image_resizer->resizeAndSaveIn($file);
                    } else {
                        throw new IntentionalException(
                            0, "La imagen excede las dimensiones permitidas"
                        );
                    }
                } else {
                    return move_uploaded_file($tmp_file, $file);
                }
            } else {
                throw new IntentionalException(
                    0, "Error al obtener dimensiones de la imagen"
                );
            }
        } else {
            return move_uploaded_file($tmp_file, $file);
        }

        return false;
    }

    public static function downloadFile( $filePath, $filename, $deleteAfter = false ) {
        if ( file_exists($filePath) ) {
            header("Content-Disposition: attachment; filename=" . basename($filename));
            header("Content-Type: application/force-download");
            header("Content-Length: " . filesize($filePath));
            header("Content-Transfer-Encoding: binary");
             
            readfile($filePath);
        } else {
            throw new IntentionalException(0, "El archivo que intenta descargar no existe o fue borrado");
        }

        if ( $deleteAfter ) {
            if ( file_exists($filePath) ) {
                FileUploaderHelper::deleteFile($filePath);
            }
        }
    }

    public static function dompdfStream( $output, $filename ) {
        header('Content-Disposition: attachment; filename=' . basename($filename . ".pdf"));
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Description: File Transfer');
        header('Content-Transfer-Encoding: binary');
        header('Content-Type: application/pdf');
        header('Pragma: public');
        header('Expires: 0');
        ob_clean();
        echo $output;
        flush();
    }

    public static function showImage( $file ) {
        if ( file_exists($file) ) {
            header('Content-Type: image/' . pathinfo($file, PATHINFO_EXTENSION));
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Content-Disposition: inline; filename=' . basename($file));
            header('Expires: 0');
            ob_clean();
            flush();
            readfile($file);
        }
    }


    public static function readXML($filePath) {
        if (file_exists($filePath)) {
            header("Content-Type: text/xml");
            header("Content-Disposition: inline; filename=" . basename($filePath));
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . filesize($filePath));

            readfile($filePath);
        } else {
            throw new IntentionalException(0, "El archivo que intenta buscar no existe o fue borrado");
        }
    }

    public static function readDoc( $filePath, $filename = "" ) {
        $filename_to_show = ($filename != "") ? $filename : basename($filePath);

        if ( file_exists($filePath) ) {
            header("Content-Type: " . self::getMimeType($filePath));
            header("Content-Disposition: inline; filename=" . $filename_to_show);
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: " . filesize($filePath));

            readfile($filePath);
        } else {
            throw new IntentionalException(0, "El archivo que intenta buscar no existe o fue borrado");
        }
    }



    /**
     *
     * @param string $nameImage :nombre dela variable file
     * @param string $savePath :Directorio conpleto con la carpeta destino
     * @param string $nameImageSave : Nombre nuevo del archivo sin extención
     * @param array $formatFiles :Array con tipo de extención de archivo
     * @param int $sizeImage :Tamaño por default del archivo es de 3mb de subida
     * @return array
     * @author freddy Chable
     */

    public static function validImage($nameImage,$savePath ,$nameImageSave = null, $formatFiles = array(),  $sizeImage = null){
        $img = $nameImage;

        if( $img['error']> 0 ){
            throw new IntentionalException(0,'Error ocurrio un error al adjuntar la imagen');
        }

        $type = explode( "/",$img['type']);
        $extension = strtolower($type[1]);
        if(empty($formatFiles)) {
            $formatFiles = array('jpg', 'jpeg', 'png', 'bmp', 'gif');
        }

        if(!in_array($extension, $formatFiles)){
            throw new IntentionalException(0,'Error la imagen no es un formato válido');
        }

        $size = ( $sizeImage == null)? 3000789 : $sizeImage;

        if($img['size']> $size){
            throw new IntentionalException(0,'Error la imagen supera el tamño permitido');
        }

        FileUploaderHelper::createDirectoryIfNotExists($savePath);
        $filename = ($nameImageSave == null )? uniqid(date("Y-m-d_"), true) : $nameImageSave;

        $filename = $filename . "." . $extension;
        $path = $savePath.$filename;
        move_uploaded_file($img['tmp_name'],$path);

        if(!file_exists($path)){
            throw new IntentionalException(0,'Error al guardar el archivo en el directorio');
        }

        return array(
            'type'=>$extension,
            'size'=>$img['size'],
            'uniqidName'=> $filename,
            'originalName'=> $img['name'],
            'path'=> $path,
        );
    }

}