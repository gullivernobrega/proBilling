<?php

require_once 'classes/Conn.class.php';
$conn = new Conn();

$conteudo = str_replace("'", '"', file_get_contents('php://input'));
$arrayDados = json_decode($conteudo, true);
$date = date("Y-m-d H:i:s");
$Query = "INSERT INTO rest_sms(sms_cus_id, sms_acc_id, origem, resposta, data_recebimento) "
        . "VALUES ({$arrayDados['data']['after'][0]['sms_cus_id']}, {$arrayDados['data']['after'][0]['sms_acc_id']}, "
        . "'{$arrayDados['data']['after'][0]['origem']}', '{$arrayDados['data']['after'][0]['resposta']}', '$date' )";
        
  $conn->Inserir($Query);      

