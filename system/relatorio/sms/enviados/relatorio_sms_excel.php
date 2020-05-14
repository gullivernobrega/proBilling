<?php

require('../../../../_app/Config.inc.php');

//Data atual e final
$dt = date("Y-m-d");
//$dt = date("2019-07-15");
$dataIni = "{$dt} 00:00:01";
$datafinal = "{$dt} 23:59:59";

// Array com os parametros do Titulo da tabela
$Th = array('Código', 'Data', 'Atualização', 'Lote', 'Campanha', 'Operadora' ,'Número', 'Mensagem', 'Status');
$excell = new Excellsms;
$excell->ExeExcell("cdr_sms", $Th, "SmsEnviados", "WHERE sms_date >= '{$dataIni}' AND sms_date <= '{$datafinal}' ORDER BY sms_date ASC");  
