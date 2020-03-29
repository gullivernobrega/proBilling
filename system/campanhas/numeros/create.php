<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Numero <small>Cadastro!</small></h1>
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
                if (isset($Data["numeroCreate"])):
                    unset($Data["numeroCreate"]);
                    
                    $cadastra = new Numero;
                    $cadastra->ExeCreate($Data);
                    if ($cadastra->getResult()):
                        //Redireciona
                        header("Location: painel.php?exe=campanhas/numeros/lista");
                    else:
                        KLErro("Ops, não foi possivel realizar o cadastro!", KL_ERROR);
                    endif;
                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formNumero" action="" method="post" id="frm">                          
                    <!--NÚMERO-->                    
                    <div class="form-group">    
                        <label for="numero_fone" class="col-sm-2 control-label">Número</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="numero_fone" id="numero_fone" 
                                placeholder="DDD + numero" 
                                value="<?php if (!empty($Data['numero_fone'])) echo $Data['numero_fone']; ?>" 
                                pattern = "[0-9]+$"
                                maxlength="13"
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o numero.</small></p>
                        </div>                        
                    </div>
                    <!--NOME-->                    
                    <div class="form-group">    
                        <label for="numero_nome" class="col-sm-2 control-label">Nome</label>
                        <div class="col-xs-6">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="numero_nome" id="numero_nome" 
                                placeholder="Nome do cliente" 
                                value="<?php if (!empty($Data['numero_nome'])) echo $Data['numero_nome']; ?>"
                                >
                            <p class="help-block"><small>Informe o nome da numero.</small></p>
                        </div>                        
                    </div>

                    <div class="form-group"> 
                        <label for="numero_cpf_cnpj" class="col-sm-2 control-label"></label>
                        <div class="col-xs-2">      
                            <input 
                                type="text" 
                                class="form-control" 
                                name="numero_cpf_cnpj" id="cpfcnpj" 
                                placeholder="Cpf ou Cnpj." 
                                value="<?php if (!empty($Data['numero_cpf_cnpj'])) echo $Data['numero_cpf_cnpj']; ?>"                                  
                                maxlength="12"
                                pattern="[0-9]+$"                
                                >                         
                            <p class="help-block"><small>Informe um cpf ou cnpj somente numeros</small></p>
                        </div>
                    </div> 

                    <!--STATUS-->
                    <div class="form-group">
                        <label for="numero_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-lg-8">
                            <?php
                            if (!empty($Data['numero_status']) && $Data['numero_status'] == "A"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="numero_status" id="numero_status1" value="A" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="numero_status" id="numero_status2" value="I"> Inativo
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="P" > Pendente
                                </label>  
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="E" > Enviado
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="B" > Bloqueado
                                </label> 
                                <?php
                            elseif (!empty($Data['numero_status']) && $Data['numero_status'] == "I"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status1" value="A" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="I" checked="checked"> Inativo
                                </label>  
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="P" > Pendente
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="E" > Enviado
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="B" > Bloqueado
                                </label> 
                                <?php
                            elseif (!empty($Data['numero_status']) && $Data['numero_status'] == "P"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status1" value="A" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="I" > Inativo
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="P" checked="checked"> Pendente                                    
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="E" > Enviado
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="B" > Bloqueado
                                </label> 
                                <?php
                            elseif (!empty($Data['numero_status']) && $Data['numero_status'] == "E"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status1" value="A" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="I" > Inativo
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="P" > Pendente                                    
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="E" checked="checked"> Enviado
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="B" > Bloqueado
                                </label> 
                                <?php
                            elseif (!empty($Data['numero_status']) && $Data['numero_status'] == "B"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status1" value="A" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="I" > Inativo
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="P" > Pendente
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="E" > Enviado
                                </label>                              
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="B" checked="checked"> Bloqueado
                                </label>                              
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status1" value="A" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="I"> Inativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="P" > Pendente
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="E" > Enviado
                                </label> 
                                <label class="radio-inline">
                                    <input type="radio" name="numero_status" id="numero_status2" value="B" > Bloqueado
                                </label> 
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>

                    <!--CAMPANHA AGENDA-->
                    <div class="form-group">     
                        <label for="agenda_id" class="col-sm-2 control-label">Agenda</label>
                        <div class="col-xs-3"> 
                            <select class="form-control" name="agenda_id" id="agenda" required>
                                <option value="">Agendas</option>
                                <?php
                                $agenda = new Read;
                                $agenda->ExeRead("agenda");

                                if (!$agenda->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes uma agenda!</option>';
                                else:
                                    foreach ($agenda->getResult() as $value):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value['agenda_id']}\" ";

                                        if (!empty($Data['agenda_id']) && $Data['agenda_id'] == $value['agenda_id']):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value['agenda_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                        </div> 
                    </div>

                    <!--BOTÕES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-success" name="numeroCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=campanhas/numeros/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->