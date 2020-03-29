<?php
//require("/Helpers/phpagi.php");
include_once 'Helpers/phpagi-asmanager.php';

/**
 * AgentAgi.class 
 * Descricao
 * @copyright (c)28/11/2018, Kleber de Souza BRAZISTELECOM
 */
class AgentAgi {

    private $Liga;
    private $Ramal;
    private $Agent;
    private $Result;
    private $Agi;

    /**
     *  Metodo respossavel por Logar o agente na fila.
     */
    public function agentFila($agi, $ramal, $agent) {
        $this->Ramal = $ramal;
        $this->Agent = $agent;
        $this->Agi = $agi;

        $this->setAgi();
        $this->Executa();
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
        $conectaServidor = $conectaServidor = $asmanager->connect('localhost', 'proBilling', 'proBillingpasswoRd');
    }

//    $this->liga = $asmanager->Command("channel originate SIP/$ramal extension $agente@fila");    
    private function setAgi() {
        $this->liga = "channel originate SIP/{$this->Ramal} extension {$this->Agent}@fila";       
    }
    
    private function Executa() {        
        $this->comAgi();
        $this->Result = $asmanager->Command($this->Liga);        
    }
}
