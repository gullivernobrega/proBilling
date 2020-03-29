<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Busca Extrato por Período <small>Listagem</small></h1>
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

                        //Duas datas
                        if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && empty($busca['tronco']) && empty($busca['disposition'])):
                            $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&pg=";
                            $search = "?di={$busca['di']}&df={$busca['df']}";
                        //Duas datas e o numero    
                        elseif (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                            $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&pg=";
                            $search = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}";
                        //Duas datas e o numero e o tipo            
                        elseif (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                            $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&tipo={$busca['tipo']}&pg=";
                            $search = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&tipo={$busca['tipo']}";
                        //Duas datas o numero e o disposition      
                        elseif (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                            $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&disposition={$busca['disposition']}&pg=";
                            $search = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&disposition={$busca['disposition']}";
                        //Duas datas e todos    
                        elseif (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                            $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}&pg=";
                            $search = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}";
                        //Duas datas e o Tronco    
                        elseif (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && !empty($busca['tronco']) && empty($busca['tipo']) && empty($busca['disposition'])):
                            $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&tronco={$busca['tronco']}&pg=";
                            $search = "?di={$busca['di']}&df={$busca['df']}&tronco={$busca['tronco']} ";
                         //Duas datas o Tronco e o disposition    
                        elseif (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && !empty($busca['tronco']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                            $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&tronco={$busca['tronco']}&disposition={$busca['disposition']}&pg=";
                            $search = "?di={$busca['di']}&df={$busca['df']}&tronco={$busca['tronco']}&disposition={$busca['disposition']} ";    
                        //Duas datas e o tipo    
                        elseif (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                            $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&tipo={$busca['tipo']}&pg=";
                            $search = "?di={$busca['di']}&df={$busca['df']}&tipo={$busca['tipo']} ";
                        //Duas datas o tipo e o disposition    
                        elseif (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                            $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}&pg=";
                            $search = "?di={$busca['di']}&df={$busca['df']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}";
                        //Duas datas e o disposition    
                        elseif (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                            $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&disposition={$busca['disposition']}&pg=";
                            $search = "?di={$busca['di']}&df={$busca['df']}&disposition={$busca['disposition']}";
                        else:
                        endif;

                    endif;

                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                    $pager = new Pager($link);
                    $pager->ExePager($getPage, 20);

                    $campos = "calldate, src, dst, tipo, tronco, billsec, disposition, userfield";

                    //LEITURA DOS DADOS
                    $read = new Select;
                    // Duas datas 
                    if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && empty($busca['tronco']) && empty($busca['disposition'])):
                        $read->ExeSelect("cdr", $campos, "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo <> '' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo <> '' ";
                    endif;

                    //Todos os Campos
                    if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && empty($busca['tronco']) && !empty($busca['disposition'])):
                        $read->ExeSelect("cdr", $campos, "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}'";
                    endif;

                    //As duas datas e o numero 
                    if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && empty($busca['tronco']) && empty($busca['disposition'])):
                        $read->ExeSelect("cdr", $campos, "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND tipo <> '' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND tipo <> '' ";
                    endif;

                    //As duas datas o numero eo tipo
                    if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && empty($busca['tronco']) && empty($busca['disposition'])):
                        $read->ExeSelect("cdr", $campos, "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND tipo = '{$busca['tipo']}' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND tipo = '{$busca['tipo']}' ";
                    endif;

                    // Duas datas o numero e o disposition
                    if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && empty($busca['tronco']) && !empty($busca['disposition'])):
                        $read->ExeSelect("cdr", $campos, "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND disposition = '{$busca['disposition']}' AND tipo <> '' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND disposition = '{$busca['disposition']}' AND tipo <> '' ";
                    endif;

                    //Duas datas e o tipo 
                    if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && empty($busca['tronco']) && empty($busca['disposition'])):
                        $read->ExeSelect("cdr", $campos, "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo = '{$busca['tipo']}' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo = '{$busca['tipo']}' ";
                    endif;

                    //Duas datas e o tronco 
                    if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && !empty($busca['tronco']) && empty($busca['disposition'])):
                        $read->ExeSelect("cdr", $campos, "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tronco = '{$busca['tronco']}' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tronco = '{$busca['tronco']}' ";
                    endif;

                    //Duas datas o tronco e o disposition
                    if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && !empty($busca['tronco']) && !empty($busca['disposition'])):
                        $read->ExeSelect("cdr", $campos, "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tronco = '{$busca['tronco']}' AND disposition = '{$busca['disposition']}' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tronco = '{$busca['tronco']}' AND disposition = '{$busca['disposition']}' ";
                    endif;
                    //Duas datas o tipo e o disposition
                    if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && empty($busca['tronco']) && !empty($busca['disposition'])):
                        $read->ExeSelect("cdr", $campos, "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' ";
                    endif;

                    //As duas datas e o disposition
                    if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && empty($busca['tronco']) && !empty($busca['disposition'])):
                        $read->ExeSelect("cdr", $campos, "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND disposition = '{$busca['disposition']}' AND tipo <> '' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND disposition = '{$busca['disposition']}' AND tipo <> '' ";
                    endif;
                    ?>
                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Nova Busca</h3>    
                        <a class="btn btn-warning nb" href="?exe=relatorio/extrato/periodo/lista" title="Nova Busca" data-toggle="tooltip" data-placement="top">Realizar uma Nova Busca</a>
                    </div>

                    <!--well botão-->
                    <div class="well text-right">                                                  
                        <a class="pull-left" href="system/relatorio/extrato/periodo/busca_excel.php<?php echo $search; ?>" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>                                
                        <a class="pull-left" href="system/relatorio/extrato/periodo/busca_pdf.php<?php echo $search; ?>" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>
                        <!--<a class="btn btn-success" href="painel.php?exe=gerenciamento/ramal/iax/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Ramal IAX</a>-->
                        <a class="voltar" href="painel.php" role="button" title="Voltar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>


                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th>Data</th>                                                        
                                <th>Origem</th>                                             
                                <th>Destino</th>                             
                                <th>Tipo</th>                             
                                <th>Tronco</th>                             
                                <th>Duração</th>       
                                <th>Status</th> 
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            if (!empty($read->getResult())):
                                echo "<h4>Total de registros encontrados: <b>{$read->getRowCount()}</b></h4>";

                                //var_dump($read->getRowCount());
                                foreach ($read->getResult() as $cdr):
                                    extract($cdr);
                                    $IP = "$_SERVER[SERVER_ADDR]";
                                    $data = explode(" ", $calldate);
                                    $dt = explode("-", $data[0]);
                                    $dataAtual = "$dt[2]-$dt[1]-$dt[0]";
                                    $link1 = "http://{$IP}/gravacoes/{$dataAtual}/{$userfield}.wav";
                                    $status = ($disposition == 'ANSWERED' ? "Atendida" : ($disposition == 'CANCEL' ? "Cancelada" : ($disposition == 'BUSY' ? "Ocupado" : ($disposition == 'NO ANSWER' ? "Não atendida" : ($disposition == 'FAIED' ? "Falha" : null)))));
                                    ?>
                                    <tr>
                                        <td scope="row"><?php echo $calldate; ?></td> 
                                        <td scope="row"><?php echo $src; ?></td> 
                                        <td scope="row"><?php echo $dst; ?></td>
                                        <td><?php echo $tipo; ?></td> 
                                        <td><?php echo $tronco; ?></td> 
                                        <td><?php echo gmdate("H:i:s", $billsec); ?></td> 
                                        <td><?php echo $status; ?></td> 
                                        <td>
                                            <a href="<?php echo $link1 ?>" target="blank"> <span class="glyphicon glyphicon-volume-up" aria-hidden="true"></span> </a>
                                            <!--<a href="painel.php?exe=gerenciamento/ramal/iax/update&iax_id=<?php //echo $iax_id                 ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>-->
                                            <!--<a href="" data-toggle="modal" data-target="#iax_<?php //echo $iax_id;                 ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>-->                                    
                                        </td> 
                                    </tr>

                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="iax_<?php echo $iax_id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Dados </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                    <div class="form-group">  
                                                        <h4>Deseja realemente apagar o ramal Iax : <?php echo "<b>{$iax_numero}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                        <input type="hidden" class="form-control" id="iax" name="iax_id" value="<?php echo $iax_id; ?>">
                                                    </div>                 

                                                    <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $iax_id; ?>">Apagar Dados</button>
                                                </form>   

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                                                <!--        <button type="button" class="btn btn-primary">Apagar</button>-->
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                                <?php
                            endforeach;
                        else:
                            KLErro("Não existe ligações cadastradas no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                </div>

                <!--PAGINAÇÃO-->
                <div class="well corWell text-center">
                    <?php
                    $pager->ExePaginator("cdr", $campos, "{$termo}");
                    echo $pager->getPaginator();
                    ?>
                </div>
                 <!--PAINEL RESUMO ESTATISTICO-->
               <!--Chamando a classe para apresentar o relatorio estatistico--> 
               <?php 
                $resultCdr = $pager->getCDRcall();
                $periodo = date('d/m/Y', strtotime($busca['di'])) . " até " . date('d/m/Y', strtotime($busca['df'])); 
//                var_dump($iniciDate);
//                exit();
                ?>
                
                <div class="col-md-6 col-md-offset-3 mg20B">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center">
                            <h3 class="panel-title">RESUMO GERAL DA PESQUISA</h3>
                        </div>
                        <div class="panel-body">
                            <!--Tabala-->                            
                            <table class="table table-condensed table-striped table-hover table-bordered text-center">
                                <!--<caption>Legenda de tabela opcional.</caption>-->
                                <thead>
<!--                                    <tr>                                            
                                        <th>DESCRIÇÃO</th>
                                        <th>TOTAL</th>                                            
                                    </tr>-->
                                </thead>
                                <tbody>
                                    <tr>                                            
                                        <td class="col-md-7">Período</td>
                                        <td><?php echo $periodo; ?></td>                                           
                                    </tr>
                                    <tr>                                            
                                        <td>Total de Chamadas</td>
                                        <td><?php echo $resultCdr[0]; ?></td>                                            
                                    </tr>
                                    <tr>                                            
                                        <td>Tempo Total</td>
                                        <td><?php echo gmdate("H:i:s", $resultCdr[1]); ?></td>                                            
                                    </tr>
                                    <tr>                                            
                                        <td>Atendidas</td>
                                        <td><?php echo  $resultCdr[2]; ?></td>                                            
                                    </tr>
                                    <tr>                                            
                                        <td>Canceladas</td>
                                        <td><?php echo $resultCdr[3]; ?></td>                                            
                                    </tr>
                                   <tr>                                            
                                        <td>Ocupadas</td>
                                        <td><?php echo $resultCdr[4]; ?></td>                                            
                                    </tr>
                                    <tr>                                            
                                        <td>Congestionadas</td>
                                        <td><?php echo $resultCdr[5]; ?></td>                                            
                                    </tr>
                                    <tr>                                            
                                        <td>Falhas</td>
                                        <td><?php echo $resultCdr[6]; ?></td>                                            
                                    </tr>
                                    
                                    
                                </tbody>
                            </table>                            
                            <!--fim tabela-->                            
                        </div>
                    </div>
                </div>
                <!--fim do painel resumo estatistico-->
                

            </div><!--panel-body-->
        </div>
    </div>
</div>
