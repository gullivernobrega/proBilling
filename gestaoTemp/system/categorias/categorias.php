<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
<div class="top">
    <h1 class="tit">Categorias e Subcategorias <small>Listagem</small></h1>
</div>       
<!--</div>-->

<!--<div class="row">-->
<div class="container-fluid">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> Painel de ações </h3>
        </div>
        <div class="panel-body">
            <div id="shieldui-grid1">
                <!--mensagem de erro-->
                <?php
                //LEITURA DAS CATEGORIAS
                $read = new read;
                $read->ExeRead("kl_categorias");
                $verifica = $read->getRowCount();
                $obj = $read->getResult();
                ?>
                <!--well botão-->
                <div class="well text-right">
                    <a class="btn btn-success" href="painel.php?exe=categorias/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Nova Categoria / Subcategoria</a>
                    <a class="voltar" href="painel.php" role="button" title="Voltar"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                </div>

                <!--tabela de listagem-->
                <table class="table table-responsive table-hover hover-color"> 
                    <thead> 
                        <tr> 
                            <th width="5%">#</th> 
                            <th>Categoria / SubCategoria</th> 
                            <th>Categoria Parente</th>                              
                            <th width="20%">Data Cadastro</th> 
                            <th width="6%">Ordem</th>
                            <th width="6%">Status</th>                             
                            <th width="7%">Ações</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        <?php
                        if ($verifica > 0):

                            foreach ($obj as $us):
                                extract($us);
                                ?>
                                <tr>
                                    <td scope="row"><?php echo $categoria_id;?></td> 
                                    <td><?php echo $categoria_nome;?></td> 
                                    <td><?php echo $categoria_parente;?></td>                                     
                                    <td><?php echo $categoria_data; ?></td> 
                                    <td><?php echo $categoria_ordem; ?></td> 
                                    <td class="txtCenter">
                                        <?php
                                        if ($categoria_status == "S"):
                                            echo ' <span class="glyphicon glyphicon-ok txtVerde " aria-hidden="true"  title="Ativo"></span>';
                                        else:
                                            echo '<span class="glyphicon glyphicon-info-sign txtRed" aria-hidden="true" title="Inativo"></span>';
                                        endif;
                                        ?>
                                    </td>   

                                    <td>
                                        <a href="painel.php?exe=categorias/update&categoria_id=<?php echo $categoria_id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                        <a href="" data-toggle="modal" data-target="#categoria_<?php echo $categoria_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>                                    
                                    </td> 
                                </tr>

                            <!-- JANELA MODAL -->                
                            <div class="modal fade" tabindex="-1" role="dialog" id="usuario_<?php echo $categoria_id; ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Apagar Dados </h4>
                                        </div>
                                        <div class="modal-body">

                                            <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                <div class="form-group">  
                                                    <h4>Deseja realemente apagar a categoria : <?php echo "<b>{$categoria_nome}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                    <input type="hidden" class="form-control" id="user" name="user_id" value="<?php echo $categoria_id; ?>">
                                                </div>                 

                                                <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $categoria_id; ?>">Apagar Dados</button>
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
</div>
</div>