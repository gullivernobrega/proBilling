<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Agentes <small>Listagem</small></h1>
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
                    $pager = new Pager("?exe=gerenciamento/agentes/lista&pg=");
                    $pager->ExePager($getPage, 20);

                    //LEITURA DOS RAMAIS
                    $read = new Read;
                    $read->ExeRead("agents", "ORDER BY agent_name ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $verifica = $read->getRowCount();
                    $obj = $read->getResult();

                    //RESULTADO DA JANELA MODAL
                    $dataDel = filter_input(INPUT_POST, "agent_id", FILTER_VALIDATE_INT);
                    if (!empty($dataDel)):

                        $Deletar = new Agent;
                        $Deletar->ExeDelete($dataDel);

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

                    //RESULTADO DA PESQUISA
                    $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($busca['btnBusca'])):
                        unset($busca['btnBusca']);
                        if (!empty($busca['agent_name'])):
                            header("Location: ?exe=gerenciamento/agentes/busca&agent_name={$busca['agent_name']}");
                            exit();
                        else:
                            KLErro("Ops, Falta parametros para a pesquisa", KL_INFOR);
                        endif;
                    endif;
                    ?>

                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Busca</h3><p>Selecione um dos itens abaixo para pesquisa</p>    
                        <form class="form-inline" action=""  method="post" name="frmPesquisa" id="frmPesquisa" >  
                            <!--*******-->
                            <div class="form-group form-group-sm">                                
                                <input 
                                    class="form-control" 
                                    name="agent_name" 
                                    id="agent_name" 
                                    type="text" 
                                    placeholder="Nome do agente" 
                                    value="<?php
                    if (isset($busca['agent_name'])): echo $busca['agent_name'];
                    endif;
                    ?>" >                                 
                            </div>  
                            <!--</button>-->                                                              
                            <button name="btnBusca" value="Buscar" type="submit" class="btn btn-warning btn-sm" title="Buscar" data-toggle="tooltip" data-placement="top"><i class="fa fa-search"></i> <strong>Localizar</strong></button>                            
                        </form>
                    </div>

                    <!--well botão-->
                    <div class="well text-right">
                        <a class="btn btn-success" href="painel.php?exe=gerenciamento/agentes/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Agente</a>
                        <a class="voltar" href="painel.php" role="button" title="Voltar"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>
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
                            if ($verifica > 0):

                                foreach ($obj as $agent):
                                    extract($agent);
                                    ?>
                                    <tr>
                                        <td scope="row"><?php echo $agent_id; ?></td> 
                                        <td scope="row"><?php echo $agent_user; ?></td> 
                                        <td scope="row"><?php echo $agent_name; ?></td>                                         
                                        <td>
                                            <a href="painel.php?exe=gerenciamento/agentes/update&agent_id=<?php echo $agent_id; ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
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

                                                    <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $agent_id; ?>">Apagar Dados</button>
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
                    <!--fim tabela-->
                    <div class="well corWell text-center">                     
                        <?php
                        $pager->ExePaginator("agents");
                        echo $pager->getPaginator();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
