<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Busca Números SMS <small>Listagem</small></h1>
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

                        //Verifica item da pesquisa
                        if (empty($busca['numero_sms_fone'])):
                            unset($busca['numero_sms_fone']);
                        endif;
                        if (empty($busca['numero_sms_lote'])):
                            unset($busca['numero_sms_lote']);
                        endif;
                        if (empty($busca['agenda_sms_id'])):
                            unset($busca['agenda_sms_id']);
                        endif;
                        if (empty($busca['numero_sms_status'])):
                            unset($busca['numero_sms_status']);
                        endif;

                        //Inicializa as variaveis
                        $data = "";
                        $dataRead = "";

                        //loop para preparar itens da pesquisa
                        foreach ($busca as $k => $v):

                            //motagem do data link e search
                            $data .= "{$k}={$v}&";

                            //verifico se a key é do fone
                            if ($k == "numero_sms_fone"):
                                $dataRead .= "$k LIKE '%{$v}%' AND ";
                            else:
                                $dataRead .= "$k = '$v' AND ";                                
                            endif;
                            
                        endforeach;
                                                
                        //retira o ultimo "&" da linha de pesquisa
                        $data = substr($data, 0, -1);
                        $dataRead = substr($dataRead, 0, -4);  
                        
                        // Monta o link e a search
                        $link = "?exe=campanhasms/numerossms/busca&{$data}&pg=";
                        $search = "?{$data}";                        
                        
//                        var_dump($dataRead); exit;
                        
                        //Numero 
//                        if (!empty($busca['numero_sms_fone']) && empty($busca['numero_sms_lote']) && empty($busca['agenda_sms_id']) && empty($busca['numero_sms_status'])): // So o numero
//
//                            $link = "?exe=campanhasms/numerossms/busca&numero_sms_fone={$busca['numero_sms_fone']}&pg=";
//                            $search = "?numero_sms_fone={$busca['numero_sms_fone']}";
//
//                        //Numero e o lote    
//                        elseif (!empty($busca['numero_sms_fone']) && !empty($busca['numero_sms_lote']) && empty($busca['agenda_sms_id']) && empty($busca['numero_sms_status'])):
//
//                            $link = "?exe=campanhasms/numerossms/busca&numero_sms_fone={$busca['numero_sms_fone']}&numero_sms_lote={$busca['numero_sms_lote']}&pg=";
//                            $search = "?numero_sms_fone={$busca['numero_sms_fone']}&numero_sms_lote={$busca['numero_sms_lote']}";
//
//                        //Numero e agenda
//                        elseif (!empty($busca['numero_sms_fone']) && empty($busca['numero_sms_lote']) && !empty($busca['agenda_sms_id']) && empty($busca['numero_sms_status'])):
//
//                            $link = "?exe=campanhasms/numerossms/busca&numero_sms_fone={$busca['numero_sms_fone']}&agenda_sms_id={$busca['agenda_sms_id']}&pg=";
//                            $search = "?numero_sms_fone={$busca['numero_sms_fone']}&agenda_sms_id={$busca['agenda_sms_id']}";
//
//                        //Numero e status
//                        elseif (!empty($busca['numero_sms_fone']) && empty($busca['numero_sms_lote']) && empty($busca['agenda_sms_id']) && !empty($busca['numero_sms_status'])):
//
//                            $link = "?exe=campanhasms/numerossms/busca&numero_sms_fone={$busca['numero_sms_fone']}&numero_sms_status={$busca['numero_sms_status']}&pg=";
//                            $search = "?numero_sms_fone={$busca['numero_sms_fone']}&numero_sms_status={$busca['numero_sms_status']}";
//
//                        //Numero e o lote e agenda    
//                        elseif (!empty($busca['numero_sms_fone']) && !empty($busca['numero_sms_lote']) && !empty($busca['agenda_sms_id']) && empty($busca['numero_sms_status'])):
//
//                            $link = "?exe=campanhasms/numerossms/busca&numero_sms_fone={$busca['numero_sms_fone']}&numero_sms_lote={$busca['numero_sms_lote']}&agenda_sms_id={$busca['agenda_sms_id']}&pg=";
//                            $search = "?numero_sms_fone={$busca['numero_sms_fone']}&numero_sms_lote={$busca['numero_sms_lote']}&agenda_sms_id={$busca['agenda_sms_id']}";
//
//                        //Numero lote a agenda e o status
//                        elseif (!empty($busca['numero_sms_fone']) && !empty($busca['numero_sms_lote']) && !empty($busca['agenda_sms_id']) && !empty($busca['numero_sms_status'])):
//
//                            $link = "?exe=campanhasms/numerossms/busca&numero_sms_fone={$busca['numero_sms_fone']}&numero_sms_lote={$busca['numero_sms_lote']}&agenda_sms_id={$busca['agenda_sms_id']}&numero_sms_status={$busca['numero_sms_status']}&pg=";
//                            $search = "?numero_sms_fone={$busca['numero_sms_fone']}&numero_sms_lote={$busca['numero_sms_lote']}&agenda_sms_id={$busca['agenda_sms_id']}&numero_sms_status={$busca['numero_sms_status']}";
//
//                        //Por lote   
//                        elseif (empty($busca['numero_sms_fone']) && !empty($busca['numero_sms_lote']) && empty($busca['agenda_sms_id']) && empty($busca['numero_sms_status'])):
//
//                            $link = "?exe=campanhasms/numerossms/busca&numero_sms_lote={$busca['numero_sms_lote']}&pg=";
//                            $search = "?numero_sms_lote={$busca['numero_sms_lote']}";
//
//                        //Por lote e agenda  
//                        elseif (empty($busca['numero_sms_fone']) && !empty($busca['numero_sms_lote']) && !empty($busca['agenda_sms_id']) && empty($busca['numero_sms_status'])):
//
//                            $link = "?exe=campanhasms/numerossms/busca&numero_sms_lote={$busca['mumero_sms_lote']}&agenda_sms_id={$busca['agenda_sms_id']}&pg=";
//                            $search = "?numero_sms_lote={$busca['numero_sms_lote']}&agenda_sms_id={$busca['agenda_sms_id']}";
//
//                        //Por lote e o estatus
//                        elseif (empty($busca['numero_sms_fone']) && !empty($busca['numero_sms_lote']) && empty($busca['agenda_sms_id']) && !empty($busca['numero_sms_status'])):
//
//                            $link = "?exe=campanhasms/numerossms/busca&numero_sms_lote={$busca['numero_sms_lote']}&numero_sms_status={$busca['numero_sms_status']}&pg=";
//                            $search = "?numero_sms_lote={$busca['numero_sms_lote']}&numero_sms_status={$busca['numero_sms_status']}";
//
//                        //Por lote agenda e status
//                        elseif (empty($busca['numero_sms_fone']) && !empty($busca['numero_sms_lote']) && !empty($busca['agenda_sms_id']) && !empty($busca['numero_sms_status'])):
//
//                            $link = "?exe=campanhasms/numerossms/busca&numero_sms_lote={$busca['numero_sms_lote']}&agenda_sms_id={$busca['agenda_sms_id']}&numero_sms_status={$busca['numero_sms_status']}&pg=";
//                            $search = "?numero_sms_lote={$busca['numero_sms_lote']}&agenda_sms_id={$busca['agenda_sms_id']}&numero_sms_status={$busca['numero_sms_status']}";
//
//                        //Por agenda 
//                        elseif (empty($busca['numero_sms_fone']) && empty($busca['numero_sms_lote']) && !empty($busca['agenda_sms_id']) && empty($busca['numero_sms_status'])):
//
//                            $link = "?exe=campanhasms/numerossms/busca&agenda_sms_id={$busca['agenda_sms_id']}&pg=";
//                            $search = "?agenda_sms_id={$busca['agenda_sms_id']}";
//
//                        //Por agenda e status    
//                        elseif (empty($busca['numero_sms_fone']) && empty($busca['numero_sms_lote']) && !empty($busca['agenda_sms_id']) && !empty($busca['numero_sms_status'])):
//
//                            $link = "?exe=campanhasms/numerossms/busca&agenda_sms_id={$busca['agenda_sms_id']}&numero_sms_status={$busca['numero_sms_status']}&pg=";
//                            $search = "?agenda_sms_id={$busca['agenda_sms_id']}&numero_sms_status={$busca['numero_sms_status']}";
//
//                        //Por status    
//                        elseif (empty($busca['numero_sms_fone']) && empty($busca['numero_sms_lote']) && empty($busca['agenda_sms_id']) && !empty($busca['numero_sms_status'])):
//
//                            $link = "?exe=campanhasms/numerossms/busca&numero_sms_status={$busca['numero_sms_status']}&pg=";
//                            $search = "?numero_sms_status={$busca['numero_sms_status']}";
//
//                        else:
//                        endif;

                    endif; //fim do if exe

                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                    $pager = new Pager($link);
                    $pager->ExePager($getPage, 20);

                    //LEITURA DOS DADOS
                    $read = new Read;
                    $read->ExeRead("numero_sms", "WHERE {$dataRead} ORDER BY numero_sms_fone LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $termo = "WHERE {$dataRead} ";                    

                    // por Numero
//                    if (!empty($busca['numero_sms_fone'])):
//                        $read->ExeRead("numero_sms", "WHERE numero_sms_fone LIKE '%{$busca['numero_sms_fone']}%' ORDER BY numero_sms_fone LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
//                        $termo = "WHERE numero_sms_fone LIKE '%{$busca['numero_sms_fone']}%' ";
//                    endif;
//
//                    // por Agenda id
//                    if (!empty($busca['agenda_sms_id'])):
//                        $read->ExeRead("numero_sms", "WHERE agenda_sms_id = :f ORDER BY numero_sms_fone LIMIT :limit OFFSET :offset", "f={$busca['agenda_sms_id']}&limit={$pager->getLimit()}&offset={$pager->getOffset()}");
//                        $termo = "WHERE agenda_sms_id = {$busca['agenda_sms_id']} ";
//                    endif;
//
//                    // por Status
//                    if (!empty($busca['numero_sms_status'])):
//                        $read->ExeRead("numero_sms", "WHERE numero_sms_status = :f ORDER BY numero_sms_fone LIMIT :limit OFFSET :offset", "f={$busca['numero_sms_status']}&limit={$pager->getLimit()}&offset={$pager->getOffset()}");
//                        $termo = "WHERE numero_sms_status = '{$busca['numero_sms_status']}' ";
//                    endif;
//
//                    // por Agenda e Status
//                    if (!empty($busca['agenda_sms_id']) && !empty($busca['numero_sms_status'])):
//                        $read->ExeRead("numero_sms", "WHERE numero_sms_status = :f AND agenda_sms_id = :id ORDER BY numero_sms_fone LIMIT :limit OFFSET :offset", "f={$busca['numero_sms_status']}&id={$busca['agenda_sms_id']}&limit={$pager->getLimit()}&offset={$pager->getOffset()}");
//                        $termo = "WHERE numero_sms_status = '{$busca['numero_sms_status']}' AND agenda_sms_id = {$busca['agenda_sms_id']} ";
//                    endif;

                    //MODAL ALTERAR TUDO
                    $UpdateSearch = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($UpdateSearch['confirmarSearchUpdate'])):
                        unset($UpdateSearch['confirmarSearchUpdate']);
                        $dataUp = "";
                        foreach ($UpdateSearch as $k => $v):
                            $dataUp .= "$k={$v}&";
                        endforeach;
                        $dataUp = substr($dataUp, 0, -1); 
                        header("Location: painel.php?exe=campanhasms/numerossms/updatesearch&{$dataUp}");
                    endif;

                    //MODAL APAGA TUDO
                    $DeletaSearch = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($DeletaSearch['confirmarSearchTp'])):
                        unset($DeletaSearch['confirmarSearchTp']);
                        
                        $Deletar = new NumeroSms;
                        $Deletar->ExeDeleteSearch($DeletaSearch);

                        if ($Deletar->getResult()):
                            header("Location: painel.php?exe=campanhasms/numerossms/lista");
                        else:
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                        endif;

                    endif;

                    //RESULTADO DA JANELA MODAL
                    $dataDel = filter_input(INPUT_POST, "numero_sms_id", FILTER_VALIDATE_INT);
                    if (!empty($dataDel)):

                        $Deletar = new NumeroSms;
                        $Deletar->ExeDelete($dataDel);

                        if ($Deletar->getResult()):
                            header("Location: painel.php?exe=campanhasms/numerossms/lista");
                        else:
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                        endif;

                    endif;
                    ?>
                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Nova Busca</h3>    
                        <a class="btn btn-warning nb" href="?exe=campanhasms/numerossms/lista" title="Nova Busca" data-toggle="tooltip" data-placement="top">Realizar uma Nova Busca</a>
                    </div>
                    <!--well botão-->
                    <div class="well text-right">                                                  
                        <!--<a class="pull-left" href="system/relatorio/extrato/periodo/busca_excel.php<?php //echo $search;                ?>" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>-->                                
                        <!--<a class="pull-left" href="system/relatorio/extrato/periodo/busca_pdf.php<?php //echo $search;                ?>" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>-->
                        <!--<a class="btn btn-info" href="painel.php?exe=campanhas/numeros/updatesearch&" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Ramal IAX</a>-->
                        <a class="btn btn-info" href="" role="button" data-toggle="modal" data-target="#updateStatus" data-placement="top" title="Alterar status"><i class="fa fa-retweet"></i> Alterar Status da pesquisa</a>
                        <a class="btn btn-danger" href="" role="button" data-toggle="modal" data-target="#deletarSearch" data-placement="top" title="Apagar Pesquisa"><i class="fa fa-trash"></i> Apagar Pesquisa</a>
                        <a class="voltar" href="painel.php" role="button" title="Voltar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--Total geral-->
                    <?php
                    $tot = new Select;
                    $tot->ExeSelect("numero_sms", "COUNT(numero_sms_fone) AS Total", $termo);
                    $total = $tot->getResult();
                    extract($total[0]);
                    ?>
                    <h3>Total: <span class="label label-success"><?php echo $Total; ?></span></h3> 

                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th width="15%">Lote</th>                                                                                           
                                <th width="15%">Número</th>                                                                                           
                                <th>Agenda</th>                                                             
                                <th width="10%">Status</th> 
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            if ($read->getRowCount() > 0):

                                foreach ($read->getResult() as $numero):
                                    extract($numero);

                                    $read = new Read;
                                    $read->ExeRead("agenda_sms", "WHERE agenda_sms_id = :id", "id={$agenda_sms_id}");

                                    if (!empty($read->getResult())):
                                        $age = $read->getResult();
                                        extract($age[0]);
                                        $agenda = (!empty($agenda_sms_nome) ? $agenda_sms_nome : null );
                                    endif;
                                    ?>
                                    <tr>
                                        <td scope="row"><?php echo $numero_sms_lote; ?></td>   
                                        <td scope="row"><?php echo $numero_sms_fone; ?></td>   
                                        <td scope="row"><?php echo $agenda; ?></td>
                                        <td>
                                            <?php
                                            if ($numero_sms_status == 'A'):
                                                echo "<span class='txtVerde' aria-hidden='true'>Ativo</span>";
                                            elseif ($numero_sms_status == 'I'):
                                                echo "<span class='txtRed' aria-hidden='true'>Inativo</span>";
                                            elseif ($numero_sms_status == 'P'):
                                                echo "<span class='txtOrange' aria-hidden='true'>Pendente</span>";
                                            elseif ($numero_sms_status == 'E'):
                                                echo "<span class='txtAzul' aria-hidden='true'>Enviado</span>";
                                            elseif ($numero_sms_status == 'B'):
                                                echo "<span class='txtRoxo' aria-hidden='true'>Bloqueado</span>";
                                            endif;
                                            ?>
                                        </td>  
                                        <td>
                                            <a href="painel.php?exe=campanhasms/numerossms/update&numero_sms_id=<?php echo $numero_sms_id; ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                            <a href="" data-toggle="modal" data-target="#numerosms_<?php echo $numero_sms_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>                                    
                                        </td> 
                                    </tr>
                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="numerosms_<?php echo $numero_sms_id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Dados Pesquisa </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">             
                                                    <div class="form-group">  
                                                        <h4>Deseja realemente apagar o número: <?php echo "<b>{$numero_sms_fone}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                        <input type="hidden" class="form-control" id="numero" name="numero_id" value="<?php echo $numero_sms_id; ?>">
                                                    </div>                 

                                                    <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $numero_sms_id; ?>">Apagar Dados</button>
                                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                                                </form>   

                                            </div>                                            
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal --> 

                                <!-- JANELA MODAL ALTERAR TODA SEARCH-->
                                <div class="modal fade" tabindex="-1" role="dialog" id="updateStatus">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Alterar Toda Pesquisa </h4>
                                            </div>
                                            <div class="modal-body">
                                                <h4>Deseja realemente alterar toda pesquisa? Clique em alterar ou cancelar para sair!.</h4>
                                                <div style="clear: both"></div>
                                                <form role="form" method="post" name="frmConfirmeUpdate" action="" id="frmConfirmeUpdate" >                    
                                                    <div class="form-group"> 
                                                        <?php                                                                                                    
//                                                        if (!empty($busca['numero_sms_fone']))://                                                                                                                          
//                                                            KLErro("Ops, impossivel alterar pesquisas por Aqui!", KL_ALERT);
//                                                            echo '<div class="text-right"><button type="button" class="btn btn-info" data-dismiss="modal">Fechar</button></div>';
//                                                        endif;
                                                        
                                                        if(!empty($busca['numero_sms_lote']) && empty($busca['agenda_sms_id']) && empty($busca['numero_sms_status'])):
                                                            
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"numero_sms_lote\" name=\"numero_sms_lote\" value=\"{$numero_sms_lote}\">";                    
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"acao\" name=\"acao\" value=\"UpSearch\">";
                                                            echo "<div class='text-right'><button type=\"submit\" class=\"btn btn-success\" name=\"confirmarSearchUpdate\" value=\"alterar\">Alterar Toda Pesquisa</button> 
                                                                  <button type=\"button\" class=\"btn btn-info\" data-dismiss=\"modal\">Cancelar</button></div>  
                                                             ";
                                                            
                                                        elseif(empty($busca['numero_sms_lote']) && !empty($busca['agenda_sms_id']) && empty($busca['numero_sms_status'])):  
                                                            
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"agenda_sms_id\" name=\"agenda_sms_id\" value=\"{$agenda_sms_id}\">";                                                           
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"acao\" name=\"acao\" value=\"UpSearch\">";
                                                            echo "<div class='text-right'><button type=\"submit\" class=\"btn btn-success\" name=\"confirmarSearchUpdate\" value=\"alterar\">Alterar Toda Pesquisa</button> 
                                                                  <button type=\"button\" class=\"btn btn-info\" data-dismiss=\"modal\">Cancelar</button></div>  
                                                             ";
                                                        
                                                        elseif(!empty($busca['numero_sms_lote']) && !empty($busca['agenda_sms_id']) && empty($busca['numero_sms_status'])):    
                                                            
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"numero_sms_lote\" name=\"numero_sms_lote\" value=\"{$numero_sms_lote}\">";
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"agenda_sms_id\" name=\"agenda_sms_id\" value=\"{$agenda_sms_id}\">";                                                           
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"acao\" name=\"acao\" value=\"UpSearch\">";
                                                            echo "<div class='text-right'><button type=\"submit\" class=\"btn btn-success\" name=\"confirmarSearchUpdate\" value=\"alterar\">Alterar Toda Pesquisa</button> 
                                                                  <button type=\"button\" class=\"btn btn-info\" data-dismiss=\"modal\">Cancelar</button></div>  
                                                             ";
                                                            
                                                        elseif (!empty($busca['numero_sms_lote']) && !empty($busca['agenda_sms_id']) && !empty($busca['numero_sms_status'])):    
                                                        
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"numero_sms_lote\" name=\"numero_sms_lote\" value=\"{$numero_sms_lote}\">";
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"agenda_sms_id\" name=\"agenda_sms_id\" value=\"{$agenda_sms_id}\">";
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"numero_sms_status\" name=\"numero_sms_status\" value=\"{$numero_sms_status}\">";
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"acao\" name=\"acao\" value=\"UpSearch\">";
                                                            echo "<div class='text-right'><button type=\"submit\" class=\"btn btn-success\" name=\"confirmarSearchUpdate\" value=\"alterar\">Alterar Toda Pesquisa</button> 
                                                                  <button type=\"button\" class=\"btn btn-info\" data-dismiss=\"modal\">Cancelar</button></div>  
                                                             ";
                                                            
                                                        else:
                                                            
                                                            KLErro("Ops, Informe um Lote e uma Agenda para liberar a alteração!!", KL_ALERT);
                                                            echo '<div class="text-right"><button type="button" class="btn btn-info" data-dismiss="modal">Fechar</button></div>';
                                                            
                                                        endif;
                                                        ?>  

                                                    </div>   
                                                </form>   

                                            </div>                                            
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->  

                                <!-- JANELA MODAL APAGA TODA SEARCH-->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="deletarSearch">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Tudo </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirmaDel" action="" id="frmConfirmaDel">                    
                                                    <div class="form-group">  
                                                        <h4>Deseja realemente apagar toda pesquisa? Clique em apagar ou cancelar.</h4>
                                                        <?php
                                                        if (!empty($busca['numero_sms_fone'])):
                                                            KLErro("Ops, impossivel deletar pesquisas por numeros!", KL_ALERT);
                                                        //header("refresh: 4; painel.php?exe=campanhas/numeros/lista");
                                                        endif;

                                                        if (!empty($busca['agenda_sms_id']) && empty($busca['numero_sms_status'])):
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"paramentro\" name=\"agenda_sms_id\" value=\"{$agenda_sms_id}\">";
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"acao\" name=\"acao\" value=\"delSearch\">";
                                                            echo "
                                                                <button type=\"submit\" class=\"btn btn-danger\" name=\"confirmarSearchTp\" value=\"apagarTp\">Apagar Toda Pesquisa</button>
                                                                <button type=\"button\" class=\"btn btn-info\" data-dismiss=\"modal\">Cancelar</button>
                                                             ";
                                                        elseif (empty($busca['agenda_sms_id']) && !empty($busca['numero_sms_status'])):
                                                            KLErro("Ops, Informe uma agenda e o status que deseja apagar!", KL_ALERT);
                                                        //header("refresh: 4; painel.php?exe=campanhas/numeros/lista");
                                                        elseif (!empty($busca['agenda_sms_id']) && !empty($busca['numero_sms_status'])):
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"paramentro\" name=\"agenda_sms_id\" value=\"{$agenda_sms_id}\">";
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"paramentro\" name=\"numero_sms_status\" value=\"{$numero_sms_status}\">";
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"acao\" name=\"acao\" value=\"delSearch\">";
                                                            echo "
                                                                <button type=\"submit\" class=\"btn btn-danger\" name=\"confirmarSearchTp\" value=\"apagarTp\">Apagar Toda Pesquisa</button>
                                                                <button type=\"button\" class=\"btn btn-info\" data-dismiss=\"modal\">Cancelar</button>
                                                             ";
                                                        endif;
                                                        ?>      
                                                    </div> 
                                                </form>   

                                            </div>                                            
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->                               

                                <?php
                            endforeach;
                        else:
                            KLErro("Não existe Numero Cadastrado no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                </div>
                <!--PAGINAÇÃO-->
                <div class="well corWell text-center">                     
                    <?php
                    $pager->ExePaginator("numero_sms", "", "{$termo}");
                    echo $pager->getPaginator();
                    ?>
                </div>
            </div><!--panel-body-->
        </div>
    </div>
</div>
