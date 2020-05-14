<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Video <small>Cadastro!</small></h1>
    </div>       
    <!--</div>-->

    <!--<div class="row">-->
    <!--<div class="container-fluid">-->
    <div class="panel panel-default">
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
                    
                    $cadastra = new Videos;
                    $cadastra->ExeCreate($Data);

                    if (!$cadastra->getResult()):
                        $erro = $cadastra->getErro();                        
                        KLErro($erro[0], $erro[1]);
                    else:
                        $erro = $cadastra->getErro();                        
                        KLErro($erro[0], $erro[1]);
                    endif;

                endif;
                ?>
                <form role="form" class="form-horizontal" name="formVideos" action="" method="post" id="frm">                          

                    <div class="form-group">  
                        <label for="cms_id" class="col-sm-2 control-label">Pagina Cms</label>
                        <div class="col-xs-5">
                            <select class="form-control" name="cms_id" id="categoria_parente">
                                <option value="NULL">Pagina Cms</option>

                                <?php
                                //$ID = 0;
                                $read = new Read;
                                //$read->ExeRead("kl_cms", "WHERE cms_id = :id ORDER BY cms_nome ASC",  "id={$ID}");
                                $read->ExeRead("kl_cms", "ORDER BY cms_nome ASC");

                                if (!$read->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes uma pagina Cms</option>';
                                else:
                                    foreach ($read->getResult() as $cms):
                                        echo "<option value=\"{$cms['cms_id']}\" ";
                                        if ($cms['cms_id'] == $Data['cms_id']):
                                            echo ' selected = "selected" ';
                                        endif;
                                        echo ">{$cms['cms_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>
                            </select>

                            <p class="help-block"><small>Informe pagina CMS.</small></p>
                        </div>
                    </div>

                    <div class="form-group">    
                        <label for="video_titulo" class="col-sm-2 control-label">Titulo</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="video_titulo" id="video_titulo" 
                                placeholder="Titulo do video" 
                                value="<?php if (!empty($Data['video_titulo'])) echo $Data['video_titulo']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o titulo do video.</small></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="video_descricao" class="col-sm-2 control-label">Descrição</label>
                        <div class="col-lg-8">
                            <textarea class="form-control" rows="3" name="video_descricao" id="video_descricao"><?php if (isset($Data)): echo $Data['video_descricao']; endif; ?></textarea>                                                        
                            <p class="help-block"><small>Informe a descrição do video.</small></p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="video_embed" class="col-sm-2 control-label">Embed do video</label>
                        <div class="col-lg-8">
                            <textarea class="form-control" rows="3" name="video_embed" id="video_embed"><?php if (isset($Data)): echo $Data['video_embed']; endif; ?></textarea>                                                        
                            <p class="help-block"><small>Informe o embed do video.</small></p>
                        </div>
                    </div>
                                     
                    <div class="form-group">                        
                        <!--<input type="hidden" name="categoria_registrado" value="<?php //echo date("Y-m-d H:i:s");   ?>">-->
                        <input type="hidden" name="video_status" value="S">
                    </div>
                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-success" name="sendCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=videos/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
    <!--</div>-->
    <!--</div>-->
</div>

