<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Busca Extrato de Recebidas Perdidas <small>Listagem</small></h1>
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
                        if (!empty($busca['di']) && !empty($busca['df'])):
                            $link = "?exe=relatorio/extrato/rperdida/busca&di={$busca['di']}&df={$busca['df']}&pg=";
                            $search = "?di={$busca['di']}&df={$busca['df']}";                       
                        else:
                        endif;

                    endif;

                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                    $pager = new Pager($link);
                    $pager->ExePager($getPage, 20);

                    $campos = "calldate, src, disposition";
                    
                    //LEITURA DOS DADOS
                    $read = new Select;
                    // Duas datas 
                    if (!empty($busca['di']) && !empty($busca['df'])):                        
                        $read->ExeSelect("cdr", $campos, "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND disposition <> 'ANSWERED' AND dcontext = 'entrada'  ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");                        
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND disposition <> 'ANSWERED' AND dcontext = 'entrada' ";                       
                    endif;
                  
                    ?>
                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Nova Busca</h3>    
                        <a class="btn btn-warning nb" href="?exe=relatorio/extrato/rperdida/lista" title="Nova Busca" data-toggle="tooltip" data-placement="top">Realizar uma Nova Busca</a>
                    </div>

                    <!--well botão-->
                    <div class="well text-right">                                                  
                        <a class="pull-left" href="system/relatorio/extrato/rperdida/busca_excel.php<?php echo $search; ?>" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>                                
                        <a class="pull-left" href="system/relatorio/extrato/rperdida/busca_pdf.php<?php echo $search; ?>" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>
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
                            if (!empty($read->getResult())):
                                echo "<h4>Total de registros encontrados: <b>{$read->getRowCount()}</b></h4>";

                                //var_dump($read->getRowCount());
                                foreach ($read->getResult() as $cdr):
                                    extract($cdr);                                    
                                    $status = ($disposition == 'ANSWERED' ? "Atendida" : ($disposition == 'CANCEL' ? "Cancelada" :($disposition == 'BUSY' ? "Ocupado" : ($disposition == 'NO ANSWER' ? "Não atendida" : ($disposition == 'FAIED' ? "Falha" : ($disposition == 'CHANUNAVAIL' ? "Indisponível" : null))))));
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

            </div><!--panel-body-->
        </div>
    </div>
</div>
