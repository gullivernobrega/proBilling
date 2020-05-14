<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Ramais IAX <small>Listagem</small></h1>
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
                    $pager = new Pager("?exe=gerenciamento/ramal/iax/lista&pg=");
                    $pager->ExePager($getPage, 20);

                    //LEITURA DOS RAMAIS
                    $read = new Read;
                    $read->ExeRead("ramaliax", "ORDER BY iax_numero ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $verifica = $read->getRowCount();
                    $obj = $read->getResult();

                    //RESULTADO DA JANELA MODAL
                    $dataDel = filter_input(INPUT_POST, "iax_id", FILTER_VALIDATE_INT);
                    if (!empty($dataDel)):

                        $Deletar = new Iax;
                        $Deletar->ExeDelete($dataDel);

                        if ($Deletar->getResult()):
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);

                            //Remonta o arquivo .conf
                            $geralConf = new Iax;
                            $geralConf->ExeConfGeral();
                            if ($geralConf->getResult()):
                                //Reloada no asterisk
                                shell_exec("sudoasterisk -rx 'reload'");
                                //Redireciona
                                header("Location: painel.php?exe=gerenciamento/ramal/iax/lista");
                            endif;

                        else:
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                        endif;
                    endif;
                    
                    //RESULTADO DA MODAL MULTIPLOS ARQUIVO
                    $dataMult = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                    if (!empty($dataMult['confirmaDadosMult'])):
                        unset($dataMult['confirmaDadosMult']);

                        if ($dataMult['iaxInicial'] < $dataMult['iaxFinal']):
                            
                            $Deletar = new Iaxmult();
                            $Deletar->ExeDeleteMult($dataMult);
                            if ($Deletar->getResult()):
                                
                                //Remonta o arquivo .conf
                                $geralConf = new Iax;
                                $geralConf->ExeConfGeral();
                                if ($geralConf->getResult()):
                                    //Reloada no asterisk
                                    shell_exec("sudoasterisk -rx 'reload'");
                                    //Redireciona
                                    header("Location: painel.php?exe=gerenciamento/ramal/iax/lista");
                                endif;
                                
                            endif;

                        else:
                            KLErro("Ops, O ramal inicial não pode ser maior que o ramal final, Verifique!", KL_INFOR);
                        endif;

                    endif;
                    
                    ?>
                    <!--well botão-->
                    <div class="well text-right">
                        <a class="btn btn-success" href="painel.php?exe=gerenciamento/ramal/iax/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Ramal IAX</a>
                        <a class="btn btn-info" href="painel.php?exe=gerenciamento/ramal/iax/multiplusiax" role="button" title="Novos"><i class="fa fa-file-o"></i> Multiplos Ramais IAX</a>
                        <button type="button" class="btn btn-danger " data-toggle="modal" data-target="#multIax"> Apagar Multiplos Ramais</button>
                        <a class="voltar" href="painel.php" role="button" title="Voltar"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th width="6%">Ramal</th>                                                        
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

                                foreach ($obj as $iax):
                                    extract($iax);
                                    ?>
                                    <tr>
                                        <td scope="row"><?php echo $iax_numero; ?></td> 
                                        <td scope="row"><?php echo $iax_callerid; ?></td> 
                                        <td scope="row"><?php echo $iax_host; ?></td>
                                        <td><?php echo $iax_codec1; ?></td> 
                                        <td><?php echo $iax_codec2; ?></td> 
                                        <td><?php echo $iax_codec3; ?></td> 
                                        <td>
                                            <a href="painel.php?exe=gerenciamento/ramal/iax/update&iax_id=<?php echo $iax_id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                            <a href="" data-toggle="modal" data-target="#iax_<?php echo $iax_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>                                    
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

                                <!-- JANELA MODAL MULT-->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="multIax">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Dados </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">   
                                                    <div class="form-group">  
                                                    <h4>Se Deseja realemente apagar multiplos ramais IAX, informe o ramal inicial e o ramal final depis clique em apagar dados, caso contrário clique em cancelar.</h4>

                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        id="sipInicial" 
                                                        name="iaxInicial" 
                                                        placeholder="ramal Inicial"
                                                        value="<?php
                                                        if (!empty($dataMult['iaxInicial'])): echo $dataMult['iaxInicial'];
                                                        endif;
                                                        ?>" 
                                                        required 
                                                        autofocus
                                                        >
                                                    <br>
                                                    <input 
                                                        type="text" 
                                                        class="form-control" 
                                                        id="iaxFinal" 
                                                        name="iaxFinal" 
                                                        placeholder="Ramal final"
                                                        value="<?php
                                                        if (!empty($dataMult['iaxFinal'])): echo $dataMult['iaxFinal'];
                                                        endif;
                                                        ?>" 
                                                        required
                                                        >
                                                    </div>
                                                    
                                                    <button type="submit" class="btn btn-success" name="confirmaDadosMult" value="Multidados">Apagar Dados</button>
                                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                                                </form>   

                                            </div>

                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                                <?php
                            endforeach;
                        else:
                            KLErro("Não exidte Ramais Cadastrado no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                    <!--PAGINAÇÃO-->
                    <div class="well corWell text-center">                     
                        <?php
                        $pager->ExePaginator("ramaliax");
                        echo $pager->getPaginator();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>