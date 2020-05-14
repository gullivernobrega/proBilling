<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Numeros <small>Cadastro com Importação Txt!</small></h1>
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
                if (isset($Data["sendCreate"])):
                    unset($Data["sendCreate"]);

                    //VERIFICO SE EXITE O AUDIO GSM
                    $Data['arquivo'] = ( $_FILES['arquivo']['tmp_name'] ? $_FILES['arquivo'] : null);
                    
                    $cadastra = new Numero;
                    $cadastra->ExeCreateMult($Data);
                    if ($cadastra->getResult()):
                        //Redireciona
                        header("Location: painel.php?exe=campanhas/numeros/lista");
                    else:
                        KLErro("Ops, não foi possivel realizar o cadastro!", KL_ERROR);
                    endif;
                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formNumeros" action="" method="post" id="frm" enctype="multipart/form-data">  
                    <div class="form-group">
                        <label for="arquivo" class="col-sm-2 control-label">Arquivo CSV</label>
                        <div class="col-lg-4">
                            <!--<input type="hidden" name="MAX_FILE_SIZE" value="73400320">-->
                            <input type="file" id="arquivo" name="arquivo" >
                            <p class="help-block">Informe o arquivo de importação CSV.</p>
                            <input type="hidden" name="numero_status" value="A">
                        </div>
                    </div>

                    <!--DELIMITER-->
                    <div class="form-group">  
                        <label for="delimiter" class="col-sm-2 control-label">Delimitador</label>
                        <div class="col-xs-3">
                            <select class="form-control" name="delimiter" id="sip_host" required>
                                <option value="">Delimitador</option>
                                <option value="," <?php if (!empty($Data) && $Data['delimiter'] == ","): ?> selected="selected" <?php endif; ?> >Virgula (,)</option>
                                <option value=";" <?php if (!empty($Data) && $Data['delimiter'] == ";"): ?> selected="selected" <?php endif; ?>>Ponto e Virgula (;)</option>                                                        
                            </select>

                            <p class="help-block"><small>Informe o delimitador do seu arquivo.</small></p>
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
                        <input type="submit" class="btn btn-success" name="sendCreate" value="Enviar Dados Txt">                        
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