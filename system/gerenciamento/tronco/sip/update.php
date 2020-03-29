<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Troncos SIP <small>Atualizar!</small></h1>
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
                $tronco_id = filter_input(INPUT_GET, "tronco_id", FILTER_VALIDATE_INT);

                if (isset($Data["troncoUpdate"])):
                    unset($Data["troncoUpdate"]);

                    $update = new Troncosip;
                    $update->ExeUpdate($tronco_id, $Data);

                    if ($update->getResult()):

                        //Monta o arquivo .conf geral
                        $geralConf = new Troncosip();
                        $geralConf->ExeConfGeral();
                        if ($geralConf->getResult()):
                            
                            $geralConfReg = new Troncosip;    
                            $geralConfReg->ExeConfGeralReg();
                            if ($geralConfReg->getResult()):
                                //Reloada no asterisk
                                shell_exec("sudo asterisk -rx 'reload'");
                                //Redireciona
                                header("Location: painel.php?exe=gerenciamento/tronco/lista");
                            endif;

                        endif;

                    else:
                        KLErro("Ops, não foi possivel realizar as alterações!", KL_ERROR);
                    endif;
                else:

                    //Busca os dados na tabela para listagem                   
                    $readSip = new Read;
                    $readSip->ExeRead("tronco", "WHERE tronco_id = :id", "id={$tronco_id}");
                    if (!$readSip->getResult()):
                        header("Location: painel.php?exe=gerenciamento/tronco/sip&update=false");
                    else:
                        $res = $readSip->getResult();
                        $Data = $res[0];

                        $arrNat = explode(',', $Data['tronco_nat']);
                        $Data['tronco_nat'] = $arrNat;
                    endif;

                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formSip" action="" method="post" id="frm">                          

                    <!--NOME-->
                    <div class="form-group"> 
                        <input type="hidden" name="tronco_tipo" value="SIP">
                        <label for="tronco_nome" class="col-sm-2 control-label">Nome</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="tronco_nome" id="tronco" 
                                placeholder="Tronco nome" 
                                value="<?php if (!empty($Data['tronco_nome'])) echo $Data['tronco_nome']; ?>"                                                                 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe Nome.</small></p>
                        </div>                        
                    </div>
                    <!--USUÁRIO-->
                    <div class="form-group">    
                        <label for="tronco_username" class="col-sm-2 control-label">Usuário</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="tronco_username" id="tronco_username" 
                                placeholder="Tronco Usuário" 
                                value="<?php if (!empty($Data['tronco_username'])) echo $Data['tronco_username']; ?>"
                                >
                            <p class="help-block"><small>Informe o Usuário.</small></p>
                        </div>                        
                    </div>
                    <!--FROMUSER-->
                    <div class="form-group">    
                        <label for="tronco_fromuser" class="col-sm-2 control-label">Fromuser</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="tronco_fromuser" id="tronco_fromuser" 
                                placeholder="Tronco fromuser" 
                                value="<?php if (!empty($Data['tronco_fromuser'])) echo $Data['tronco_fromuser']; ?>"  
                                >
                            <p class="help-block"><small>Informe Fromuser.</small></p>
                        </div>                        
                    </div>
                    <!--SENHA-->
                    <div class="form-group"> 
                        <label for="tronco_senha" class="col-sm-2 control-label">Senha</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="password" 
                                class="form-control" 
                                name="tronco_senha" id="tronco_senha" 
                                placeholder="******" 
                                value="<?php if (!empty($Data['tronco_senha'])) echo $Data['tronco_senha']; ?>" 
                                >
                            <p class="help-block"><small>Informe uma senha.</small></p>
                        </div>
                    </div>
                    <!--CALLERID-->
                    <div class="form-group"> 
                        <label for="tronco_callerid" class="col-sm-2 control-label">Callerid</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="tronco_callerid" id="tronco_callerid" 
                                placeholder="Número do Callerid" 
                                value="<?php if (!empty($Data['tronco_callerid'])) echo $Data['tronco_callerid']; ?>" 
                                maxlength="15"                   
                                >
                            <p class="help-block"><small>Informe o callerid.</small></p>
                        </div>
                    </div>

                    <!--CODEC 1-->
                    <div class="form-group">  
                        <label for="tronco_codec1" class="col-sm-2 control-label">Codec 1</label>
                        <div class="col-xs-3">
                            <select class="form-control" name="tronco_codec1" id="tronco_host" required>
                                <option value="">Informe o Codec 1</option>
                                <option value="ulaw" <?php if (!empty($Data) && $Data['tronco_codec1'] == "ulaw"): ?> selected="selected" <?php endif; ?> >Ulaw</option>
                                <option value="alaw" <?php if (!empty($Data) && $Data['tronco_codec1'] == "alaw"): ?> selected="selected" <?php endif; ?>>Alaw</option>
                                <option value="ilbc" <?php if (!empty($Data) && $Data['tronco_codec1'] == "ilbc"): ?> selected="selected" <?php endif; ?>>Ilbc</option>
                                <option value="gsm" <?php if (!empty($Data) && $Data['tronco_codec1'] == "gsm"): ?> selected="selected" <?php endif; ?>>Gsm</option>
                                <option value="g729" <?php if (!empty($Data) && $Data['tronco_codec1'] == "g729"): ?> selected="selected" <?php endif; ?>>G729</option>                                
                            </select>
                            <p class="help-block"><small>Informe o Codec 1.</small></p>
                        </div>
                    </div>

                    <!--CODEC 2-->
                    <div class="form-group">  
                        <label for="tronco_codec2" class="col-sm-2 control-label">Codec 2</label>
                        <div class="col-xs-3">
                            <select class="form-control" name="tronco_codec2" id="tronco_host" required>
                                <option value="">Informe o Codec 2</option>
                                <option value="ulaw" <?php if (!empty($Data) && $Data['tronco_codec2'] == "ulaw"): ?> selected="selected" <?php endif; ?> >Ulaw</option>
                                <option value="alaw" <?php if (!empty($Data) && $Data['tronco_codec2'] == "alaw"): ?> selected="selected" <?php endif; ?>>Alaw</option>
                                <option value="ilbc" <?php if (!empty($Data) && $Data['tronco_codec2'] == "ilbc"): ?> selected="selected" <?php endif; ?>>Ilbc</option>
                                <option value="gsm" <?php if (!empty($Data) && $Data['tronco_codec2'] == "gsm"): ?> selected="selected" <?php endif; ?>>Gsm</option>
                                <option value="g729" <?php if (!empty($Data) && $Data['tronco_codec2'] == "g729"): ?> selected="selected" <?php endif; ?>>G729</option>                                
                            </select>

                            <p class="help-block"><small>Informe o Codec 2.</small></p>
                        </div>
                    </div>
                    <!--CODEC 3-->
                    <div class="form-group">  
                        <label for="tronco_codec3" class="col-sm-2 control-label">Codec 3</label>
                        <div class="col-xs-3">
                            <select class="form-control" name="tronco_codec3" id="tronco_host" required>
                                <option value="">Informe o Codec 3</option>
                                <option value="ulaw" <?php if (!empty($Data) && $Data['tronco_codec3'] == "ulaw"): ?> selected="selected" <?php endif; ?> >Ulaw</option>
                                <option value="alaw" <?php if (!empty($Data) && $Data['tronco_codec3'] == "alaw"): ?> selected="selected" <?php endif; ?>>Alaw</option>
                                <option value="ilbc" <?php if (!empty($Data) && $Data['tronco_codec3'] == "ilbc"): ?> selected="selected" <?php endif; ?>>Ilbc</option>
                                <option value="gsm" <?php if (!empty($Data) && $Data['tronco_codec3'] == "gsm"): ?> selected="selected" <?php endif; ?>>Gsm</option>
                                <option value="g729" <?php if (!empty($Data) && $Data['tronco_codec3'] == "g729"): ?> selected="selected" <?php endif; ?>>G729</option>                                
                            </select>

                            <p class="help-block"><small>Informe o Codec 3.</small></p>
                        </div>
                    </div>

                    <!--HOST-->
                    <div class="form-group"> 
                        <label for="tronco_host" class="col-sm-2 control-label">Host</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="tronco_host" id="tronco_host"                                 
                                value="<?php
                                if (!empty($Data['tronco_host'])): echo $Data['tronco_host'];
                                else: echo 'dynamic';
                                endif;
                                ?>"                                 
                                maxlength="15"                                                                
                                >                         
                            <p class="help-block"><small>Informe o Host ou deixe dynamic.</small></p>
                        </div>
                    </div>

                    <!--FROMDOMAIN-->
                    <div class="form-group"> 
                        <label for="tronco_fromdomain" class="col-sm-2 control-label">Fromdomain</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="tronco_fromdomain" id="tronco_fromdomain" 
                                placeholder="Fromdomain"                                 
                                value="<?php if (!empty($Data['tronco_fromdomain'])) echo $Data['tronco_fromdomain']; ?>"                                 
                                maxlength="15"
                                required                                 
                                >                         
                            <p class="help-block"><small>Informe o Fromdomain.</small></p>
                        </div>
                    </div>
                    
                    <!--PORTA-->
                    <div class="form-group"> 
                        <label for="tronco_port" class="col-sm-2 control-label">Porta</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="tronco_port" id="tronco_port"                                 
                                value="<?php
                                if (!empty($Data['tronco_port'])): echo $Data['tronco_port'];
                                else: echo '5060';
                                endif;
                                ?>"                                 
                                maxlength="4"
                                required
                                >                         
                            <p class="help-block"><small>Informe a Porta ou deixe padrão.</small></p>
                        </div>
                    </div>
                    
                    <!--REMOVER PREFIXO tronco_remover_prefixo`-->
                    <div class="form-group"> 
                        <label for="tronco_remover_prefixo" class="col-sm-2 control-label">Remover Prefixo</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="tronco_remover_prefixo" id="tronco_remover_prefixo" 
                                placeholder="Remover Prefixo"                                 
                                value="<?php if (!empty($Data['tronco_remover_prefixo']) && $Data['tronco_remover_prefixo'] == "0"): echo "0"; else: echo $Data['tronco_remover_prefixo']; endif;?>"                                                                                                                                 
                                >                         
                            <p class="help-block"><small>Informe se quer remover Prefixo.</small></p>
                        </div>
                    </div>
                    
                    <!--ADD PREFIXO `tronco_add_prefixo`-->
                    <div class="form-group"> 
                        <label for="tronco_add_prefixo" class="col-sm-2 control-label">Add Prefixo</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="tronco_add_prefixo" id="tronco_add_prefixo" 
                                placeholder="Add Prefixo"                                 
                                value="<?php if (!empty($Data['tronco_add_prefixo']) && $Data['tronco_add_prefixo'] == "0"): echo "0"; else: echo $Data['tronco_add_prefixo']; endif;?>"  
                                >                         
                            <p class="help-block"><small>Informe se quer adicionar Prefixo.</small></p>
                        </div>
                    </div>
                    
                    <!--DTMF MOLD-->
                    <div class="form-group">
                        <label for="tronco_dtmf_mold" class="col-sm-2 control-label">DTMF MOLD</label>
                        <div class="col-lg-4">
                            <?php
                            if (!empty($Data['tronco_dtmf_mold']) && $Data['tronco_dtmf_mold'] == "rfc2833"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="tronco_dtmf_mold" id="tronco_dtmf_mold1" value="rfc2833" checked="checked"> RFC2833
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tronco_dtmf_mold" id="tronco_dtmf_mold2" value="inband"> INBAND
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('3');" type="radio" name="tronco_dtmf_mold" id="tronco_dtmf_mold3" value="auto"> AUTO
                                </label>
                                <?php
                            elseif (!empty($Data['tronco_dtmf_mold']) && $Data['tronco_dtmf_mold'] == "inband"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="tronco_dtmf_mold" id="nivel1" value="rfc2833" > RFC2833
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="tronco_dtmf_mold" id="nivel2" value="inband" checked="checked"> INBAND
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="tronco_dtmf_mold" id="nivel3" value="auto"> AUTO
                                </label>
                                <?php
                            elseif (!empty($Data['tronco_dtmf_mold']) && $Data['tronco_dtmf_mold'] == "auto"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="tronco_dtmf_mold" id="nivel1" value="rfc2833" > RFC2833
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="tronco_dtmf_mold" id="nivel2" value="inband"> INBAND
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="tronco_dtmf_mold" id="nivel3" value="auto" checked="checked"> AUTO
                                </label>
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="tronco_dtmf_mold" id="nivel1" value="rfc2833" checked="checked"> RFC2833
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="tronco_dtmf_mold" id="nivel2" value="inband"> INBAND
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="tronco_dtmf_mold" id="nivel3" value="auto" > AUTO
                                </label>
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    <!--DIRECTMEDIA-->
                    <div class="form-group">
                        <label for="tronco_directmedia" class="col-sm-2 control-label">Directmedia?</label>
                        <div class="col-lg-4">
                            <?php
                            if (!empty($Data['tronco_directmedia']) && $Data['tronco_directmedia'] == "yes"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="tronco_directmedia" id="tronco_directmedia1" value="yes" checked="checked"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tronco_directmedia" id="tronco_directmedia2" value="no"> No
                                </label>                                
                                <?php
                            elseif (!empty($Data['tronco_directmedia']) && $Data['tronco_directmedia'] == "no"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="tronco_directmedia" id="tronco_directmedia1" value="yes" > Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="tronco_directmedia" id="tronco_directmedia2" value="no" checked="checked"> No
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="tronco_directmedia" id="tronco_directmedia1" value="yes" checked="checked"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="tronco_directmedia" id="tronco_directmedia2" value="no"> No
                                </label>                               
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>

                    <!--NAT-->
                    <div class="form-group">  
                        <label for="tronco_nat" class="col-sm-2 control-label">NAT</label> 
                        <div class="col-xs-6">    
                            <label class="checkbox-inline">                                
                                <input type="checkbox" name="tronco_nat[]" id="tronco_nat1" value="no" 
                                <?php
                                if (!empty($Data['tronco_nat'])):
                                    foreach ($Data['tronco_nat'] as $val): if ($val == 'no'): echo "checked";
                                        endif;
                                    endforeach;
                                endif;
                                ?>> No
                            </label>
                            <label class="checkbox-inline">                                
                                <input type="checkbox" name="tronco_nat[]" id="tronco_nat2" value="force_rport" 
                                <?php
                                if (!empty($Data['tronco_nat'])):
                                    foreach ($Data['tronco_nat'] as $val): if ($val == 'force_rport'): echo "checked";
                                        endif;
                                    endforeach;
                                endif;
                                ?>> Force_Rport                              
                            </label>
                            <label class="checkbox-inline">                                
                                <input type="checkbox" name="tronco_nat[]" id="tronco_nat3" value="comedia" 
                                <?php
                                if (!empty($Data['tronco_nat'])):
                                    foreach ($Data['tronco_nat'] as $val): if ($val == 'comedia'): echo "checked";
                                        endif;
                                    endforeach;
                                endif;
                                ?>> Comedia                              
                            </label>
                            <label class="checkbox-inline">                                
                                <input type="checkbox" name="tronco_nat[]" id="tronco_nat4" value="auto_force_rport" 
                                <?php
                                if (!empty($Data['tronco_nat'])):
                                    foreach ($Data['tronco_nat'] as $val): if ($val == 'auto_force_rport'): echo "checked";
                                        endif;
                                    endforeach;
                                endif;
                                ?>> Auto_Force_Rport                              
                            </label>
                            <label class="checkbox-inline">                                
                                <input type="checkbox" name="tronco_nat[]" id="tronco_nat5" value="auto_comedia" 
                                <?php
                                if (!empty($Data['tronco_nat'])):
                                    foreach ($Data['tronco_nat'] as $val): if ($val == 'auto_comedia'): echo "checked";
                                        endif;
                                    endforeach;
                                endif;
                                ?>> Auto_Comedia                              
                            </label>
                        </div>
                    </div>
                    <!--INSECURE-->
                    <div class="form-group"> 
                        <label for="tronco_insecure" class="col-sm-2 control-label">Insecure</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="tronco_insecure" id="tronco_host"                                 
                                value="<?php
                                if (!empty($Data['tronco_insecure'])): echo $Data['tronco_insecure'];
                                else: echo 'port,invite';
                                endif;
                                ?>"  
                                required                                 
                                >                         
                            <p class="help-block"><small>Informe o Insecure.</small></p>
                        </div>
                    </div>

                    <!--REGISTER-->
                    <div class="form-group"> 
                        <label for="tronco_register" class="col-sm-2 control-label">Register</label>
                        <div class="col-xs-5">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="tronco_register" id="tronco_register"  
                                placeholder="Tronco Register" 
                                value="<?php
                                if (!empty($Data['tronco_register'])): echo $Data['tronco_register'];
                                endif;
                                ?>"                                                            
                                >                         
                            <p class="help-block"><small>Informe o Register ou deixe em branco.</small></p>
                        </div>
                    </div>

                    <!--QUALIFILY-->
                    <div class="form-group">     
                        <label for="tronco_qualify" class="col-sm-2 control-label">Qualify</label>
                        <div class="col-xs-7">

                            <?php
                            if (!empty($Data['tronco_qualify']) && $Data['tronco_qualify'] == 'yes'):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="tronco_qualify" id="tronco_qualify1" value="yes" checked="checked"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tronco_qualify" id="tronco_qualify2" value="no"> No
                                </label>                                
                                <?php
                            elseif (!empty($Data['tronco_qualify']) && $Data['tronco_qualify'] == 'no'):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="tronco_qualify" id="tronco_qualify1" value="yes" > Yes
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tronco_qualify" id="tronco_qualify2" value="no" checked="checked"> No
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="tronco_qualify" id="tronco_qualify1" value="yes" checked="checked"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="tronco_qualify" id="tronco_qualify2" value="no"> No
                                </label>                               
                            <?php
                            endif;
                            ?>
                            <p class="help-block"><small>Ativar ou não o controle de qualidade.</small></p>
                        </div>
                    </div> 

                    <!--BOTÔES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-warning" name="troncoUpdate" value="Atualizar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=gerenciamento/tronco/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->

