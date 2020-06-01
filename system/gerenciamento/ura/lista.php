<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">URA <small>Listagem</small></h1>
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
                    $pager = new Pager("?exe=gerenciamento/ura/lista&pg=");
                    $pager->ExePager($getPage, 20);

                    //LEITURA DOS DADOS
                    $read = new Read;
                    $read->ExeRead("ura", "ORDER BY ura_nome ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $verifica = $read->getRowCount();
                    $obj = $read->getResult();
                   

                    //RESULTADO DA JANELA MODAL
                    $dataDel = filter_input(INPUT_POST, "ura_id", FILTER_VALIDATE_INT);
                    if (!empty($dataDel)):

                        $Deletar = new Ura();
                        $Deletar->ExeDelete($dataDel);

                        if ($Deletar->getResult()):
                            header("Location: painel.php?exe=gerenciamento/ura/lista");
                        else:
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                        endif;
                    endif;
                    ?>
                    <!--well botão-->
                    <div class="well text-right">
                        <a class="btn btn-success" href="painel.php?exe=gerenciamento/ura/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Nova URA</a>                                                
                        <a class="voltar" href="painel.php" role="button" title="Voltar"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th>Nome</th>
                                <th>Áudio</th>                             
                                <th>Op-01</th>                             
                                <th>Op-02</th>                             
                                <th>Op-03</th>                             
                                <th>Op-04</th>                             
                                <th>Op-05</th>                             
                                <th>Time-out</th>                             
<!--                                <th width="5%">Status</th> -->
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            if ($verifica > 0):

                                foreach ($obj as $ura):
                                    extract($ura);
                                    ?>
                                    <tr>
                                        <td scope="row"><?php echo $ura_nome; ?></td>                                         
                                        <td scope="row"><?php echo $ura_audio; ?></td>                                         
                                        <td scope="row"><?php echo $op_1; ?></td>                                         
                                        <td scope="row"><?php echo $op_2; ?></td>                                         
                                        <td scope="row"><?php echo $op_3; ?></td>                                         
                                        <td scope="row"><?php echo $op_4; ?></td>                                         
                                        <td scope="row"><?php echo $op_5; ?></td>                                         
                                        <td scope="row"><?php echo $op_t; ?></td>                                         
                                        <td>
                                            <a href="painel.php?exe=gerenciamento/ura/update&ura_id=<?php echo $ura_id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                            <a href="" data-toggle="modal" data-target="#ura_<?php echo $ura_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>                                    
                                        </td> 
                                    </tr>

                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="ura_<?php echo $ura_id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Dados </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                    <div class="form-group">  
                                                        <h4>Deseja realmente apagar a URA: <?php echo "<b>{$ura_nome}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                        <input type="hidden" class="form-control" id="ura" name="ura_id" value="<?php echo $ura_id; ?>">
                                                    </div>                 

                                                    <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $ura_id; ?>">Apagar Dados</button>
                                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                                                </form>   

                                            </div>                                            
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->                               

                                <?php
                            endforeach;
                        else:
                            KLErro("Não existe URA Cadastrado no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                    <!--PAGINAÇÃO-->
                    <div class="well corWell text-center">                     
                        <?php
                        $pager->ExePaginator("ura");
                        echo $pager->getPaginator();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>