<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;

define("VALORSMS", 0.032);
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Dashboard SMS Busca <small>Listagem</small></h1>
    </div>       
    <!--</div>-->

    <!--<div class="row">-->
    <div class="container-fluid">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> Painel de ações </h3>
            </div>
            <div class="panel-body txtblue">
                <div id="shieldui-grid1">
                    <!--mensagem de erro-->
                    <?php
                    $busca = filter_input_array(INPUT_GET, FILTER_DEFAULT);

                    if (!empty($busca['exe'])):
                        unset($busca['exe']);

                        //Verifica os parametros da pesquisa;
//                        if(empty($busca['sms_numero'])): unset($busca['sms_numero']); endif;
//                        if(empty($busca['sms_campanha'])): unset($busca['sms_campanha']); endif;
//                        if(empty($busca['sms_operadora'])): unset($busca['sms_operadora']); endif;
//                        if(empty($busca['sms_status'])): unset($busca['sms_status']); endif;
//                        if(empty($busca['sms_lote'])): unset($busca['sms_lote']); endif;                    
                        //Inicializa as variaveis
                        $data = "";
                        $dataRead = "";

                        //loop para preparar itens da pesquisa
                        foreach ($busca as $k => $v):

                            //motagem do data link e search
                            $data .= "{$k}={$v}&";

                            //verifico se a key é do fone 
                            if ($k == "di"):
                                $dataRead .= "sms_date >= '{$v}' AND ";
                            elseif ($k == "df"):
                                $dataRead .= "sms_date <= '{$v}' AND ";
                            else:
                                $dataRead .= "$k = '$v' AND ";
                            endif;

                        endforeach;

                        //retira o ultimo "&" da linha de pesquisa
                        $data = substr($data, 0, -1);
                        $dataRead = substr($dataRead, 0, -4);

                        // Monta o link e a search
                        $link = "?exe=relatorio/sms/daschboard/busca&{$data}&pg=";
                        $search = "?{$data}";

                    endif;

                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                    $pager = new Pager($link);
                    $pager->ExePager($getPage, 20);

                    //$campos = "sms_id, sms_date, sms_date_atualizacao, sms_campanha, sms_operadora, sms_numero, sms_msg, sms_status, sms_lote";
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

                    // Listagem dos Lotes e os itens +++++++++++++++++++++++++++++++++++++//
                    $campoDistinto = "DISTINCT sms_lote";

                    $objLote = new Select;
                    $objLote->ExeSelect("cdr_sms", $campoDistinto, "WHERE {$dataRead} ORDER BY sms_date ASC ");
                    $totalLote = $objLote->getRowCount();
                    $resultLote = $objLote->getResult();

                    if ($totalLote > 0):
                        foreach ($resultLote as $lt):

                            $camposs = "sms_date, sms_lote, sms_campanha, sms_status, count(*) as total";
                            $objByLote = new Select;
                            $objByLote->ExeSelect("cdr_sms", $camposs, "WHERE {$dataRead} AND sms_lote = '{$lt['sms_lote']}' group by sms_status ORDER BY sms_date ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                            $totais = $objByLote->getRowCount();
                            $sms[] = $objByLote->getResult();

                        endforeach;
                    endif;
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
                    ?>
                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Nova Busca</h3>    
                        <a class="btn btn-warning nb" href="?exe=relatorio/sms/dashboard/lista" title="Nova Busca" data-toggle="tooltip" data-placement="top">Realizar uma Nova Busca</a>
                    </div>

                    <!--well botão-->
                    <div class="well text-right">                                                  
                        <a class="pull-left" href="system/relatorio/sms/dashboard/busca_sms_excel.php<?php echo $search; ?>" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>                                
                        <a class="pull-left" href="system/relatorio/sms/dashboard/busca_sms_pdf.php<?php echo $search; ?>" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>
                        <!--<a class="btn btn-success" href="painel.php?exe=gerenciamento/ramal/iax/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Ramal IAX</a>-->
                        <a class="voltar" href="painel.php" role="button" title="Voltar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
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
                        </thead> 
                        <tbody> 
                            <?php
                            if(!empty($arrayDados)):
                            foreach ($arrayDados as $key => $value) :
                                $sms_lote = (!empty($key)) ? $key : "0";
                                $sms_campanha = (!empty($value['sms_campanha'])) ? $value['sms_campanha'] : "0";
                                $sms_date = (!empty($value['sms_date'])) ? $value['sms_date'] : "0";
                                $inserido = (!empty($value['ACCEPTED'])) ? $value['ACCEPTED'] : "0";
                                $naoEntregavel = (!empty($value['UNDELIVERABLE'])) ? $value['UNDELIVERABLE'] : "0";
                                $enviado = (!empty($value['SENT'])) ? $value['SENT'] : "0";
                                $entregue = (!empty($value['DELIVERED'])) ? $value['DELIVERED'] : "0";
                                $naoEntregue = (!empty($value['UNKNOWN'])) ? $value['UNKNOWN'] : "0";
                                $total = $inserido + $naoEntregavel + $enviado + $entregue + $naoEntregue;
                                
                                ?>
                                <tr>
                                    <td scope="row"><?php echo $sms_lote; ?></td> 
                                    <td scope="row"><?php echo $sms_campanha; ?></td>
                                    <td scope="row"><?php echo $sms_date; ?></td>
                                    <td scope="row"><?php echo $inserido; ?></td>
                                    <td scope="row"><?php echo $naoEntregavel; ?></td>
                                    <td scope="row"><?php echo $enviado; ?></td>
                                    <td scope="row"><?php echo $entregue; ?></td>
                                    <td scope="row"><?php echo $naoEntregue;?></td>
                                    <td scope="row"><?php echo $total; ?></td>
                                </tr>   
                                <?php
                            endforeach;
                            else:
                                KLErro("Não existe listagem de Sms momento!", KL_ALERT);
                            endif;
                            ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                </div>

                <!--PAGINAÇÃO-->
                <div class="well corWell text-center">
                    <?php
                    $pager->ExePaginator("cdr_sms", $campoDistinto, "WHERE {$dataRead} ORDER BY sms_date ASC");
                    echo $pager->getPaginator();
                    ?>
                </div>

