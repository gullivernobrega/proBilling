<?php
require('../_app/Config.inc.php');

$grafico = new Callcenter;
$valor = $grafico->getGrfStatusAgents();

$rows = array();
foreach ($valor as $val):
    
    $tipo = $val['tipo'];
    $total = $val['total'];
      
    $temp = array();
    $temp[] = array('v' => $tipo);
    $temp[] = array('v' => $total);
    $rows[] = array('c' => $temp);  
    
endforeach;

//VALORES COLS
$linhaCols[] = array("label" => "tipo", "type" => "string");
$linhaCols[] = array("label" => "total", "type" => "number");
$cols = array($linhaCols[0], $linhaCols[1]);

$tabela['cols'] = $cols;
$tabela['rows'] = $rows;

// Enviar dados na forma de JSON    
$jsonTable = json_encode($tabela);
echo $jsonTable;
