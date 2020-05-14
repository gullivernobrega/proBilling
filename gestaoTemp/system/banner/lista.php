<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Banners <small>Listagem</small></h1>
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
                $read->ExeRead("kl_banner");
                $verifica = $read->getRowCount();
                $obj = $read->getResult();

                //RESULTADO DA JANELA MODAL
                $dataDel = filter_input(INPUT_POST, "ban_id", FILTER_VALIDATE_INT);
                if (!empty($dataDel)):
                    $Deletar = new Banner;
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
                    <a class="btn btn-success" href="painel.php?exe=banner/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Banner</a>
                    <a class="voltar" href="painel.php" role="button" title="Voltar"><i class="fa fa-share"></i> Voltar</a>
                </div>

                <!--tabela de listagem-->
                <table class="table table-responsive table-hover hover-color"> 
                    <thead> 
                        <tr> 
                            <th width="5%">#</th> 
                            <th>Imagem</th> 
                            <th>Titulo</th>                              
                            <th>Link</th>
                            <th width="6%">Ordem</th> 
                            <th width="6%">Posição</th>                            
                            <th width="6%">Status</th>                             
                            <th width="6%">Ações</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        <?php
                        if ($verifica > 0):
                            $dir = INCLUDE_PATH . "/images";
                            foreach ($obj as $ban):
                                extract($ban);
                                ?>
                                <tr>
                                    <td scope="row"><?php echo $ban_id; ?></td> 
                                    <td>
                                        <?php
                                        if (!empty($ban_image)):
                                            echo "<img src=\"{$dir}/{$ban_image}\" width=\"50\">";
                                        else:
                                            echo "<img src=\"{$dir}/semIco.jpg\" width=\"50\">";
                                        endif;
                                        ?>
                                    </td> 
                                    <td><?php echo $ban_titulo; ?></td>                                     
                                    <td><?php echo $ban_link; ?></td> 
                                    <td><?php echo $ban_ordem; ?></td> 
                                    <td><?php echo $ban_posicao; ?></td> 
                                    <td class="txtCenter">
                                        <?php
                                        if ($ban_status == "S"):
                                            echo ' <span class="glyphicon glyphicon-ok txtVerde " aria-hidden="true"  title="Ativo"></span>';
                                        else:
                                            echo '<span class="glyphicon glyphicon-info-sign txtRed" aria-hidden="true" title="Inativo"></span>';
                                        endif;
                                        ?>
                                    </td>   

                                    <td>
                                        <a href="painel.php?exe=banner/update&ban_id=<?php echo $ban_id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                        <a href="" data-toggle="modal" data-target="#banner_<?php echo $ban_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>                                    
                                    </td> 
                                </tr>

                                <!-- JANELA MODAL -->                
                            <div class="modal fade" tabindex="-1" role="dialog" id="banner_<?php echo $ban_id; ?>">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title">Apagar Dados </h4>
                                        </div>
                                        <div class="modal-body">

                                            <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                <div class="form-group">  
                                                    <h4>Deseja realemente apagar o Banner : <?php echo "<b>{$ban_titulo}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                    <input type="hidden" class="form-control" id="user" name="ban_id" value="<?php echo $ban_id; ?>">
                                                </div>                 

                                                <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $ban_id; ?>">Apagar Dados</button>
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
                        KLErro("Não existe Banner Cadastrado no momento!", KL_ALERT);
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