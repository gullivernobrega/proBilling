<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Números SMS <small>Atualizar!</small></h1>
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

                if (isset($Data["numeroUpdate"])):
                    unset($Data["numeroUpdate"]);

                    $update = new NumeroSms;
                    $update->ExeUpdate($numero_sms_id, $Data);

                    if ($update->getResult()):
                        //Redireciona
                        header("Location: painel.php?exe=campanhasms/numerossms/lista");
                    else:
                        KLErro("Ops, não foi possivel realizar as alterações!", KL_ERROR);
                    endif;

                else:

                    //Busca os dados na tabela para listagem                   
                    $readSip = new Read;
                    $readSip->ExeRead("numero_sms", "WHERE numero_sms_id = :id", "id={$numero_sms_id}");
                    if (!$readSip->getResult()):
                        header("Location: painel.php?exe=campanhasms/numerossms&update=false");
                    else:
                        $res = $readSip->getResult();
                        $Data = $res[0];
                    endif;

                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formNumeroSms" action="" method="post" id="frm">  

                    <!--NÚMERO-->                    
                    <div class="form-group">    
                        <label for="numero_sms_fone" class="col-sm-2 control-label">Número</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="numero_sms_fone" id="numero_sms_fone" 
                                placeholder="DDD + numero" 
                                value="<?php if (!empty($Data['numero_sms_fone'])) echo $Data['numero_sms_fone']; ?>" 
                                pattern = "[0-9]+$"
                                maxlength="13"
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o numero SMS.</small></p>
                        </div>                        
                    </div>
                    <!--MENSAGEM-->                    
                    <div class="form-group">    
                        <label for="numero_sms_msg" class="col-sm-2 control-label">Mensagem</label>
                        <div class="col-xs-6">     
                            <textarea class="form-control" rows="3" name="numero_sms_msg" id="numero_sms_msg"><?php if (!empty($Data['numero_sms_msg'])): echo ltrim($Data['numero_sms_msg']); endif;?></textarea>   
                            <p class="help-block"><small>Informe a mensagem.</small></p>
                        </div>                        
                    </div>
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
                        <label for="agenda_sms_id" class="col-sm-2 control-label">Agenda</label>
                        <div class="col-xs-3"> 
                            <select class="form-control" name="agenda_sms_id" id="agenda" required>
                                <option value="">Agendas</option>
                                <?php
                                $agenda = new Read;
                                $agenda->ExeRead("agenda_sms");

                                if (!$agenda->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes uma agenda SMS!</option>';
                                else:
                                    foreach ($agenda->getResult() as $value):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value['agenda_sms_id']}\" ";

                                        if (!empty($Data['agenda_sms_id']) && $Data['agenda_sms_id'] == $value['agenda_sms_id']):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value['agenda_sms_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                        </div> 
                    </div>
                    
                    <!--NÚMERO DO LOTE--> 
                    <div class="form-group">    
                        <label for="numero_sms_lote" class="col-sm-2 control-label">Nome do Lote</label>
                        <div class="col-xs-3">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="numero_sms_lote" id="numero_sms_lote" 
                                placeholder="Nome para o Lote" 
                                value="<?php if (!empty($Data['numero_sms_lote'])) echo $Data['numero_sms_lote']; ?>"                                 
                                required                                 
                                >
                            <p class="help-block"><small>Informe um nome para o Lote.</small></p>
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

