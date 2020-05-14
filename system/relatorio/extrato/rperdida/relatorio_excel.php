<?php

require('../../../../_app/Config.inc.php');

//Data atual e final
$dt = date("Y-m-d");
$dataIni = "{$dt} 00:00:01";
$datafinal = "{$dt} 23:59:59";

// Array com os parametros do Titulo da tabela
//select para busca
//SELECT `calldate`, `src`, `disposition` FROM `cdr` WHERE `calldate` >= '2018-08-21 00:00:01' AND `calldate` <= '2018-08-21 23:59:59' AND `disposition` <> 'ANSWER' AND `dcontext` = 'entrada'
$Th = array('Data', 'Número', 'Status');
$excell = new Excellextratorperdida;
$excell->ExeExcell("cdr", $Th, "ExtratoBuscaRecebidasPerdidas", "WHERE calldate >= '{$dataIni}' AND calldate <= '{$datafinal}' AND `disposition` <> 'ANSWERED' AND `dcontext` = 'entrada'  ORDER BY calldate ASC");
