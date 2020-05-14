<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Audio <small>Cadastro!</small></h1>
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

                if (isset($Data["audioCreate"])):
                    unset($Data["audioCreate"]);
                
                    //VERIFICO SE EXITE O AUDIO GSM
                    $Data['audio_arquivo'] = ( $_FILES['audio_arquivo']['tmp_name'] ? $_FILES['audio_arquivo'] : null);
                    
                    $cadastra = new Audio;
                    $cadastra->ExeCreate($Data);
                        
                    if ($cadastra->getResult()):
                        header("Location: painel.php?exe=gerenciamento/audios/lista");
                    else:
                        KLErro("Ops, não foi possivel realizar o cadastro!", KL_ERROR);
                    endif;
                    
                endif;
                ?>

                <form role="form" class="form-horizontal txtblue" name="formAudio" action="" method="post" id="frm" enctype="multipart/form-data">                          

                    <!--AUDIO-->
                    <div class="form-group">  
                        <label for="audio_arquivo" class="col-sm-2 control-label">Arquivo de Audio</label>
                        <div class="col-xs-4">
                            <input 
                                type="file" 
                                class="form-control" 
                                name="audio_arquivo" id="audio_destino" 
                                placeholder="Informe o audio .Gsm" 
                                value="" 
                                required
                                >
                            <p class="help-block"><small>Informe o audio .gsm</small></p>
                        </div>
                    </div>

                    <!--DID DESTINO-->
                    <div class="form-group">  
                        <label for="ramal" class="col-sm-2 control-label">Destino</label>

                        <div class="col-lg-4">
                            <?php
                            if (!empty($Data['audio_status']) && $Data['audio_status'] == "S"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="audio_status" id="audio_status"  value="S" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="audio_status" id="audio_status"  value="N"> Inativo
                                </label>  
                                <?php
                            elseif (!empty($Data['audio_status']) && $Data['audio_status'] == "N"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="audio_status" id="audio_status"  value="S" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="audio_status" id="audio_status"  value="N" checked="checked"> Inativo
                                </label>  
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="audio_status" id="audio_status"  value="S" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="audio_status" id="audio_status"  value="N"> Inativo
                                </label>        
                            <?php
                            endif;
                            ?>
                        </div>  
                    </div>

                    <!--BOTÕES-->
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-success" name="audioCreate" id="btn" value="Salvar Cadastro">                                                
                        <!--<button type="button" class="btn btn-primary">Salvar Cadastro</button>-->
                        <a class="btn btn-default" href="painel.php?exe=gerenciamento/audios/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--<script src="_cdn/jdestino.js"></script>-->
<!--</div>-->