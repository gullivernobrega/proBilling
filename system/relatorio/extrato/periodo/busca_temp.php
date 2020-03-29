<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Busca Extrato por Período <small>Listagem</small></h1>
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
                    endif;

                    //!empty($dataHoraIni) && !empty($dataHoraFim) ||
                    if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}&pg=";
                        $search = "?di{$busca['di']}&df={$busca['df']}&num={$busca['num']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}";

                    elseif (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&tipo={$busca['tipo']}&pg=";
                        $search = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&tipo={$busca['tipo']}";

                    elseif (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&pg=";
                        $search = "?di={$busca['di']}&df={$busca['df']}&num={$busca['num']}";

                    elseif (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&disposition={$busca['disposition']}&pg=";
                        $search = "?di={$busca['di']}&df={$busca['df']}&disposition={$busca['disposition']}";

                    elseif (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}&pg=";
                        $search = "?di{$busca['di']}&df={$busca['df']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}";

                    elseif (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&num={$busca['num']}&disposition={$busca['disposition']}&pg=";
                        $search = "?di{$busca['di']}&df={$busca['df']}&num={$busca['num']}&disposition={$busca['disposition']}";

                    elseif (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&df={$busca['df']}&pg=";
                        $search = "?di={$busca['di']}&df={$busca['df']}";

                    elseif (!empty($busca['di']) && empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&pg=";
                        $search = "?di={$busca['di']}";

                    elseif (!empty($busca['di']) || !empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&num={$busca['num']}&disposition={$busca['disposition']}&pg=";
                        $search = "?di{$busca['di']}&num={$busca['num']}&disposition={$busca['disposition']}";

                    elseif (!empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&num={$busca['num']}&pg=";
                        $search = "?di{$busca['di']}&num={$busca['num']}";

                    elseif (!empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&num={$busca['num']}&tipo={$busca['tipo']}&pg=";
                        $search = "?di{$busca['di']}&num={$busca['num']}&tipo={$busca['tipo']}";

                    elseif (!empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&num={$busca['num']}&disposition={$busca['disposition']}&pg=";
                        $search = "?di{$busca['di']}&num={$busca['num']}&disposition={$busca['disposition']}";

                    elseif (!empty($busca['di']) && empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&tipo={$busca['tipo']}&pg=";
                        $search = "?di{$busca['di']}&tipo={$busca['tipo']}";

                    elseif (!empty($busca['di']) && empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&di={$busca['di']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}&pg=";
                        $search = "?di{$busca['di']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}";

                    elseif (empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&num={$busca['num']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}&pg=";
                        $search = "?num={$busca['num']}&tipo={$busca['tipo']}&disposition={$busca['disposition']}";

                    elseif (empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&num={$busca['num']}&pg=";
                        $search = "?num={$busca['num']}";

                    elseif (empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&num={$busca['num']}&tipo={$busca['tipo']}&pg=";
                        $search = "?num={$busca['num']}&tipo={$busca['tipo']}";

                    elseif (empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&num={$busca['num']}&disposition={$busca['disposition']}&pg=";
                        $search = "?num={$busca['num']}&disposition={$busca['disposition']}";

                    elseif (empty($busca['di']) && empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&tipo={$busca['tipo']}&pg=";
                        $search = "?tipo={$busca['tipo']}";

                    elseif (empty($busca['di']) && empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&tipo={$busca['tipo']}&disposition={$busca['disposition']}&pg=";
                        $search = "?tipo={$busca['tipo']}&disposition={$busca['disposition']}";

                    elseif (empty($busca['di']) && empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                        $link = "?exe=relatorio/extrato/periodo/busca&disposition={$busca['disposition']}&pg=";
                        $search = "?disposition={$busca['disposition']}";

                    else:
                    endif;


                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                    $pager = new Pager($link);
                    $pager->ExePager($getPage, 20);

                    //LEITURA DOS DADOS
                    $read = new Read;

                    //Todos os Campos
                    if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND dst = '{$busca['num']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND dst = '{$busca['num']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}'";
                    endif;

                    //As duas datas o numero eo tipo
                    if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND dst = '{$busca['num']}' AND tipo <> '' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND dst = '{$busca['num']}' AND tipo <> '' ";
                    endif;

                    //As duas datas e o numero 
                    if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND dst = '{$busca['num']}' AND tipo <> '' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND dst = '{$busca['num']}' AND tipo <> '' ";
                    endif;

                    //As duas datas e o disposition
                    if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND disposition = '{$busca['disposition']}' AND tipo <> '' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND disposition = '{$busca['disposition']}' AND tipo <> '' ";
                    endif;

                    //Duas datas o tipo e o disposition
                    if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' ";
                    endif;

                    // Duas datas o numero e o disposition
                    if (!empty($busca['di']) && !empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND dst = '{$busca['num']}' AND disposition = '{$busca['disposition']}' AND tipo <> '' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND dst = '{$busca['num']}' AND disposition = '{$busca['disposition']}' AND tipo <> '' ";
                    endif;

                    // Duas datas 
                    if (!empty($busca['di']) && !empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']} 00:00:01' AND calldate <= '{$busca['df']} 23:59:59' AND tipo <> '' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo <> '' ";
                    endif;

                    // data inicial
                    if (!empty($busca['di']) && empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate LIKE CONCAT('%','{$busca['di']}','%') AND tipo <> '' ORDER BY calldate DESC", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate LIKE CONCAT('%','{$busca['di']}','%') AND tipo <> '' ";
                    endif;

                    //Data inicial, numero, tipo, e o disposition
                    if (!empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND dst = '{$busca['num']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND dst = '{$busca['num']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}'";
                    endif;
                    
                    //Data inicial e o numero
                    if (!empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND dst = '{$busca['num']}' AND tipo <> '' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND dst = '{$busca['num']}' AND tipo <> ''";
                    endif;

                    //Data inicial o numero e o tipo
                    if (!empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND dst = '{$busca['num']}' AND tipo = '{$busca['tipo']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND dst = '{$busca['num']}' AND tipo = '{$busca['tipo']}'";
                    endif;

                    //Data inicial o numero e o disposition
                    if (!empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND dst = '{$busca['num']}' AND tipo <> '' AND disposition = '{$busca['disposition']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND dst = '{$busca['num']}' AND tipo <> '' AND disposition = '{$busca['disposition']}'";
                    endif;

                    // Data inicial o tipo
                    if (!empty($busca['di']) && empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND tipo = '{$busca['tipo']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND tipo = '{$busca['tipo']}' ";
                    endif;

                    // Data inicial o tipo e o disposition
                    if (!empty($busca['di']) && empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' ";
                    endif;

                    // Data inicial e o disposition
                    if (!empty($busca['di']) && empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND tipo <> '' AND disposition = '{$busca['disposition']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE calldate >= '{$busca['di']}' AND tipo <> '' AND disposition = '{$busca['disposition']}' ";
                    endif;
                    
                    //Numero, tipo e o disposition
                    if (empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE dst = '{$busca['num']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE dst = '{$busca['num']}' AND tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}'";
                    endif;
                    
                    //Numero
                    if (empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE dst = '{$busca['num']}' AND tipo <> '' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE dst = '{$busca['num']}' AND tipo <> ''";
                        echo $termo;
                    endif;
                    
                    //Numero e o tipo
                    if (empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE dst = '{$busca['num']}' AND tipo = '{$busca['tipo']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE dst = '{$busca['num']}' AND tipo = '{$busca['tipo']}' ";
                    endif;
                    
                    //Numero e o disposition
                    if (empty($busca['di']) && empty($busca['df']) && !empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE dst = '{$busca['num']}' AND tipo <> '' AND disposition = '{$busca['disposition']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE dst = '{$busca['num']}' AND tipo <> '' AND disposition = '{$busca['disposition']}'";                           
                    endif;
                    
                    //Tipo
                    if (empty($busca['di']) && empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE tipo = '{$busca['tipo']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE tipo = '{$busca['tipo']}'";
                    endif;
                    
                    //Tipo e o disposition
                    if (empty($busca['di']) && empty($busca['df']) && empty($busca['num']) && !empty($busca['tipo']) && !empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE tipo = '{$busca['tipo']}' AND disposition = '{$busca['disposition']}'";
                    endif;
                    
                    //Disposition
                    if (empty($busca['di']) && empty($busca['df']) && empty($busca['num']) && empty($busca['tipo']) && !empty($busca['disposition'])):
                        $read->ExeRead("cdr", "WHERE tipo <> '' AND disposition = '{$busca['disposition']}' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
                        $termo = "WHERE tipo <> '' AND disposition = '{$busca['disposition']}'";
                    endif;



//                    if (!empty($busca['df'])):
//                        
//                        $read->ExeRead("cdr", "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo <> '' ORDER BY calldate", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
//                        $termo = "WHERE calldate >= '{$busca['di']}' AND calldate <= '{$busca['df']}' AND tipo <> '' ";
//                    else:
//                        
//                        $read->ExeRead("cdr", "WHERE calldate LIKE CONCAT('%','{$busca['di']}','%') AND tipo <> '' ORDER BY calldate DESC", "LIMIT {$pager->getLimit()} OFFSET {$pager->getOffset()}");
//                        $termo = "WHERE calldate LIKE CONCAT('%','{$busca['di']}','%') AND tipo <> '' ";
//                    endif;
                    //$read->ExeRead("cdr", "ORDER BY calldate ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    //RESULTADO DA PESQUISA
//                    $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
//                    if (!empty($busca['btnBusca'])):
//                        unset($busca['btnBusca']);
//
//                        if (!empty($busca['dataInicio']) || !empty($busca['dataFim']) || !empty($busca['numero']) || !empty($busca['tipo']) || !empty($busca['status'])):
//                            //var_dump($busca);
//                            header("Location: ?exe=relatorio/extrato/periodo/busca&di={$busca['dataInicio']}&df={$busca['dataFim']}&num={$busca['numero']}&tipo={$busca['tipo']}&status={$busca['status']}");
//                            exit();
//                        else:
//
//                            KLErro("Ops, Falta parametros para a pesquisa", KL_ALERT);
//                        endif;
//
//                    endif;
                    ?>
                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Nova Busca</h3>    
                        <a class="btn btn-warning nb" href="?exe=relatorio/extrato/periodo/lista" title="Nova Busca" data-toggle="tooltip" data-placement="top">Realizar uma Nova Busca</a>
                    </div>

                    <!--well botão-->
                    <div class="well text-right">                                                  
                        <a class="pull-left" href="system/relatorio/extrato/periodo/busca_excel.php<?php echo $search; ?>" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>                                
                        <a class="pull-left" href="system/relatorio/extrato/periodo/busca_pdf.php<?php echo $search; ?>" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>
                        <!--<a class="btn btn-success" href="painel.php?exe=gerenciamento/ramal/iax/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Ramal IAX</a>-->
                        <a class="voltar" href="painel.php" role="button" title="Voltar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
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
                        <tbody> 
                            <?php
                            if (!empty($read->getRowCount() > 0)):
                                echo "<h4>Total de registros encontrados: <b>{$read->getRowCount()}</b></h4>";

                                //var_dump($read->getRowCount());
                                foreach ($read->getResult() as $cdr):
                                    extract($cdr);
                                    $data = explode(" ", $calldate);
                                    $link = "http://{$data[0]}/audio/gravacoes/{$data[0]}/{$userfield}.wav";
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
                                            <a href="<?php echo $link ?>" target="blank"> <span class="glyphicon glyphicon-volume-up" aria-hidden="true"></span> </a>
                                            <!--<a href="painel.php?exe=gerenciamento/ramal/iax/update&iax_id=<?php //echo $iax_id             ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>-->
                                            <!--<a href="" data-toggle="modal" data-target="#iax_<?php //echo $iax_id;             ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>-->                                    
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
                            KLErro("Não exidte Ramais Cadastrado no momento!", KL_ALERT);
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
    </div>
</div>
