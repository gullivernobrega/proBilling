<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Busca Resposta SMS <small>Listagem</small></h1>
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
                    $busca = filter_input_array(INPUT_GET, FILTER_DEFAULT); 
                    if (!empty($busca['exe'])):                        
                        unset($busca['exe']);

                        //Duas datas
                        if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['numero'])):
                            $link = "?exe=relatorio/sms/respostas/busca&di={$busca['di']}&df={$busca['df']}&pg=";
                            $retorno = "?exe=relatorio/sms/respostas/busca&di={$busca['di']}&df={$busca['df']}&pg=";
                            $search = "?di={$busca['di']}&df={$busca['df']}";
                        //Duas datas e o numero    
                        elseif (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['numero'])):
                            $link = "?exe=relatorio/sms/respostas/busca&di={$busca['di']}&df={$busca['df']}&numero={$busca['numero']}&pg=";
                            $retorno = "?exe=relatorio/sms/respostas/busca&di={$busca['di']}&df={$busca['df']}&numero={$busca['numero']}&pg=";
                            $search = "?di={$busca['di']}&df={$busca['df']}&numero={$busca['numero']}";                        
                        else:
                        endif;                       
                        
                    endif;
                    
                    /** Muda o estado do botao de visualização */                    
                    $acao = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($acao['btAcao'])):
                        unset($acao['btAcao']);
                        
                            $Dados['acao'] = $acao['acao'];
                            $lido = new Update;
                            $lido->ExeUpdate("rest_sms", $Dados, "WHERE id = :ID", "ID={$acao['id']}");
                            $resultado = $lido->getResult();
                            if($resultado):
                                header("location: $retorno");
                            endif;

                    endif;

                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                    $pager = new Pager($link);
                    $pager->ExePager($getPage, 20);

                    $campos = "id, sms_cus_id, sms_acc_id, origem, resposta, data_recebimento, acao";

                    //LEITURA DOS DADOS
                    $read = new Select;
                    // Duas datas 
                    if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['numero'])):
                        $read->ExeSelect("rest_sms", $campos, "WHERE data_recebimento >= '{$busca['di']}' AND data_recebimento <= '{$busca['df']}' ORDER BY data_recebimento LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE data_recebimento >= '{$busca['di']}' AND data_recebimento <= '{$busca['df']}'";
                    endif;

                    //Todos os Campos
                    if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['numero'])):
                        $read->ExeSelect("rest_sms", $campos, "WHERE data_recebimento >= '{$busca['di']}' AND data_recebimento <= '{$busca['df']}' AND origem = {$busca['numero']} ORDER BY data_recebimento LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE data_recebimento >= '{$busca['di']}' AND data_recebimento <= '{$busca['df']}' AND origem = {$busca['numero']}";
                    endif;
                    
                    ?>
                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Nova Busca</h3>    
                        <a class="btn btn-warning nb" href="?exe=relatorio/sms/respostas/lista" title="Nova Busca" data-toggle="tooltip" data-placement="top">Realizar uma Nova Busca</a>
                    </div>

                    <!--well botão-->
                    <div class="well text-right">                                                  
                        <a class="pull-left" href="system/relatorio/sms/respostas/busca_rest_sms_excel.php<?php echo $search; ?>" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>                                
                        <a class="pull-left" href="system/relatorio/sms/respostas/busca_rest_sms_pdf.php<?php echo $search; ?>" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>
                        <!--<a class="btn btn-success" href="painel.php?exe=gerenciamento/ramal/iax/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Ramal IAX</a>-->
                        <a class="voltar" href="painel.php" role="button" title="Voltar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th>Id cus / acc</th>                                                        
                                <th>Data</th>                                                                                                                         
                                <th>Origem</th>
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            if (!empty($read->getResult())):
                                echo "<h4>Total de registros encontrados: <b>{$read->getRowCount()}</b></h4>";

                                //var_dump($read->getRowCount());
                                foreach ($read->getResult() as $rest_sms):

                                    extract($rest_sms);
                                    $IP = "$_SERVER[SERVER_ADDR]";

                                    ?>
                                    <tr>
                                        <td scope="row"><?php echo "{$sms_cus_id} / {$sms_acc_id}";?></td> 
                                        <td scope="row"><?php echo $data_recebimento;?></td>                                        
                                        <td><?php echo $origem; ?></td> 
                                        <!--<td><?php //echo $status; ?></td>--> 
                                        <td class="text-center">
                                            
                                             <?php
                                            if ($acao == 0):
                                                ?>                                            
                                                <a href="" data-toggle="modal" data-target="#restSms_<?php echo $id; ?>" data-placement="top" title="Ver Mensagem" class="del" id="btAcao"><i class="fa fa-eye size20" aria-hidden="true"></i></a> 
                                                <?php
                                            else:
                                                ?>
                                                <a href="" data-toggle="modal" data-target="#restSms_<?php echo $id; ?>" data-placement="top" title="Ver Mensagem lida" class="lido"><i class="fa fa-eye size20" aria-hidden="true"></i></a> 
                                            <?php
                                            endif;
                                            ?>                                   
                                        </td> 
                                    </tr>

                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="restSms_<?php echo $id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Mensagem Eviada </h4>
                                            </div>
                                            <div class="modal-body">                                                    
                                                <?php
                                                echo "<h4>{$resposta}</h4>";
                                                ?>
                                                <form method="post" action="" name="frmAcao">
                                                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                                                    <input type="hidden" name="acao" value="1">
                                                    <div class="text-right">
                                                        <button type="submit" class="btn btn-warning" name="btAcao" value="btAcao">Recebido</button> 
                                                    </div>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <!--<button type="button" class="btn btn-warning" data-dismiss="modal">Fechar</button>-->
                                                <!--        <button type="button" class="btn btn-primary">Apagar</button>-->
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                                <?php
                            endforeach;
                        else:
                            KLErro("Não existe SMS cadastradas no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                </div>

                <!--PAGINAÇÃO-->
                <div class="well corWell text-center">
                    <?php
                    $pager->ExePaginator("rest_sms", $campos, "{$termo}");
                    echo $pager->getPaginator();
                    ?>
                </div>

            </div><!--panel-body-->
        </div>
    </div>
</div>
