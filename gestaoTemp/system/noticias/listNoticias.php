<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Noticias <small>Listagem</small></h1>
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
                $cat_id = filter_input(INPUT_GET, "cat_id", FILTER_VALIDATE_INT);
                
                //LEITURA DAS NOTICIAS
                $read = new Read;
                $read->ExeRead("kl_noticia", "WHERE cat_id = :id", "id={$cat_id}");
                $verifica = $read->getRowCount();
                $obj = $read->getResult();

                //RESULTADO DA JANELA MODAL
                $dataDel = filter_input(INPUT_POST, "not_id", FILTER_VALIDATE_INT);
                if (!empty($dataDel)):
                    $Deletar = new Noticias();
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
                    <a class="btn btn-success" href="painel.php?exe=noticias/createNoticias&cat_id=<?php echo $cat_id;?>" role="button" title="Novo"><i class="fa fa-file-o"></i> Nova Noticia</a>
                    <a class="voltar" href="painel.php?exe=noticias/lista" role="button" title="Voltar"><i class="fa fa-share"></i> Voltar</a>
                </div>

                <!--tabela de listagem-->
                <table class="table table-responsive table-hover hover-color"> 
                    <thead> 
                        <tr> 
                            <!--`not_id`, `not_titulo`, `not_data_post`, `not_texto`, `not_status`, `not_ordem`, `not_grupo`, `cat_id`-->
                            <th width="5%">#</th> 
                            <th>Noticia Titulo</th>                                                           
                            <th width="12%">Data Postagem</th>
                            <th width="6%">Status</th>                            
                            <th width="20%">Categoria</th>  
                            <th width="8%">Ações</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        <?php
                        if ($verifica > 0):

                            foreach ($obj as $nt):
                                extract($nt);
                                ?>
                                <tr>
                                    <td scope="row"><?php echo $not_id; ?></td> 
                                    <td><?php echo $not_titulo; ?></td> 
                                    <td><?php echo $not_data_post; ?></td>
                                    <td class="txtCenter">
                                        <?php
                                        if ($not_status == "S"):
                                            echo ' <span class="glyphicon glyphicon-ok txtVerde " aria-hidden="true"  title="Ativo"></span>';
                                        else:
                                            echo '<span class="glyphicon glyphicon-info-sign txtRed" aria-hidden="true" title="Inativo"></span>';
                                        endif;
                                        ?>
                                    </td>   
                                    <td><?php echo $cat_id; ?></td>
                                    <td>
                                        <a href="painel.php?exe=noticias/listNoticias&not_id=<?php echo $not_id ?>" data-toggle="tooltip" data-placement="top" title="Noticia" class="corGrem"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span></a>
                                        <a href="painel.php?exe=noticias/update&not_id=<?php echo $not_id;?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                        <a href="" data-toggle="modal" data-target="#not_<?php echo $not_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>                                    
                                    </td> 
                                </tr>

                                <!-- JANELA MODAL -->                
                            <div class="modal fade" tabindex="-1" role="dialog" id="not_<?php echo $not_id; ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Apagar Dados </h4>
                                        </div>
                                        <div class="modal-body">

                                            <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                <div class="form-group">  
                                                    <h4>Deseja realemente apagar a Noticia: <?php echo "<b>{$not_titulo}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                    <input type="hidden" class="form-control" id="user" name="not_id" value="<?php echo $not_id; ?>">
                                                </div>                 

                                                <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $not_id; ?>">Apagar Dados</button>
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
                        KLErro("Não exidte Noticias Cadastrado no momento!", KL_ALERT);
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
