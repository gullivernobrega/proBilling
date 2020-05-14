<?php
require('../_app/Config.inc.php');
//Prepara a data atual inicio e fim
$data = date("Y-m-d");
//$data = date("2018-06-06");
$dtIni = $data . " 00:00:01";
$dtFim = $data . " 23:59:59";

//campos para busca
$Campos = "COUNT(dst) AS val";

/**
 * Consulta de ligações Atendias
 */
$select = new Select;
$select->ExeSelect("cdr", $Campos, "WHERE calldate >= '{$dtIni}' AND calldate <= '{$dtFim}' AND disposition = 'ANSWERED'");
$Atendidas = $result = $select->getResult();
$AT = (int) $Atendidas[0]['val'];

/**
 * Consulta de ligações Não Atendias
 */
$select->ExeSelect("cdr", $Campos, "WHERE calldate >= '{$dtIni}' AND calldate <= '{$dtFim}' AND (disposition = 'CANCEL' || disposition = 'NO ANSWER')");
$NaoAtendidas = $result = $select->getResult();
$NAT = (int) $NaoAtendidas[0]['val'];

/**
 * Consulta de ligações Invalidas
 */
$select->ExeSelect("cdr", $Campos, "WHERE calldate >= '{$dtIni}' AND calldate <= '{$dtFim}' AND disposition = 'FAILED'");
$Invalidas = $result = $select->getResult();
$INV = (int) $Invalidas[0]['val'];

/**
 * Consulta de ligações Indisponivel
 */
$select->ExeSelect("cdr", $Campos, "WHERE calldate >= '{$dtIni}' AND calldate <= '{$dtFim}' AND disposition = 'CONGESTION'");
$Indisponivel = $result = $select->getResult();
$IND = (int) $Indisponivel[0]['val'];

//PARA GRAFICO
$valor[] = array('tipo' => 'Não Atendidas', 'total' => $NAT);
$valor[] = array('tipo' => 'Indisponível', 'total' => $IND);
$valor[] = array('tipo' => 'Inválidas', 'total' => $INV);
$valor[] = array('tipo' => 'Atendidas', 'total' => $AT);

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
