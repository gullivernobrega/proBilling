<?php

require('../../../../_app/Config.inc.php');

//Data atual e final
$dt = date("Y-m-d");
//$dt = date("2019-07-12");
$dataIni = "{$dt} 00:00:01";
$datafinal = "{$dt} 23:59:59";

// Array com os parametros do Titulo da tabela
$Th = array('SMS Cus/Acc', 'Data', 'Origem', 'Resposta'); //sms_cus_id, sms_acc_id, origem, resposta, data_recebimento
$excell = new ExcellSmsRest;
$excell->ExeExcell("rest_sms", $Th, "SmsRecebido", "WHERE data_recebimento >= '{$dataIni}' AND data_recebimento <= '{$datafinal}' ORDER BY data_recebimento ASC");  
