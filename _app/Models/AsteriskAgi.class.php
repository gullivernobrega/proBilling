<?php
//require("/Helpers/phpagi.php");
//require("Helpers/phpagi-asmanager.php");
include '_app/Helpers/phpagi-asmanager.php';

/**
 * AgentAgi.class 
 * Descricao
 * @copyright (c)28/11/2018, Gulliver Nobrega BRAZISTELECOM
 */
class AsteriskAgi {

    private $Fila;
    private $Result;
    private $Agi;

    /**
     *  Metodo respossavel por Logar o agente na fila.
     */
    public function QueueAsteriskStatistic($queue) {
        $this->Fila = $queue;
        $this->comAgi();
    }

    /** Retorna o resultado  */
    public function getResult() {
        return $this->Result;
    }

    /**
     * ****************************************
     * *********** PRIVATE METHODS ************
     * ****************************************
     */
    private function comAgi() {
        $asmanager = new AGI_AsteriskManager;
        $conectaServidor = $asmanager->connect('localhost', 'proBilling', 'proBilling');
        $server = $asmanager->Command( "queue show $this->Fila" );
	$arr = explode( "\n", $server["data"] );
	$arr2 = explode( ",", $arr[1] );
        $this->Result = $arr2;
        
        
    }

//    $this->liga = $asmanager->Command("channel originate SIP/$ramal extension $agente@fila");    
//    private function setAgi() {
//        $this->comando = "queue show $this->Fila";       
//    }
    
//    private function Executa() {        
////        $asmanager2 = $this->comAgi();
//         print_r($asmanager2->Command($this->comando));        
////        $this->Result = $asmanager2->Command($this->comando);        
//    }
}
