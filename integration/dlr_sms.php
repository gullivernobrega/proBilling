<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


require_once 'classes/Conn.class.php';
$conn = new Conn();

$conteudo = str_replace("'", '"', file_get_contents('php://input'));
$arrayDados = json_decode($conteudo, true);
$date = date("Y-m-d H:i:s");


$Query = "UPDATE cdr_sms SET sms_date_atualizacao= '$date',sms_operadora='{$arrayDados['data']['after'][0]['sms_operator']}',`sms_status`= '{$arrayDados['data']['after'][0]['status']}' WHERE sms_id = {$arrayDados['data']['after'][0]['id']}";
$conn->Inserir($Query);
