<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Campanha <small>Cadastro!</small></h1>
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
                $Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if (isset($Data["campanhaCreate"])):
                    unset($Data["campanhaCreate"]);

                    //Verificação IAX
                    if (!empty($Data['campanha_destino_tipo']) && $Data['campanha_destino_tipo'] == 'IAX' && !empty($Data['ramalIax'])):
                        unset($Data['ramalSip'], $Data['queue'], $Data['group'], $Data['custom']);
                    endif;
                    //Verificação SIP
                    if (!empty($Data['campanha_destino_tipo']) && $Data['campanha_destino_tipo'] == 'SIP' && !empty($Data['ramalSip'])):
                        unset($Data['ramalIax'], $Data['queue'], $Data['group'], $Data['custom']);
                    endif;
                    //Verificação QUEUE 
                    if (!empty($Data['campanha_destino_tipo']) && $Data['campanha_destino_tipo'] == 'QUEUE' && !empty($Data['queue'])):
                        unset($Data['ramalIax'], $Data['ramalSip'], $Data['group'], $Data['custom']);
                    endif;
                    //Verificação GROUP
                    if (!empty($Data['campanha_destino_tipo']) && $Data['campanha_destino_tipo'] == 'GROUP' && !empty($Data['group'])):
                        unset($Data['ramalIax'], $Data['ramalSip'], $Data['queue'], $Data['custom']);
                    endif;
                    // Verificação CUSTOM
                    if (!empty($Data['campanha_destino_tipo']) && $Data['campanha_destino_tipo'] == 'CUSTOM' && !empty($Data['custom'])):
                        unset($Data['ramalIax'], $Data['ramalSip'], $Data['queue'], $Data['group']);
                    endif;
                    
                    //Retira espaços em brancos no inicio e final do texto.
                    $Data['campanha_tts_1'] = trim($Data['campanha_tts_1']);
                    $Data['campanha_tts_2'] = trim($Data['campanha_tts_2']);
        
                    //if ($Data['campanha_limite_chamada'] <= 500):

                        $cadastra = new Campanha;
                        $cadastra->ExeCreate($Data);
                        if ($cadastra->getResult()):
                            //Redireciona
                            header("Location: painel.php?exe=campanhas/cadcampanha/lista");
                        else:
                            KLErro("Ops, não foi possivel realizar o cadastro!", KL_ERROR);
                        endif;

//                    else:
//                        KLErro("<strong>Ops!</strong> O limite de chamada não pode exceder a 500, Verifique!", KL_INFOR);
//                    endif;
                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formCampanha" action="" method="post" id="frm"> 
                    
                     <!--CAMPANHA TIPO-->
                    <div class="form-group">
                        <label for="campanha_tipo" class="col-sm-2 control-label">Campanha Tipo</label>
                        <div class="col-lg-4">
                            <?php
                            if (!empty($Data['campanha_tipo']) && $Data['campanha_tipo'] == "T"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="campanha_tipo" id="campanha_tipo1" value="T" checked="checked"> Torpedo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="campanha_tipo" id="campanha_tipo2" value="D"> Discador
                                </label>                                
                                <?php
                            elseif (!empty($Data['campanha_tipo']) && $Data['campanha_tipo'] == "D"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_tipo" id="campanha_tipo1" value="T" > Torpedo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_tipo" id="campanha_tipo2" value="D" checked="checked"> Discador
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_tipo" id="campanha_status1" value="T" checked="checked"> Torpedo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_tipo" id="campanha_tipo2" value="D"> Discador
                                </label>                               
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    
                    <!--NOME-->
                    <div class="form-group">    
                        <label for="campanha_nome" class="col-sm-2 control-label">Campanha Nome</label>
                        <div class="col-xs-6">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="campanha_nome" id="campanha_nome" 
                                placeholder="Nome da Campanha" 
                                value="<?php if (!empty($Data['campanha_nome'])) echo $Data['campanha_nome']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o nome da campanha.</small></p>
                        </div>                        
                    </div>
                    <!--DATA INICIO-->
                    <div class="form-group">    
                        <label for="campanha_data_inicio" class="col-sm-2 control-label">Data Início</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text"                                
                                class="form-control" 
                                name="campanha_data_inicio" id="campanha_data_inicio" 
                                placeholder="Data Inicio" 
                                value="<?php if (!empty($Data['campanha_data_inicio'])) echo $Data['campanha_data_inicio']; ?>" 
                                required                                 
                                >
                            <!--<p class="help-block"><small>Informe a data inicial da campanha.</small></p>-->
                        </div> 

                        <label for="campanha_data_fim" class="col-sm-1 control-label">Data Final</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="campanha_data_fim" id="campanha_data_fim" 
                                placeholder="Data Final" 
                                value="<?php if (!empty($Data['campanha_data_fim'])) echo $Data['campanha_data_fim']; ?>" 
                                required                                 
                                >
                            <!--<p class="help-block"><small>Informe a data inicial da campanha.</small></p>-->
                        </div>                        
                    </div>

                    <!--ROTAS-->
                    <div class="form-group"> 
                        <label class="col-sm-2 control-label">Rotas</label>
                        <!--ROTA FIXO-->
                        <div class="col-xs-3"> 
                            <select class="form-control" name="campanha_rota_fixo" id="campanha_rota_fixo">
                                <option value="">Rota Fixo</option>
                                <?php
                                $teste = new Read;
                                $teste->ExeRead("tronco");

                                if (!$teste->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                else:
                                    foreach ($teste->getResult() as $value):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value['tronco_tipo']}/{$value['tronco_nome']}\"";
                                        
                                        $troncoFixo = "{$value['tronco_tipo']}/{$value['tronco_nome']}";
                                            
                                        if ($Data['campanha_rota_fixo'] == $troncoFixo):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value['tronco_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                            <p class="help-block"><small>Informe uma rota Fixo.</small></p>
                        </div>
                        
                        <!--ROTA MOVEL-->
                        <div class="col-xs-3"> 
                            <select class="form-control" name="campanha_rota_movel" id="campanha_rota_movel" >
                                <option value="">Rota Movel</option>
                                <?php
                                $teste = new Read;
                                $teste->ExeRead("tronco");

                                if (!$teste->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                else:
                                    foreach ($teste->getResult() as $value):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value['tronco_tipo']}/{$value['tronco_nome']}\" ";
                                        
                                        $troncoMovel = "{$value['tronco_tipo']}/{$value['tronco_nome']}";

                                        if ($Data['campanha_rota_movel'] == $troncoMovel):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value['tronco_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                            <p class="help-block"><small>Informe uma rota Movel.</small></p>
                        </div>
                        
                        <!--ROTA INTERNACIONAL-->                        
                        <div class="col-xs-3"> 
                            <select class="form-control" name="campanha_rota_internacional" id="campanha_rota_internacional" >
                                <option value="">Rota Internacional</option>
                                <?php
                                $teste = new Read;
                                $teste->ExeRead("tronco");

                                if (!$teste->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                else:
                                    foreach ($teste->getResult() as $value):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value['tronco_tipo']}/{$value['tronco_nome']}\" ";
                                        
                                        $troncoInter = "{$value['tronco_tipo']}/{$value['tronco_nome']}";

                                        if ($Data['campanha_rota_internacional'] == $troncoInter):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value['tronco_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                            <p class="help-block"><small>Informe uma rota Internacional.</small></p>
                        </div>
                        
                    </div>

                    <!--ÁUDIO 1-->
                    <div class="form-group">     
                        <label for="campanha_audio_1" class="col-sm-2 control-label">Áudio 1</label>
                        <div class="col-xs-3"> 
                            <select class="form-control" name="campanha_audio_1" id="campanha_audio_1" >
                                <option value="">Áudios</option>
                                <?php
                                $audio = new Read;
                                $audio->ExeRead("audio");

                                if (!$audio->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um áudio!</option>';
                                else:
                                    foreach ($audio->getResult() as $value):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value['audio_nome']}\" ";

                                        if (!empty($Data['campanha_audio_1']) && $Data['campanha_audio_1'] == $value['audio_nome']):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value['audio_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                        </div> 
                    </div>

                    <!--ÁUDIO 2-->
                    <div class="form-group">     
                        <label for="campanha_audio_2" class="col-sm-2 control-label">Áudio 2</label>
                        <div class="col-xs-3"> 
                            <select class="form-control" name="campanha_audio_2" id="campanha_audio_1" >
                                <option value="">Áudios</option>
                                <?php
                                $audio1 = new Read;
                                $audio1->ExeRead("audio");

                                if (!$audio1->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um áudio!</option>';
                                else:
                                    foreach ($audio1->getResult() as $value1):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value1['audio_nome']}\" ";

                                        if (!empty($Data['campanha_audio_2']) && $Data['campanha_audio_2'] == $value1['audio_nome']):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value1['audio_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                        </div> 
                    </div>
                    <!--LIMITE MINIMO DE CHAMADA--><!--pattern = "[0-9]+$"-->
                    <div class="form-group"> 
                        <label for="campanha_limite_chamada" class="col-sm-2 control-label">Limite de Chamada</label>
                        <div class="col-xs-2">      
                            <input 
                                type="text" 
                                class="form-control" 
                                name="campanha_limite_chamada" id="campanha_limite_chamada" 
                                placeholder="Mínimo/Máximo" 
                                value="<?php if (!empty($Data['campanha_limite_chamada'])) echo $Data['campanha_limite_chamada']; ?>"                                 
                                maxlength="7"
                                min="1"                                 
                                required                                 
                                >                         
                            <p class="help-block"><small>Mínimo/Máximo.</small></p>
                        </div>
                    </div>  
                    
                    <!--LIMITE MÁXIMO DE CHAMADA-->
<!--                    <div class="form-group"> 
                        <label for="campanha_limite_maximo_chamada" class="col-sm-2 control-label">Limite máximo de Chamada</label>
                        <div class="col-xs-1">      
                            <input 
                                type="text" 
                                class="form-control" 
                                name="campanha_limite_maximo_chamada" id="campanha_limite_maximo_chamada" 
                                placeholder="Limite máximo de chamadas" 
                                value="<?php //if (!empty($Data['campanha_limite_maximo_chamada'])) echo $Data['campanha_limite_maximo_chamada']; ?>" 
                                pattern = "[0-9]+$"
                                maxlength="3"
                                min="1" 
                                max="500"                                                                 
                                >                         
                            <p class="help-block"><small>Informe um limite máximo de chamada.</small></p>
                        </div>
                    </div>  -->
                    
                    <!--TTS 1-->
                    <div class="form-group"> 
                        <label for="campanha_tts_1" class="col-sm-2 control-label">TTS 1</label>
                        <div class="col-xs-6">      
                            <textarea 
                                class="form-control" 
                                name="campanha_tts_1" 
                                id="campanha_tts_1"
                                rows="3" 
                                placeholder="Descrição"><?php if (!empty($Data['campanha_tts_1'])) echo trim($Data['campanha_tts_1']); ?>
                            </textarea>                       
                            <p class="help-block"><small>Informe um texto tts 1.</small></p>
                        </div>
                    </div>  
                    <!--TTS 2-->
                    <div class="form-group"> 
                        <label for="campanha_tts_2" class="col-sm-2 control-label">TTS 2</label>
                        <div class="col-xs-6">      
                            <textarea 
                                class="form-control" 
                                name="campanha_tts_2" 
                                id="campanha_tts_2"
                                rows="3" 
                                placeholder="Descrição"><?php if (!empty($Data['campanha_tts_2'])) echo trim($Data['campanha_tts_2']); ?>
                            </textarea>                       
                            <p class="help-block"><small>Informe um texto tts 2.</small></p>
                        </div>
                    </div>  

                    <!--ASR-->
                    <div class="form-group">    
                        <label for="campanha_asr" class="col-sm-2 control-label">Campanha ASR</label>
                        <div class="col-xs-6">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="campanha_asr" id="campanha_nome" 
                                placeholder="Asr da Campanha" 
                                value="<?php if (!empty($Data['campanha_asr'])) echo $Data['campanha_asr']; ?>"                                 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o asr da campanha.</small></p>
                        </div>                        
                    </div>

                    <!--DESTINO-->
                    <div class="form-group">  
                        <label for="campanha_destino_tipo" class="col-sm-2 control-label">Destino</label>
                        <div class="col-lg-4">                        
                            <label class="radio-inline">
                                <input onClick="return mudacor('1');" type="radio" name="campanha_destino_tipo" id="iax"  value="IAX" > IAX
                            </label>
                            <label class="radio-inline">
                                <input onClick="return mudacor('2');" type="radio" name="campanha_destino_tipo" id="sip"  value="SIP"> SIP
                            </label>                                
                            <label class="radio-inline">
                                <input onClick="return mudacor('2');" type="radio" name="campanha_destino_tipo" id="fqueue"  value="QUEUE"> QUEUE
                            </label>                                
                            <label class="radio-inline">
                                <input onClick="return mudacor('2');" type="radio" name="campanha_destino_tipo" id="fgroup"  value="GROUP"> GROUP
                            </label>                                
                            <label class="radio-inline">
                                <input onClick="return mudacor('2');" type="radio" name="campanha_destino_tipo" id="fcustom"  value="CUSTOM"> CUSTOM
                            </label>  
                        </div>  
                    </div>

                    <!--COMPLEMENTO DESTINO-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <!--DESTINO IAX-->
                        <div id="ramalIax" style="display: none;">
                            <div class="col-xs-3">
                                <select class="form-control" name="ramalIax" id="ramalIax" >
                                    <option value="">Ramal Iax</option>
                                    <?php
                                    $read = new Read;
                                    $read->ExeRead("ramaliax");

                                    if (!$read->getResult()):
                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Ramal!</option>';
                                    else:
                                        foreach ($read->getResult() as $value):
                                            //passa o id e o tipo 
                                            echo "<option value=\"{$value['iax_numero']}\" ";

                                            if (!empty($iax_numero) == $value['iax_numero']):
                                                echo ' selected = "selected" ';
                                            endif;

                                            echo ">{$value['iax_numero']}</option>";
                                        endforeach;
                                    endif;
                                    ?>               
                                </select> 
                                <!--<p class="help-block"><small>Informe o ramal iax.</small></p>-->
                            </div>
                        </div>

                        <!--DESTINO SIP-->
                        <div id="ramalSip"  style="display: none;">
                            <div class="col-xs-3">
                                <select class="form-control" name="ramalSip" id="ramalSip">
                                    <option value="">Ramal Sip</option>
                                    <?php
                                    $read = new Read;
                                    $read->ExeRead("ramalsip");

                                    if (!$read->getResult()):
                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Ramal!</option>';
                                    else:
                                        foreach ($read->getResult() as $value):
                                            //passa o id e o tipo 
                                            echo "<option value=\"{$value['sip_numero']}\" ";

                                            if (!empty($sip_numero) == $value['sip_numero']):
                                                echo ' selected = "selected" ';
                                            endif;

                                            echo ">{$value['sip_numero']}</option>";
                                        endforeach;
                                    endif;
                                    ?>               
                                </select> 
                                <!--<p class="help-block"><small>Informe o ramal sip.</small></p>-->
                            </div>
                        </div>

                        <!--DESTINO QUEUE-->
                        <div id="queue"  style="display: none;">
                            <div class="col-xs-3">
                                <select class="form-control" name="queue" id="queue">
                                    <option value="">Fila Queue</option>
                                    <?php
                                    $read = new Read;
                                    $read->ExeRead("queues");

                                    if (!$read->getResult()):
                                        echo '<option disabled="disabled" value="NULL">Cadastre antes uma Queue !</option>';
                                    else:
                                        foreach ($read->getResult() as $value):
                                            //passa o id e o tipo 
                                            echo "<option value=\"{$value['queue_name']}\" ";

                                            if (!empty($queue_numero) == $value['queue_name']):
                                                echo ' selected = "selected" ';
                                            endif;

                                            echo ">{$value['queue_name']}</option>";
                                        endforeach;
                                    endif;
                                    ?>               
                                </select> 
                                <!--<p class="help-block"><small>Informe o Ramal Queue.</small></p>-->
                            </div>
                        </div>

                        <!--DESTINO GROUP-->
                        <div id="group" style="display: none;">
                            group
                        </div>

                        <!--DESTINO CUSTOM-->
                        <div id="custom" style="display: none;">
                            <!--<label for="did_origem" class="col-sm-2 control-label">Origem</label>-->
                            <div class="col-xs-3">                                    
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="custom" id="cust" 
                                    placeholder="Informe Destino personalizado" 
                                    value="<?php //if (!empty($Data['custom'])) echo $Data['custom'];               ?>" 
                                    >
                                <!--<p class="help-block"><small>Destino personalizado.</small></p>-->
                            </div>
                        </div>

                    </div>

                    <!--CAMPANHA AGENDA-->
                    <div class="form-group">     
                        <label for="campanha_agenda" class="col-sm-2 control-label">Campanha Agenda</label>
                        <div class="col-xs-3"> 
                            <select class="form-control" name="campanha_agenda" id="campanha_audio_1" required>
                                <option value="">Agendas</option>
                                <?php
                                $agenda = new Read;
                                $agenda->ExeRead("agenda");

                                if (!$agenda->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes uma agenda!</option>';
                                else:
                                    foreach ($agenda->getResult() as $value):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value['agenda_nome']}\" ";

                                        if (!empty($Data['campanha_agenda']) && $Data['campanha_agenda'] == $value['agenda_nome']):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value['agenda_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                        </div> 
                    </div>

                    <!--AMD-->
                    <div class="form-group">
                        <label for="campanha_amd" class="col-sm-2 control-label">AMD</label>
                        <div class="col-lg-4">
                            <?php
                            if (!empty($Data) && $Data['campanha_amd'] == "1"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="campanha_amd" id="campanha_amd1" value="1" checked="checked"> Sim
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="campanha_amd" id="campanha_amd2" value="0"> Não
                                </label>                                
                                <?php
                            elseif (!empty($Data) && $Data['campanha_amd'] == "0"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_amd" id="campanha_amd1" value="1" > Sim
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_amd" id="campanha_amd2" value="0" checked="checked"> Não
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_amd" id="campanha_amd1" value="1" > Sim
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_amd" id="campanha_amd2" value="0" checked="checked"> Não
                                </label>                               
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>

                    <!--STATUS-->
                    <div class="form-group">
                        <label for="campanha_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-lg-4">
                            <?php
                            if (!empty($Data['campanha_status']) && $Data['campanha_status'] == "A"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="campanha_status" id="campanha_status1" value="A" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="campanha_status" id="campanha_status2" value="I"> Inativo
                                </label>                                
                                <?php
                            elseif (!empty($Data['campanha_status']) && $Data['campanha_status'] == "I"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_status" id="campanha_status1" value="A" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_status" id="campanha_status2" value="I" checked="checked"> Inativo
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_status" id="campanha_status1" value="A" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_status" id="campanha_status2" value="I"> Inativo
                                </label>                               
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>

                    <!--BOTÕES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-success" name="campanhaCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=campanhas/cadcampanha/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->