<?php

namespace util\image;

use plataforma\exception\FileNotFoundException;
use plataforma\exception\NotPermitedImageType;

use util\config\Config;

class ImageResizer {
    
    const DEFAULT_MAX_WIDTH = 300;
    const DEFAULT_MAX_HEIGHT = 300;
        
    private $imagePath;
    private $maxWidth = 0;
    private $maxHeight = 0;
    
    private $imageInfo;
        
    public function  __construct( $path ) {
        if ( file_exists($path) ) {
            $this->imagePath = $path;
            $this->imageInfo = getimagesize($this->imagePath);

            $this->setMaxWidthAndHeight(
                self::DEFAULT_MAX_WIDTH,
                self::DEFAULT_MAX_HEIGHT
            );
            
        } else {
            throw new FileNotFoundException(" '$path' not found");
        }
    }    
    
    public function setMaxWidthAndHeight( $maxWidth, $maxHeight ) {
        $this->maxWidth = $maxWidth;
        $this->maxHeight = $maxHeight;
    }
    
    public function resize() {
        $image = null;
        $type = $this->imageInfo[2];

        switch ( $type ) {
            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($this->imagePath);
            break;
            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($this->imagePath);
            break;
            default:
                throw new NotPermitedImageType("Image type '" . $type . "' not allowed");
            break;
        }

        $newSize = $this->fixDimensions();
        $newImg = imagecreatetruecolor($newSize[0], $newSize[1]);

        imagealphablending($newImg, false);
        imagesavealpha($newImg, true);
        $transparent = imagecolorallocatealpha($newImg, 255, 255, 255, 127);
        imagefilledrectangle($newImg, 0, 0, $newSize[0], $newSize[1], $transparent);
        imagecopyresized($newImg, $image, 0, 0, 0, 0, $newSize[0], $newSize[1], $this->imageInfo[0], $this->imageInfo[1]);

        return $newImg;
    }
    
    
    /**
     * 
     * @param string $path
     * @throws NotPermitedImageType
     * @return boolean true if saved
     */
    public function resizeAndSaveIn( $path ) {
    	$image = $this->resize();
        $type = $this->imageInfo[2];
        $result = false;

        switch ( $type ) {
            case IMAGETYPE_JPEG:
                $result = imagejpeg($image, $path);
            break;
            case IMAGETYPE_PNG:
                $result = imagepng($image, $path);
            break;
            default:
                throw new NotPermitedImageType("Image type '" . $type . "' not allowed");
            break;
        }

        // free memory
        imagedestroy($image);

        return $result;
    }
    
    /**
     * Devuelve un array con las dimensiones ajustadas
     * 0 - ancho
     * 1 - alto
     * @return array
     */
    private function fixDimensions() {
        $width = $this->imageInfo[0];
        $height = $this->imageInfo[1];

        if ( $height > $this->maxHeight ) {
            $width = ($this->maxHeight / $height) * $width;
            $height = $this->maxHeight;
        }

        if ( $width > $this->maxWidth ) {
            $height = ($this->maxWidth / $width) * $height;
            $width = $this->maxWidth;
        }

        return array($width, $height);
    }

    public static function getDataImage64Encode( $image, $type = 'png' ) {
        return 'data:image/' . $type . ';base64,' . base64_encode(file_get_contents(Config::get('path_sistema') . '/assets/img/' . $image));
    }
}