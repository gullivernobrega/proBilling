<?php

include_once '../_app/Config.inc.php';

//$dtIni = "2018-05-17 00:00:01";
//$dtFim = "2018-05-17 23:59:59";

$Campos = "COUNT(dst) AS val";

//        $select = new Select;
//        $select->ExeSelect("cdr", $Campos, "WHERE calldate >= '{$dtIni}' AND calldate <= '{$dtFim}' AND disposition = 'ANSWER'");
//        $select->ExeSelect("cdr", $Campos, "WHERE disposition = 'ANSWER'");
//        $Atendidas = $result = $select->getResult();
//        $AT = (int) $Atendidas[0]['val'];
//        $select->ExeSelect("cdr", $Campos, "WHERE calldate >= '{$dtIni}' AND calldate <= '{$dtFim}' AND (disposition = 'CANCEL' || disposition = 'NO ANSWER')");
//        $select->ExeSelect("cdr", $Campos, "WHERE (disposition = 'CANCEL' || disposition = 'NO ANSWER')");
//        $NaoAtendidas = $result = $select->getResult();
//        $NAT = (int) $NaoAtendidas[0]['val'];
//        $select->ExeSelect("cdr", $Campos, "WHERE calldate >= '{$dtIni}' AND calldate <= '{$dtFim}' AND disposition = 'FAILED'");
//        $select->ExeSelect("cdr", $Campos, "WHERE disposition = 'FAILED'");
//        $Invalidas = $result = $select->getResult();
//        $IV = (int) $Invalidas[0]['val'];
//        $select->ExeSelect("cdr", $Campos, "WHERE calldate >= '{$dtIni}' AND calldate <= '{$dtFim}' AND disposition = 'CONGESTION'");
//        $select->ExeSelect("cdr", $Campos, "WHERE disposition = 'CONGESTION'");
//        $Indisponivel = $result = $select->getResult();
//        $IND = (int) $Indisponivel[0]['val'];

//SIMULA BANCO DE DADOS
$valor[] = array('tipo' => 'Não Atendidas', 'total' => 4512);
$valor[] = array('tipo' => 'Indisponível', 'total' => 2012);
$valor[] = array('tipo' => 'Inválidas', 'total' => 1000);
$valor[] = array('tipo' => 'Atendidas', 'total' => 8554);

$rows = array();
foreach ($valor as $val): 
    
    $tipo = $val['tipo'];
    $total = $val['total'];

    $temp = array();
    $temp[] = array('v' => $tipo);
    $temp[] = array('v' => $total);
    $rows[] = array('c' => $temp);    
endforeach;

$linhaCols[] = array("label" => "tipo", "type" => "string");
$linhaCols[] = array("label" => "total", "type" => "number");
$cols = array($linhaCols[0], $linhaCols[1]);

$tabela['cols'] = $cols;
$tabela['rows'] = $rows;

// Enviar dados na forma de JSON    
$jsonTable = json_encode($tabela);
echo $jsonTable;
