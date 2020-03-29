<?php
require('../_app/Config.inc.php');
session_start();
$dados = $_SESSION['totalStatus'];

//$dados = $_GET;
//unset($_GET);


$valor[] = array('tipo' => "Em Atendimento", 'total' => (int) $dados['tEmAtendimento']);
$valor[] = array('tipo' => "Em Pause", 'total' => (int) $dados['tPause']);
$valor[] = array('tipo' => "Discando", 'total' => (int) $dados['tDiscando']);
$valor[] = array('tipo' => "Disponivel", 'total' => (int) $dados['tDisponivel']);
//$valor[] = array('tipo' => "Deslogado", 'total' => (int) $dados['tDeslogado']);



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
