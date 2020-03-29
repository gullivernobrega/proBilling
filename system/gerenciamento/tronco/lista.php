<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Tronco <small>Listagem</small></h1>
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
                    $pager = new Pager("?exe=gerenciamento/tronco/lista&pg=");
                    $pager->ExePager($getPage, 20);

                    //LEITURA DOS RAMAIS
                    $read = new Read;
                    $read->ExeRead("tronco", "ORDER BY tronco_nome ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $verifica = $read->getRowCount();
                    
                    //RESULTADO DA JANELA MODAL
                    //$dataDel = filter_input(INPUT_POST, "tronco_id", FILTER_VALIDATE_INT);
                    $dataDel = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($dataDel)):
                        
                        if ($dataDel['tronco_tipo'] == "IAX2"):

                            $Deletar = new Troncoiax();
                            $Deletar->ExeDelete($dataDel['tronco_id']);

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
                                    header("Location: painel.php?exe=gerenciamento/tronco/lista");
                                endif;

                            else:
                                $erro = $Deletar->getErro();
                                KLErro($erro[0], $erro[1]);
                            endif;

                        else:

                            $Deletar = new Troncosip;
                            $Deletar->ExeDelete($dataDel['tronco_id']);

                            if ($Deletar->getResult()):
                                $erro = $Deletar->getErro();
                                KLErro($erro[0], $erro[1]);

                                //Remonta o arquivo .conf
                                $geralConf = new Troncosip;
                                $geralConf->ExeConfGeral();
                                if ($geralConf->getResult()):
                                    //Reloada no asterisk
                                    shell_exec("sudo asterisk -rx 'reload'");
                                    //Redireciona
                                    header("Location: painel.php?exe=gerenciamento/tronco/lista");
                                endif;

                            else:
                                $erro = $Deletar->getErro();
                                KLErro($erro[0], $erro[1]);
                            endif;

                        endif;

                    endif;
                    ?>
                    <!--well botão-->
                    <div class="well text-right">
                        <a class="btn btn-info" href="painel.php?exe=gerenciamento/tronco/iax/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Tronco IAX</a>
                        <a class="btn btn-success" href="painel.php?exe=gerenciamento/tronco/sip/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Tronco SIP</a>
                        <a class="voltar" href="painel.php" role="button" title="Voltar"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th>Nome</th>                                                        
                                <th width="6%">Tipo</th>                                                        
                                <th width="6%">Usuário</th>                                                        
                                <th width="8%">Callerid</th>                                             
                                <th>Codec 1</th>                             
                                <th>Codec 2</th>                             
                                <th>Codec 3</th>       
                                <th>Host</th> 
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            if ($verifica > 0):

                                foreach ($read->getResult() as $tr):
                                    extract($tr);
                                    ?>
                                    <tr>
                                        <td scope="row"><?php echo $tronco_nome; ?></td> 
                                        <td scope="row"><?php echo $tronco_tipo; ?></td> 
                                        <td scope="row"><?php echo $tronco_username; ?></td> 
                                        <td scope="row"><?php echo $tronco_callerid; ?></td>                                         
                                        <td><?php echo $tronco_codec1; ?></td> 
                                        <td><?php echo $tronco_codec2; ?></td> 
                                        <td><?php echo $tronco_codec3; ?></td> 
                                        <td scope="row"><?php echo $tronco_host; ?></td>
                                        <td>
                                            <?php
                                            if ($tronco_tipo == 'IAX2'):
                                                ?>
                                                <a href="painel.php?exe=gerenciamento/tronco/iax/update&tronco_id=<?php echo $tronco_id ?>" data-toggle="tooltip" data-placement="top" title="Editar IAX"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>                                                                               
                                                <a href="" data-toggle="modal" data-target="#tronco_<?php echo $tronco_id; ?>" data-placement="top" title="Apagar IAX" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>
                                            <?php else: ?>
                                                <a href="painel.php?exe=gerenciamento/tronco/sip/update&tronco_id=<?php echo $tronco_id ?>" data-toggle="tooltip" data-placement="top" title="Editar SIP"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>                                                                                
                                                <a href="" data-toggle="modal" data-target="#tronco_<?php echo $tronco_id; ?>" data-placement="top" title="Apagar SIP" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>
                                            <?php endif; ?>
                                            
                                        </td> 
                                    </tr>

                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="tronco_<?php echo $tronco_id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Dados </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                    <div class="form-group">  
                                                        <h4>Deseja realemente apagar o tronco: <?php echo "<b>{$tronco_tipo} {$tronco_nome}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                        <input type="hidden" class="form-control" id="tronco1" name="tronco_id" value="<?php echo $tronco_id; ?>">
                                                        <input type="hidden" class="form-control" id="tronco2" name="tronco_tipo" value="<?php echo $tronco_tipo; ?>">
                                                    </div>                 

                                                    <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $tronco_id; ?>">Apagar Dados</button>
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
                            KLErro("Não existe Troncos Cadastrado no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                    <!--PAGINAÇÃO-->
                    <div class="well corWell text-center">                     
                        <?php
                        $pager->ExePaginator("tronco");
                        echo $pager->getPaginator();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>