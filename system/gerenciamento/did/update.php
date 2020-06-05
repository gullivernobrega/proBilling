<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="page-header">
    <h1>DID <small>Atualizar!</small></h1>
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
                $did_id = filter_input(INPUT_GET, "did_id", FILTER_VALIDATE_INT);

                if (isset($Data["didUpdate"])):
                    unset($Data["didUpdate"]);

                    if (!empty($Data['tipo']) && $Data['tipo'] == 'IAX' && !empty($Data['ramalIax'])):
                        unset($Data['ramalSip'], $Data['queue'], $Data['ura'], $Data['custom']);
                    endif;

                    if (!empty($Data['tipo']) && $Data['tipo'] == 'SIP' && !empty($Data['ramalSip'])):
                        unset($Data['ramalIax'], $Data['queue'], $Data['ura'], $Data['custom']);
                    endif;

                    if (!empty($Data['tipo']) && $Data['tipo'] == 'QUEUE' && !empty($Data['queue'])):
                        unset($Data['ramalIax'], $Data['ramalSip'], $Data['ura'], $Data['custom']);
                    endif;

                    if (!empty($Data['tipo']) && $Data['tipo'] == 'URA' && !empty($Data['ura'])):
                        unset($Data['ramalIax'], $Data['ramalSip'], $Data['queue'], $Data['custom']);
                    endif;

                    if (!empty($Data['tipo']) && $Data['tipo'] == 'CUSTOM' && !empty($Data['custom'])):
                        unset($Data['ramalIax'], $Data['ramalSip'], $Data['queue'], $Data['ura']);
                    endif;

                    if (empty($Data['did_hora_ss_ini']) && empty($Data['did_hora_ss_fim'])):
                        KLErro("Ops, Informe a hora inicial e final!", KL_ERROR);
                    endif;

//                    if (empty($Data['did_hora_s_ini']) || empty($Data['did_hora_s_fim'])):                    
//                        unset($Data['did_hora_s_ini'], $Data['did_hora_s_fim']);
//                    endif;
//
//                    if (empty($Data['did_hora_d_ini']) || empty($Data['did_hora_d_fim'])):    
//                        unset($Data['did_hora_d_ini'], $Data['did_hora_d_fim']);
//                    endif;
                    //VERIFICO SE EXITE O AUDIO GSM
                    //$Data['did_arquivo'] = (!empty($_FILES['did_arquivo']['tmp_name']) ? $_FILES['did_arquivo'] : null);

                    $update = new Did;
                    $update->ExeUpdate($did_id, $Data);

                    if ($update->getResult()):
                        header("Location: painel.php?exe=gerenciamento/did/lista");
                    else:
                        KLErro("Ops, não foi possivel realizar as alterações!", KL_ERROR);
                    endif;

                else:
                    
                    //Busca os dados na tabela para listagem                   
                    $read = new Read;
                    $read->ExeRead("did", "WHERE did_id = :id", "id={$did_id}");
                    
                    
                    if ($read->getResult()):
                        $res = $read->getResult();
                        $Data = $res[0];
//                        
                        //$audio = $Data['did_arquivo'];
//                        $arr = explode("/", $Data['did_destino']);
                        
                    else:
                    // header("Location: painel.php?exe=gerenciamento/did&update=false");                   
                    endif;

                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formSip" action="" method="post" id="frm" enctype="multipart/form-data">  
                    <!--DID NOME-->
                    <div class="form-group">    
                        <label for="did_nome" class="col-sm-2 control-label">Nome</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="did_nome" id="did_nome" 
                                placeholder="Nome para o Did" 
                                value="<?php if (!empty($Data['did_nome'])) echo $Data['did_nome']; ?>"
                                required 
                                autofocus
                                >
                            <!--<p class="help-block"><small>Informe somente número.</small></p>-->
                        </div>                        
                    </div>
                    <!--DID ORIGEM-->
                    <div class="form-group"> 
                        <label for="did_origem" class="col-sm-2 control-label">Origem</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="did_origem" id="did_destino" 
                                placeholder="Informe a Origem" 
                                value="<?php if (!empty($Data['did_origem'])) echo $Data['did_origem']; ?>"     
                                pattern = "[0-9]+$"
                                maxlength="15"
                                required                                 
                                >
                            <p class="help-block"><small>Somente números exe: 556299999999.</small></p>
                        </div>
                    </div> 
                    <!--DID DESTINO-->
                    <?php
                    if ($Data['did_destino_func'] == "IAX"):
                        $arr = explode("/", $Data['did_destino']);
                        $Data['ramalIax'] = $arr[1];
                        ?>
                        <div class="form-group">  
                            <label class="col-sm-2 control-label">Destino</label>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="tipo" id="iax"  value="IAX" checked="checked" > IAX
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="sip"  value="SIP"> SIP
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fqueue"  value="QUEUE"> QUEUE
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fgroup"  value="URA"> URA
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fcustom"  value="CUSTOM"> CUSTOM
                                </label> 
                            </div>
                        </div>
                        <?php
                    elseif ($Data['did_destino_func'] == "SIP"):
                        $arr = explode("/", $Data['did_destino']);
                        $Data['ramalSip'] = $arr[1];
                        ?>
                        <div class="form-group">  
                            <label class="col-sm-2 control-label">Destino</label>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="tipo" id="iax"  value="IAX" > IAX
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="sip"  value="SIP" checked="checked" > SIP
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fqueue"  value="QUEUE"> QUEUE
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fgroup"  value="URA"> URA
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fcustom"  value="CUSTOM"> CUSTOM
                                </label>
                            </div>
                        </div>
                        <?php
                    elseif (!empty($Data['did_destino_func']) && $Data['did_destino_func'] == "QUEUE"):
                        $Data['queue'] = $Data['did_destino'];
                        ?>
                        <div class="form-group">  
                            <label class="col-sm-2 control-label">Destino</label>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="tipo" id="iax"  value="IAX" > IAX
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="sip"  value="SIP" > SIP
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fqueue"  value="QUEUE" checked="checked"> QUEUE
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fgroup"  value="URA"> URA
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fcustom"  value="CUSTOM"> CUSTOM
                                </label>  
                            </div>
                        </div>
                        <?php
                    elseif ($Data['did_destino_func'] == "URA"):
                        $Data['ura'] = $Data['did_destino'];
                        ?>
                        <div class="form-group">  
                            <label class="col-sm-2 control-label">Destino</label>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="tipo" id="iax"  value="IAX" > IAX
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="sip"  value="SIP" > SIP
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fqueue"  value="QUEUE" > QUEUE
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fgroup"  value="URA" checked="checked"> URA
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fcustom"  value="CUSTOM"> CUSTOM
                                </label>  
                            </div>
                        </div>
                        <?php
                    elseif ($Data['did_destino_func'] == "CUSTOM"):
                        $Data['custom'] = $Data['did_destino'];
                        ?>
                        <div class="form-group">  
                            <label class="col-sm-2 control-label">Destino</label>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="tipo" id="iax"  value="IAX" > IAX
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="sip"  value="SIP" > SIP
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fqueue"  value="QUEUE" > QUEUE
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fgroup"  value="URA" > URA
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fcustom"  value="CUSTOM" checked="checked"> CUSTOM
                                </label>  
                            </div>
                        </div>
                        <?php
                    else:
                        ?>
                        <div class="form-group">  
                            <label class="col-sm-2 control-label">Destino</label>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="tipo" id="iax"  value="IAX" > IAX
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="sip"  value="SIP"> SIP
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fqueue"  value="QUEUE"> QUEUE
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fgroup"  value="URA"> URA
                                </label>                                
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tipo" id="fcustom"  value="CUSTOM"> CUSTOM
                                </label>                         
                            </div>
                        </div>
                    <?php
                    endif;
                    ?>
                    <!--COMPLEMENTO DESTINO-->
                    <div class="form-group">
                        <label class="col-sm-2 control-label"></label>
                        <!--DESTINO IAX-->
                        <div id="ramalIax" style="<?php echo (!empty($Data['ramalIax']) ? 'display: block;' : 'display: none;'); ?>" >                            
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

                                            if (!empty($Data['ramalIax']) && $Data['ramalIax'] == $value['iax_numero']):
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
                        <div id="ramalSip"  style="<?php echo (!empty($Data['ramalSip']) ? 'display: block;' : 'display: none;'); ?>" >
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

                                            if (!empty($Data['ramalSip']) && $Data['ramalSip'] == $value['sip_numero']):
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
                        <div id="queue" style="<?php echo (!empty($Data['queue']) ? 'display: block;' : 'display: none;'); ?>" >                            
                            <div class="col-xs-3">
                                <select class="form-control" name="queue" id="queues">
                                    <option value="">Fila Queue</option>
                                    <?php
                                    $read = new Read;
                                    $read->ExeRead("queues");

                                    if (!$read->getResult()):
                                        echo '<option disabled="disabled" value="NULL">Cadastre antes uma Queue!</option>';
                                    else:
                                        foreach ($read->getResult() as $value):
                                            //passa o id e o tipo 
                                            echo "<option value=\"{$value['queue_name']}\"";

                                            if (!empty($Data['queue']) && $Data['queue'] == $value['queue_name']):
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
                        <div id="ura-id" style="<?php echo (!empty($Data['ura']) ? 'display: block;' : 'display: none;'); ?>" >
                            <div class="col-xs-3">
                                <select class="form-control" name="ura" id="ura-id">
                                    <option value="">URA</option>
                                    <?php
                                    $read = new Read;
                                    $read->ExeRead("ura");

                                    if (!$read->getResult()):
                                        echo '<option disabled="disabled" value="NULL">Cadastre antes uma URA!</option>';
                                    else:
                                        foreach ($read->getResult() as $value):
                                            //passa o id e o tipo 
                                            echo "<option value=\"{$value['ura_nome']}\"";

                                            if (!empty($Data['ura']) && $Data['ura'] == $value['ura_nome']):
                                                echo ' selected = "selected" ';
                                            endif;

                                            echo ">{$value['ura_nome']}</option>";
                                        endforeach;
                                    endif;
                                    ?>               
                                </select> 
                                <!--<p class="help-block"><small>Informe o Ramal Queue.</small></p>-->
                            </div>
                        </div>
                        <!--DESTINO CUSTOM-->
                        <div id="custom" style="<?php echo (!empty($Data['custom']) ? 'display: block;' : 'display: none;'); ?>" >
                            <!--<label for="did_origem" class="col-sm-2 control-label">Origem</label>-->
                            <div class="col-xs-3">                                    
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="custom" id="cust" 
                                    placeholder="Informe Destino personalizado" 
                                    value="<?php
                                    if (!empty($Data['custom'])): echo $Data['custom'];
                                    endif
                                    ?>" 
                                    >
                                <!--<p class="help-block"><small>Destino personalizado.</small></p>-->
                            </div>
                        </div>
                    </div><!-- fim form-group -->
                    <!--DID_HORA_SS_INI - DID_HORA_SS_FIM -->                    
                    <div class="form-group"> 
                        <!--INICIO-->
                        <label for="did_hora_ss_ini" class="col-sm-2 control-label">Segunda a Sexta</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="did_hora_ss_ini" id="did_hora_ss_ini" 
                                placeholder="Hora inicial."                                
                                value="<?php
                                if (!empty($Data['did_hora_ss_ini'])): echo $Data['did_hora_ss_ini'];
                                endif;
                                ?>" 
                                required
                                >                            
                        </div>
                        <!--FINAL-->
                        <!--<label for="did_horas_ss_ini" class="col-sm-1 control-label">Final</label>-->
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="did_hora_ss_fim" id="did_hora_ss_fim"  
                                placeholder="Hora final."                                
                                value="<?php
                                if (!empty($Data['did_hora_ss_fim'])): echo $Data['did_hora_ss_fim'];
                                endif;
                                ?>" 
                                required
                                >                            
                        </div> 
                        <!--AUDIO-->
                        <div class="col-sm-2"> 
                            <!--<div class="form-group">-->                                                    
                            <select class="form-control" name="did_arquivo_ss" id="did_arquivo_ss">
                                <option value="">Árquivo</option>
                                <?php
                                $audio = new Read;
                                $audio->ExeRead("audio");

                                if (!$audio->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um áudio!</option>';
                                else:
                                    foreach ($audio->getResult() as $value):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value['audio_nome']}\" ";

                                        if (!empty($Data['did_arquivo_ss']) && $Data['did_arquivo_ss'] == $value['audio_nome']):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value['audio_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                            <!--</div>-->
                        </div>
                    </div>
                    <!--DID_HORA_S_INI - DID_HORA_S_FIM -->                    
                    <div class="form-group"> 
                        <!--INICIO-->
                        <label for="did_hora_s_ini" class="col-sm-2 control-label">Sabado</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="did_hora_s_ini" id="did_hora_s_ini" 
                                placeholder="Hora inicial." 
                                value="<?php if (!empty($Data['did_hora_s_ini'])) echo $Data['did_hora_s_ini']; ?>" 
                                >                            
                        </div>
                        <!--FINAL-->
                        <!--<label for="did_horas_ss_ini" class="col-sm-1 control-label">Final</label>-->
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="did_hora_s_fim" id="did_hora_s_fim" 
                                placeholder="Hora final" 
                                value="<?php if (!empty($Data['did_hora_s_fim'])) echo $Data['did_hora_s_fim']; ?>" 
                                >
                        </div>
                        <!--AUDIO-->
                        <div class="col-sm-2"> 
                            <!--<div class="form-group">-->                                                    
                            <select class="form-control" name="did_arquivo_s" id="did_arquivo_s" >
                                <option value="">Áudio</option>
                                <?php
                                $audio = new Read;
                                $audio->ExeRead("audio");

                                if (!$audio->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um áudio!</option>';
                                else:
                                    foreach ($audio->getResult() as $value):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value['audio_nome']}\" ";

                                        if (!empty($Data['did_arquivo_s']) && $Data['did_arquivo_s'] == $value['audio_nome']):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value['audio_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                            <!--</div>-->
                        </div>
                    </div>
                    <!--DID_HORA_D_INI - DID_HORA_D_FIM -->                    
                    <div class="form-group"> 
                        <!--INICIO-->
                        <label for="did_hora_d_ini" class="col-sm-2 control-label">Domingo</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="did_hora_d_ini" id="did_hora_d_ini" 
                                placeholder="Hora inicial." 
                                value="<?php if (!empty($Data['did_hora_d_ini'])) echo $Data['did_hora_d_ini']; ?>" 
                                >
                        </div>
                        <!--FINAL-->
                        <!--<label for="did_horas_ss_ini" class="col-sm-1 control-label">Final</label>-->
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="did_hora_d_fim" id="did_hora_d_fim" 
                                placeholder="Hora final" 
                                value="<?php if (!empty($Data['did_hora_d_fim'])) echo $Data['did_hora_d_fim']; ?>" 
                                >
                        </div>
                        <!--AUDIO-->
                        <div class="col-sm-2"> 
                            <!--<div class="form-group">-->                                                    
                            <select class="form-control" name="did_arquivo_d" id="did_arquivo_d">
                                <option value="">Áudio</option>
                                <?php
                                $audio = new Read;
                                $audio->ExeRead("audio");

                                if (!$audio->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um áudio!</option>';
                                else:
                                    foreach ($audio->getResult() as $value):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value['audio_nome']}\" ";

                                        if (!empty($Data['did_arquivo_d']) && $Data['did_arquivo_d'] == $value['audio_nome']):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value['audio_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                            <!--</div>-->
                        </div>                        
                    </div>
                    <!--BOTÔES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-warning" name="didUpdate" value="Atualizar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=gerenciamento/did/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->

