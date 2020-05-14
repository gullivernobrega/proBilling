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
    if (!empty($data['di']) && !empty($data['df']) && empty($data['numero']) && empty($data['status'])):
        $read->ExeRead("rest_sms", "WHERE  data_recebimento >= '{$data['di']}' AND data_recebimento <= '{$data['df']}' ORDER BY data_recebimento ASC");

    //Duas datas e o sms_numero
    elseif (!empty($data['di']) && !empty($data['df']) && !empty($data['numero'])):
        $read->ExeRead("rest_sms", "WHERE  data_recebimento >= '{$data['di']}' AND data_recebimento <= '{$data['df']}' AND origem = {$data['numero']} ORDER BY data_recebimento ASC");

    else:
    endif;
    
endif;

//$read->ExeRead("rest_sms", "WHERE data_recebimento = {$dataAtual} AND sms_status <> '' ORDER BY data_recebimento ASC");
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
        <h2>RELATÓRIO DE LIGAÇÔES POR PERÍODO <small>{$data}</small></h2>
        <table border=1 width='100%' class='table table-bordered ttabless'>
            <tr class='trc'>
                <th>Cus Id</th>                                                        
                <th>Acc Id</th>                                                        
                <th>Data</th>                                                                                                                         
                <th>Origem</th>
                <th>Resposta/th>     
            </tr>
            ";

    $session_time = '';

    if (!empty($read->getResult())):
        foreach ($read->getResult() as $row):
            //extract($row);            
             $sms_cus_id = $row['sms_cus_id'];
            $sms_acc_id = $row['sms_acc_id'];
            $date = $row['data_recebimento'];
            $origem = $row['origem'];
            $resposta = $row['resposta'];
            //$sms_status = ($row['sms_status'] == 'ACCEPTED' ? "Enviado" : ($row['sms_status'] == 'UNKNOWN' ? "Pendente" : ($row['sms_status'] == 'PAYREQUIRED' ? "Bloqueado" : "INVALIDO")));

            $saidaPdf .= "<tr class='trc1'>
        <td>{$sms_cus_id}</td>
        <td>{$sms_acc_id}</td>
        <td>{$date}</td> 
        <td>{$origem}</td>        
        <td>{$resposta}</td>
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
$arquivoPdf = "SmsBuscaEnviados.pdf";

$mpdf = new mPDF;
$mpdf->SetDisplayMode("fullpage");
$mpdf->WriteHTML($saidaPdf);
/**
 * F - Salva arquivo no diretorio
 * I - Abre no navegador
 * D - Chama no prompt
 */
$mpdf->Output($arquivoPdf, "I");
