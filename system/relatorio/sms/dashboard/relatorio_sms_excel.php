<?php

require('../../../../_app/Config.inc.php');

//Data atual e final
$dt = date("Y-m-d");
//$dt = date("2019-07-15");
$dataIni = "{$dt} 00:00:01";
$datafinal = "{$dt} 23:59:59";

// Array com os parametros do Titulo da tabela
$Th = array("Lote", "Campanha", "Data", "Inserido", "Não Entregavel", "Enviado", "Entregue", "Não Entregue", "Total");
$excell = new ExcellDashboardSms;
$excell->ExeExcell("cdr_sms", $Th, "RelatórioGeralSms", "WHERE sms_date >= '{$dataIni}' AND sms_date <= '{$datafinal}'");  
