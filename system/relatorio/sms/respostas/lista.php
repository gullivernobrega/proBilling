<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">SMS Resposta <small>Listagem</small></h1>
    </div>       

    <div class="container-fluid">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> Painel de ações </h3>
            </div>
            <div class="panel-body txtblue">
                <div id="shieldui-grid1">                    
                    <?php
                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                    $pager = new Pager("?exe=relatorio/sms/respostas/lista&pg=");
                    $pager->ExePager($getPage, 20);

                    //LEITURA DOS DADOS  
                    $dt = date("Y-m-d");
                    //$dt = date("2019-07-12");
                    $dataIni = "{$dt} 00:00:01";
                    $datafinal = "{$dt} 23:59:59";

                    $campos = "id, sms_cus_id, sms_acc_id, origem, resposta, data_recebimento, acao";

                    $read = new Select;
                    //$read->ExeSelect("cdr", $campos ,"WHERE calldate >= '{$dataIni}' AND calldate <= '{$datafinal}' AND tipo <> '' ORDER BY calldate ASC");
                    $read->ExeSelect("rest_sms", $campos, "WHERE data_recebimento >= '{$dataIni}' AND data_recebimento <= '{$datafinal}' ORDER BY data_recebimento ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $verifica = $read->getRowCount();

                    //RESULTADO DA PESQUISA
                    $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($busca['btnBusca'])):
                        unset($busca['btnBusca']);

                        if (!empty($busca['dataInicio']) && !empty($busca['dataFim']) || !empty($busca['origem'])):

                            header("Location: ?exe=relatorio/sms/respostas/busca&di={$busca['dataInicio']}&df={$busca['dataFim']}&numero={$busca['origem']}");
                            exit();

                        else:

                            KLErro("Ops, Falta parametros para a pesquisa", KL_INFOR);

                        endif;

                    endif;

                    //Muda o estado do botao de visualização
                    $acao = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($acao['btAcao'])):
                        unset($acao['btAcao']);

                            $Dados['acao'] = $acao['acao'];
                            $lido = new Update;
                            $lido->ExeUpdate("rest_sms", $Dados, "WHERE id = :ID", "ID={$acao['id']}");
                            $resultado = $lido->getResult();
                            if($resultado):
                                header("location: ?exe=relatorio/sms/respostas/lista");
                            endif;

                    endif;
                    ?>
                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Busca</h3>    
                        <form class="form-inline" action=""  method="post" name="frmPesquisa" id="frmPesquisa" >

                            <div class="form-group form-group-sm">                                
                                <label for="dataInicio">Data inicio </label>
                                <input 
                                    class="form-control" 
                                    name="dataInicio" 
                                    id="datetimeIni" 
                                    type="text" 
                                    placeholder="data inicio" 
                                    value="<?php
                                    if (isset($busca['dataInicio'])): echo $busca['dataInicio'];
                                    endif;
                                    ?>" 
                                    required
                                    > 

                                <label>Data final </label>
                                <input 
                                    class="form-control" 
                                    name="dataFim" 
                                    id="datetimeFim" 
                                    type="text" 
                                    placeholder="data final" 
                                    value="<?php
                                    if (isset($busca['dataFim'])): echo $busca['dataFim'];
                                    endif;
                                    ?>" 
                                    required
                                    >                                 
                            </div>

                            <!--*******-->
                            <div class="form-group form-group-sm">                                
                                <input 
                                    class="form-control" 
                                    name="origem" 
                                    id="origem" 
                                    type="text" 
                                    placeholder="Informe numero sms" 
                                    value="<?php
                                    if (isset($busca['origem'])): echo $busca['origem'];
                                    endif;
                                    ?>" >                                
                            </div>
                            <!--</button>-->                                                            
                            <button name="btnBusca" value="Buscar" type="submit" class="btn btn-info btn-sm" title="Buscar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-search" aria-hidden="true"></span></button>                         
                        </form>
                    </div>

                    <!--well botão-->
                    <div class="well text-right">                                                  
                        <a class="pull-left" href="system/relatorio/sms/respostas/relatorio_rest_sms_excel.php" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>                                
                        <a class="pull-left" href="system/relatorio/sms/respostas/relatorio_rest_sms_pdf.php" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>                        
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
                            ////`sms_cus_id`, `sms_acc_id`, `origem`, `resposta`, `data_recebimento`
                            if ($verifica > 0):
                                echo "<h4>Total de registros encontrados: <b>{$verifica}</b></h4>";
                                foreach ($read->getResult() as $rest):
                                    extract($rest);
                                    ?>                                    
                                    <tr>
                                        <td scope="row"><?php echo "{$sms_cus_id} / {$sms_acc_id}"; ?></td> 
                                        <td scope="row"><?php echo $data_recebimento; ?></td>
                                        <td><?php echo $origem; ?></td>
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
                                                <h4 class="modal-title">Mensagem Resposta </h4>
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
                                                <!--<button type="button" class="btn btn-info" data-dismiss="modal" id="btnClick">Fechar</button>-->                                                
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                                <?php
                            endforeach;
                        else:
                            KLErro("Não existe Sms Cadastrado no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                </div>

                <!--PAGINAÇÃO-->
                <div class="well corWell text-center"> 
                    <?php
                    $pager->ExePaginator("rest_sms", $campos, "WHERE data_recebimento >= '{$dataIni}' AND data_recebimento <= '{$datafinal}' ORDER BY data_recebimento ASC");
                    //$pager->ExePaginator("cdr", "WHERE calldate >= '{$dataAtual}' AMD tipo <> '' ");
                    //SELECT * FROM `cdr` WHERE `calldate` >= '2018-04-09 00:00:01' AND `calldate` <= '2018-04-09 23:59:59' AND `tipo` <> ''
                    echo $pager->getPaginator();
                    ?>
                </div>

            </div><!--panel-body-->
        </div>
    </div>
</div>
