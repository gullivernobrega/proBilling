<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Agentes <small>Atualizar!</small></h1>
</div>       
<!--</div>-->

<!--<div class="row">-->
<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> Painel de Cadastro</h3>
        </div>
        <div class="panel-body">
            <div id="shieldui-grid1">
                <!--FORMULARIO-->
                <!--<div class="col-lg-10">-->
                <?php
                // realiza a alteração
                $Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $agent_id = filter_input(INPUT_GET, "agent_id", FILTER_VALIDATE_INT);

                if (isset($Data["agentUpdate"])):
                    unset($Data["agentUpdate"]);
                    varDump::exeVD($Data);
                    varDump::exeVD($agent_id);
                
                
                    $update = new Agent;
                    $update->ExeUpdate($agent_id, $Data);

                    if ($update->getResult()):

                        //Monta o arquivo .conf geral
                        $geralConf = new Agent;
                        $geralConf->ExeConfGeral();
                        if ($geralConf->getResult()):
                            //Reloada no asterisk
                            shell_exec("sudo asterisk -rx 'reload'");
                            //Redireciona
                            header("Location: painel.php?exe=gerenciamento/agentes/lista");
                        endif;
                        
                    else:
                        KLErro("Ops, não foi possivel realizar as alterações!", KL_ERROR);
                    endif;
                else:

                    //Busca os dados na tabela para listagem                   
                    $readSip = new Read;
                    $readSip->ExeRead("agents", "WHERE agent_id = :id", "id={$agent_id}");
                    if (!$readSip->getResult()):
                        header("Location: painel.php?exe=gerenciamento/agentes&update=false");
                    else:
                        $res = $readSip->getResult();
                        $Data = $res[0];
                    endif;

                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formSip" action="" method="post" id="frm">                          

                    <!--USUÁRIO-->
                    <div class="form-group">    
                        <label for="agent_user" class="col-sm-2 control-label">USUÁRIO</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="agent_user" id="agent_user" 
                                placeholder="Nome de Usuário." 
                                value="<?php if (!empty($Data['agent_user'])) echo $Data['agent_user']; ?>"                                                                 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe um usuário.</small></p>
                        </div>                        
                    </div>
                    <!--NOME DO AGENTE-->
                    <div class="form-group">    
                        <label for="agent_name" class="col-sm-2 control-label">NOME DO AGENTE</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="agent_name" id="agent_name" 
                                placeholder="Nome do agente." 
                                value="<?php if (!empty($Data['agent_name'])) echo $Data['agent_name']; ?>"                                                                                                 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o nome do agente.</small></p>
                        </div>                        
                    </div>

                    <!--SENHA-->
                    <div class="form-group"> 
                        <label for="agent_pass" class="col-sm-2 control-label">Senha</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="password" 
                                class="form-control" 
                                name="agent_pass" id="agent_pass" 
                                placeholder="******" 
                                value="" 
                                minlength="6"                 
                                >
                            <p class="help-block"><small>Informe uma nova senha mínimo 6 caracteres ou deixe em branco.</small></p>
                        </div>
                    </div>  

                    <!--BOTÔES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-warning" name="agentUpdate" value="Atualizar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=gerenciamento/agentes/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->


