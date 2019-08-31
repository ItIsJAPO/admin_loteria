<?php

namespace util\seguridad;

use util\config\Config;

class Seguridad {

   private $reserved_words;

   private $hash;


   public function __construct() {
      $config_file  = Config::get('path_sistema') . DIRECTORY_SEPARATOR . 'util';
      $config_file .= DIRECTORY_SEPARATOR . 'seguridad' . DIRECTORY_SEPARATOR . 'reserved_words.php';

      $key = md5( Config::get('mod_seguridad_KeyPhrase'),TRUE);
      $key .= substr($key,0,8);
      $this->hash = $key;

      include $config_file;

      $this->reserved_words = $words;
   }

   public function XOREncryption( $InputString, $KeyPhrase ) {
      $KeyPhraseLength = strlen($KeyPhrase);

      for ( $i = 0; $i < strlen($InputString); $i++ ) {
         $rPos = $i % $KeyPhraseLength;
         $r = ord($InputString[$i]) ^ ord($KeyPhrase[$rPos]);
         $InputString[$i] = chr($r);
      }

      return $InputString;
   }

   public function XOREncrypt( $InputString ) {
      $InputString .= "";
      $KeyPhrase = Config::get('mod_seguridad_KeyPhrase');
      $InputString = $this->XOREncryption($InputString, $KeyPhrase);
      $InputString = base64_encode($InputString);

      return $InputString;
   }

   public function XORDecrypt( $InputString ) {
      $InputString = str_replace(' ', '+', $InputString);
      $KeyPhrase = Config::get('mod_seguridad_KeyPhrase');
      $InputString = base64_decode($InputString);
      $InputString = $this->XOREncryption($InputString, $KeyPhrase);

      return $InputString;
   }

   private function pkcs5_pad($data){
      //Pad for PKCS5
      $blockSize = mcrypt_get_block_size('tripledes', 'ecb');
      $len = strlen($data);
      $pad = $blockSize - ($len % $blockSize);
      $data .= str_repeat(chr($pad), $pad);
      return $data;
   }
   private function pkcs5_unpad($data){
      //Unpad for PKCS5
      $pad = ord($data{strlen($data)-1});
      if ($pad > strlen($data)) return false;
      if (strspn($data, chr($pad), strlen($data) - $pad) != $pad) return false;
      return substr($data, 0, -1 * $pad);
   }
   /**
    * @param $data
    * @return string
    */
   public function Encrypt($data){
      $data = $this->pkcs5_pad($data);
      $encData = mcrypt_encrypt('tripledes', $this->hash , $data, 'ecb');
      return base64_encode($encData);
   }
   /**
    * @param $data
    * @return bool|string
    */
   public function Decrypt($data){
      $data = base64_decode($data);
      $data = mcrypt_decrypt('tripledes', $this->hash, $data, 'ecb');
      return $this->pkcs5_unpad($data);
   }


   public function replaceReservedWords( $string_to_analize ) {
      return preg_replace('/"/', '\'', preg_replace($this->reserved_words, "", $string_to_analize));
   }
}