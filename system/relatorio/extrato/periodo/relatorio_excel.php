<?php

require('../../../../_app/Config.inc.php');

//Data atual e final
$dt = date("Y-m-d");
$dataIni = "{$dt} 00:00:01";
$datafinal = "{$dt} 23:59:59";

// Array com os parametros do Titulo da tabela
$Th = array('Data', 'Origem', 'Destino', 'Tipo', 'Tronco', 'Duração', 'Status');
$excell = new Excellextrato;
$excell->ExeExcell("cdr", $Th, "ExtratoPorPeríodo", "WHERE calldate >= '{$dataIni}' AND calldate <= '{$datafinal}' AND tipo <> '' ORDER BY calldate ASC");
