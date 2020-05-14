<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Filas (queues) <small>Listagem</small></h1>
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
                    $pager = new Pager("?exe=gerenciamento/filas/lista&pg=");
                    $pager->ExePager($getPage, 20);

                    //LEITURA DOS QUEUES
                    $read = new Read;
                    $read->ExeRead("queues", "ORDER BY queue_name ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $verifica = $read->getRowCount();

                    //RESULTADO DA JANELA MODAL
                    //$dataDel = filter_input(INPUT_POST, "tronco_id", FILTER_VALIDATE_INT);
                    $dataDel = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($dataDel)):

                        $Deletar = new Queues();
                        $Deletar->ExeDelete($dataDel['queue_id']);

                        if ($Deletar->getResult()):
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);

                            //Remonta o arquivo .conf
                            $geralConf = new Queues;
                            $geralConf->ExeConfGeral();
                            if ($geralConf->getResult()):
                                //Reloada no asterisk
                                shell_exec("sudo asterisk -rx 'reload'");
                                //Redireciona
                                header("Location: painel.php?exe=gerenciamento/filas/lista");
                            endif;

                        else:
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                        endif;

                    endif;
                    ?>
                    <!--well botão-->
                    <div class="well text-right">                        
                        <a class="btn btn-success" href="painel.php?exe=gerenciamento/filas/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Nova Fila (Queues)</a>
                        <a class="voltar" href="painel.php" role="button" title="Voltar"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--tabela de listagem-->                    
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <!--name-->
                                <th>Nome</th>
                                <!--strategy-->
                                <th>Estratégia de distribuição das chamadas</th>                                                        
                                <!--Ringinuse-->
                                <th>Chamar menbro da fila que estiver em ligação</th>                                                        
                                <!--Timeout-->
                                <th>Tempo de toque em cada agente</th>                                 
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            if ($verifica > 0):

                                foreach ($read->getResult() as $fila):
                                    extract($fila);
                                    ?>
                                    <tr>
                                        <td ><?php echo $queue_name; ?></td> 
                                        <td ><?php echo $queue_strategy; ?></td> 
                                        <td ><?php echo ($queue_ringinuse == "S") ? "Sim" : "Não"; ?></td> 
                                        <td><?php echo $queue_timeout; ?></td> 
                                        <td>                                           
                                            <a href="painel.php?exe=gerenciamento/filas/update&queue_id=<?php echo $queue_id ?>" data-toggle="tooltip" data-placement="top" title="Editar Queue"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>                                                                               
                                            <a href="" data-toggle="modal" data-target="#queue_<?php echo $queue_id; ?>" data-placement="top" title="Apagar Queue" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a> 
                                        </td> 
                                    </tr>

                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="queue_<?php echo $queue_id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Dados </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                    <div class="form-group">  
                                                        <h4>Deseja realemente apagar o queue: <?php echo "<b>{$queue_name}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                        <input type="hidden" class="form-control" id="tronco1" name="queue_id" value="<?php echo $queue_id; ?>">                                                        
                                                    </div>                 

                                                    <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $queue_id; ?>">Apagar Dados</button>
                                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                                                </form>   

                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                                <?php
                            endforeach;
                        else:
                            KLErro("Não existe Queues Cadastrado no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                    <!--PAGINAÇÃO-->
                    <div class="well corWell text-center">                     
                        <?php
                        $pager->ExePaginator("queues");
                        echo $pager->getPaginator();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>