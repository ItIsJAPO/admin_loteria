<?php
namespace util\foreignExchange;

class ForexDataClient
{
    private $api_key;
    private $client;

    public function __construct($api_key)
    {
        $this->api_key = $api_key;

        $this->client = array('base_uri' => 'http://forex.1forge.com/1.0.3/quotes?pairs=');

        $this->last_heartbeat = time();
        $this->heartbeat_interval = 15;
    }

    private function uri($divisaA,$divisaB)
    {
       return $this->client['base_uri'].$divisaA.$divisaB.'&api_key='.$this->api_key;
    }

    public function consult($divisaA,$divisaB)
    {
        $cliente = curl_init();
        $datas=array();
        curl_setopt($cliente, CURLOPT_URL,self::uri($divisaA,$divisaB));
        curl_setopt($cliente, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($cliente, CURLOPT_HEADER, false);
        $contenido = curl_exec($cliente);
        curl_close($cliente);

        $data = json_decode($contenido,true);

        $datas = array('cambio'=>$data[0]['bid']);

        return $datas;
    }

}