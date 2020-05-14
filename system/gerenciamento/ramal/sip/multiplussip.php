<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Multiplus Ramais SIP<small>Cadastro!</small></h1>
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
                
                //Verifica se houve um submit
                if (isset($Data["sipCreate"])):
                    unset($Data["sipCreate"]);

                    //verifica se o número inicial e menor que o número final
                    if ($Data['sipInicial'] < $Data['sipFinal']):

                        //Verifica se existe o sip_nat
                        if (!empty($Data) && !empty($Data['sip_nat'])):
                            //Instância a classe
                            $cadastra = new Sipmult;
                            $cadastra->ExeCreate($Data);
                            //Verifica se houve um resultado
                            if ($cadastra->getResult()):
                                $result = $cadastra->getResult();
                            
                                //Verifica duplicidade de arquivo
                                if ($result == 'N'):
                                    $erro = $cadastra->getErro();
                                    echo KLErro($erro[0], $erro[1]);
                                else:

                                    //Repassa os dados para o arquivo .conf
                                    $cadConf = new Sipmult();
                                    $cadConf->ExeConfGeral();
                                    if ($cadConf->getResult()):
                                        //Reloada no asterisk
                                        shell_exec("sudo asterisk -rx 'reload'");
                                        //Redireciona
                                        header("Location: painel.php?exe=gerenciamento/ramal/sip/lista");
                                    endif;

                                endif;

                            else:
                                KLErro("Ops, não foi possivel realizar o cadastro!", KL_ERROR);
                            endif;

                        else:
                            KLErro("Falta informar o Nat", KL_INFOR);
                        endif;

                    else:
                        KLErro("Ops, O número inicial não pode ser maior que o número final. Verifique!", KL_ERROR);
                    endif;

                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formSip" action="" method="post" id="frm">                          
                    <!--RAMAL SIP INICIO-->
                    <div class="form-group">    
                        <label for="sipInicial" class="col-sm-2 control-label">Ramal Inicial</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="sipInicial" id="ramal" 
                                placeholder="Sip Inicio 4 digitos" 
                                value="<?php if (!empty($Data['sipInicial'])) echo $Data['sipInicial']; ?>" 
                                maxlength="4"
                                pattern = "[0-9]+$"
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe somente número.</small></p>
                        </div>                        
                    </div>
                    <!--RAMAL SIP FINAL-->
                    <div class="form-group">    
                        <label for="sipFinal" class="col-sm-2 control-label">Ramal Final</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="sipFinal" id="ramal" 
                                placeholder="Sip final 4 digitos" 
                                value="<?php if (!empty($Data['sipFinal'])) echo $Data['sipFinal']; ?>" 
                                maxlength="4"
                                pattern = "[0-9]+$"
                                required                                 
                                >
                            <p class="help-block"><small>Informe somente número.</small></p>
                        </div>                        
                    </div>

                    <!--HOST-->
                    <div class="form-group"> 
                        <label for="sip_host" class="col-sm-2 control-label">Host</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="sip_host" id="sip_host"                                 
                                value="<?php
                                if (!empty($Data['sip_host'])): echo $Data['sip_host'];
                                else: echo 'dynamic';
                                endif;
                                ?>"                                 
                                maxlength="15"
                                required                                 
                                >                         
                            <p class="help-block"><small>Informe o Host ou deixe dynamic.</small></p>
                        </div>
                    </div>
                    <!--DTMF MOLD-->
                    <div class="form-group">
                        <label for="sip_dtmf_mold" class="col-sm-2 control-label">DTMF MOLD</label>
                        <div class="col-lg-4">
                            <?php
                            if (!empty($Data['sip_dtmf_mold']) && $Data['sip_dtmf_mold'] == "rfc2833"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="sip_dtmf_mold" id="sip_dtmf_mold1" value="rfc2833" checked="checked"> RFC2833
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="sip_dtmf_mold" id="sip_dtmf_mold2" value="inband"> INBAND
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('3');" type="radio" name="sip_dtmf_mold" id="sip_dtmf_mold3" value="auto"> AUTO
                                </label>
                                <?php
                            elseif (!empty($Data['sip_dtmf_mold']) && $Data['sip_dtmf_mold'] == "inband"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="sip_dtmf_mold" id="nivel1" value="rfc2833" > RFC2833
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="sip_dtmf_mold" id="nivel2" value="inband" checked="checked"> INBAND
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="sip_dtmf_mold" id="nivel3" value="auto"> AUTO
                                </label>
                                <?php
                            elseif (!empty($Data['sip_dtmf_mold']) && $Data['sip_dtmf_mold'] == "auto"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="sip_dtmf_mold" id="nivel1" value="rfc2833" > RFC2833
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="sip_dtmf_mold" id="nivel2" value="inband"> INBAND
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="sip_dtmf_mold" id="nivel3" value="auto" checked="checked"> AUTO
                                </label>
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="sip_dtmf_mold" id="nivel1" value="rfc2833" checked="checked"> RFC2833
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="sip_dtmf_mold" id="nivel2" value="inband"> INBAND
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="sip_dtmf_mold" id="nivel3" value="auto" > AUTO
                                </label>
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    <!--DIRECTMEDIA-->
                    <div class="form-group">
                        <label for="sip_directmedia" class="col-sm-2 control-label">Directmedia?</label>
                        <div class="col-lg-4">
                            <?php
                            if (!empty($Data['sip_directmedia']) && $Data['sip_directmedia'] == "yes"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="sip_directmedia" id="sip_directmedia1" value="yes" checked="checked"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="sip_directmedia" id="sip_directmedia2" value="no"> No
                                </label>                                
                                <?php
                            elseif (!empty($Data['sip_directmedia']) && $Data['sip_directmedia'] == "no"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="sip_directmedia" id="sip_directmedia1" value="yes" > Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="sip_directmedia" id="sip_directmedia2" value="no" checked="checked"> No
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="sip_directmedia" id="sip_directmedia1" value="yes" checked="checked"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="sip_directmedia" id="sip_directmedia2" value="no"> No
                                </label>                               
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>

                    <!--NAT-->
                    <div class="form-group">  
                        <label for="sip_nat" class="col-sm-2 control-label">NAT</label> 
                        <div class="col-xs-6">    
                            <label class="checkbox-inline">                                
                                <input type="checkbox" name="sip_nat[]" id="sip_qualifily1" value="no" 
                                <?php
                                if (!empty($Data['sip_nat'])):
                                    foreach ($Data['sip_nat'] as $val): if ($val == 'no'): echo "checked";
                                        endif;
                                    endforeach;
                                endif;
                                ?>> No
                            </label>
                            <label class="checkbox-inline">                                
                                <input type="checkbox" name="sip_nat[]" id="sip_qualifily2" value="force_rport" 
                                <?php
                                if (!empty($Data['sip_nat'])):
                                    foreach ($Data['sip_nat'] as $val): if ($val == 'force_rport'): echo "checked";
                                        endif;
                                    endforeach;
                                endif;
                                ?>> Force_Rport                              
                            </label>
                            <label class="checkbox-inline">                                
                                <input type="checkbox" name="sip_nat[]" id="sip_qualifily3" value="comedia" 
                                <?php
                                if (!empty($Data['sip_nat'])):
                                    foreach ($Data['sip_nat'] as $val): if ($val == 'comedia'): echo "checked";
                                        endif;
                                    endforeach;
                                endif;
                                ?>> Comedia                              
                            </label>
                            <label class="checkbox-inline">                                
                                <input type="checkbox" name="sip_nat[]" id="sip_qualifily4" value="auto_force_rport" 
                                <?php
                                if (!empty($Data['sip_nat'])):
                                    foreach ($Data['sip_nat'] as $val): if ($val == 'auto_force_rport'): echo "checked";
                                        endif;
                                    endforeach;
                                endif;
                                ?>> Auto_Force_Rport                              
                            </label>
                            <label class="checkbox-inline">                                
                                <input type="checkbox" name="sip_nat[]" id="sip_qualifily5" value="auto_comedia" 
                                <?php
                                if (!empty($Data['sip_nat'])):
                                    foreach ($Data['sip_nat'] as $val): if ($val == 'auto_comedia'): echo "checked";
                                        endif;
                                    endforeach;
                                endif;
                                ?>> Auto_Comedia                              
                            </label>
                        </div>
                    </div>

                    <!--CODEC 1-->
                    <div class="form-group">  
                        <label for="sip_codec1" class="col-sm-2 control-label">Codec 1</label>
                        <div class="col-xs-3">
                            <select class="form-control" name="sip_codec1" id="sip_host" required>
                                <option value="">Informe o Codec 1</option>
                                <option value="ulaw" <?php if (!empty($Data) && $Data['sip_codec1'] == "ulaw"): ?> selected="selected" <?php endif; ?> >Ulaw</option>
                                <option value="alaw" <?php if (!empty($Data) && $Data['sip_codec1'] == "alaw"): ?> selected="selected" <?php endif; ?>>Alaw</option>
                                <option value="ilbc" <?php if (!empty($Data) && $Data['sip_codec1'] == "ilbc"): ?> selected="selected" <?php endif; ?>>Ilbc</option>
                                <option value="gsm" <?php if (!empty($Data) && $Data['sip_codec1'] == "gsm"): ?> selected="selected" <?php endif; ?>>Gsm</option>
                                <option value="g729" <?php if (!empty($Data) && $Data['sip_codec1'] == "g729"): ?> selected="selected" <?php endif; ?>>G729</option>                                
                            </select>
                            <p class="help-block"><small>Informe o Codec 1.</small></p>
                        </div>
                    </div>
                    <!--CODEC 2-->
                    <div class="form-group">  
                        <label for="sip_codec2" class="col-sm-2 control-label">Codec 2</label>
                        <div class="col-xs-3">
                            <select class="form-control" name="sip_codec2" id="sip_host" required>
                                <option value="">Informe o Codec 2</option>
                                <option value="ulaw" <?php if (!empty($Data) && $Data['sip_codec2'] == "ulaw"): ?> selected="selected" <?php endif; ?> >Ulaw</option>
                                <option value="alaw" <?php if (!empty($Data) && $Data['sip_codec2'] == "alaw"): ?> selected="selected" <?php endif; ?>>Alaw</option>
                                <option value="ilbc" <?php if (!empty($Data) && $Data['sip_codec2'] == "ilbc"): ?> selected="selected" <?php endif; ?>>Ilbc</option>
                                <option value="gsm" <?php if (!empty($Data) && $Data['sip_codec2'] == "gsm"): ?> selected="selected" <?php endif; ?>>Gsm</option>
                                <option value="g729" <?php if (!empty($Data) && $Data['sip_codec2'] == "g729"): ?> selected="selected" <?php endif; ?>>G729</option>                                
                            </select>

                            <p class="help-block"><small>Informe o Codec 2.</small></p>
                        </div>
                    </div>
                    <!--CODEC 3-->
                    <div class="form-group">  
                        <label for="sip_codec3" class="col-sm-2 control-label">Codec 3</label>
                        <div class="col-xs-3">
                            <select class="form-control" name="sip_codec3" id="sip_host" required>
                                <option value="">Informe o Codec 3</option>
                                <option value="ulaw" <?php if (!empty($Data) && $Data['sip_codec3'] == "ulaw"): ?> selected="selected" <?php endif; ?> >Ulaw</option>
                                <option value="alaw" <?php if (!empty($Data) && $Data['sip_codec3'] == "alaw"): ?> selected="selected" <?php endif; ?>>Alaw</option>
                                <option value="ilbc" <?php if (!empty($Data) && $Data['sip_codec3'] == "ilbc"): ?> selected="selected" <?php endif; ?>>Ilbc</option>
                                <option value="gsm" <?php if (!empty($Data) && $Data['sip_codec3'] == "gsm"): ?> selected="selected" <?php endif; ?>>Gsm</option>
                                <option value="g729" <?php if (!empty($Data) && $Data['sip_codec3'] == "g729"): ?> selected="selected" <?php endif; ?>>G729</option>                                
                            </select>

                            <p class="help-block"><small>Informe o Codec 3.</small></p>
                        </div>
                    </div>
                    <!--QUALIFILY-->
                    <div class="form-group">     
                        <label for="sip_qualifily" class="col-sm-2 control-label">Qualify</label>
                        <div class="col-xs-7">

                            <?php
                            if (!empty($Data['sip_qualifily']) && $Data['sip_qualifily'] == 'yes'):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="sip_qualifily" id="sip_qualifily1" value="yes" checked="checked"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="sip_qualifily" id="sip_qualifily2" value="no"> No
                                </label>                                
                                <?php
                            elseif (!empty($Data['sip_qualifily']) && $Data['sip_qualifily'] == 'no'):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="sip_qualifily" id="sip_qualifily1" value="yes" > Yes
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="sip_qualifily" id="sip_qualifily2" value="no" checked="checked"> No
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="sip_qualifily" id="sip_qualifily1" value="yes" checked="checked"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="sip_qualifily" id="sip_qualifily2" value="no"> No
                                </label>                               
                            <?php
                            endif;
                            ?>
                            <p class="help-block"><small>Ativar ou não o controle de qualidade.</small></p>
                        </div>
                    </div> 
                    <!--BOTÕES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-success" name="sipCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=gerenciamento/ramal/sip/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->