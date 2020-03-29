<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Agentes<small> Cadastro!</small></h1>
</div>       

<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> Painel de Cadastro</h3>
        </div>
        <div class="panel-body txtblue">
            <div id="shieldui-grid1">
                <!--FORMULARIO-->                
                <?php
                $Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                                
                if (isset($Data["agentCreate"])):
                    unset($Data["agentCreate"]);
                    $cadastra = new Agent;
                    $cadastra->ExeCreate($Data);
                    if ($cadastra->getResult()):

                        //Repassa os dados para o arquivo .conf
                        $cadConf = new Agent;
                        $cadConf->ExeConf($cadastra->getResult());
                        if ($cadConf->getResult()):
                            //Reloada no asterisk
                            shell_exec("sudo asterisk -rx 'reload'");
                            //Redireciona
                            header("Location: painel.php?exe=gerenciamento/agentes/lista");
                        endif;
                        
                    else:
                        KLErro("Ops, não foi possivel realizar o cadastro!", KL_ERROR);
                    endif;
                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formAgent" action="" method="post" id="frm">                          
                    <!--USUÁRIO-->
                    <div class="form-group">    
                        <label for="agent_user" class="col-sm-2 control-label">Usuário</label>
                        <div class="col-xs-3">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="agent_user" id="agent_user" 
                                placeholder="Nome do agente." 
                                value="<?php if (!empty($Data['agent_user'])) echo $Data['agent_user']; ?>" 
                                required 
                                autofocus
                                >                            
                        </div>                        
                    </div>
                    <!--NOME COMPLETO-->
                    <div class="form-group">    
                        <label for="agent_name" class="col-sm-2 control-label">Nome Completo</label>
                        <div class="col-xs-6">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="agent_name" id="agent_name" 
                                placeholder="Nome Completo." 
                                value="<?php if (!empty($Data['agent_name'])) echo $Data['agent_name']; ?>" 
                                required                                 
                                >                            
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
                                value="<?php if (!empty($Data['agent_pass'])) echo $Data['agent_pass']; ?>" 
                                minlength="6"                               
                                required                                 
                                >
                            <p class="help-block"><small>Informe uma senha mínimo 6 caracteres.</small></p>
                        </div>
                    </div>  

                    <!--BOTÕES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-success" name="agentCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=gerenciamento/agentes/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>                
            </div>
        </div>
    </div>
</div>
