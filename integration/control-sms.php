<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once 'classes/Conn.class.php';
require_once 'classes/smsControl.class.php';
$today = date("Y-m-d H:i:s");
$conn = new Conn();
$sms = new smsControl();

// Pegando informações de campanhas de SMS ativas
$dados = $sms->Campanha();


foreach ($dados as $dado) {

    if ($today >= $dado['campanha_sms_data_inicio']) {
        $agenda = $dado['campanha_sms_agenda'];
        $campanha = $dado['campanha_sms_nome'];

//        Passando como parametro agenda da campanha para pegar numeros ativos.
        $numeros = $sms->Numeros($agenda);

//        Montando Json para enviar sms via CURL  
        $num_Json = $sms->MountJson();

//        Chamando metodo CURL para enviar sms-massa        
        $Api_integration = $sms->ApiSms();
        


//        Alimentando tabelas de numeros e relatorios.
      foreach ($sms->url_api as $ret) {
            
            $key = array_search($ret['numero'], $numeros);
            
                
                //Update status numeros
                $sms->updateAgendaNum($agenda, $ret['status']);
                
                //Pegando Lote do numero
                $sms->LoteSMS($agenda, $ret['numero']);
                
                
                //Inserindo dados na tabela cdr_relatorio
                $sms->cdrSmsInsert($ret['id'], $campanha, $ret['numero'], $ret['status']);
               
             
        }
    }
}


//


