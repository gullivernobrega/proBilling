<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Extrato por Período <small>Listagem</small></h1>
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
                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);                                                            
                    $pager = new Pager("?exe=relatorio/extrato/periodo/lista&pg=");
                    $pager->ExePager($getPage, 20);
                    
                    //LEITURA DOS DADOS     
                    //$dt = gmdate("Y-m-d", time()-(3600*27));
                                        
                    $dt = date("Y-m-d");
                    $dataIni = "{$dt} 00:00:01";
                    $datafinal = "{$dt} 23:59:59";
                    
                    $campos = "calldate, src, dst, tipo, tronco, billsec, disposition, userfield";
                    
                    $read = new Select;
                    //$read->ExeSelect("cdr", $campos ,"WHERE calldate >= '{$dataIni}' AND calldate <= '{$datafinal}' AND tipo <> '' ORDER BY calldate ASC");
                    $read->ExeSelect("cdr", $campos ,"WHERE calldate >= '{$dataIni}' AND calldate <= '{$datafinal}' AND tipo <> '' ORDER BY calldate ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $verifica = $read->getRowCount();
                   
                    
                    //RESULTADO DA PESQUISA
                    $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($busca['btnBusca'])):
                        unset($busca['btnBusca']);
                                          
                        // Se a data fim for diferente de vazio adiciona mais um dia
                        /*if (!empty($busca['dataFim'])):
                            $busca['dataFim'] = date('Y-m-d', strtotime("+1 days", strtotime($busca['dataFim'])));
                        endif;*/

                        if (!empty($busca['dataInicio']) && !empty($busca['dataFim']) || !empty($busca['src']) || !empty($busca['dst']) || !empty($busca['tronco']) || !empty($busca['tipo']) || !empty($busca['disposition'])):

                            if (!empty($busca['src'])):
                                $num = "src = '{$busca['src']}'";
                            elseif (!empty($busca['dst'])):
                                $num = "dst = '{$busca['dst']}'";
                            endif;

                            /*$busca['dataInicio'] = "{$busca['dataInicio']} 00:00:01";
                            $busca['dataFim'] = "{$busca['dataFim']} 23:59:59";*/

                            header("Location: ?exe=relatorio/extrato/periodo/busca&di={$busca['dataInicio']}&df={$busca['dataFim']}&num={$num}&tronco={$busca['tronco']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}");
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
                            <!--<div class="loc">--> 
                            <div class="form-group form-group-sm">
                                <!--<div class="col-xs-2">-->
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
                                <!--</div>-->
                            </div>

                            <!--*******-->
                            <div class="form-group form-group-sm">
                                <!--<div class="col-xs-2">-->
                                <!--<label>Numero </label>-->
                                <input 
                                    class="form-control" 
                                    name="src" 
                                    id="src" 
                                    type="text" 
                                    placeholder="Informe numero de Origem" 
                                    value="<?php
                                    if (isset($busca['src'])): echo $busca['src'];
                                    endif;
                                    ?>" > 
                                <!--</div>-->
                            </div>

                            <!--*******-->
                            <div class="form-group form-group-sm">
                                <!--<div class="col-xs-2">-->
                                <!--<label>Numero </label>-->
                                <input 
                                    class="form-control" 
                                    name="dst" 
                                    id="dst" 
                                    type="text" 
                                    placeholder="Informe numero Destino" 
                                    value="<?php
                                    if (isset($busca['dst'])): echo $busca['dst'];
                                    endif;
                                    ?>" > 
                                <!--</div>-->
                            </div>
                            <!--*******-->
                            <div class="form-group form-group-sm">
                                <!--<div class="col-xs-2">-->
                                <!--<label>Numero </label>-->
                                <input 
                                    class="form-control" 
                                    name="tronco" 
                                    id="tronco" 
                                    type="text" 
                                    placeholder="Informe tronco" 
                                    value="<?php
                                    if (isset($busca['tronco'])): echo $busca['tronco'];
                                    endif;
                                    ?>" > 
                                <!--</div>-->
                            </div>

                            <!--*******-->
                            <div class="form-group form-group-sm"> 
                                <select class="form-control" name="tipo" id="tronco_host" >
                                    <option value="">Informe o Tipo</option>
                                    <option value="Brasil-Fixo" <?php if (!empty($busca) && $busca['tipo'] == "Brasil-Fixo"): ?> selected="selected" <?php endif; ?> >Brasil Fixo</option>
                                    <option value="Brasil-Movel" <?php if (!empty($busca) && $busca['tipo'] == "Brasil-Movel"): ?> selected="selected" <?php endif; ?>>Brasil Movel</option>
                                    <option value="Internas" <?php if (!empty($busca) && $busca['tipo'] == "Internas"): ?> selected="selected" <?php endif; ?>>Internas</option>
                                    <option value="Internacional" <?php if (!empty($busca) && $busca['tipo'] == "Internacional"): ?> selected="selected" <?php endif; ?>>Internacional</option>                                    
                                    <option value="Recebida" <?php if (!empty($busca) && $busca['tipo'] == "Recebida"): ?> selected="selected" <?php endif; ?>>Recebida</option>                                    
                                </select>
                            </div>                            
                            <!--*******-->
                            <div class="form-group form-group-sm"> 
                                <select class="form-control" name="disposition" id="tronco_host" >
                                    <option value="">Informe Status</option>
                                    <option value="ANSWERED" <?php if (!empty($busca) && $busca['disposition'] == "ANSWERED"): ?> selected="selected" <?php endif; ?> >Atendida</option>
                                    <option value="FAILED" <?php if (!empty($busca) && $busca['disposition'] == "FAILED"): ?> selected="selected" <?php endif; ?>>Falha</option>
                                    <option value="NO ANSWER" <?php if (!empty($busca) && $busca['disposition'] == "NO ANSWER"): ?> selected="selected" <?php endif; ?>>Não Atedida</option>
                                    <option value="BUSY" <?php if (!empty($busca) && $busca['disposition'] == "BUSY"): ?> selected="selected" <?php endif; ?>>Ocupado</option>
				    <option value="CANCEL" <?php if (!empty($busca) && $busca['disposition'] == "CANCEL"): ?> selected="selected" <?php endif; ?>>Cancelada</option>
	                                    
                                </select>
                            </div>

                            <!--</button>-->
                            <!--<div class="col-xs-2">-->                                    
                            <button name="btnBusca" value="Buscar" type="submit" class="btn btn-info btn-sm" title="Buscar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                            <!--<input name="btnBusca" id="btnBusca" type="submit" class="btn btn-info" title="Buscar" data-toggle="tooltip" data-placement="right" value="Buscar">-->
                                <!--<span class="glyphicon glyphicon-search" aria-hidden="true"></span>-->
                            <!--</div>-->
                        </form>
                    </div>

                    <!--well botão-->
                    <div class="well text-right">                                                  
                        <a class="pull-left" href="system/relatorio/extrato/periodo/relatorio_excel.php" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>                                
                        <a class="pull-left" href="system/relatorio/extrato/periodo/relatorio_pdf.php" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>
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
                            if ($verifica > 0):
                                
                                echo "<h4>Total de registros encontrados: <b>{$verifica}</b></h4>";
                                foreach ($read->getResult() as $cdr):
                                    
                                    extract($cdr);
                                    $IP = "$_SERVER[SERVER_ADDR]";
                                    $IP2 = "";
                                    $data = explode(" ", $calldate);
                                    $dt = explode("-", $data[0]);
                                    $dataAtual = "$dt[2]-$dt[1]-$dt[0]";                                      
                                    $link = "http://{$IP}/gravacoes/{$dataAtual}/{$userfield}.wav";
                                    $status = ($disposition == 'ANSWERED' ? "Atendida" : ($disposition == 'CANCEL' ? "Cancelada" :($disposition == 'BUSY' ? "Ocupado" : ($disposition == 'NO ANSWER' ? "Não atendida" : ($disposition == 'FAILED' ? "Falha" : null)))));
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
                                            <a href="<?php echo $link ?>" target="blank"> <span class="glyphicon glyphicon-volume-up" aria-hidden="true"></span> </a>
                                            <!--<a href="painel.php?exe=gerenciamento/ramal/iax/update&iax_id=<?php //echo $iax_id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>-->
                                            <!--<a href="" data-toggle="modal" data-target="#iax_<?php //echo $iax_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>-->                                    
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
                            KLErro("Não existe Extrato Cadastrado no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                </div>

                <!--PAGINAÇÃO-->
                <div class="well corWell text-center"> 
                    <?php
                    $pager->ExePaginator("cdr", $campos, "WHERE calldate >= '{$dataIni}' AND calldate <= '{$datafinal}' AND tipo <> '' ORDER BY calldate ASC");
                    //$pager->ExePaginator("cdr", "WHERE calldate >= '{$dataAtual}' AMD tipo <> '' ");
                    //SELECT * FROM `cdr` WHERE `calldate` >= '2018-04-09 00:00:01' AND `calldate` <= '2018-04-09 23:59:59' AND `tipo` <> ''
                    echo $pager->getPaginator();
                    ?>
                </div>
                
                <!--PAINEL RESUMO ESTATISTICO-->
               <!--Chamando a classe para apresentar o relatorio estatistico--> 
               <?php 
                $resultCdr = $pager->getCDRcall();
                
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
                                        <td><?php echo "$dt[2]/$dt[1]/$dt[0]"; ?></td>                                           
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
