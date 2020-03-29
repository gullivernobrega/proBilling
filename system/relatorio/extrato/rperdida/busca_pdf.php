<?php

date_default_timezone_set('America/Sao_Paulo');

require('../../../../_app/Config.inc.php');
include '../../../../_app/Library/MPDF60/mpdf.php';

// Array com os parametros do Titulo da tabela
//$Th = array('Data', 'Origem', 'Destino', 'Tipo', 'Duração', 'Status');

$data = filter_input_array(INPUT_GET, FILTER_DEFAULT);
$read = new Read;

if (!empty($data)):

    //Duas datas
    if (!empty($data['di']) && !empty($data['df']) ):
        $read->ExeRead("cdr", "WHERE  calldate >= '{$data['di']}' AND calldate <= '{$data['df']}' AND `disposition` <> 'ANSWERED' AND `dcontext` = 'entrada' ORDER BY calldate ASC");
    else:
    endif;
    
endif;

//$read->ExeRead("cdr", "WHERE calldate = {$dataAtual} AND tipo <> '' ORDER BY calldate ASC");
//$dados = $read->getResult();

$data = date("d/m/Y H:i:s");

$saidaPdf = "";

if ($read->getRowCount() > 0):

    $saidaPdf .= "
        
<html >
    <head>        
        <title></title>
    </head>
    <body>        
        <h2>RELATÓRIO DE RECEBIDAS PERDIDAS <small>{$data}</small></h2>
        <table border=1 width='100%' class='table table-bordered ttabless'>
            <tr class='trc'>
                <th>Data</th>
                <th>Número</th>                
                <th>Status</th>     
            </tr>
            ";


    $session_time = '';

    if (!empty($read->getResult())):
        foreach ($read->getResult() as $row):
            //extract($row);

            $calldate = $row['calldate'];
            $src = $row['src'];
            $dst = $row['dst'];
            $tipo = $row['tipo'];
            $billsec = gmdate("H:i:s", $row['billsec']);
            $status = ($row['disposition'] == 'ANSWERED' ? "Atendida" : ($row['disposition'] == 'CANCEL' ? "Cancelada" : ($row['disposition'] == 'BUSY' ? "Ocupado" : ($row['disposition'] == 'NO ANSWER' ? "Não atendida" : ($row['disposition'] == 'FAILED' ? "Falha" : ($row['disposition'] == 'CHANUNAVAIL' ? "Indisponível" : null))))));

            $saidaPdf .= "<tr class='trc1'>
        <td>{$calldate}</td>
        <td>{$src}</td>        
        <td>{$status}</td>
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
$arquivoPdf = "ExtratoRecebidasPerdidas.pdf";

$mpdf = new mPDF;
$mpdf->SetDisplayMode("fullpage");
$mpdf->WriteHTML($saidaPdf);
/**
 * F - Salva arquivo no diretorio
 * I - Abre no navegador
 * D - Chama no prompt
 */
$mpdf->Output($arquivoPdf, "I");
