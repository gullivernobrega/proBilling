<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of smsControl
 *
 * @author Gulliver Nóbrega
 */
require_once 'Conn.class.php';

class smsControl extends Conn {

    //put your code here

    private $conn;
    private $numeros;
    private $numeroMSG;
    public $numeros_json;
    public $url_api;
    public $Lote;

    public function Campanha() {
        $Query = "SELECT * FROM campanha_sms WHERE campanha_sms_status = 'A'";
        $this->conn = $this->Consultar($Query);

        return $this->conn;
    }

    public function Numeros($agenda) {
        $Query = "SELECT * FROM numero_sms WHERE numero_sms_status = 'A' AND agenda_sms_id = (SELECT agenda_sms_id FROM agenda_sms WHERE agenda_sms_nome = '$agenda')";
        $this->numeros = $this->Consultar($Query);
       
        return $this->numeros;
    }

    public function MountJson() {

        foreach ($this->numeros as $numero) {
            $sms [] = (
                    array(
                        'numero' => $numero['numero_sms_fone'],
                        'servico' => 'short',
                        'mensagem' => $numero['numero_sms_msg'],
                        'codificacao' => '0'
            ));
            $this->numeroMSG[$numero['numero_sms_fone']] = $numero['numero_sms_msg'];
        }
        $this->numeros_json = json_encode($sms);
    }

    public function ApiSms() {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.disparopro.com.br/mt",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => "$this->numeros_json",
            CURLOPT_HTTPHEADER => [
                "authorization: Bearer a8b2d4fcb69a0f9951492410a6518396202e3723",
                "content-type: application/json"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            echo $response;
        }
        $this->url_api = json_decode($response, TRUE);
        $this->url_api = $this->url_api['detail'];

//        return $this->url_api;
    }
    public function LoteSMS($agenda , $numero) {
        $Query = "SELECT numero_sms_lote FROM numero_sms WHERE numero_sms_fone = '$numero' AND "
                . "agenda_sms_id = (SELECT agenda_sms_id FROM agenda_sms WHERE agenda_sms_nome = '$agenda') ";
       $lote = $this->Consultar($Query);
       print_r($lote);
       $this->Lote = $lote[0]['numero_sms_lote'];
    }

    public function updateAgendaNum($agenda, $status) {
        switch ($status) {
            case 'ACCEPTED':
                $up = 'E';
                break;
            case 'UNKNOWN':
                $up = 'P';
                break;
            case 'PAYREQUIRED':
                $up = 'B';
                break;
            default:
                $up = 'I';
                break;
        }
        $Query = "UPDATE numero_sms SET numero_sms_status = '$up' WHERE agenda_sms_id = (SELECT agenda_sms_id FROM agenda_sms WHERE agenda_sms_nome = '$agenda')";
        $this->conn = $this->Inserir($Query);
    }

    public function cdrSmsInsert($id, $campanha, $numero, $status) {
        
        $dataMomento = date("Y-m-d H:i:s");
  
        $Query = "INSERT INTO `cdr_sms`(sms_id, sms_date, sms_campanha, sms_numero, sms_msg, sms_status, sms_lote) "
                . "VALUES ($id,'$dataMomento', '$campanha','$numero','{$this->numeroMSG[$numero]}','$status', '$this->Lote')";
        $this->conn = $this->Inserir($Query);
    
        
                }

}
