<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Agenda SMS <small>Cadastro!</small></h1>
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
                if (isset($Data["agendaCreate"])):
                    unset($Data["agendaCreate"]);

                    $cadastra = new AgendaSms;
                    $cadastra->ExeCreate($Data);
                    if ($cadastra->getResult()):
                        //Redireciona
                        header("Location: painel.php?exe=campanhasms/agendasms/lista");
                    else:
                        KLErro("Ops, não foi possivel realizar o cadastro!", KL_ERROR);
                    endif;
                endif;
                ?>
                <!--`agenda_sms_id`, `agenda_sms_msg`, `agenda_sms_status`-->
                <form role="form" class="form-horizontal txtblue" name="formAgendasms" action="" method="post" id="frm"> 
                    <!--NOME-->
                    <div class="form-group">    
                        <label for="agenda_sms_nome" class="col-sm-2 control-label">Agenda Nome</label>
                        <div class="col-xs-6">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="agenda_sms_nome" id="agenda_sms_nome" 
                                placeholder="Nome da Agenda SMS" 
                                value="<?php if (!empty($Data['agenda_sms_nome'])) echo $Data['agenda_sms_nome']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o nome da agenda SMS.</small></p>
                        </div>                        
                    </div>
                
                    <!--STATUS-->
                    <div class="form-group">
                        <label for="agenda_sms_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-lg-4">
                            <?php
                            if (!empty($Data['agenda_sms_status']) && $Data['agenda_sms_status'] == "A"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="agenda_sms_status" id="agenda_sms_status1" value="A" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="agenda_sms_status" id="agenda_sms_status2" value="I"> Inativo
                                </label>                                
                                <?php
                            elseif (!empty($Data['agenda_sms_status']) && $Data['agenda_sms_status'] == "I"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="agenda_sms_status" id="agenda_sms_status1" value="A" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="agenda_sms_status" id="agenda_sms_status2" value="I" checked="checked"> Inativo
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="agenda_sms_status" id="agenda_sms_status1" value="A" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="agenda_sms_status" id="agenda_sms_status2" value="I"> Inativo
                                </label>                               
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    <!--BOTÕES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-success" name="agendaCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=campanhasms/agendasms/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->