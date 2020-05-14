<?php

//set_time_limit(300);
//ini_set('memory_limit', '-1');

date_default_timezone_set('America/Sao_Paulo');

require('../../../../_app/Config.inc.php');
include '../../../../_app/Library/MPDF60/mpdf.php';

//Data atual e final
$dt = date("Y-m-d");
//$dt = date("2019-07-15");
$dataIni = "{$dt} 00:00:01";
$datafinal = "{$dt} 23:59:59";

//Define o valor do SMS
define("VALORSMS", 0.032);

$data = date("d/m/Y H:i:s");

// Array com os parametros do Titulo da tabela
$Th = array("Lote", "Campanha", "Data", "Inserido", "Não Entregavel", "Enviado", "Entregue", "Não Entregue", "Total");


//Inicialização das variaveis
$inserido = 0;
$enviado = 0;
$entregue = 0;
$falha = 0;
$semSaldo = 0;
$naoEntregue = 0;
$expirado = 0;
$deletado = 0;
$rejeitado = 0;
$naoEntregavel = 0;

//$Data = "";
// Listagem dos Lotes e os itens +++++++++++++++++++++++++++++++++++++//
$campoDistinto = "DISTINCT sms_lote";

//Select do campo distinto
$objLote = new Select;
$objLote->ExeSelect("cdr_sms", $campoDistinto, "WHERE sms_date >= '{$dataIni}' AND sms_date <= '{$datafinal}' ORDER BY sms_date ASC ");
$totalLote = $objLote->getRowCount();
$resultLote = $objLote->getResult();

if ($totalLote > 0):
    foreach ($resultLote as $lt):

        $camposs = "sms_date, sms_lote, sms_campanha, sms_status, count(*) as total";
        $objByLote = new Select;
        $objByLote->ExeSelect("cdr_sms", $camposs, "WHERE sms_date >= '{$dataIni}' AND sms_date <= '{$datafinal}' AND sms_lote = '{$lt['sms_lote']}' group by sms_status ORDER BY sms_date ASC ");
        $totais = $objByLote->getRowCount();
        $sms[] = $objByLote->getResult();

    endforeach;
endif;

//Loop com a montagem dos dados
if(!empty($sms)):
foreach ($sms as $dados) {
    $lote = NULL;
    foreach ($dados as $dado) {

        if ($dado['sms_lote'] != $lote) {

            $arrayDados[$dado['sms_lote']] = array('sms_date' => $dado['sms_date'], 'sms_lote' => $dado['sms_lote'], 'sms_campanha' => $dado['sms_campanha'], 'ACCEPTED' => '0', 'UNDELIVERABLE' => '0', 'SENT' => '0', 'DELIVERED' => '0', 'UNKNOWN' => '0');
            $arrayDados[$dado['sms_lote']][$dado['sms_status']] = $dado['total'];
            $lote = $dado['sms_lote'];
        } elseif ($dado['sms_lote'] == $lote) {

            $arrayDados[$dado['sms_lote']][$dado['sms_status']] = $dado['total'];
        }
    }
}
endif;


//$read = new Read;
//$read->ExeRead("cdr_sms", "WHERE sms_date >= '{$dataIni}' AND sms_date <= '{$datafinal}' ORDER BY sms_date ASC");
//$dados = $read->getResult();



$saidaPdf = "";

if ($totalLote > 0):

    $saidaPdf .= "        
<html >
    <head>        
        <title></title>
    </head>
    <body>        
        <h2>RELATÓRIO GERAL DE SMS <small>{$data}</small></h2>
        <table border=1 width='100%' class='table table-bordered ttabless'>
            <tr class='trc'>                
                <th>Lote</th>                                             
                <th>Campanha</th> 
                <th>Data</th>                                             
                <th>Inserido</th>
                <th>Não Entregavel</th>
                <th>Enviado</th>
                <th>Entregue</th>
                <th>Não Entregue</th>
                <th>Total</th>                  
            </tr>
            ";

    $session_time = '';

    if (!empty($arrayDados)):

        foreach ($arrayDados as $key => $value):

            $sms_lote = (!empty($key)) ? $key : "0";
            $sms_campanha = (!empty($value['sms_campanha'])) ? $value['sms_campanha'] : "0";
            $sms_date = (!empty($value['sms_date'])) ? $value['sms_date'] : "0";
            $Inserido = (!empty($value['ACCEPTED'])) ? $value['ACCEPTED'] : "0";
            $NaoEntregavel = (!empty($value['UNDELIVERABLE'])) ? $value['UNDELIVERABLE'] : "0";
            $Enviado = (!empty($value['SENT'])) ? $value['SENT'] : "0";
            $Entregue = (!empty($value['DELIVERED'])) ? $value['DELIVERED'] : "0";
            $NaoEntregue = (!empty($value['UNKNOWN'])) ? $value['UNKNOWN'] : "0";
            $Total = $Inserido + $NaoEntregavel + $Enviado + $Entregue + $NaoEntregue;


            $saidaPdf .= "<tr class='trc1'>
        <td>{$sms_lote}</td>
        <td>{$sms_campanha}</td>        
        <td>{$sms_date}</td>        
        <td>{$Inserido}</td>        
        <td>{$NaoEntregavel}</td>        
        <td>{$Enviado}</td>        
        <td>{$Entregue}</td>        
        <td>{$NaoEntregue}</td>        
        <td>{$Total}</td>
        </tr>";

        endforeach;


    else:
        KLErro('Não Foi possivel gerar o relatorio PDF!', KL_INFOR);
    endif;

    $saidaPdf .= "                
        </table>
        </body>
        </html>
        ";


else:
    $saidaPdf .= "Não Foi possivel gerar o relatorio PDF!";
endif;

//$saidaPdf = utf8_decode($saidaPdf);
//$dompdf = new Dompdf;
//$dompdf->load_html
// Nome do arquivo pdf
$arquivoPdf = "smsEnviados.pdf";

$mpdf = new mPDF('utf-8', 'A4-L');
$mpdf->SetDisplayMode("fullpage");
$mpdf->WriteHTML($saidaPdf);
/**
 * F - Salva arquivo no diretorio
 * I - Abre no navegador
 * D - Chama no prompt
 */
$mpdf->Output($arquivoPdf, "I");
