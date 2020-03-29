<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;

//Define o valor do SMS
define("VALORSMS", 0.032);
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Dashboard SMS <small>Listagem</small></h1>
    </div>       

    <div class="container-fluid">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> Painel de ações </h3>
            </div>
            <div class="panel-body txtblue">
                <div id="shieldui-grid1">                    
                    <?php
                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                    $pager = new Pager("?exe=relatorio/sms/dashboard/lista&pg=");
                    $pager->ExePager($getPage, 10);

                    //LEITURA DOS DADOS  
                    $dt = date("Y-m-d");
                    //$dt = date("2019-07-15");
                    $dataIni = "{$dt} 00:00:01";
                    $datafinal = "{$dt} 23:59:59";

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

                    $objLote = new Select;
                    $objLote->ExeSelect("cdr_sms", $campoDistinto, "WHERE sms_date >= '{$dataIni}' AND sms_date <= '{$datafinal}' ORDER BY sms_date ASC ");
                    $totalLote = $objLote->getRowCount();
                    $resultLote = $objLote->getResult();

                    if ($totalLote > 0):
                        foreach ($resultLote as $lt):

                            $camposs = "sms_date, sms_lote, sms_campanha, sms_status, count(*) as total";
                            $objByLote = new Select;
                            $objByLote->ExeSelect("cdr_sms", $camposs, "WHERE sms_date >= '{$dataIni}' AND sms_date <= '{$datafinal}' AND sms_lote = '{$lt['sms_lote']}' group by sms_status ORDER BY sms_date ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                            $totais = $objByLote->getRowCount();
                            $sms[] = $objByLote->getResult();

                        endforeach;
                    endif;

                    if (!empty($sms)):
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

                    // RESULTADO DA PESQUISA **************************** //
                    $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($busca['btnBusca'])):
                        unset($busca['btnBusca']);

                        if (!empty($busca['dataInicio']) && !empty($busca['dataFim'])):

                            $dateDi = new DateTime($busca['dataInicio']);
                            $di = date_format($dateDi, 'Y-m-d H:m:s');

                            $dateDf = new DateTime($busca['dataFim']);
                            $df = date_format($dateDf, 'Y-m-d H:m:s');

                            header("Location: ?exe=relatorio/sms/dashboard/busca&di={$di}&df={$df}");
                            exit();

                        else:

                            KLErro("Ops, Falta parametros para a pesquisa", KL_INFOR);

                        endif;

                    endif;
                    ?>
                    <!--BASE PESQUISA-->
                    <div class="well seach">
                        <h3>Busca</h3>    
                        <form class="form-inline" action=""  method="post" name="frmPesquisa" id="frmPesquisa" >

                            <div class="form-group form-group-sm">                                
                                <label for="dataInicio">Data inicio </label>
                                <input 
                                    class="form-control" 
                                    name="dataInicio" 
                                    id="datetimeIni" 
                                    type="text" 
                                    placeholder="data inicio" 
                                    value="<?php
                                    if (isset($busca['dataInicio'])): echo $busca['dataInicio'];
                                    endif;
                                    ?>" 
                                    required
                                    > 

                                <label>Data final </label>
                                <input 
                                    class="form-control" 
                                    name="dataFim" 
                                    id="datetimeFim" 
                                    type="text" 
                                    placeholder="data final" 
                                    value="<?php
                                    if (isset($busca['dataFim'])): echo $busca['dataFim'];
                                    endif;
                                    ?>" 
                                    required
                                    >                                 
                            </div>


                            <!--*******-->
                            <!--                            <div class="form-group form-group-sm">                                
                                                            <input 
                                                                class="form-control" 
                                                                name="sms_campanha" 
                                                                id="sms_campanha" 
                                                                type="text" 
                                                                placeholder="Nome da campanha" 
                                                                value="<?php
                            //if (isset($busca['sms_campanha'])): echo $busca['sms_campanha']; endif;
                            ?>" >                                
                                                        </div>                   -->

                            <!--*******-->
                            <!--                            <div class="form-group form-group-sm">                                
                                                            <input 
                                                                class="form-control" 
                                                                name="sms_lote" 
                                                                id="sms_lote" 
                                                                type="text" 
                                                                placeholder="Nome do Lote" 
                                                                value="<?php
                            //if (isset($busca['sms_lote'])): echo $busca['sms_lote']; endif;
                            ?>"
                                                                >                                
                                                        </div>  -->

                            <!--</button>-->
                            <button name="btnBusca" value="Buscar" type="submit" class="btn btn-info btn-sm" title="Buscar" data-toggle="tooltip" data-placement="top"><i class="fa fa-search"></i> Buscar</button>                         

                        </form>
                    </div>

                    <!--well botão-->
                    <div class="well text-right">                                                  
                        <a class="pull-left" href="system/relatorio/sms/dashboard/relatorio_sms_excel.php" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>                                
                        <a class="pull-left" href="system/relatorio/sms/dashboard/relatorio_sms_pdf.php" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>                        
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
                                
                                ?>
                                <tr>
                                    <td scope="row"><?php echo $key; ?></td> 
                                    <td scope="row"><?php echo $value['sms_campanha']; ?></td>
                                    <td scope="row"><?php echo $value['sms_date']; ?></td>
                                    <td scope="row"><?php echo $value['ACCEPTED']; ?></td>
                                    <td scope="row"><?php echo $value['UNDELIVERABLE']; ?></td>
                                    <td scope="row"><?php echo $value['SENT']; ?></td>
                                    <td scope="row"><?php echo $value['DELIVERED']; ?></td>
                                    <td scope="row"><?php echo $value['UNKNOWN']; ?></td>
                                    <td scope="row"><?php echo $value['ACCEPTED'] + $value['UNDELIVERABLE'] + $value['SENT'] + $value['DELIVERED'] + $value['UNKNOWN']; ?></td>
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
                    $pager->ExePaginator("cdr_sms", $campoDistinto, "WHERE sms_date >= '{$dataIni}' AND sms_date <= '{$datafinal}' ORDER BY sms_date ASC");
                    echo $pager->getPaginator();
                    ?>
                </div>
