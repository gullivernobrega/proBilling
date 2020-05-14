<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Audios <small>Listagem</small></h1>
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
                    $read->ExeRead("audio");
                    //$read->ExeRead("rotas", "ORDER BY iax_nome ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
//                    $verifica = $read->getRowCount();
//                    $obj = $read->getResult();

                    //RESULTADO DA JANELA MODAL
                    $dataDel = filter_input(INPUT_POST, "audio_id", FILTER_VALIDATE_INT);
                    if (!empty($dataDel)):
                        
                        $Deletar = new Audio;
                        $Deletar->ExeDelete($dataDel);

                        if ($Deletar->getResult()):
                            header("Location: painel.php?exe=gerenciamento/audios/lista");
                        else:
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                        endif;
                    endif;
                    
                    //Autera status do audio
                    $id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
                    $status = filter_input(INPUT_GET, "status", FILTER_DEFAULT);
                    if (!empty($id) && !empty($status)):
                        $Data['audio_status'] = $status;
       
                        $update = new Audio();
                        $update->ExeUpdate($id, $Data);
                        if (!$update->getResult()):
                            $erro = $update->getErro();
                            KLErro($erro[0], $erro[1]);                            
                        endif;
                    endif;
                    ?>
                    <!--well botão-->
                    <div class="well text-right">
                        <a class="btn btn-success" href="painel.php?exe=gerenciamento/audios/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Audio</a>
                        <a class="voltar" href="painel.php" role="button" title="Voltar"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--tabela de listagem--> 
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th >Nome</th>                                                        
                                <th >Audio Gsm</th> 
                                <th width="6%">Status</th> 
                                <!--<th>Audio</th>--> 
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            if ($read->getRowCount() > 0):
                                foreach ($read->getResult() as $audio):
                                    extract($audio);
                                    ?>
                                    <tr>                                        
                                        <td scope="row"><?php echo $audio_nome; ?></td> 
                                        <td scope="row"><?php echo $audio_arquivo; ?></td> 
                                        <td scope="row">

                                            <?php
                                            //updateEstado
                                            if ($audio_status == "S"):
                                                $status = "N";
                                                echo "<a href='painel.php?exe=gerenciamento/audios/lista&id={$audio_id}&status={$status}' data-toggle='tooltip' data-placement='top' title='Desativar'><span class='glyphicon glyphicon-ok txtVerde' aria-hidden='true'></span></a>";
                                            else:
                                                $status = "S";
                                                echo "<a href='painel.php?exe=gerenciamento/audios/lista&id={$audio_id}&status={$status}' data-toggle='tooltip' data-placement='top' title='Ativar'><span class='glyphicon glyphicon-remove-sign txtRed' aria-hidden='true'></span></a>";
                                            endif;
                                            ?>

                                        </td>                                         

                                        <td>
                                            <!--<a href="painel.php?exe=gerenciamento/audio/update&audio_id=<?php //echo $audio_id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>-->                                            
                                            <a href="" data-toggle="modal" data-target="#audio_<?php echo $audio_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>                                    
                                        </td> 
                                    </tr>

                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="audio_<?php echo $audio_id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Dados </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                    <div class="form-group">  
                                                        <h4>Deseja realemente apagar este Audio: <?php echo "<b>{$audio_nome}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                        <input type="hidden" class="form-control" id="audio" name="audio_id" value="<?php echo $audio_id; ?>">
                                                    </div>                 

                                                    <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $audio_id; ?>">Apagar Dados</button>
                                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                                                </form>   

                                            </div>

                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                                <?php
                            endforeach;
                        else:
                            KLErro("Não existe Audio Cadastrado no momento!", KL_ALERT);
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