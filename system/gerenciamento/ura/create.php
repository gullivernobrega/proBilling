<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>URA <small>Cadastro!</small></h1>
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

                if (isset($Data["uraCreate"])):
                    unset($Data["uraCreate"]);
                    
                    $cadastra = new Ura;
                    $cadastra->ExeCreate($Data);
                    if ($cadastra->getResult()):
                        //Redireciona
                        header("Location: painel.php?exe=gerenciamento/ura/lista");
                    else:
                        KLErro("Ops, não foi possivel realizar o cadastro!", KL_ERROR);
                    endif;

//                    else:
//                        KLErro("<strong>Ops!</strong> O limite de chamada não pode exceder a 500, Verifique!", KL_INFOR);
//                    endif;
                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formUra" action="" method="post" id="frm"> 

                    <!--NOME-->
                    <div class="form-group">    
                        <label for="ura_nome" class="col-sm-2 control-label">Nome</label>
                        <div class="col-xs-6">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="ura_nome" id="ura_nome" 
                                placeholder="Nome da URA" 
                                value="<?php if (!empty($Data['ura_nome'])) echo $Data['ura_nome']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o nome da URA.</small></p>
                        </div>                        
                    </div>

                    <!--ÁUDIO da URA-->
                    <div class="form-group">     
                        <label for="ura_audio" class="col-sm-2 control-label">Áudio da URA</label>
                        <div class="col-xs-3"> 
                            <select class="form-control" name="ura_audio" id="ura_audio" >
                                <option value="">Áudios</option>
                                <?php
                                $audio = new Read;
                                $audio->ExeRead("audio");

                                if (!$audio->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um áudio!</option>';
                                else:
                                    foreach ($audio->getResult() as $value):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value['audio_nome']}\" ";

                                        if (!empty($Data['ura_audio']) && $Data['ura_audio'] == $value['audio_nome']):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value['audio_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                        </div> 
                    </div>

                    <!--ÁUDIO opção INVALIDA-->
                    <div class="form-group">     
                        <label for="ura_audio_invalida" class="col-sm-2 control-label">Áudio Inválida</label>
                        <div class="col-xs-3"> 
                            <select class="form-control" name="ura_audio_invalida" id="ura_audio1" >
                                <option value="">Áudios</option>
                                <?php
                                $audio1 = new Read;
                                $audio1->ExeRead("audio");

                                if (!$audio1->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um áudio!</option>';
                                else:
                                    foreach ($audio1->getResult() as $value1):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value1['audio_nome']}\" ";

                                        if (!empty($Data['ura_audio_invalida']) && $Data['ura_audio_invalida'] == $value1['audio_nome']):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value1['audio_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                        </div> 
                    </div>

                    <!--ÁUDIO Tentativa-->
                    <div class="form-group">     
                        <label for="ura_audio_tentativa" class="col-sm-2 control-label">Áudio Tentativas</label>
                        <div class="col-xs-3"> 
                            <select class="form-control" name="ura_audio_tentativa" id="ura_audio_tentativa" >
                                <option value="">Áudios</option>
                                <?php
                                $audio3 = new Read;
                                $audio3->ExeRead("audio");

                                if (!$audio3->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um áudio!</option>';
                                else:
                                    foreach ($audio3->getResult() as $value3):
                                        //passa o id e o tipo 
                                        echo "<option value=\"{$value3['audio_nome']}\" ";

                                        if (!empty($Data['ura_audio_tentativa']) && $Data['ura_audio_tentativa'] == $value3['audio_nome']):
                                            echo ' selected = "selected" ';
                                        endif;

                                        echo ">{$value3['audio_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>               
                            </select> 
                        </div> 
                    </div>

                    <!--Quantidade de tentativas da URA-->
                    <div class="form-group">
                        <label for="ura_tentativa" class="col-sm-2 control-label">Quantidade de tentativas:</label>
                        <div class="col-md-2">
                            <select class="col-sm-2 control-label form-control" name ="ura_tentativa" id="ura_tentativa" required>
                                <option>1</option>
                                <option>2</option>
                                <option>3</option>
                                <option>4</option>
                                <option>5</option>
                            </select>
                        </div>
                    </div>

                    <?php
                    for ($i = 1; $i < 10; $i++) {
                        ?>  
                        <!--DESTINO1-->
                        <div class="form-group">    
                            <label for="ura_op_<?php echo $i; ?>" class="col-sm-2 control-label">Opcão-0<?php echo $i; ?></label>
                            <div class="col-xs-6">                                    
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    name="ura_op_<?php echo $i; ?>" id="ura_op_<?php echo $i; ?>" 
                                    placeholder="Destino: Ex SIP/1010" 
                                    value="<?php if (!empty($Data['ura_nome'])) echo $Data['ura_nome']; ?>" 
                                    autofocus
                                    >
                                <p class="help-block"><small>Informe o destino da URA.</small></p>
                            </div>                        
                        </div>

                        <?php
                    }
                    ?>
                    <div class="form-group">    
                        <label for="ura_timeout" class="col-sm-2 control-label">Time-Out</label>
                        <div class="col-xs-6">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="ura_timeout" id="ura_timeout" 
                                placeholder="Destino: Ex SIP/1010" 
                                value="<?php if (!empty($Data['ura_nome'])) echo $Data['ura_nome']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o destino da URA.</small></p>
                        </div>                        
                    </div>




                    <!--BOTÕES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-success" name="uraCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=gerenciamento/ura/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->