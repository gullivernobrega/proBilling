<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Did <small>Listagem</small></h1>
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
                    $read->ExeRead("did");
                    //$read->ExeRead("rotas", "ORDER BY iax_nome ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
//                    $verifica = $read->getRowCount();
//                    $obj = $read->getResult();

                    //RESULTADO DA JANELA MODAL
                    $dataDel = filter_input(INPUT_POST, "did_id", FILTER_VALIDATE_INT);
                    if (!empty($dataDel)):

                        $Deletar = new Did;
                        $Deletar->ExeDelete($dataDel);

                        if ($Deletar->getResult()):                            
                            header("Location: painel.php?exe=gerenciamento/did/lista");
                        else:
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                        endif;
                    endif;
                    ?>
                    <!--well botão-->
                    <div class="well text-right">
                        <a class="btn btn-success" href="painel.php?exe=gerenciamento/did/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Did</a>
                        <a class="voltar" href="painel.php" role="button" title="Voltar"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--tabela de listagem-->                      
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th >Nome</th>                                                        
                                <th >Origem</th>                                                        
                                <th >Destino</th>          
                                <th>Segunda a Sexta</th>                             
                                <th>Sabado</th>       
                                <th>Domigo</th> 
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            if ($read->getRowCount() > 0):
                                foreach ($read->getResult() as $did):
                                    extract($did);   
                                    ?>
                                    <tr>                                        
                                        <td scope="row"><?php echo $did_nome; ?></td> 
                                        <td scope="row"><?php echo $did_origem; ?></td> 
                                        <td scope="row"><?php echo $did_destino; ?></td> 
                                        <td><?php echo "{$did_hora_ss_ini} - {$did_hora_ss_fim}"; ?></td>
                                        <td><?php echo "{$did_hora_s_ini} - {$did_hora_s_fim}"; ?></td> 
                                        <td><?php echo "{$did_hora_d_ini} - {$did_hora_d_fim}"; ?></td>                                         
                                        <td>
                                            <a href="painel.php?exe=gerenciamento/did/update&did_id=<?php echo $did_id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                            <a href="" data-toggle="modal" data-target="#did_<?php echo $did_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>                                    
                                        </td> 
                                    </tr>

                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="did_<?php echo $did_id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Dados </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                    <div class="form-group">  
                                                        <h4>Deseja realemente apagar este Did: <?php echo "<b>{$did_nome}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                        <input type="hidden" class="form-control" id="did" name="did_id" value="<?php echo $did_id; ?>">
                                                    </div>                 

                                                    <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $did_id; ?>">Apagar Dados</button>
                                                </form>   

                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>                                                
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                                <?php
                            endforeach;
                        else:
                            KLErro("Não existe Did Cadastrado no momento!", KL_ALERT);
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