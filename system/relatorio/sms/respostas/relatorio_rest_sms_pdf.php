<?php

date_default_timezone_set('America/Sao_Paulo');

require('../../../../_app/Config.inc.php');
include '../../../../_app/Library/MPDF60/mpdf.php';

//Data atual e final
$dt = date("Y-m-d");
//$dt = date("2019-07-12");
$dataIni = "{$dt} 00:00:01";
$datafinal = "{$dt} 23:59:59";

// Array com os parametros do Titulo da tabela
$Th = array('SMS Cus/Acc', 'Data', 'Origem', 'Resposta');

$read = new Read;
$read->ExeRead("rest_sms", "WHERE data_recebimento >= '{$dataIni}' AND data_recebimento <= '{$datafinal}' ORDER BY data_recebimento ASC");
$dados = $read->getResult();

$data = date("d/m/Y H:i:s");
?>

<?php

$saidaPdf = "";

if ($read->getRowCount() > 0):

    $saidaPdf .= "
        
<html >
    <head>        
        <title></title>
    </head>
    <body>        
        <h2>RELATÓRIO SMS RESPOSTA/S <small>{$data}</small></h2>
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

    if (!empty($dados)):
        foreach ($dados as $row):

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
$arquivoPdf = "smsEnviados.pdf";

$mpdf = new mPDF;
$mpdf->SetDisplayMode("fullpage");
$mpdf->WriteHTML($saidaPdf);
/**
 * F - Salva arquivo no diretorio
 * I - Abre no navegador
 * D - Chama no prompt
 */
$mpdf->Output($arquivoPdf, "I");
?>
