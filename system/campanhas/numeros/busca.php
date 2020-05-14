<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Busca Números <small>Listagem</small></h1>
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

                        //Analiza e elimina o parametros vazio
                        if (empty($busca['numero_fone'])):
                            unset($busca['numero_fone']);
                        endif;

                        if (empty($busca['numero_nome'])):
                            unset($busca['numero_nome']);
                        endif;

                        if (empty($busca['agenda_id'])):
                            unset($busca['agenda_id']);
                        endif;

                        if (empty($busca['numero_status'])):
                            unset($busca['numero_status']);
                        endif;

                        //Numero fone
                        if (!empty($busca['numero_fone'])):
                            $link = "?exe=campanhas/numeros/busca&numero_fone={$busca['numero_fone']}&pg=";
                            $search = "?numero_fone={$busca['numero_fone']}";
                        elseif (!empty($busca['numero_nome'])):
                            $link = "?exe=campanhas/numeros/busca&numero_nome={$busca['numero_nome']}&pg=";
                            $search = "?numero_nome={$busca['numero_nome']}";
                        elseif (!empty($busca['agenda_id']) && empty($busca['numero_status'])):
                            $link = "?exe=campanhas/numeros/busca&agenda_id={$busca['agenda_id']}&pg=";
                            $search = "?agenda_id={$busca['agenda_id']}";
                        elseif (empty($busca['agenda_id']) && !empty($busca['numero_status'])):
                            $link = "?exe=campanhas/numeros/busca&numero_status={$busca['numero_status']}&pg=";
                            $search = "?numero_status={$busca['numero_status']}";
                        elseif (!empty($busca['agenda_id']) && !empty($busca['numero_status'])):
                            $link = "?exe=campanhas/numeros/busca&agenda_id={$busca['agenda_id']}&numero_status={$busca['numero_status']}&pg=";
                            $search = "?agenda_id={$busca['agenda_id']}&numero_status={$busca['numero_status']}";
                        else:
                        endif;

                    endif;

                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                    $pager = new Pager($link);
                    $pager->ExePager($getPage, 20);

                    //LEITURA DOS DADOS
                    $read = new Read;

                    // por Numero
                    if (!empty($busca['numero_fone'])):
                        $read->ExeRead("numero", "WHERE numero_fone LIKE '%{$busca['numero_fone']}%' ORDER BY numero_nome LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE numero_fone LIKE '%{$busca['numero_fone']}%' ";                        
                    endif;

                    // por Nome
                    if (!empty($busca['numero_nome'])):
                        $read->ExeRead("numero", "WHERE numero_nome LIKE '%{$busca['numero_nome']}%' ORDER BY numero_nome LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE numero_nome LIKE '%{$busca['numero_nome']}%' ";
                    endif;

                    // por Agenda id
                    if (!empty($busca['agenda_id'])):
                        $read->ExeRead("numero", "WHERE agenda_id = :f ORDER BY numero_nome LIMIT :limit OFFSET :offset", "f={$busca['agenda_id']}&limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE agenda_id = {$busca['agenda_id']} ";
                    endif;

                    // por Status
                    if (!empty($busca['numero_status'])):
                        $read->ExeRead("numero", "WHERE numero_status = :f ORDER BY numero_nome LIMIT :limit OFFSET :offset", "f={$busca['numero_status']}&limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE numero_status = '{$busca['numero_status']}' ";
                    endif;

                    // por Agenda e Status
                    if (!empty($busca['agenda_id']) && !empty($busca['numero_status'])):
                        $read->ExeRead("numero", "WHERE numero_status = :f AND agenda_id = :id ORDER BY numero_nome LIMIT :limit OFFSET :offset", "f={$busca['numero_status']}&id={$busca['agenda_id']}&limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE numero_status = '{$busca['numero_status']}' AND agenda_id = {$busca['agenda_id']} ";
                    endif;

                    //MODAL ALTERAR TUDO
                    $UpdateSearch = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($UpdateSearch['confirmarSearchUpdate'])):
                        unset($UpdateSearch['confirmarSearchUpdate']);
                        header("Location: painel.php?exe=campanhas/numeros/updatesearch&agenda_id={$UpdateSearch['agenda_id']}&numero_status={$UpdateSearch['numero_status']}&acao={$UpdateSearch['acao']}");
                    endif;

                    //MODAL APAGA TUDO
                    $DeletaSearch = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($DeletaSearch['confirmarSearchTp'])):
                        unset($DeletaSearch['confirmarSearchTp']);
                        
                        $Deletar = new Numero;
                        $Deletar->ExeDeleteSearch($DeletaSearch);

                        if ($Deletar->getResult()):
                            header("Location: painel.php?exe=campanhas/numeros/lista");
                        else:
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                        endif;

                    endif;
                    ?>
                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Nova Busca</h3>    
                        <a class="btn btn-warning nb" href="?exe=campanhas/numeros/lista" title="Nova Busca" data-toggle="tooltip" data-placement="top">Realizar uma Nova Busca</a>
                    </div>
                    <!--well botão-->
                    <div class="well text-right">                                                  
                        <!--<a class="pull-left" href="system/relatorio/extrato/periodo/busca_excel.php<?php //echo $search;           ?>" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>-->                                
                        <!--<a class="pull-left" href="system/relatorio/extrato/periodo/busca_pdf.php<?php //echo $search;           ?>" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>-->
                        <!--<a class="btn btn-info" href="painel.php?exe=campanhas/numeros/updatesearch&" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Ramal IAX</a>-->
                        <a class="btn btn-info" href="" role="button" data-toggle="modal" data-target="#updateStatus" data-placement="top" title="Alterar status"><i class="fa fa-retweet"></i> Alterar Status da pesquisa</a>
                        <a class="btn btn-danger" href="" role="button" data-toggle="modal" data-target="#deletarSearch" data-placement="top" title="Apagar Pesquisa"><i class="fa fa-trash"></i> Apagar Pesquisa</a>
                        <a class="voltar" href="painel.php" role="button" title="Voltar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>
                    
                    <!--Total geral-->
                    <?php
                    $tot = new Select;
                    $tot->ExeSelect("numero", "COUNT(numero_fone) AS Total", $termo);
                    $total = $tot->getResult();
                    extract($total[0]); 
                    ?>
                    <h3>Total: <span class="label label-success"><?php echo $Total;?></span></h3> 
                    
                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th width="15%">Número</th>
                                <th>Nome</th>                                                             
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
                                    $read->ExeRead("agenda", "WHERE agenda_id = :id", "id={$agenda_id}");

                                    if (!empty($read->getResult())):
                                        $age = $read->getResult();
                                        extract($age[0]);
                                        $agenda = (!empty($agenda_nome) ? $agenda_nome : null );
                                    endif;
                                    ?>
                                    <tr>
                                        <td scope="row"><?php echo $numero_fone; ?></td>                                         
                                        <td scope="row"><?php echo $numero_nome; ?></td> 
                                        <td scope="row"><?php echo $agenda; ?></td>
                                        <td>
                                            <?php
                                            if ($numero_status == 'A'):
                                                echo "<span class='txtVerde' aria-hidden='true'>Ativo</span>";
                                            elseif ($numero_status == 'I'):
                                                echo "<span class='txtRed' aria-hidden='true'>Inativo</span>";
                                            elseif ($numero_status == 'P'):
                                                echo "<span class='txtOrange' aria-hidden='true'>Pendente</span>";
                                            elseif ($numero_status == 'E'):
                                                echo "<span class='txtAzul' aria-hidden='true'>Enviado</span>";
                                            elseif ($numero_status == 'B'):
                                                echo "<span class='txtRoxo' aria-hidden='true'>Bloqueado</span>";
                                            endif;
                                            ?>
                                        </td>  
                                        <td>
                                            <a href="painel.php?exe=campanhas/numeros/update&numero_id=<?php echo $numero_id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                            <a href="" data-toggle="modal" data-target="#numero_<?php echo $numero_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>                                    
                                        </td> 
                                    </tr>
                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="numero_<?php echo $numero_id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Dados </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">             
                                                    <div class="form-group">  
                                                        <h4>Deseja realemente apagar o número: <?php echo "<b>{$numero_fone}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                        <input type="hidden" class="form-control" id="numero" name="numero_id" value="<?php echo $numero_id; ?>">
                                                    </div>                 

                                                    <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $numero_id; ?>">Apagar Dados</button>
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
                                                        if (!empty($busca['numero_fone']))://                                                                                                                          
                                                            KLErro("Ops, impossivel alterar pesquisas por Aqui!", KL_ALERT);
                                                            //header("refresh: 4; painel.php?exe=campanhas/numeros/lista");
                                                        endif;

                                                        if (!empty($busca['numero_nome']))://                                                          
                                                            KLErro("Ops, impossivel alterar pesquisas por Aqui!", KL_ALERT);
                                                            //header("refresh: 4; painel.php?exe=campanhas/numeros/lista");
                                                        endif;

                                                        if (!empty($busca['agenda_id']) && empty($busca['numero_status'])):
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"agenda_id\" name=\"agenda_id\" value=\"{$agenda_id}\">";
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"acao\" name=\"acao\" value=\"UpSearch\">";
                                                            echo "<button type=\"submit\" class=\"btn btn-danger\" name=\"confirmarSearchUpdate\" value=\"alterar\">Alterar Toda Pesquisa</button> 
                                                                   
                                                             ";
                                                        elseif (empty($busca['agenda_id']) && !empty($busca['numero_status'])):
                                                            KLErro("Ops, Informe uma agenda e o status que deseja apagar!", KL_ALERT);
                                                            //header("refresh: 4; painel.php?exe=campanhas/numeros/lista");
                                                        elseif (!empty($busca['agenda_id']) && !empty($busca['numero_status'])):
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"agenda_id\" name=\"agenda_id\" value=\"{$agenda_id}\">";
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"numero_status\" name=\"numero_status\" value=\"{$numero_status}\">";
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"acao\" name=\"acao\" value=\"UpSearch\">";
                                                            echo "<button type=\"submit\" class=\"btn btn-danger\" name=\"confirmarSearchUpdate\" value=\"alterar\">Alterar Toda Pesquisa</button> 
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
                                                        if (!empty($busca['numero_fone'])):
                                                            KLErro("Ops, impossivel deletar pesquisas por numeros!", KL_ALERT);
                                                            //header("refresh: 4; painel.php?exe=campanhas/numeros/lista");
                                                        endif;

                                                        if (!empty($busca['numero_nome'])):
                                                            KLErro("Ops, impossivel deletar pesquisas por nome!", KL_ALERT);
                                                            //header("refresh: 4; painel.php?exe=campanhas/numeros/lista");
                                                        endif;

                                                        if (!empty($busca['agenda_id']) && empty($busca['numero_status'])):
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"paramentro\" name=\"agenda_id\" value=\"{$agenda_id}\">";
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"acao\" name=\"acao\" value=\"delSearch\">";
                                                            echo "
                                                                <button type=\"submit\" class=\"btn btn-danger\" name=\"confirmarSearchTp\" value=\"apagarTp\">Apagar Toda Pesquisa</button>
                                                                <button type=\"button\" class=\"btn btn-info\" data-dismiss=\"modal\">Cancelar</button>
                                                             ";
                                                        elseif (empty($busca['agenda_id']) && !empty($busca['numero_status'])):
                                                            KLErro("Ops, Informe uma agenda e o status que deseja apagar!", KL_ALERT);
                                                            //header("refresh: 4; painel.php?exe=campanhas/numeros/lista");
                                                        elseif (!empty($busca['agenda_id']) && !empty($busca['numero_status'])):
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"paramentro\" name=\"agenda_id\" value=\"{$agenda_id}\">";
                                                            echo "<input type=\"hidden\" class=\"form-control\" id=\"paramentro\" name=\"numero_status\" value=\"{$numero_status}\">";
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
                    $pager->ExePaginator("numero", "", "{$termo}");
                    echo $pager->getPaginator();                    
                    ?>
                </div>
            </div><!--panel-body-->
        </div>
    </div>
</div>
