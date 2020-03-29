<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Interacao_Asterisk
 *
 * @author Gulliver Nóbrega
 */
class Interacao_Asterisk {

    //put your code here
    private $agi;
    private $numCall;
    private $numeroDestino;
    private $tipo;
    private $rota;
    private $data;
    private $dataPasta;

    public function Noop_Asterisk($agi, $numCall, $numeroDestino, $tipo, $rota) {
        $this->data = date('Y-m-d_H:i:s');
        $this->dataPasta = date('d-m-Y');
        
        $this->agi = $agi;
        $this->numCall = $numCall;
        $this->numeroDestino = $numeroDestino;
        $this->tipo = $tipo;
        $this->rota = $rota;

        $this->NoopAterisk();
    }

    public function dialStatus() {
            $dialstatus = $this->agi->get_variable("CDR(disposition)");
            $dialstatus = $dialstatus['data'];
            $this->agi->verbose("DIAL status " . $dialstatus);
            return $dialstatus;
            
    }
    public function Unique_Asterisk($unique) {
        $uniqueidTratado = explode('.', $unique);
//          Tirando o .(ponto) do UniqueID no asterisk.
        $unique = $uniqueidTratado[0] . $uniqueidTratado[1];
        return $unique;
    }

    private function NoopAterisk() {

        $this->agi->verbose(print_r($this->agi->request, true));
        $this->agi->exec("NOOP", "Numero\ Discado\ $this->tipo:\ $this->numeroDestino ");
        $this->agi->exec("NOOP", "Numero\ Tratado:\ $this->numCall");
        $this->agi->exec("NOOP", "Data\ da\ ligacao\ $this->data ");
        $this->agi->exec("NOOP", "Rota\ da\ ligacao\ $this->rota ");
    }

}
