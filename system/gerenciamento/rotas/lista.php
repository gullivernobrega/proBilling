<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Rotas <small>Listagem</small></h1>
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
//                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
//                    $pager = new Pager("?exe=gerenciamento/ramal/iax/lista&pg=");
//                    $pager->ExePager($getPage, 20);
                    
                    //LEITURA DAS ROTAS
                    $read = new Read;
                    $read->ExeRead("rotas");
                    //$read->ExeRead("rotas", "ORDER BY iax_nome ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $verifica = $read->getRowCount();
                    $obj = $read->getResult();

                    //RESULTADO DA JANELA MODAL
                    $dataDel = filter_input(INPUT_POST, "rota_id", FILTER_VALIDATE_INT);
                    if (!empty($dataDel)):

                        $Deletar = new Troncoiax;
                        $Deletar->ExeDelete($dataDel);

                        if ($Deletar->getResult()):
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                            
                            //Remonta o arquivo .conf
                            $geralConf = new Troncoiax;
                            $geralConf->ExeConfGeral();
                            if ($geralConf->getResult()):
                                //Reloada no asterisk
                                shell_exec("sudo asterisk -rx 'reload'");                                
                                //Redireciona
                                header("Location: painel.php?exe=gerenciamento/rotas/lista");
                            endif;
                            
                        else:
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                        endif;
                    endif;
                    ?>
                    <!--well botão-->
                    <div class="well text-right">
                        <a class="btn btn-success" href="painel.php?exe=gerenciamento/rotas/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Nova Rota</a>
                        <a class="voltar" href="painel.php" role="button" title="Voltar"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--tabela de listagem-->                    
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th >Rota Tipo</th>                                                        
                                <th >Tronco Fixo</th>                                                        
                                <th >Tipo Tronco Fixo</th>                                             
                                <th>Tronco Movel</th>                             
                                <th>Tipo Tronco Movel</th>                             
                                <th>Tronco Inter.</th>       
                                <th>Tipo Tronco Inter.</th> 
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            if ($verifica > 0):

                                foreach ($obj as $rota):
                                    extract($rota);
                            
                                    $readTronco = new Read;
                                    $readTronco->ExeRead("tronco");
                                    foreach ($readTronco->getResult() as $valTronco):
                                        if($valTronco['tronco_id'] == $tronco_id_fixo):
                                            $tronco_fixo = $valTronco['tronco_nome'];
                                        endif;
                                        
                                        if($valTronco['tronco_id'] == $tronco_id_movel):
                                            $tronco_movel = $valTronco['tronco_nome'];
                                        endif;
                                        
                                        if($valTronco['tronco_id'] == $tronco_id_inter):
                                            $tronco_inter = $valTronco['tronco_nome'];
                                        endif;
                                    endforeach;
                                    
                                    ?>
                                    <tr>
                                        <!--``rota_tipo`, `tronco_id_fixo`, `rota_tronco_fixo_tipo`, `tronco_id_movel`, `rota_tronco_movel_tipo`, `tronco_id_inter`, `rota_tronco_inter_tipo`-->
                                        <td scope="row"><?php echo $rota_tipo; ?></td> 
                                        <td scope="row"><?php echo $tronco_fixo; ?></td> 
                                        <td scope="row"><?php echo $rota_tronco_fixo_tipo; ?></td> 
                                        <td scope="row"><?php echo $tronco_movel; ?></td>
                                        <td><?php echo $rota_tronco_movel_tipo; ?></td> 
                                        <td><?php echo $tronco_inter; ?></td> 
                                        <td><?php echo $rota_tronco_inter_tipo; ?></td> 
                                        <td>
                                            <a href="painel.php?exe=gerenciamento/rotas/update&rota_id=<?php echo $rota_id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                            <a href="" data-toggle="modal" data-target="#rota_<?php echo $rota_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>                                    
                                        </td> 
                                    </tr>

                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="rota_<?php echo $rota_id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Dados </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                    <div class="form-group">  
                                                        <h4>Deseja realemente apagar esta Rota: <?php echo "<b>{$rota_tipo}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                        <input type="hidden" class="form-control" id="iax" name="rota_id" value="<?php echo $rota_id; ?>">
                                                    </div>                 

                                                    <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $rota_id; ?>">Apagar Dados</button>
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
                            KLErro("Não existe Rotas Cadastrado no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                    <!--PAGINAÇÃO-->
                    <div class="well corWell text-center">                     
                        <?php
//                        $pager->ExePaginator("troncoiax");
//                        echo $pager->getPaginator();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>