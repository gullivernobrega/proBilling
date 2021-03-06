<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Categorias Noticias <small>Listagem</small></h1>
    </div>       
    <!--</div>-->

    <!--<div class="row">-->
    <!--<div class="container-fluid">-->

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> Painel de ações </h3>
        </div>
        <div class="panel-body">
            <div id="shieldui-grid1">
                <!--mensagem de erro-->
                <?php
                //LEITURA DAS CATEGORIAS
                $read = new Read;
                $read->ExeRead("kl_noticia_categoria");
                $verifica = $read->getRowCount();
                $obj = $read->getResult();

                //RESULTADO DA JANELA MODAL
                $dataDel = filter_input(INPUT_POST, "cat_id", FILTER_VALIDATE_INT);
                if (!empty($dataDel)):
                    $Deletar = new CategoriesNews;
                    $Deletar->ExeDelete($dataDel);

                    if ($Deletar->getResult()):
                        $erro = $Deletar->getErro();
                        KLErro($erro[0], $erro[1]);
                    else:
                        $erro = $Deletar->getErro();
                        KLErro($erro[0], $erro[1]);
                    endif;
                endif;
                ?>
                <!--well botão-->
                <div class="well text-right">
                    <a class="btn btn-success" href="painel.php?exe=noticias/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Nova Categoria Noticia</a>
                    <a class="voltar" href="painel.php" role="button" title="Voltar"><i class="fa fa-share"></i> Voltar</a>
                </div>

                <!--tabela de listagem-->
                <table class="table table-responsive table-hover hover-color"> 
                    <thead> 
                        <tr> 
                            <th width="5%">#</th> 
                            <th>Categoria Noticia</th>                                                           
                            <th width="20%">Posição</th>                             
                            <th width="6%">Status</th>                             
                            <th width="8%">Ações</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        <?php
                        if ($verifica > 0):

                            foreach ($obj as $us):
                                extract($us);
                                ?>
                                <tr>
                                    <td scope="row"><?php echo $cat_id; ?></td> 
                                    <td><?php echo $cat_descricao; ?></td> 
                                    <td><?php echo $cat_posicao; ?></td>
                                    <td class="txtCenter">
                                        <?php
                                        if ($cat_status == "S"):
                                            echo ' <span class="glyphicon glyphicon-ok txtVerde " aria-hidden="true"  title="Ativo"></span>';
                                        else:
                                            echo '<span class="glyphicon glyphicon-info-sign txtRed" aria-hidden="true" title="Inativo"></span>';
                                        endif;
                                        ?>
                                    </td>   

                                    <td>
                                        <a href="painel.php?exe=noticias/listNoticias&cat_id=<?php echo $cat_id ?>" data-toggle="tooltip" data-placement="top" title="Noticia" class="corGrem"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                                        <a href="painel.php?exe=noticias/update&cat_id=<?php echo $cat_id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                        <a href="" data-toggle="modal" data-target="#cat_<?php echo $cat_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>                                    
                                    </td> 
                                </tr>

                                <!-- JANELA MODAL -->                
                            <div class="modal fade" tabindex="-1" role="dialog" id="cat_<?php echo $cat_id; ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Apagar Dados </h4>
                                        </div>
                                        <div class="modal-body">

                                            <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                <div class="form-group">  
                                                    <h4>Deseja realemente apagar a Categoria Noticia: <?php echo "<b>{$cat_descricao}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                    <input type="hidden" class="form-control" id="user" name="cat_id" value="<?php echo $cat_id; ?>">
                                                </div>                 

                                                <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $cat_id; ?>">Apagar Dados</button>
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
                        KLErro("Não exidte Categorias Cadastrado no momento!", KL_ALERT);
                    endif;
                    ?>   
                    </tbody> 
                </table>
                <!--fim tabela-->
            </div>
        </div>
    </div>
    <!--</div>-->
</div>