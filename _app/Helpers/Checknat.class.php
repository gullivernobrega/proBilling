<?php

/**
 * Checknat.class [ HELPE ]
 * Checa e faz a conversão no formato NAT.
 * @copyright (c) 06/03/2018, Kleber de Souza BRAZISTELECOM
 */
class Checknat {

    private $Data;
    private $Format;

    /**
     * <b>NatString</b> Verifica e transforma para o formata nat.
     *  
     * @param STRING $natString
     * @return string 
     */
    public function NatString($data) {
        $this->Data = $data;
        $sip_nat = '';
        foreach ($this->Data['sip_nat'] as $val):
            $sip_nat .= $val . ',';
        endforeach;
        $sip_nat = substr($sip_nat, 0, -1);

        return $this->Data = $sip_nat;
    }
    
    /**
     * <b>NatArr</b> Verifica e transforma em um array para campo data.
     *  
     * @param ARRAY $natArr
     * @return array 
     */
    public function NatArr($data) {
        $this->Data = $data;
        
        
       return $this->Data;
    }

}
