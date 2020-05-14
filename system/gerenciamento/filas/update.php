<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Filas (Queues) <small>Atualizar!</small></h1>
</div>       

<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> Painel de Cadastro</h3>
        </div>
        <div class="panel-body">
            <div id="shieldui-grid1">
                <!--FORMULARIO-->               
                <?php
                // realiza a alteração
                $queue_id = filter_input(INPUT_GET, "queue_id", FILTER_VALIDATE_INT);
                $Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                
                if (isset($Data["queueUpdate"])):
                    unset($Data["queueUpdate"]);

                    if (!empty($Data['queue_tipo']) && $Data['queue_tipo'] == 'R' && !empty($Data['ramalSip'])):
                        unset($Data['agentsList']);
                    endif;
                    
                    if (!empty($Data['queue_tipo']) && $Data['queue_tipo'] == 'A' && !empty($Data['agentsList'])):
                        unset($Data['ramalSip']);
                    endif;

                    $update = new Queues;
                    $update->ExeUpdate($queue_id, $Data);

                    if ($update->getResult()):

                        //Monta o arquivo .conf geral
                        $geralConf = new Queues();
                        $geralConf->ExeConfGeral();
                        if ($geralConf->getResult()):
                            //Reloada no asterisk
                            shell_exec("sudo asterisk -rx 'reload'");
                            //Redireciona
                            header("Location: painel.php?exe=gerenciamento/filas/lista");
                        endif;

                    else:
                        KLErro("Ops, não foi possivel realizar as alterações!", KL_ERROR);
                    endif;

                else:

                    //Busca os dados na tabela para listagem                   
                    $read = new Read;
                    $read->ExeRead("queues", "WHERE queue_id = :id", "id={$queue_id}");
                    if ($read->getResult()):
                        $res = $read->getResult();
                        $Data = $res[0];
                    endif;

                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formQueue" action="" method="post" id="frm">                          

                    <!--NAME-->
                    <div class="form-group">                         
                        <label for="queue_name" class="col-sm-5 control-label">Nome</label>
                        <div class="col-xs-5">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="queue_name" id="queue_name" 
                                placeholder="Nome" 
                                value="<?php if (!empty($Data['queue_name'])) echo $Data['queue_name']; ?>"                                                                 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Nome da Queue.</small></p>
                        </div>                        
                    </div>
                    <!--`queue_name`, `queue_strategy`, `queue_ringinuse`, `queue_timeout`, `announce_frequency`, 
                    `queue_retry`, `queue_wrapuptime`, `queue_maxlen`, `queue_weight`-->
                    <!--STRATEGY-->
                    <div class="form-group">  
                        <label for="queue_strategy" class="col-sm-5 control-label">Estratégia de distribuição das chamandas</label>
                        <div class="col-xs-5">
                            <select class="form-control" name="queue_strategy" id="queue_strategy" required>
                                <option value="">Informe o Queue Stragegy</option>
                                <option value="ringall" <?php if (!empty($Data) && $Data['queue_strategy'] == "ringall"): ?> selected="selected" <?php endif; ?> >Para todos os agente disponíveis (ringall)</option>
                                <option value="roundrobin" <?php if (!empty($Data) && $Data['queue_strategy'] == "roundrobin"): ?> selected="selected" <?php endif; ?>>Buscar um agente disponível (roundrobin)</option>
                                <option value="leastrecent" <?php if (!empty($Data) && $Data['queue_strategy'] == "leastrecent"): ?> selected="selected" <?php endif; ?>>Para o agente ocioso por mais tempo (leastrecent)</option>
                                <option value="fewestcalls" <?php if (!empty($Data) && $Data['queue_strategy'] == "fewestcalls"): ?> selected="selected" <?php endif; ?>>Para o agente que atendeu menos chamadas (fewestcalls)</option>
                                <option value="rrmemory" <?php if (!empty($Data) && $Data['queue_strategy'] == "rrmemory"): ?> selected="selected" <?php endif; ?>>Igualmente (rrmemory)</option>                                
                            </select>
                            <p class="help-block"><small>Informe o queue strategy.</small></p>
                        </div>
                    </div>
                    <!--RINGINUSE-->
                    <div class="form-group">
                        <label for="queue_ringinuse" class="col-sm-5 control-label">Chamar membro da fila que estiver em ligação</label>
                        <div class="col-lg-4">
                            <?php
                            if (!empty($Data['queue_ringinuse']) && $Data['queue_ringinuse'] == "S"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="queue_ringinuse" id="queue_riginuse1" value="S" checked="checked"> Sim
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="queue_ringinuse" id="queue_riginuse2" value="N"> Não
                                </label>                                
                                <?php
                            elseif (!empty($Data['queue_ringinuse']) && $Data['queue_ringinuse'] == "N"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="queue_ringinuse" id="queue_riginuse1" value="S" > Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="queue_ringinuse" id="queue_riginuse2" value="N" checked="checked"> Não
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="queue_ringinuse" id="queue_riginuse1" value="S" checked="checked"> Sim
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="queue_ringinuse" id="queue_riginuse2" value="N"> Não
                                </label>                               
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    <!--TIMEOUT-->
                    <div class="form-group">    
                        <label for="queue_timeout" class="col-sm-5 control-label">Tempo de toque em cada agente</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="queue_timeout" id="queue_timeout" 
                                placeholder="Em Segundos" 
                                value="<?php if (!empty($Data['queue_timeout'])) echo $Data['queue_timeout']; ?>"
                                maxlength="2"
                                pattern = "[0-9]+$"
                                >
                            <!--<p class="help-block"><small>Informe o queue timeout.</small></p>-->
                        </div>                        
                    </div>
                    <!--ANNOUNCE_FREQUENCY-->
                    <div class="form-group">    
                        <label for="queue_announce_frequency" class="col-sm-5 control-label">Intervalo de repetição das mensagens ao chamador</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="queue_announce_frequency" id="queue_announce_frequency" 
                                placeholder="Em Segundos" 
                                value="<?php if (!empty($Data['queue_announce_frequency'])) echo $Data['queue_announce_frequency']; ?>" 
                                required                                                                
                                maxlength="2"
                                pattern = "[0-9]+$"
                                >                            
                        </div>                        
                    </div>
                    <!--RETRY-->
                    <div class="form-group">    
                        <label for="queue_retry" class="col-sm-5 control-label">Tempo de espera para tocar todos os agentes novamentes</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="queue_retry" id="queue_retry" 
                                placeholder="Em Segundos" 
                                value="<?php if (!empty($Data['queue_retry'])) echo $Data['queue_retry']; ?>" 
                                required                                                               
                                maxlength="2"
                                pattern = "[0-9]+$"
                                >                            
                        </div>                        
                    </div>
                    <!--WRAPUPTIME-->
                    <div class="form-group">    
                        <label for="queue_wrapuptime" class="col-sm-5 control-label">Tempo de descanso do agente entre chamadas</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="queue_wrapuptime" id="queue_wrapuptime" 
                                placeholder="Em Segundos" 
                                value="<?php if (!empty($Data['queue_wrapuptime'])) echo $Data['queue_wrapuptime']; ?>" 
                                required
                                min="1"                                
                                maxlength="2"
                                pattern = "[0-9]+$"
                                >                            
                        </div>                        
                    </div>
                    <!--MAXLEN-->
                    <div class="form-group">    
                        <label for="queue_maxlen" class="col-sm-5 control-label">Número máximo de ligações esperando na fila</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="queue_maxlen" id="queue_maxlen" 
                                placeholder="Número" 
                                value="<?php if (!empty($Data['queue_maxlen'])) echo $Data['queue_maxlen']; ?>" 
                                required                                 
                                pattern = "[0-9]+$"
                                >                            
                        </div>                        
                    </div>
                    <!--WEIGHT-->
                    <div class="form-group">    
                        <label for="queue_weight" class="col-sm-5 control-label">Prioridade da fila</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="queue_weight" id="queue_weight" 
                                placeholder="Número" 
                                value="<?php if (!empty($Data['queue_weight'])) echo $Data['queue_weight']; ?>" 
                                required                                 
                                pattern = "[0-9]+$"
                                >                            
                        </div>                        
                    </div>

                    <!--AÇÃO RAMAL OU AGENTES-->
                    <div class="form-group">  
                        <label for="acao" class="col-sm-5 control-label">Acão</label>
                        <div class="col-lg-2">   
                            <?php
                            if ($Data['queue_tipo'] == "R"):
                                $Data['ramalSip'] = explode(", ", $Data['queue_ramal']);
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="queue_tipo" id="Ramal" value="R" checked="checked"> Ramal
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="queue_tipo" id="Agent" value="A"> Agentes
                                </label>
                                <?php
                            elseif ($Data['queue_tipo'] == "A"):
                                $Data['agentsList'] = explode(", ", $Data['queue_ramal']);                                
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="queue_tipo" id="Ramal" value="R"> Ramal
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="queue_tipo" id="Agent" value="A" checked="checked"> Agentes
                                </label>
                            <?php endif; ?>
                        </div>  
                    </div>

                    <!--SELEÇÃO DE RAMAIS-->
                    <section id="sectionRamais" style="<?php echo ($Data['queue_tipo'] == "R") ? 'display: block;' : 'display: none;'; ?>">
                        <div class="row mg20B">
                            <div class="col-md-4 col-md-offset-5">
                                <!--SELECT 1-->
                                <select class="form-control ramal" name="ramalSip[]" id="ramalSip" size="10" multiple="multiple">
                                    <!--<option value="">Ramal Sip</option>-->
                                    <?php
                                    $read = new Read;
                                    $read->ExeRead("ramalsip");

                                    if (!$read->getResult()):
                                        echo '<option disabled="disabled" value="NULL">Cadastre um Ramal!</option>';
                                    else:
                                        foreach ($read->getResult() as $value):
                                            //passa o id e o tipo 
                                            echo "<option value=\"{$value['sip_numero']}\" ";

                                            $totalRamal = (!empty($Data['ramalSip'])) ? count($Data['ramalSip']) : null;
                                            for ($i = 0; $i < $totalRamal; $i++):

                                                if (!empty($Data) && $Data['ramalSip'][$i] == $value['sip_numero']):
                                                    echo ' selected = "selected" ';
                                                endif;

                                            endfor;

                                            echo ">{$value['sip_numero']}</option>";
                                        endforeach;
                                    endif;
                                    ?>               
                                </select>  
                            </div>
                        </div>
                    </section>

                    <!--AGENTES-->
                    <section id="sectionAgents" style="<?php echo ($Data['queue_tipo'] == "A") ? 'display: block;' : 'display: none;'; ?>">
                        <div class="row mg20B">
                            <div class="col-md-4 col-md-offset-5">
                                <!--SELECT 1-->
                                <select class="form-control agents" name="agentsList[]" id="agentsList" size="10" multiple="multiple">
                                    <!--<option value="">Ramal Sip</option>-->
                                    <?php
                                    $readAg = new Read;
                                    $readAg->ExeRead("agents");
                                    if (!$readAg->getResult()):
                                        echo '<option disabled="disabled" value="NULL">Cadastre um Agent!</option>';
                                    else:
                                        foreach ($readAg->getResult() as $valueAg):
                                            //passa o id e o tipo 
                                            echo "<option value=\"{$valueAg['agent_user']}\" ";
                                            $totalAgent = (!empty($Data['agentsList'])) ? count($Data['agentsList']) : null;
                                            for ($a = 0; $a < $totalAgent; $a++):
                                                if (!empty($Data) && $Data['agentsList'][$a] == $valueAg['agent_user']):
                                                    echo ' selected = "selected" ';
                                                endif;
                                            endfor;
                                            echo ">{$valueAg['agent_user']}</option>";
                                        endforeach;
                                    endif;
                                    ?>               
                                </select>
                            </div>                        
                    </section>


                    <!--BOTÔES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-warning" name="queueUpdate" value="Atualizar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=gerenciamento/filas/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>