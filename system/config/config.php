<?php
extract($_SESSION['userlogin']);
?>
<!--<div class="row">-->
<!--<div class="col-lg-12">-->
<div class="page-header">            
    <h1>Olá <?php echo "{$user_nome}"; ?>, <small>configure seu sistema!</small></h1>
</div>       
<!--</div>-->

<!--<div class="row">-->
<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user-md"></i> Parametros do sistema</h3>
        </div>
        <div class="panel-body">
            <div id="shieldui-grid1">
                <!--FORMULARIO-->
                <!--<div class="col-lg-10">-->
                <?php

                //LEITURA DOS DADOS NO BD
                function leitura() {
                    $read = new Read;
                    $read->ExeRead("config", "");
                    $verifica = $read->getRowCount();
                    return $read->getResult();
                }
                $obj = leitura();
                //Atualizando dados.
                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if (!empty($dados)):
                    unset($dados['configEdit']);

                    $config = new Config;
                    $config->ExeUpdate($obj[0]['config_id'], $dados);

                    if ($config->getResultado()):
                        $error = $config->getError();
                        KLErro($error[0], $error[1]);
                        $obj = leitura();
//                        header("refresh: 2; painel.php?exe=config/config");
                    else:
                        $error = $config->getError();
                        KLErro($error[0], $error[1]);
                    endif;
                endif;
                ?>
                <form role="form" class="form-horizontal txtblue" name="formUser" action="" method="post" id="frm">                          

                    <div class="form-group">    
                        <label for="ddd-local" class="col-sm-2 control-label">DDD Local</label>
                        <div class="col-xs-2">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="config_ddd" id="ddd" 
                                placeholder="DDD com 2 dígitos" 
                                value="<?php if (!empty($obj[0]['config_ddd'])) echo $obj[0]['config_ddd']; ?>" 
                                maxlength="2"
                                pattern = "[1-9]+$"
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>DDD local do pabx.</small></p>
                        </div>                        
                    </div>


<?php
if ($user_nivel == "2"):
    ?>

                        <div class="form-group">
                            <label for="tts" class="col-sm-2 control-label">TTS - Provedor</label>
                            <div class="col-lg-4">
                                <label class="radio-inline">
    <?php
    if ($obj[0]['config_tts_provider'] == 'aws'):
        ?> 
                                        <input type="radio" name="config_tts_provider" id="provedor" value="aws" checked="checked" > AWS
                                    </label>
                                    <label class="radio-inline">

                                        <input type="radio" name="config_tts_provider" id="provedor" value="ibm" > IBM
                                    </label>

        <?php
    elseif ($obj[0]['config_tts_provider'] == 'ibm'):
        ?>
                                    <input type="radio" name="config_tts_provider" id="provedor" value="aws" > AWS
                                    </label>
                                    <label class="radio-inline">

                                        <input type="radio" name="config_tts_provider" id="provedor" value="ibm" checked="checked" > IBM
                                    </label>
        <?php
    else :
        ?>
                                    <input type="radio" name="config_tts_provider" id="provedor" value="aws" > AWS
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="config_tts_provider" id="provedor" value="ibm" > IBM
                                    </label>
    <?php
    endif;
    ?>   

                            </div>
                        </div>


                        <div class="form-group">     
                            <label for="id_tts" class="col-sm-2 control-label">TTS-id</label>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" name="config_tts_id" id="id_tts" placeholder="TTS-id" value="<?php if (!empty($obj[0]['config_tts_id'])) echo $obj[0]['config_tts_id']; ?>">
                                <p class="help-block"><small>Informe sua chave ID.</small></p>
                            </div>

                        </div>
                        <div class="form-group"> 
                            <label for="secret_tts" class="col-sm-2 control-label">TTS-Secret</label>
                            <div class="col-lg-3">
                                <input type="text" class="form-control" name="config_tts_secret" id="secret_tts" placeholder="Secret TTS" value="<?php if (!empty($obj[0]['config_tts_secret'])) echo $obj[0]['config_tts_secret']; ?>">
                                <p class="help-block"><small>Informe sua chave tts secret.</small></p>
                            </div>
                        </div>

    <?php
endif;
?>

                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-success" name="configEdit" value="Salvar Alteração">
                        <!--<button type="reset" class="btn btn-default" value="Voltar"><i class="fa fa-arrow-left"></i> Voltar</button>-->
                        <a class="btn btn-default" href="painel.php" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>