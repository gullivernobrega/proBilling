<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Rotas<small> Cadastro e Alteração!</small></h1>
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
                if (isset($Data["rotaCreate"]) && $Data['acao'] == "Adicionar"):
                    unset($Data["rotaCreate"]);
                    $acao = $Data['acao'];
                    unset($Data['acao']);

                    $cadastra = new Rotas();
                    $cadastra->ExeCreate($Data);
                    if ($cadastra->getResult()):
                        //Repassa os dados para o arquivo .conf
                        header("Location: painel.php?exe=gerenciamento/rotas/create");
                    else:
                        KLErro("Ops, não foi possivel realizar o cadastro!", KL_ERROR);
                    endif;

                else:

                    //Busca os dados na tabela para listagem                   
                    $read = new Read;
                    $read->ExeRead("rotas");
                    if ($read->getRowCount() > 0):
                        $acao = "Editar";
                    else:
                        $acao = "Adicionar";
                    endif;

                endif;

                /*                 * *************** FORMULARO CREATE ROTAS  ****************** */
                if ($acao == "Adicionar"):
                    //echo $acao;
                    ?>
                    <!--class="form-horizontal"-->
                    <form role="form" class="form-horizontal txtblue" name="formIax" action="" method="post" id="frm">                          

                        <div class="container-fluid">
                            <div class="row">

                                <!--Coluna 1-->
                                <div class="col-sm-4">  
                                    <h3>FIXO</h3>
                                    <!--MASTER-->
                                    <div class="form-group">  
                                        <!-- rota_tipo`, `tronco_id_fixo`, `rota_tronco_fixo_tipo`, `tronco_id_movel`, `rota_tronco_movel_tipo`, 
                                        `tronco_id_inter`, `rota_tronco_inter_tipo` -->
                                        <label for="tronco_id-masterFixo" class="col-sm-3 control-label">Master</label>
                                        <div class="col-xs-9">                                               
                                            <input type="hidden" name="rota_tipo-masterFixo" value="master">                                                                                           
                                            <select class="form-control" name="tronco_id_fixo-master" id="tronco" required>
                                                <option value="">Master</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['tronco_id-masterFixo'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select>  

                                        </div>
                                    </div>
                                    <!--BACKUP 1-->
                                    <!-- rota_tipo`, `tronco_id_fixo`, `rota_tronco_fixo_tipo`, `tronco_id_movel`, `rota_tronco_movel_tipo`, 
                                        `tronco_id_inter`, `rota_tronco_inter_tipo` -->
                                    <div class="form-group">  
                                        <label for="iax_codec2" class="col-sm-3 control-label">Backup 1</label>
                                        <div class="col-xs-9">
                                            <input type="hidden" name="rota_tipo-backup1Fixo" value="backup1">

                                            <select class="form-control" name="tronco_id_fixo-backup1" id="tronco">
                                                <option value="">Backup1</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['troncoiax'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select>                                             
                                        </div>
                                    </div>
                                    <!--BACKUP 2-->
                                    <!-- rota_tipo`, `tronco_id_fixo`, `rota_tronco_fixo_tipo`, `tronco_id_movel`, `rota_tronco_movel_tipo`, 
                                        `tronco_id_inter`, `rota_tronco_inter_tipo` -->
                                    <div class="form-group">  
                                        <label for="iax_codec3" class="col-sm-3 control-label">Backup 2</label>
                                        <div class="col-xs-9">
                                            <input type="hidden" name="rota_tipo-backup2Fixo" value="backup2">                                            
                                            <select class="form-control" name="tronco_id_fixo-backup2" id="tronco">
                                                <option value="">Backup2</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['troncoiax'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                            <!--<p class="help-block"><small>Informe o Codec 3.</small></p>-->
                                        </div>
                                    </div>  

                                </div>

                                <!--Coluna 2-->
                                <!-- rota_tipo`, `tronco_id_fixo`, `rota_tronco_fixo_tipo`, `tronco_id_movel`, `rota_tronco_movel_tipo`, 
                                        `tronco_id_inter`, `rota_tronco_inter_tipo` -->
                                <div class="col-sm-4"> 
                                    <h3>MOVEL</h3>
                                    <!--MASTER_M-->
                                    <div class="form-group">  
                                        <!--<label for="iax_codec1" class="col-sm-2 control-label">Codec 1</label>-->
                                        <div class="col-xs-12">
                                            <input type="hidden" name="rota_tipo-masterMovel" value="master">
                                            <select class="form-control" name="tronco_id_movel-master" id="tronco" required>
                                                <option value="">Master</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['troncoiax'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select>                                             
                                        </div>
                                    </div>
                                    <!--BACKUP 1-->
                                    <!-- rota_tipo`, `tronco_id_fixo`, `rota_tronco_fixo_tipo`, `tronco_id_movel`, `rota_tronco_movel_tipo`, 
                                        `tronco_id_inter`, `rota_tronco_inter_tipo` -->
                                    <div class="form-group">  
                                        <!--<label for="iax_codec2" class="col-sm-2 control-label">Codec 2</label>-->
                                        <div class="col-xs-12">
                                            <input type="hidden" name="rota_tipo-backup1Movel" value="backup1">                                            
                                            <select class="form-control" name="tronco_id_movel-backup1" id="tronco" >
                                                <option value="">Backup1</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['troncoiax'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                            <!--<p class="help-block"><small>Informe o Codec 2.</small></p>-->
                                        </div>
                                    </div>
                                    <!--BACKUP 2-->
                                    <!-- rota_tipo`, `tronco_id_fixo`, `rota_tronco_fixo_tipo`, `tronco_id_movel`, `rota_tronco_movel_tipo`, 
                                        `tronco_id_inter`, `rota_tronco_inter_tipo` -->
                                    <div class="form-group">  
                                        <!--<label for="iax_codec3" class="col-sm-2 control-label">Codec 3</label>-->
                                        <div class="col-xs-12">
                                            <input type="hidden" name="rota_tipo-backup2Movel" value="backup2">

                                            <select class="form-control" name="tronco_id_movel-backup2" id="tronco" >
                                                <option value="">Backup2</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['troncoiax'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                            <!--<p class="help-block"><small>Informe o Codec 3.</small></p>-->
                                        </div>
                                    </div>  

                                </div>
                                <!--coluna 3-->
                                <!-- rota_tipo`, `tronco_id_fixo`, `rota_tronco_fixo_tipo`, `tronco_id_movel`, `rota_tronco_movel_tipo`, 
                                        `tronco_id_inter`, `rota_tronco_inter_tipo` -->
                                <div class="col-sm-4"> 
                                    <h3>INTERNACIONAL</h3>
                                    <!--MASTER-->
                                    <div class="form-group">  
                                        <!--<label for="iax_codec1" class="col-sm-2 control-label">Codec 1</label>-->
                                        <div class="col-xs-12">
                                            <input type="hidden" name="rota_tipo-masterInter" value="master">                                            
                                            <select class="form-control" name="tronco_id_inter-master" id="tronco" required>
                                                <option value="">Master</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['troncoiax'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                            <!--<p class="help-block"><small>Informe o Codec 1.</small></p>-->
                                        </div>
                                    </div>
                                    <!--BACKUP 1-->
                                    <!-- rota_tipo`, `tronco_id_fixo`, `rota_tronco_fixo_tipo`, `tronco_id_movel`, `rota_tronco_movel_tipo`, 
                                        `tronco_id_inter`, `rota_tronco_inter_tipo` -->
                                    <div class="form-group">  
                                        <!--<label for="iax_codec2" class="col-sm-2 control-label">Codec 2</label>-->
                                        <div class="col-xs-12">
                                            <input type="hidden" name="rota_tipo-backup1Inter" value="backup1">                                            
                                            <select class="form-control" name="tronco_id_inter-backup1" id="tronco" >
                                                <option value="">Backup1</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['troncoiax'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                            <!--<p class="help-block"><small>Informe o Codec 2.</small></p>-->
                                        </div>
                                    </div>
                                    <!--CODEC 3-->
                                    <!-- rota_tipo`, `tronco_id_fixo`, `rota_tronco_fixo_tipo`, `tronco_id_movel`, `rota_tronco_movel_tipo`, 
                                        `tronco_id_inter`, `rota_tronco_inter_tipo` -->
                                    <div class="form-group">  
                                        <!--<label for="iax_codec3" class="col-sm-2 control-label">Codec 3</label>-->
                                        <div class="col-xs-12">
                                            <input type="hidden" name="rota_tipo-backup2Inter" value="backup2">                                            
                                            <select class="form-control" name="tronco_id_inter-backup2" id="tronco" >
                                                <option value="">Backup2</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['troncoiax'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                            <!--<p class="help-block"><small>Informe o Codec 3.</small></p>-->
                                        </div>
                                    </div>  

                                </div>

                            </div><!--fim row-->
                        </div>         

                        <!--BOTÕES-->
                        <div class="well txtCenter">
                            <input type="hidden" name="acao" value="<?php echo $acao; ?>">
                            <input type="submit" class="btn btn-success" name="rotaCreate" value="Salvar Rotas">                        
                            <a class="btn btn-default" href="painel.php" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                        </div>
                    </form>

                    <?php
                /*                 * ******************************** FORMULARO UPDATE ROTAS *********************** */
                elseif ($acao == 'Editar'):
                    ?>
                    <form role="form" class="form-horizontal txtblue" name="formUpdate" action="" method="post" id="frm">                          

                        <div class="container-fluid">
                            <div class="row">
                                <?php
                                foreach ($read->getResult() as $Data):
                                    //var_dump($Data);  
                                    if($Data['rota_tipo'] == 'master'): 
                                ?>
                                <h3>MASTER</h3>
                                <div class="col-sm-4">
                                    <?php if(!empty($Data['tronco_id_fixo'])): ?>
                                    <p>FIXO</p>                                        
                                    <div class="form-group">                    
                                        <input type="hidden" name="rota_tipo-masterFixo" value="master">                                                                                           
                                        <select class="form-control" name="tronco_id_fixo-master" id="tronco" required>
                                            <option value="">Master</option>
                                            <?php
                                            $teste = new Read;
                                            $teste->ExeRead("tronco");

                                            if (!$teste->getResult()):
                                                echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                            else:
                                                foreach ($teste->getResult() as $value):
                                                    //passa o id e o tipo 
                                                    echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                    if ($Data['tronco_id_fixo'] == $value['tronco_id']):
                                                        echo ' selected = "selected" ';
                                                    endif;

                                                    echo ">{$value['tronco_nome']}</option>";
                                                endforeach;
                                            endif;
                                            ?>               
                                        </select>  
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-sm-4">
                                    <?php if(!empty($Data['tronco_id_movel'])): ?>
                                    <p>MOVEL</p>
                                    <div class="form-group">  
                                        <!--<label for="iax_codec1" class="col-sm-2 control-label">Codec 1</label>-->
                                        <div class="col-xs-12">
                                            <input type="hidden" name="rota_tipo-masterMovel" value="master">
                                            <select class="form-control" name="tronco_id_movel-master" id="tronco" required>
                                                <option value="">Master</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['tronco_id_movel'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select>                                             
                                        </div>
                                    </div>
                                    <?php endif;?>
                                </div>

                                <div class="col-sm-4">
                                    <?php if(!empty($Data['tronco_id_inter'])): ?>
                                    <p>INTERNACIONAL</p>
                                    <div class="form-group">  
                                        <!--<label for="iax_codec1" class="col-sm-2 control-label">Codec 1</label>-->
                                        <div class="col-xs-12">
                                            <input type="hidden" name="rota_tipo-masterInter" value="master">                                            
                                            <select class="form-control" name="tronco_id_inter-master" id="tronco" required>
                                                <option value="">Master</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['tronco_id_inter'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                            <!--<p class="help-block"><small>Informe o Codec 1.</small></p>-->
                                        </div>
                                    </div>
                                    <?php endif;?>
                                </div>
                                <?php 
                                elseif($Data['rota_tipo'] == 'backup1'):                                
                                ?>                                
                                <h3>BACKUP 1</h3>
                                <div class="col-sm-4">
                                    <?php //if(!empty($Data['tronco_id_fixo'])): ?>
                                    <p>FIXO</p>
                                    <div class="form-group"> 
                                        <input type="hidden" name="rota_tipo-backup1Fixo" value="backup1">

                                        <select class="form-control" name="tronco_id_fixo-backup1" id="tronco">
                                            <option value="">Backup1</option>
                                            <?php
                                            $teste = new Read;
                                            $teste->ExeRead("tronco");

                                            if (!$teste->getResult()):
                                                echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                            else:
                                                foreach ($teste->getResult() as $value):
                                                    //passa o id e o tipo 
                                                    echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                    if ($Data['tronco_id_fixo'] == $value['tronco_id']):
                                                        echo ' selected = "selected" ';
                                                    endif;

                                                    echo ">{$value['tronco_nome']}</option>";
                                                endforeach;
                                            endif;
                                            ?>               
                                        </select> 
                                    </div>
                                    <?php //endif;?>
                                </div>

                                <div class="col-sm-4">
                                    <p>MOVEL</p>
                                    <div class="form-group">  
                                        <!--<label for="iax_codec2" class="col-sm-2 control-label">Codec 2</label>-->
                                        <div class="col-xs-12">
                                            <input type="hidden" name="rota_tipo-backup1Movel" value="backup1">                                            
                                            <select class="form-control" name="tronco_id_movel-backup1" id="tronco" >
                                                <option value="">Backup1</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['troncoiax'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select>                                            
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <p>INTERNACIONAL</p>
                                    <div class="form-group">  
                                        <!--<label for="iax_codec2" class="col-sm-2 control-label">Codec 2</label>-->
                                        <div class="col-xs-12">
                                            <input type="hidden" name="rota_tipo-backup1Inter" value="backup1">                                            
                                            <select class="form-control" name="tronco_id_inter-backup1" id="tronco" >
                                                <option value="">Backup1</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['troncoiax'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                            <!--<p class="help-block"><small>Informe o Codec 2.</small></p>-->
                                        </div>
                                    </div>
                                </div>
                                <?php 
                                elseif($Data['rota_tipo'] == 'backup2'):                                
                                ?>
                                <h3>BACKUP 2</h3>
                                <div class="col-sm-4">
                                    <p>FIXO</p>
                                    <div class="form-group"> 
                                        <input type="hidden" name="rota_tipo-backup2Fixo" value="backup2">                                            
                                        <select class="form-control" name="tronco_id_fixo-backup2" id="tronco">
                                            <option value="">Backup2</option>
                                            <?php
                                            $teste = new Read;
                                            $teste->ExeRead("tronco");

                                            if (!$teste->getResult()):
                                                echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                            else:
                                                foreach ($teste->getResult() as $value):
                                                    //passa o id e o tipo 
                                                    echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                    if ($Data['troncoiax'] == $value['tronco_id']):
                                                        echo ' selected = "selected" ';
                                                    endif;

                                                    echo ">{$value['tronco_nome']}</option>";
                                                endforeach;
                                            endif;
                                            ?>               
                                        </select>                                              
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <p>MOVEL</p>
                                    <div class="form-group">                                         
                                        <div class="col-xs-12">
                                            <input type="hidden" name="rota_tipo-backup2Movel" value="backup2">
                                            <select class="form-control" name="tronco_id_movel-backup2" id="tronco" >
                                                <option value="">Backup2</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['troncoiax'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select>                                                 
                                        </div>
                                    </div> 
                                </div>

                                <div class="col-sm-4">
                                    <p>INTERNACIONAL</p>
                                    <div class="form-group">  
                                        <!--<label for="iax_codec3" class="col-sm-2 control-label">Codec 3</label>-->
                                        <div class="col-xs-12">
                                            <input type="hidden" name="rota_tipo-backup2Inter" value="backup2">                                            
                                            <select class="form-control" name="tronco_id_inter-backup2" id="tronco" >
                                                <option value="">Backup2</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_id']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['troncoiax'] == $value['tronco_id']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select>                                             
                                        </div>
                                    </div> 
                                </div>                                                             
                                <?php
                                endif; //fim if master                                
                                endforeach; 
                                ?>
                            </div><!--fim row-->
                        </div>         

                        <!--BOTÕES-->
                        <div class="well txtCenter">
                            <input type="hidden" name="acao" value="{$acao}"> 
                            <input type="submit" class="btn btn-warning" name="rotasUpdate" value="Atualizar Rotas">                        
                            <a class="btn btn-default" href="painel.php" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                        </div>
                    </form>   


                <?php endif; ?>

                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->