<?php
extract($_SESSION['userlogin']);
$_ID = $user_id;
?>
<div class="page-header">
    <h1>Usuários <small>Listagem</small></h1>
</div>       
<!--</div>-->

<!--<div class="row">-->
<div class="container-fluid">

    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-users"></i> Painel de ações </h3>
        </div>
        <div class="panel-body">
            <div id="shieldui-grid1">
                <!--mensagem de erro-->
                <?php
                $res = filter_input(INPUT_GET, 'result', FILTER_DEFAULT);
                if ($res):
                    KLErro("Alteraçãoes realizado com sucesso!", KL_INFOR);
                //header("refresh: 5; painel.php?exe=users/users");                    
                endif;
                
                
                $id_del = filter_input(INPUT_POST, 'user_id', FILTER_VALIDATE_INT);
                
                if ($id_del):

                    if ($id_del != $_ID):
                        $apagar = new Usuario();
                        $apagar->ExeDelete($id_del);

                        if (!$apagar->getResultado()):
                            $erro = $apagar->getErro();
                            KLErro($erro['0'], $erro['1']);
                        else:
                            header("Location: painel.php?exe=users/users");
                        endif;
                    else:
                        KLErro("Este usuário não pode ser apagado!", KL_ALERT);
                    endif;

                endif;
                ?>
                <!--well botão-->
                <div class="well text-right">
                    <a class="btn btn-success" href="painel.php?exe=users/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Usuário</a>
                    <a class="voltar" href="painel.php" role="button" title="Voltar"><i class="fa fa-share"></i> Voltar</a>
                </div>

                <!--tabela de listagem-->
                <table class="table table-responsive table-hover hover-color"> 
                    <thead> 
                        <tr> 
                            <th width="5%">#</th> 
                            <th>Nome</th> 
                            <th>E-mail</th> 
                            <th width="20%">Login</th> 
                            <th width="20%">Data Cadastro</th> 
                            <th width="6%">Status</th>                             
                            <th width="6%">Ações</th> 
                        </tr> 
                    </thead> 
                    <tbody> 
                        <?php
                        $read = new read;
                        $read->ExeRead("kl_users");
                        $obj = $read->getResult();
                        foreach ($obj as $us):
                            extract($us);
                            ?>
                            <tr>
                                <td scope="row"><?php echo $user_id; ?></td> 
                                <td><?php echo $user_nome; ?></td> 
                                <td><?php echo $user_email; ?></td> 
                                <td><?php echo $user_login; ?></td> 
                                <td><?php echo $user_registrado; ?></td> 
                                <td class="txtCenter">
                                    <?php
                                    if ($user_status == "S"):
                                        echo ' <span class="glyphicon glyphicon-ok txtVerde " aria-hidden="true"  title="Ativo"></span>';
                                    else:
                                        echo '<span class="glyphicon glyphicon-info-sign txtRed" aria-hidden="true" title="Inativo"></span>';
                                    endif;
                                    ?>
                                </td>   

                                <td>
                                    <a href="painel.php?exe=users/update&user_id=<?php echo $user_id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><i class="fa fa-edit size20"></i></a>
                                    <a href="" data-toggle="modal" data-target="#usuario_<?php echo $user_id; ?>" data-placement="top" title="Apagar" class="del"><i class="fa fa-remove size20"></i></a>                                    
                                </td> 
                            </tr>

                            <!-- JANELA MODAL -->                
                        <div class="modal fade" tabindex="-1" role="dialog" id="usuario_<?php echo $user_id; ?>">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title">Apagar Dados </h4>
                                    </div>
                                    <div class="modal-body">

                                        <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                            <div class="form-group">  
                                                <h4>Deseja realemente apagar o usuário: <?php echo "<b>{$user_nome}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                <input type="hidden" class="form-control" id="user" name="user_id" value="<?php echo $user_id; ?>">
                                            </div>                 

                                            <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $user_id; ?>">Apagar Dados</button>
                                        </form>   

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                                        <!--        <button type="button" class="btn btn-primary">Apagar</button>-->
                                    </div>
                                </div><!-- /.modal-content -->
                            </div><!-- /.modal-dialog -->
                        </div><!-- /.modal -->

                    <?php endforeach; ?>   
                    </tbody> 
                </table>
                <!--fim tabela-->
            </div>
        </div>
    </div>
</div>