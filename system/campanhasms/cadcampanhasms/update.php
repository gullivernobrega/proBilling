<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>CMS Campanha <small>Atualizar!</small></h1>
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
                $campanha_sms_id = filter_input(INPUT_GET, "campanha_sms_id", FILTER_VALIDATE_INT);

                if (isset($Data["campanhaUpdate"])):
                    unset($Data["campanhaUpdate"]);


                        $update = new CampanhaSms;
                        $update->ExeUpdate($campanha_sms_id, $Data);

                        if ($update->getResult()):
                            //Redireciona
                            header("Location: painel.php?exe=campanhasms/cadcampanhasms/lista");
                        else:
                            KLErro("Ops, não foi possivel realizar as alterações!", KL_ERROR);
                        endif;


                else:

                    //Busca os dados na tabela para listagem                   
                    $readSip = new Read;
                    $readSip->ExeRead("campanha_sms", "WHERE campanha_sms_id = :id", "id={$campanha_sms_id}");
                    if (!$readSip->getResult()):
                        header("Location: painel.php?exe=campanhasms/cadcampanhasms&update=false");
                    else:
                        $res = $readSip->getResult();
                        $Data = $res[0];
                    endif;

                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formCampanhaSms" action="" method="post" id="frm"> 
                    
                     <!--CAMPANHA TIPO-->
                    <div class="form-group">
                        <label for="campanha_sms_tipo" class="col-sm-2 control-label">Campanha Tipo</label>
                        <div class="col-xs-1">
                             <h4 class="txtRed"><b>SMS</b></h4> 
                            <!--<input  type="text" name="campanha_sms_tipo" id="campanha_sms_tipo" value="SMS" disabled>--> 
                            <input  type="hidden" name="campanha_sms_tipo" id="campanha_sms_tipo" value="S">
                        </div>
                    </div>                    
                    
                    <!--NOME-->
                    <div class="form-group">    
                        <label for="campanha_sms_nome" class="col-sm-2 control-label">Campanha SMS Nome</label>
                        <div class="col-xs-6">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="campanha_sms_nome" id="campanha_sms_nome" 
                                placeholder="Nome da Campanha CMS" 
                                value="<?php if (!empty($Data['campanha_sms_nome'])) echo $Data['campanha_sms_nome']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o nome da campanha.</small></p>
                        </div>                        
                    </div>
                    <!--DATA INICIO-->
                    <div class="form-group">    
                        <label for="campanha_sms_data_inicio" class="col-sm-2 control-label">Data Início</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="campanha_sms_data_inicio" id="campanha_data_inicio" 
                                placeholder="Data Inicio" 
                                value="<?php if (!empty($Data['campanha_sms_data_inicio'])) echo $Data['campanha_sms_data_inicio']; ?>" 
                                required                                 
                                >
                            <!--<p class="help-block"><small>Informe a data inicial da campanha.</small></p>-->
                        </div> 
                    </div>
                    <!--CAMPANHA AGENDA-->
                    <div class="form-group">     
                        <label for="campanha_sms_agenda" class="col-sm-2 control-label">Campanha Agenda</label>
                        <div class="col-xs-3"> 
                            <select class="form-control" name="campanha_sms_agenda" id="campanha_sms_agenda" >
                                <option value="">Agendas SMS</option>
                                <?php
                                $agenda = new Read;
                                $agenda->ExeRead("agenda_sms");

                                if (!$agenda->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes uma agenda SMS!</option>';
                                else:
                                    foreach ($agenda->getResult() as $value):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value['agenda_sms_nome']}\" ";

                                        if (!empty($Data['campanha_sms_agenda']) && $Data['campanha_sms_agenda'] == $value['agenda_sms_nome']):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value['agenda_sms_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                        </div> 
                    </div>
                   
                    <!--STATUS-->
                    <div class="form-group">
                        <label for="campanha_sms_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-lg-4">
                            <?php
                            if (!empty($Data['campanha_sms_status']) && $Data['campanha_sms_status'] == "A"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="campanha_sms_status" id="campanha_sms_status1" value="A" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="campanha_sms_status" id="campanha_sms_status2" value="I"> Inativo
                                </label>                                
                                <?php
                            elseif (!empty($Data['campanha_sms_status']) && $Data['campanha_sms_status'] == "I"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_sms_status" id="campanha_sms_status1" value="A" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_sms_status" id="campanha_sms_status2" value="I" checked="checked"> Inativo
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_sms_status" id="campanha_sms_status1" value="A" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="campanha_sms_status" id="campanha_sms_status2" value="I"> Inativo
                                </label>                               
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>

                    <!--BOTÔES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-warning" name="campanhaUpdate" value="Atualizar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=campanhasms/cadcampanhasms/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->

