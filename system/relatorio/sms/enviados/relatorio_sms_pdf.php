<?php
set_time_limit(300);

//ini_set('memory_limit', '1024M');
ini_set('memory_limit', '-1');
date_default_timezone_set('America/Sao_Paulo');

require('../../../../_app/Config.inc.php');
include '../../../../_app/Library/MPDF60/mpdf.php';

//Data atual e final
$dt = date("Y-m-d");
//$dt = date("2019-07-15");
$dataIni = "{$dt} 00:00:01";
$datafinal = "{$dt} 23:59:59";

// Array com os parametros do Titulo da tabela
$Th = array('Código', 'Data', 'Atualização', 'Lote', 'Campanha', 'Operadora', 'Número', 'Status', 'Mensagem');

$read = new Read;
$read->ExeRead("cdr_sms", "WHERE sms_date >= '{$dataIni}' AND sms_date <= '{$datafinal}' ORDER BY sms_date ASC");
$dados = $read->getResult();

$data = date("d/m/Y H:i:s");

//Define o valor do SMS
define("VALORSMS", 0.032);


$saidaPdf = "";

if ($read->getRowCount() > 0):

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

    $saidaPdf .= "        
<html >
    <head>        
        <title></title>
    </head>
    <body>        
        <h2>RELATÓRIO DE SMS ENVIADOS <small>{$data}</small></h2>
        <table border=1 width='100%' class='table table-bordered ttabless'>
            <tr class='trc'>                
                <th>Código</th>                                                        
                <th>Data</th>                                             
                <th>Atualização</th>                                             
                <th>Lote</th>                                             
                <th>Campanha</th>                                             
                <th>Operadora</th>                                                             
                <th>Número</th>
                <th>Mensagem</th>
                <th>Status</th>                 
            </tr>
            ";

    $session_time = '';

    if (!empty($dados)):
        
        foreach ($dados as $row):

            $sms_codigo = $row['sms_id'];
            $sms_date = $row['sms_date'];
            $sms_date_atualizacao = $row['sms_date_atualizacao'];
            $sms_lote = $row['sms_lote'];
            $sms_campanha = $row['sms_campanha'];
            $sms_operadora = $row['sms_operadora'];
            $sms_numero = $row['sms_numero'];
            $sms_msg = $row['sms_msg'];
            
            // Status de SMS
            if ($row['sms_status'] == 'ACCEPTED'):
                $status = "Inserido";
                $inserido = $inserido + 1;
            elseif ($row['sms_status'] == 'PAYREQUIRED'):
                $status = "Sem Saldo";
                $semSaldo = $semSaldo + 1;
            elseif ($row['sms_status'] == 'SENT'):
                $status = "Enviado";
                $enviado = $enviado + 1;
            elseif ($row['sms_status'] == 'DELIVERED'):
                $status = "Entregue";
                $entregue = $entregue + 1;
            elseif ($row['sms_status'] == 'FAILED'):
                $status = "Falha";
                $falha = $falha + 1;
            elseif ($row['sms_status'] == 'UNKNOWN'):
                $status = "Não Entregue";
                $naoEntregue = $naoEntregue + 1;
            elseif ($row['sms_status'] == 'EXPIRED'):
                $status = "Expirado";
                $expirado = $expirado + 1;
            elseif ($row['sms_status'] == 'DELETED'):
                $status = "Deletado";
                $deletado = $deletado + 1;
            elseif ($row['sms_status'] == 'REJECTED'):
                $status = "Rejeitado";
                $rejeitado = $rejeitado + 1;
            elseif ($row['sms_status'] == 'UNDELIVERABLE'):
                $status = "Não Entregável";
                $naoEntregavel = $naoEntregavel + 1;
            else:
                $status = "INVALIDO";
            endif;

            $saidaPdf .= "<tr class='trc1'>
        <td>{$sms_codigo}</td>
        <td>{$sms_date}</td>        
        <td>{$sms_date_atualizacao}</td>        
        <td>{$sms_lote}</td>        
        <td>{$sms_campanha}</td>        
        <td>{$sms_operadora}</td>        
        <td>{$sms_numero}</td>        
        <td>{$sms_msg}</td>        
        <td>{$status}</td>
        </tr>";
        endforeach;   

    else:
        KLErro('Não Foi possivel gerar o relatorio PDF!', KL_INFOR);
    endif;

    //Valor total de SMS
    $valor = number_format(($enviado + $entregue + $naoEntregavel) * VALORSMS, 3, ",", ".");
    $falhado = $semSaldo + $falha + $naoEntregue + $expirado + $deletado + $rejeitado;

    $saidaPdf .= "                
        </table>
        
            <h3>RESUMO DE SMS</h3>
            <table border='1'>            
            <tr>
            <th>DESCRIÇÃO</th>
            <th>TOTAL</th>
            </tr>            
            <tr>
            <td>Total SMS</td>
            <td>{$read->getRowCount()}</td>
            </tr>            
            <tr>
            <td>Entregue</td>
            <td>{$entregue}</td>
            </tr>            
            <tr>
            <td>Eviado</td>
            <td>{$enviado}</td>
            </tr>;            
            <tr>;
            <td>Inserido</td>
            <td>{$inserido}</td>
            </tr>  
            <tr>
            <td>Não Entregável</td>
            <td>{$naoEntregavel}</td>
            </tr>            
            <tr>
            <td>Falhado</td>
            <td>{$falhado}</td>
            </tr>
            <tr>
            <td>Valor</td>
            <td>R$ {$valor}</td>
            </tr>
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
