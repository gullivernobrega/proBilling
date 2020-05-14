<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Ramais IAX<small>Cadastro!</small></h1>
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
                if (isset($Data["iaxCreate"])):
                    unset($Data["iaxCreate"]);
                    $cadastra = new Iax;
                    $cadastra->ExeCreate($Data);
                    if ($cadastra->getResult()):
                        //Repassa os dados para o arquivo .conf
                        $cadConf = new Iax();
                        $cadConf->ExeConf($cadastra->getResult());
                        if ($cadConf->getResult()):
                            //Reloada no asterisk
                            shell_exec("sudo asterisk -rx 'reload'");
                            //Redireciona
                            header("Location: painel.php?exe=gerenciamento/ramal/iax/lista");
                        endif;
                    else:
                        KLErro("Ops, não foi possivel realizar o cadastro!", KL_ERROR);
                    endif;
                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formIax" action="" method="post" id="frm">                          
                    <!--RAMAL SIP-->
                    <div class="form-group">    
                        <label for="iax_numero" class="col-sm-2 control-label">Número Ramal</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="iax_numero" id="ramal" 
                                placeholder="Iax com 4 digitus" 
                                value="<?php if (!empty($Data['iax_numero'])) echo $Data['iax_numero']; ?>" 
                                maxlength="4"
                                pattern = "[0-9]+$"
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe somente número.</small></p>
                        </div>                        
                    </div>
                    <!--SENHA-->
                    <div class="form-group"> 
                        <label for="iax_senha" class="col-sm-2 control-label">Senha</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="password" 
                                class="form-control" 
                                name="iax_senha" id="iax_senha" 
                                placeholder="******" 
                                value="<?php if (!empty($Data['iax_senha'])) echo $Data['iax_senha']; ?>" 
                                minlength="6"                               
                                required                                 
                                >
                            <p class="help-block"><small>Informe uma senha mínimo 6 caracteres.</small></p>
                        </div>
                    </div>  
                    <!--CALLERID-->
                    <div class="form-group"> 
                        <label for="iax_callerid" class="col-sm-2 control-label">Callerid</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="iax_callerid" id="iax_callerid" 
                                placeholder="Número do Ramal" 
                                value="<?php if (!empty($Data['iax_callerid'])) echo $Data['iax_callerid']; ?>" 
                                maxlength="15"
                                pattern = "[0-9]+$"
                                required                                 
                                >
                            <p class="help-block"><small>Por padrão informar o mesmo iax_numero do Ramal.</small></p>
                        </div>
                    </div>
                    <!--CODEC 1-->
                    <div class="form-group">  
                        <label for="iax_codec1" class="col-sm-2 control-label">Codec 1</label>
                        <div class="col-xs-3">
                            <select class="form-control" name="iax_codec1" id="iax_host" required>
                                <option value="">Informe o Codec 1</option>
                                <option value="ulaw" <?php if (!empty($Data) && $Data['iax_codec1'] == "ulaw"): ?> selected="selected" <?php endif; ?> >Ulaw</option>
                                <option value="alaw" <?php if (!empty($Data) && $Data['iax_codec1'] == "alaw"): ?> selected="selected" <?php endif; ?>>Alaw</option>
                                <option value="ilbc" <?php if (!empty($Data) && $Data['iax_codec1'] == "ilbc"): ?> selected="selected" <?php endif; ?>>Ilbc</option>
                                <option value="gsm" <?php if (!empty($Data) && $Data['iax_codec1'] == "gsm"): ?> selected="selected" <?php endif; ?>>Gsm</option>
                                <option value="g729" <?php if (!empty($Data) && $Data['iax_codec1'] == "g729"): ?> selected="selected" <?php endif; ?>>G729</option>                                
                            </select>
                            <p class="help-block"><small>Informe o Codec 1.</small></p>
                        </div>
                    </div>
                    <!--CODEC 2-->
                    <div class="form-group">  
                        <label for="iax_codec2" class="col-sm-2 control-label">Codec 2</label>
                        <div class="col-xs-3">
                            <select class="form-control" name="iax_codec2" id="iax_host" required>
                                <option value="">Informe o Codec 2</option>
                                <option value="ulaw" <?php if (!empty($Data) && $Data['iax_codec2'] == "ulaw"): ?> selected="selected" <?php endif; ?> >Ulaw</option>
                                <option value="alaw" <?php if (!empty($Data) && $Data['iax_codec2'] == "alaw"): ?> selected="selected" <?php endif; ?>>Alaw</option>
                                <option value="ilbc" <?php if (!empty($Data) && $Data['iax_codec2'] == "ilbc"): ?> selected="selected" <?php endif; ?>>Ilbc</option>
                                <option value="gsm" <?php if (!empty($Data) && $Data['iax_codec2'] == "gsm"): ?> selected="selected" <?php endif; ?>>Gsm</option>
                                <option value="g729" <?php if (!empty($Data) && $Data['iax_codec2'] == "g729"): ?> selected="selected" <?php endif; ?>>G729</option>                                
                            </select>
                            <p class="help-block"><small>Informe o Codec 2.</small></p>
                        </div>
                    </div>
                    <!--CODEC 3-->
                    <div class="form-group">  
                        <label for="iax_codec3" class="col-sm-2 control-label">Codec 3</label>
                        <div class="col-xs-3">
                            <select class="form-control" name="iax_codec3" id="iax_host" required>
                                <option value="">Informe o Codec 3</option>
                                <option value="ulaw" <?php if (!empty($Data) && $Data['iax_codec3'] == "ulaw"): ?> selected="selected" <?php endif; ?> >Ulaw</option>
                                <option value="alaw" <?php if (!empty($Data) && $Data['iax_codec3'] == "alaw"): ?> selected="selected" <?php endif; ?>>Alaw</option>
                                <option value="ilbc" <?php if (!empty($Data) && $Data['iax_codec3'] == "ilbc"): ?> selected="selected" <?php endif; ?>>Ilbc</option>
                                <option value="gsm" <?php if (!empty($Data) && $Data['iax_codec3'] == "gsm"): ?> selected="selected" <?php endif; ?>>Gsm</option>
                                <option value="g729" <?php if (!empty($Data) && $Data['iax_codec3'] == "g729"): ?> selected="selected" <?php endif; ?>>G729</option>                                
                            </select>

                            <p class="help-block"><small>Informe o Codec 3.</small></p>
                        </div>
                    </div>
                    <!--HOST-->
                    <div class="form-group"> 
                        <label for="iax_host" class="col-sm-2 control-label">Host</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="iax_host" id="iax_host"                                 
                                value="<?php
                                if (!empty($Data['iax_host'])): echo $Data['iax_host'];
                                else: echo 'dynamic';
                                endif;
                                ?>"                                 
                                maxlength="15"
                                required                                 
                                >                         
                            <p class="help-block"><small>Informe o Host ou deixe dynamic.</small></p>
                        </div>
                    </div>
                    <!--TRUNK-->
                    <div class="form-group">
                        <label for="iax_trunk" class="col-sm-2 control-label">Trunk</label>
                        <div class="col-lg-4">
                            <?php
                            if (!empty($Data['iax_trunk']) && $Data['iax_trunk'] == "yes"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="iax_trunk" id="iax_trunk1" value="yes" checked="checked"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="iax_trunk" id="iax_trunk2" value="no"> No
                                </label>                                
                                <?php
                            elseif (!empty($Data['iax_trunk']) && $Data['iax_trunk'] == "no"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="iax_trunk" id="iax_trunk1" value="yes" > Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="iax_trunk" id="iax_trunk2" value="no" checked="checked"> No
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="iax_trunk" id="iax_trunk1" value="yes" checked="checked"> Yes
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="iax_trunk" id="iax_trunk2" value="no"> No
                                </label>                               
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    <!--BOTÕES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-success" name="iaxCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=gerenciamento/ramal/iax/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->