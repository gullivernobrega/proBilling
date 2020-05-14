<?php
include_once '../_app/Config.inc.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Kleber de Souza">

        <!--css-->
        <link href="../_app/Library/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/jquery.datetimepicker.min.css" rel="stylesheet">
        <!--<link href="css/jPages.css" rel="stylesheet">-->
        <link href="css/geralTorpedo.css" rel="stylesheet">

        <title>ProSearch</title>

    </head>
    <body>
        <!--CONTAINER-->
        <div class="container mgTop">
            <!--LOGO-->
            <div class="row">
                <div class="col-md-2">
                    <img class="img-responsive" src="images/logoAmi.png" title="AME Digital" alt="" >
                </div>                
            </div>

            <div class="page-header">
                <h1>Busca Extrato por Período <small>Listagem</small></h1>
            </div>  

            <div class="container">
                <div class="row">

                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h3 class="panel-title"><i class="fa fa-list"></i> Painel de ações </h3>
                        </div>
                        <div class="panel-body ">
                            <div id="shieldui-grid1">
                                <!--mensagem de erro-->
                                <?php
                                $busca = filter_input_array(INPUT_GET, FILTER_DEFAULT);

                                if (!empty($busca)):
                                    //unset($busca);
                                    //Duas datas
                                    if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                                        $link = "?di={$busca['di']}&df={$busca['df']}&pg=";
                                        //$link = "?exe=ativos/busca&di={$busca['di']}&df={$busca['df']}&pg=";
                                        $search = "?di={$busca['di']}&df={$busca['df']}";
                                    //Duas datas e o numero    
                                    elseif (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                                        $link = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&pg=";
                                        $search = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}";
                                    //Duas datas e o numero e o tipo            
                                    elseif (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                                        $link = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&tipo={$busca['tipo']}&pg=";
                                        $search = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&tipo={$busca['tipo']}";
                                    //Duas datas o numero e o disposition      
                                    elseif (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                                        $link = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&disposition={$busca['disposition']}&pg=";
                                        $search = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&disposition={$busca['disposition']}";
                                    //Duas datas e todos    
                                    elseif (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                                        $link = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}&pg=";
                                        $search = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}";
                                    //Duas datas e o tipo    
                                    elseif (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                                        $link = "?di={$busca['di']}&df={$busca['df']}&tipo={$busca['tipo']}&pg=";
                                        $search = "?di={$busca['di']}&df={$busca['df']}&tipo={$busca['tipo']} ";
                                    //Duas datas o tipo e o disposition    
                                    elseif (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                                        $link = "?di={$busca['di']}&df={$busca['df']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}&pg=";
                                        $search = "?di={$busca['di']}&df={$busca['df']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}";
                                    //Duas datas e o disposition    
                                    elseif (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                                        $link = "?di={$busca['di']}&df={$busca['df']}&disposition={$busca['disposition']}&pg=";
                                        $search = "?di={$busca['di']}&df={$busca['df']}&disposition={$busca['disposition']}";
                                    else:
                                    endif;

                                endif;

                                /** PAGINAÇÃO */
                                $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                                $pager = new Pager($link);
                                $pager->ExePager($getPage, 20);
                                //LEITURA DOS DADOS
                                $read = new Read;
                                // Duas datas 
                                if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                                    $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND src >= '5000' AND src <= '5099' OR src = '0005' AND tipo <> '' ORDER BY calldate ");
//                                    $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND src >= '5000' AND src <= '5099' OR src = '0005' AND tipo <> '' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                                    $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND src >= '5000' AND src <= '5099' OR src = '0005' AND tipo <> '' ";
                                endif;

                                //Todos os Campos
                                if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                                    $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' AND src >= '5000' AND src <= '5099' OR src = '0005' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                                    $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' AND src >= '5000' AND src <= '5099' OR src = '0005'";
                                endif;

                                //As duas datas e o numero 
                                if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                                    $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND tipo <> '' AND src >= '5000' AND src <= '5099' OR src = '0005' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                                    $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND tipo <> '' AND src >= '5000' AND src <= '5099' OR src = '0005' ";
                                endif;

                                //As duas datas o numero eo tipo
                                if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                                    $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND tipo = '{$busca['tipo']}' AND src >= '5000' AND src <= '5099' OR src = '0005' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                                    $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND tipo = '{$busca['tipo']}' AND src >= '5000' AND src <= '5099' OR src = '0005'";
                                endif;

                                // Duas datas o numero e o disposition
                                if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                                    $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND disposition = '{$busca['disposition']}' AND tipo <> '' AND src >= '5000' AND src <= '5099' OR src = '0005' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                                    $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND {$busca['num']} AND disposition = '{$busca['disposition']}' AND tipo <> '' AND src >= '5000' AND src <= '5099' OR src = '0005' ";
                                endif;

                                //Duas datas e o tipo 
                                if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                                    $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo = '{$busca['tipo']}' AND src >= '5000' AND src <= '5099' OR src = '0005' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                                    $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo = '{$busca['tipo']}' AND src >= '5000' AND src <= '5099' OR src = '0005'";
                                endif;

                                //Duas datas o tipo e o disposition
                                if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                                    $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' AND src >= '5000' AND src <= '5099' OR src = '0005' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                                    $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' AND src >= '5000' AND src <= '5099' OR src = '0005'";
                                endif;

                                //As duas datas e o disposition
                                if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                                    $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND src >= '5000' AND src <= '5099' OR src = '0005' AND disposition = '{$busca['disposition']}' AND tipo <> '' ORDER BY calldate LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                                    $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND src >= '5000' AND src <= '5099' OR src = '0005' AND disposition = '{$busca['disposition']}' AND tipo <> '' ";
                                endif;
                                ?>
                                <!--BASE PESQUISA-->  
                                <div class="well seach">
                                    <h3>Nova Busca</h3>    
                                    <a class="btn btn-warning nb" href="index.php" title="Nova Busca" data-toggle="tooltip" data-placement="top">Realizar uma Nova Busca</a>
                                </div>

                                <!--well botão-->
                                <!-- <div class="well text-right">                                                  
                                    <a class="pull-left" href="system/relatorio/extrato/periodo/busca_excel.php<?php //echo $search;      ?>" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>                                
                                    <a class="pull-left" href="system/relatorio/extrato/periodo/busca_pdf.php<?php //echo $search;      ?>" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>
                                    <a class="btn btn-success" href="painel.php?exe=gerenciamento/ramal/iax/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Ramal IAX</a>
                                    <a class="voltar" href="painel.php" role="button" title="Voltar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                                </div>-->

                                <!--tabela de listagem-->
                                <table class="table table-responsive table-hover hover-color"> 
                                    <thead> 
                                        <tr>   
                                            <th>Data</th>                                                        
                                            <th>Origem</th>                                             
                                            <th>Destino</th>                             
                                            <th>Tipo</th>                             
                                            <th>Duração</th>       
                                            <th>Status</th> 
                                            <th width="7%">Ações</th> 
                                        </tr> 
                                    </thead> 
                                    <tbody > 
                                        <?php
                                        if (!empty($read->getResult())):
                                            echo "<h4>Total de registros encontrados: <b>{$read->getRowCount()}</b></h4>";

                                            //var_dump($read->getRowCount());
                                            foreach ($read->getResult() as $cdr):
                                                extract($cdr);
                                                $IP = "$_SERVER[SERVER_ADDR]";
                                                $data = explode(" ", $calldate);
                                                $dt = explode("-", $data[0]);
                                                $dataAtual = "$dt[2]-$dt[1]-$dt[0]";
                                                $link1 = "http://{$IP}/gravacoes/{$dataAtual}/{$userfield}.wav";
                                                $status = ($disposition == 'ANSWERED' ? "Atendida" : ($disposition == 'BUSY' ? "Ocupado" : ($disposition == 'NO ANSWER' ? "Não atendida" : ($disposition == 'FAILED' ? "Falha" : null))));
                                                ?>
                                                <tr>
                                                    <td scope="row"><?php echo $calldate; ?></td> 
                                                    <td scope="row"><?php echo $src; ?></td> 
                                                    <td scope="row"><?php echo $dst; ?></td>
                                                    <td><?php echo $tipo; ?></td> 
                                                    <td><?php echo gmdate("H:i:s", $billsec); ?></td> 
                                                    <td><?php echo $status; ?></td> 
                                                    <td>
                                                        <a href="<?php echo $link1 ?>" target="blank"> <span class="glyphicon glyphicon-volume-up" aria-hidden="true"></span> </a>
                                                        <!--<a href="painel.php?exe=gerenciamento/ramal/iax/update&iax_id=<?php //echo $iax_id                         ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>-->
                                                        <!--<a href="" data-toggle="modal" data-target="#iax_<?php //echo $iax_id;                         ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>-->                                    
                                                    </td> 
                                                </tr>

                                                <!-- JANELA MODAL -->                
                                            <div class="modal fade" tabindex="-1" role="dialog" id="iax_<?php echo $iax_id; ?>">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                            <h4 class="modal-title">Apagar Dados </h4>
                                                        </div>
                                                        <div class="modal-body">

                                                            <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                                <div class="form-group">  
                                                                    <h4>Deseja realemente apagar o ramal Iax : <?php echo "<b>{$iax_numero}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                                    <input type="hidden" class="form-control" id="iax" name="iax_id" value="<?php echo $iax_id; ?>">
                                                                </div>                 

                                                                <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $iax_id; ?>">Apagar Dados</button>
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
                                        KLErro("Não exidte resuldados no momento!", KL_ALERT);
                                    endif;
                                    ?>   
                                    </tbody> 
                                </table>
                                <!--fim tabela-->
                            </div>

                            <!--PAGINAÇÃO-->
                            <div class="well corWell text-center">                     
                                <?php
                                $pager->ExePaginator("cdr", "{$termo}");
                                echo $pager->getPaginator();
                                ?>
                            </div>

                        </div><!--panel-body-->
                    </div>
                </div><!--fim row-->      
                <div class="pull-right">AMÉRICO ADVOGADOS 2016 - Todos os Direitos Reservados</div>
            </div><!--fim container-->


        </div>

        <!--<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>--> 
        <!--JS-->
        <script src="../_cdn/jquery.js"></script>
        <script src="../_cdn/MaskedInput.js"></script>
        <script src="js/jquery.datetimepicker.full.min.js"></script>
        <!--<script src="js/jPages.min.js"></script>-->
        <script src="js/geralDataTimePicker.js"></script>
    </body>
</html>