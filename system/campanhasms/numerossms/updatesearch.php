<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Números SMS<small>Atualizar!</small></h1>
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
                $numero_sms_id = filter_input(INPUT_GET, "numero_sms_id", FILTER_VALIDATE_INT);
                $updateSearch = filter_input_array(INPUT_GET, FILTER_DEFAULT);
                
                if (isset($Data["numeroUpdate"])):
                    unset($Data["numeroUpdate"]); 
                    
                    $update = new NumeroSms;
                    $update->ExeUpdateSearch($Data);

                    if ($update->getResult()):
                        //Redireciona
                        header("Location: painel.php?exe=campanhasms/numerossms/lista");
                    else:
                        KLErro("Ops, não foi possivel realizar as alterações!", KL_ERROR);
                    endif;

                endif;   
                
                //Verifica a ação e recebe os dados
                if(!empty($updateSearch['acao'])):
                    unset($updateSearch['exe'], $updateSearch['acao']);
                    
                    $Data['numero_sms_lote'] = (!empty($updateSearch['numero_sms_lote'])) ? $updateSearch['numero_sms_lote'] : "";
                    $Data['agenda_sms_id'] = (!empty($updateSearch['agenda_sms_id'])) ? $updateSearch['agenda_sms_id'] : "";
                    $Data['oldStatus'] = (!empty($updateSearch['numero_sms_status'])) ? $updateSearch['numero_sms_status'] : "";
                    $Data['numero_sms_status'] = (!empty($updateSearch['numero_sms_status'])) ? $updateSearch['numero_sms_status'] : "";
                    
                endif; 
                ?>
                <form role="form" class="form-horizontal txtblue" name="formNumero" action="" method="post" id="frm">  

                    <!--STATUS-->
                    <div class="form-group">
                        <label for="numero_sms_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-lg-8">
                            <?php
                            if (!empty($Data['numero_sms_status']) && $Data['numero_sms_status'] == "A"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="numero_sms_status" id="numero_sms_status1" value="A" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="numero_sms_status" id="numero_sms_status2" value="I"> Inativo
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="P" > Pendente
                                </label>  
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="E" > Enviado
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="B" > Bloqueado
                                </label> 
                                <?php
                            elseif (!empty($Data['numero_sms_status']) && $Data['numero_sms_status'] == "I"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status1" value="A" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="I" checked="checked"> Inativo
                                </label>  
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="P" > Pendente
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="E" > Enviado
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="B" > Bloqueado
                                </label> 
                                <?php
                            elseif (!empty($Data['numero_sms_status']) && $Data['numero_sms_status'] == "P"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status1" value="A" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="I" > Inativo
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="P" checked="checked"> Pendente                                    
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="E" > Enviado
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="B" > Bloqueado
                                </label> 
                                <?php
                            elseif (!empty($Data['numero_sms_status']) && $Data['numero_sms_status'] == "E"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status1" value="A" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="I" > Inativo
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="P" > Pendente                                    
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="E" checked="checked"> Enviado
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="B" > Bloqueado
                                </label> 
                                <?php
                            elseif (!empty($Data['numero_sms_status']) && $Data['numero_sms_status'] == "B"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status1" value="A" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="I" > Inativo
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="P" > Pendente
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="E" > Enviado
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="B" checked="checked"> Bloqueado
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status1" value="A" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="I"> Inativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="P" > Pendente
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="E" > Enviado
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_sms_status" id="numero_sms_status2" value="B" > Bloqueado
                                </label> 
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>

                    <!--CAMPANHA AGENDA-->
                    <div class="form-group">     
                        
                        <div class="col-xs-3">                               
                            <input type="hidden" class="form-control" id="paramentro" name="oldStatus" value="<?php if (!empty($Data['oldStatus'])) echo $Data['oldStatus']; ?>">
                            <input type="hidden" class="form-control" id="paramentro" name="numero_sms_lote" value="<?php if (!empty($Data['numero_sms_lote'])) echo $Data['numero_sms_lote']; ?>">                            
                            <input type="hidden" class="form-control" id="paramentro" name="agenda_sms_id" value="<?php if (!empty($Data['agenda_sms_id'])) echo $Data['agenda_sms_id']; ?>">                            
                        </div> 
                    </div>

                    <!--BOTÔES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-warning" name="numeroUpdate" value="Atualizar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=campanhasms/numerossms/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->
