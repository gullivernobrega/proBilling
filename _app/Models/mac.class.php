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

class mac {

    public $mac;

    public function mac() {

        $this->getMac();
    }

    private function getMac() {

        $output = shell_exec("cat /sys/class/net/*/address");
//      SEPARA A STRING DE SAIDA POR ESPACO EM BRANCO
        $mac = preg_split("/\s+/", $output);
        $mac = array_filter($mac);
        $remover = array("00:00:00:00:00:00");
        $this->mac = array_diff($mac, $remover);
    }

    public function resultMac() {
        return $this->mac;
    }

}
