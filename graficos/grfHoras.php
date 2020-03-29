<?php

require('../_app/Config.inc.php');
/**
 * Resultados por Horas 
 */
//Instancia da classe
$grfHora = new Grfhora;

//ATENDIDAS
$grfHora->ExeAtendidas();
$Atd = $grfHora->getResult();

//NÃO ATENDIDAS
$grfHora->ExeNaoAtendidas();
$Natd = $grfHora->getResult();

//INDISPONIVEL
$grfHora->ExeIndisponivel();
$Ind = $grfHora->getResult();

//INVALIDAS
$grfHora->ExeInvalidas();
$Inv = $grfHora->getResult();


//PARA GRAFICO
$valor[] = array('horas' => '08 às 10', 'totalNA' => $Natd['Nat8as10'], 'totalIND' => $Ind['Ind8as10'], 'totalINV' => $Inv['Inv8as10'], 'totalAT' => $Atd['at8as10']);
$valor[] = array('horas' => '11 às 13', 'totalNA' => $Natd['Nat11as13'], 'totalIND' => $Ind['Ind11as13'], 'totalINV' => $Inv['Inv11as13'], 'totalAT' => $Atd['at11as13']);
$valor[] = array('horas' => '14 às 16', 'totalNA' => $Natd['Nat14as16'], 'totalIND' => $Ind['Ind14as16'], 'totalINV' => $Inv['Inv14as16'], 'totalAT' => $Atd['at14as16']);
$valor[] = array('horas' => '17 às 19', 'totalNA' => $Natd['Nat17as19'], 'totalIND' => $Ind['Ind17as19'], 'totalINV' => $Inv['Inv17as19'], 'totalAT' => $Atd['at17as19']);
$valor[] = array('horas' => '20 às 22', 'totalNA' => $Natd['Nat20as22'], 'totalIND' => $Ind['Ind20as22'], 'totalINV' => $Inv['Inv20as22'], 'totalAT' => $Atd['at20as22']);

$rows = array();
foreach ($valor as $val):

    $horas = $val['horas'];
    $nAtedidas = $val['totalNA'];
    $indisponivel = $val['totalIND'];
    $invalidos = $val['totalINV'];
    $atendidas = $val['totalAT'];

    $temp = array();
    $temp[] = array('v' => $horas);
    $temp[] = array('v' => $nAtedidas);
    $temp[] = array('v' => $indisponivel);
    $temp[] = array('v' => $invalidos);
    $temp[] = array('v' => $atendidas);
    $rows[] = array('c' => $temp);
endforeach;

//VALORES COLS
$linhaCols[] = array("label" => "horas", "type" => "string");
$linhaCols[] = array("label" => "Não Atendidas", "type" => "number");
$linhaCols[] = array("label" => "Indisponivel", "type" => "number");
$linhaCols[] = array("label" => "Invalidas", "type" => "number");
$linhaCols[] = array("label" => "Atendidas", "type" => "number");

$cols = array($linhaCols[0], $linhaCols[1], $linhaCols[2], $linhaCols[3], $linhaCols[4]);

$tabela['cols'] = $cols;
$tabela['rows'] = $rows;

// Enviar dados na forma de JSON    
$jsonTable = json_encode($tabela);
echo $jsonTable;
