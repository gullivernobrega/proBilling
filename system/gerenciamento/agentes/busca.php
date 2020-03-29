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

                        if (!empty($busca['agent_name'])):
                            $link = "?exe=gerenciamento/agentes/busca&agent_name={$busca['agent_name']}&pg=";
                            $search = "?agent_name={$busca['agent_name']}";
                        else:
                        endif;

                    endif;

                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                    $pager = new Pager($link);
                    $pager->ExePager($getPage, 20);

                    //LEITURA DOS DADOS
                    $read = new read;

                    // por Numero
                    if (!empty($busca['agent_name'])):
                        $read->ExeRead("agents", "WHERE agent_name LIKE '%{$busca['agent_name']}%' ORDER BY agent_name LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                        $termo = "WHERE agent_name LIKE '%{$busca['agent_name']}%' ";
                    endif;

                    //MODAL ALTERAR TUDO
                    $UpdateSearch = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($UpdateSearch['confirmarSearchUpdate'])):
                        unset($UpdateSearch['confirmarSearchUpdate']);
                        header("Location: painel.php?exe=campanhas/numeros/updatesearch&agenda_id={$UpdateSearch['agenda_id']}&numero_status={$UpdateSearch['numero_status']}&acao={$UpdateSearch['acao']}");
                    endif;

                    //MODAL APAGA
                    $DeletaSearch = filter_input(INPUT_POST, 'agent_id', FILTER_VALIDATE_INT);

                    if (!empty($DeletaSearch)):

                        $Deletar = new Agent;
                        $Deletar->ExeDelete($DeletaSearch);

                        if ($Deletar->getResult()):

                            //Remonta o arquivo .conf
                            $geralConf = new Agent;
                            $geralConf->ExeConfGeral();
                            if ($geralConf->getResult()):
                                //Reloada no asterisk
                                shell_exec("sudoasterisk -rx 'reload'");
                                //Redireciona
                                header("Location: painel.php?exe=gerenciamento/agentes/lista");
                            endif;
                            
                        else:
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                        endif;

                    endif;
                    ?>
                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Nova Busca</h3>    
                        <a class="btn btn-warning nb" href="?exe=gerenciamento/agentes/lista" title="Nova Busca" data-toggle="tooltip" data-placement="top">Realizar uma Nova Busca</a>
                    </div>

                    <!--well botão-->
                    <div class="well text-right">
                        <!--<a class="btn btn-success" href="painel.php?exe=gerenciamento/agentes/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Agente</a>-->
                        <a class="voltar" href="?exe=gerenciamento/agentes/lista" role="button" title="Voltar"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--Total geral-->
                    <?php
                    $tot = new Select;
                    $tot->ExeSelect("agents", "COUNT(agent_name) AS Total", $termo);
                    $total = $tot->getResult();
                    extract($total[0]);
                    ?>
                    <h3>Total: <span class="label label-success"><?php echo $Total; ?></span></h3> 

                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>                            
                                <th width="6%">#</th>                                                        
                                <th>Usuário</th>                                                                                                                                               
                                <th>Nome</th>                                       
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            if (!empty($read->getResult())):

                                foreach ($read->getResult() as $agent):
                                    extract($agent);
                                    ?>
                                    <tr>
                                        <td scope="row"><?php echo $agent_id; ?></td> 
                                        <td scope="row"><?php echo $agent_user; ?></td> 
                                        <td scope="row"><?php echo $agent_name; ?></td>                                         
                                        <td>
                                            <a href="painel.php?exe=gerenciamento/agentes/updatesearch&agent_id=<?php echo $agent_id; ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                            <a href="" data-toggle="modal" data-target="#agent_<?php echo $agent_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>                                    
                                        </td> 
                                    </tr>

                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="agent_<?php echo $agent_id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Dados </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                    <div class="form-group">  
                                                        <h4>Deseja realemente apagar o Agente : <?php echo "<b>{$agent_name}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                        <input type="hidden" class="form-control" id="agent" name="agent_id" value="<?php echo $agent_id; ?>">
                                                    </div>                 

                                                    <button type="submit" class="btn btn-success" name="confirmaDados">Apagar Dados</button>
                                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                                                </form>   

                                            </div>                                           
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                                <?php
                            endforeach;
                        else:
                            KLErro("Não existe Agentes Cadastrado no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>

                    <!--PAGINAÇÃO-->
                    <div class="well corWell text-center">                     
                        <?php
                        $pager->ExePaginator("agents", "", "{$termo}");
                        echo $pager->getPaginator();
                        ?>
                    </div>
                </div><!--panel-body-->
            </div>
        </div>
    </div>
