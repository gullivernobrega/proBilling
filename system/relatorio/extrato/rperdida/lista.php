<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;

//select para busca
//SELECT `calldate`, `src`, `disposition` FROM `cdr` WHERE `calldate` >= '2018-08-21 00:00:01' AND `calldate` <= '2018-08-21 23:59:59' AND `disposition` <> 'ANSWER' AND `dcontext` = 'entrada'
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Extrato de Recebidas Perdidas <small>Listagem</small></h1>
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
                    $pager = new Pager("?exe=relatorio/extrato/rperdida/lista&pg=");
                    $pager->ExePager($getPage, 20);
                    
                    //LEITURA DOS DADOS     
                    //$dt = gmdate("Y-m-d", time()-(3600*27));
                                        
                    $dt = date("Y-m-d");
                    //$dt = "2018-06-06";
                    $dataIni = "{$dt} 00:00:01";
                    $datafinal = "{$dt} 23:59:59";
                    
                    $campos = "calldate, src, disposition";                    
                    
                    $read = new Select;
                    //$read->ExeSelect("cdr", $campos ,"WHERE calldate >= '{$dataIni}' AND calldate <= '{$datafinal}' AND tipo <> '' ORDER BY calldate ASC");
                    $read->ExeSelect("cdr", $campos ,"WHERE calldate >= '{$dataIni}' AND calldate <= '{$datafinal}' AND disposition <> 'ANSWERED' AND dcontext = 'entrada' ORDER BY calldate ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $verifica = $read->getRowCount();

                    //RESULTADO DA PESQUISA
                    $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($busca['btnBusca'])):
                        unset($busca['btnBusca']);
                                          
                        // Se a data fim for diferente de vazio adiciona mais um dia
                        /*if (!empty($busca['dataFim'])):
                            $busca['dataFim'] = date('Y-m-d', strtotime("+1 days", strtotime($busca['dataFim'])));
                        endif;*/

                        if (!empty($busca['dataInicio']) && !empty($busca['dataFim'])):

//                            if (!empty($busca['src'])):
//                                $num = "src = '{$busca['src']}'";
//                            elseif (!empty($busca['dst'])):
//                                $num = "dst = '{$busca['dst']}'";
//                            endif;

                            /*$busca['dataInicio'] = "{$busca['dataInicio']} 00:00:01";
                            $busca['dataFim'] = "{$busca['dataFim']} 23:59:59";*/

                            header("Location: ?exe=relatorio/extrato/rperdida/busca&di={$busca['dataInicio']}&df={$busca['dataFim']}");
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

                            <!--</button>-->
                            <!--<div class="col-xs-2">-->                                    
                            <button name="btnBusca" value="Buscar" type="submit" class="btn btn-info btn-sm" title="Buscar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>
                        </form>
                    </div>

                    <!--well botão-->
                    <div class="well text-right">                                                  
                        <a class="pull-left" href="system/relatorio/extrato/rperdida/relatorio_excel.php" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>                                
                        <a class="pull-left" href="system/relatorio/extrato/rperdida/relatorio_pdf.php" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>
                        <!--<a class="btn btn-success" href="painel.php?exe=gerenciamento/ramal/iax/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Ramal IAX</a>-->
                        <a class="voltar" href="painel.php" role="button" title="Voltar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th>Data</th>                                                        
                                <th>Número</th> 
                                <th>Status</th>                                 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            if ($verifica > 0):
                                
                                echo "<h4>Total de registros encontrados: <b>{$verifica}</b></h4>";
                                foreach ($read->getResult() as $cdr):
                                    extract($cdr);                                    
//                                    $data = explode(" ", $calldate);
//                                    $dt = explode("-", $data[0]);
//                                    $dataAtual = "$dt[2]-$dt[1]-$dt[0]";                                      
                                    //
                                    $status = ($disposition == 'ANSWERED' ? "Atendida" : ($disposition == 'CANCEL' ? "Cancelada" :($disposition == 'BUSY' ? "Ocupado" : ($disposition == 'NO ANSWER' ? "Não atendida" : ($disposition == 'FAILED' ? "Falha" : ($disposition == 'CHANUNAVAIL' ? "Indisponível" : null))))));
                                    ?>
                                    <tr>
                                        <td scope="row"><?php echo $calldate; ?></td> 
                                        <td scope="row"><?php echo $src; ?></td>
                                        <td><?php echo $status; ?></td> 
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
                    $pager->ExePaginator("cdr", $campos, "WHERE calldate >= '{$dataIni}' AND calldate <= '{$datafinal}' AND disposition <> 'ANSWERED' AND dcontext = 'entrada' ORDER BY calldate ASC");                    
                    echo $pager->getPaginator();
                    ?>
                </div>

            </div><!--panel-body-->
        </div>
    </div>
</div>
