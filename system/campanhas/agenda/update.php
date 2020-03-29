<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Agenda <small>Atualizar!</small></h1>
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
                $agenda_id = filter_input(INPUT_GET, "agenda_id", FILTER_VALIDATE_INT);

                if (isset($Data["agendaUpdate"])):
                    unset($Data["agendaUpdate"]);

                    $update = new Agenda;
                    $update->ExeUpdate($agenda_id, $Data);

                    if ($update->getResult()):
                        //Redireciona
                        header("Location: painel.php?exe=campanhas/agenda/lista");
                    else:
                        KLErro("Ops, não foi possivel realizar as alterações!", KL_ERROR);
                    endif;
                else:

                    //Busca os dados na tabela para listagem                   
                    $readSip = new Read;
                    $readSip->ExeRead("agenda", "WHERE agenda_id = :id", "id={$agenda_id}");
                    if (!$readSip->getResult()):
                        header("Location: painel.php?exe=campanhas/agenda&update=false");
                    else:
                        $res = $readSip->getResult();
                        $Data = $res[0];
                    endif;

                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formAgenda" action="" method="post" id="frm">  
                    <!--NOME-->
                    <div class="form-group">    
                        <label for="agenda_nome" class="col-sm-2 control-label">Agenda Nome</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="agenda_nome" id="ramal" 
                                placeholder="Nome da Agenda" 
                                value="<?php if (!empty($Data['agenda_nome'])) echo $Data['agenda_nome']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o nome da agenda.</small></p>
                        </div>                        
                    </div>
                    <!--DESCRIÇÃO-->
                    <div class="form-group"> 
                        <label for="agenda_descricao" class="col-sm-2 control-label">Descrição</label>
                        <div class="col-xs-6">      
                            <textarea 
                                class="form-control" 
                                name="agenda_descricao" 
                                id="agenda_descricao"
                                rows="3" 
                                placeholder="Descrição"><?php if (!empty($Data['agenda_descricao'])) echo trim($Data['agenda_descricao']); ?>
                            </textarea>                            
                            <p class="help-block"><small>Informe uma descrição.</small></p>
                        </div>
                    </div>  
                    <!--STATUS-->
                    <div class="form-group">
                        <label for="agenda_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-lg-4">
                            <?php
                            if (!empty($Data['agenda_status']) && $Data['agenda_status'] == "A"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="agenda_status" id="agenda_status1" value="A" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="agenda_status" id="agenda_status2" value="I"> Inativo
                                </label>                                
                                <?php
                            elseif (!empty($Data['agenda_status']) && $Data['agenda_status'] == "I"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="agenda_status" id="agenda_status1" value="A" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="agenda_status" id="agenda_status2" value="I" checked="checked"> Inativo
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="agenda_status" id="agenda_status1" value="A" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="agenda_status" id="agenda_status2" value="I"> Inativo
                                </label>                               
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    <!--BOTÔES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-warning" name="agendaUpdate" value="Atualizar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=campanhas/agenda/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->

