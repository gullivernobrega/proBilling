<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Tronco SIP <small>Listagem</small></h1>
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
                    $read->ExeRead("troncosip", "ORDER BY sip_nome ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $verifica = $read->getRowCount();
                    $obj = $read->getResult();

                    //RESULTADO DA JANELA MODAL
                    $dataDel = filter_input(INPUT_POST, "sip_id", FILTER_VALIDATE_INT);
                    if (!empty($dataDel)):

                        $Deletar = new Sip;
                        $Deletar->ExeDelete($dataDel);

                        if ($Deletar->getResult()):
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                            
                            //Remonta o arquivo .conf
                            $geralConf = new Sip;
                            $geralConf->ExeConfGeral();
                            if ($geralConf->getResult()):
                                //Reloada no asterisk
                                shell_exec("sudoa sterisk -rx 'reload'");                                
                                //Redireciona
                                header("Location: painel.php?exe=gerenciamento/tronco/sip/lista");
                            endif;
                            
                        else:
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                        endif;
                    endif;
                    ?>
                    <!--well botão-->
                    <div class="well text-right">
                        <a class="btn btn-success" href="painel.php?exe=gerenciamento/tronco/sip/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Tronco SIP</a>
                        <a class="voltar" href="painel.php" role="button" title="Voltar"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    
<!--                    `sip_id`, `sip_nome`, `sip_username`, `sip_fromuser`, `sip_senha`, `sip_callerid`, `sip_host`, `sip_fromdomain`, 
                    `sip_dtmf_mold`, `sip_directmedia`, `sip_nat`, `sip_insecure`, `sip_codec1`, `sip_codec2`, `sip_codec3`, `sip_qualifily`-->
                    
                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>                            
                                <th width="6%">Nome</th>                                                        
                                <th width="6%">Usuário</th>                                                                                                                                               
                                <th width="8%">Callerid</th> 
                                <th>Host</th> 
                                <th>DTMF Mold</th>                            
                                <th>NAT</th>                            
                                <th>Codec 1</th>                             
                                <th>Codec 2</th>                             
                                <th>Codec 3</th>          
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            if ($verifica > 0):

                                foreach ($obj as $sip):
                                    extract($sip);
                                    ?>
                                    <tr>
                                        <td scope="row"><?php echo $sip_nome; ?></td> 
                                        <td scope="row"><?php echo $sip_username; ?></td> 
                                        <td scope="row"><?php echo $sip_callerid; ?></td> 
                                        <td scope="row"><?php echo $sip_host; ?></td>                                      
                                        <td><?php echo $sip_dtmf_mold; ?></td>                                     
                                        <td><?php echo $sip_nat; ?></td>                                     
                                        <td><?php echo $sip_codec1; ?></td> 
                                        <td><?php echo $sip_codec2; ?></td> 
                                        <td><?php echo $sip_codec3; ?></td> 
                                        <td>
                                            <a href="painel.php?exe=gerenciamento/tronco/sip/update&sip_id=<?php echo $sip_id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                            <a href="" data-toggle="modal" data-target="#sip_<?php echo $sip_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>                                    
                                        </td> 
                                    </tr>

                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="sip_<?php echo $sip_id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Dados </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                    <div class="form-group">  
                                                        <h4>Deseja realemente apagar o tronco sip : <?php echo "<b>{$sip_numero}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                        <input type="hidden" class="form-control" id="sip" name="sip_id" value="<?php echo $sip_id; ?>">
                                                    </div>                 

                                                    <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $sip_id; ?>">Apagar Dados</button>
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
                            KLErro("Não exidte Troncos Cadastrado no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                    <div class="well corWell text-center">                     
                        <?php
                        $pager->ExePaginator("troncosip");
                        echo $pager->getPaginator();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>