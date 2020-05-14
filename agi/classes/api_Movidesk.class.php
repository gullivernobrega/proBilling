<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Conn
 *
 * @author Gulliver Nobrega
 */
class api_Movidesk {

    private $dataApi;
    private $metodo;
    private $unique;
    private $clientNumber;
    private $Result;
    private $link;
    private $ramal;

    public function setApi($dataApi, $unique, $clientNumber, $metodo, $agi) {

        
        $this->dataApi = $dataApi;
        $this->unique = $unique;
        $this->clientNumber = $clientNumber;
        $this->metodo = $metodo;

        if ($this->metodo == 'receivedCall') {
            $this->receivedCall($agi);
        } 
    }

    public function setCompleted($link, $unique) {

        $this->link = $link;
        $this->unique = $unique;

        $this->completedCall();
    }

    public function getResult() {

        return $this->Result;
    }

    private function receivedCall($agi) {

        $data = array("token" => "91d80a81-673a-4b37-afe8-32ed183bf75f", "queueId" => "1", "clientNumber" => "$this->clientNumber", "id" => "$this->unique", "callDate" => "$this->dataApi");
        $data_string = json_encode($data);

        $ch = curl_init("https://api.movidesk.com/public/v1/asterisk_receivedCall?token=91d80a81-673a-4b37-afe8-32ed183bf75f&queueId=1&clientNumber=$this->clientNumber&id=$this->unique&callDate=$this->dataApi");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        $ramal = json_decode($result, true);
        $ramal = explode(' ', $ramal);
//var_dump($ramal);
        $ramal2 = $ramal[0];    
        $this->ramal = $ramal2;
        $this->Result = "$result";

    }

    public function transferedCall($dataApi, $unique, $ramal) {
        $data = array("token" => "91d80a81-673a-4b37-afe8-32ed183bf75f", "id" => "$unique", "branchLine" => "$ramal", "transferDate" => "$dataApi");
        $data_string = json_encode($data);

        $ch = curl_init("https://api.movidesk.com/public/v1/asterisk_transferedCall?token=91d80a81-673a-4b37-afe8-32ed183bf75f&id=$unique&branchLine=$ramal&transferDate=$dataApi");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        

    }

    private function completedCall() {
        $horaAtual = date('H:i:s');
        $dateAtual = date('Y-m-d');
        $dateAPI = $dateAtual . 'T' . $horaAtual;
        $data = array("token" => "91d80a81-673a-4b37-afe8-32ed183bf75f", "id" => "$this->unique", "link" => "$this->link", "completedDate" => "$dateAPI");
        $data_string = json_encode($data);

        $ch = curl_init("https://api.movidesk.com/public/v1/asterisk_completedCall?token=91d80a81-673a-4b37-afe8-32ed183bf75f&id=$this->unique&link=$this->link&completedDate=$dateAPI");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
    }

}
