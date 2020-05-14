<?php

/*
 * Classe com metodos para analisar agente QUEUE do asteris, e armazenar dados 
 * no BD.
 * Integração com CRM via URL-API.
 * 
 * @AUTOR= Gulliver Nóbrega
 * @DATA = 26/04/2019
 * @PART= Kleber de Souza
 */

require_once 'Conn.class.php';

class consultaVersion extends Conn {

    private $version;
    public $result;
    
    
    public function version() {
        $this->getVersion();
                
        
    }

    private function getVersion() {
        
        $Query = "SELECT * FROM `update` WHERE 1";
        $this->result = $this->Consultar($Query);
        $this->result = $this->result[0];
         
        
        /*
         * Função para analisar numeros atendidos e enviar URL.
         * Envio de URL apenas uma vez por AGENTE(USUARIO)
         * 
         */

    }

    public function result() {
        return $this->result;
        
    }
}
