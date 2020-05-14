<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Galerias <small>Cadastro!</small></h1>
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
                $galeriaData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if (isset($galeriaData) && $galeriaData["sendCreate"]):
                    unset($galeriaData["sendCreate"]);
                    $galeriaData['galeria_img_capa'] = ($_FILES['galeria_img_capa']['tmp_name'] ? $_FILES['galeria_img_capa'] : null);

                    $cadastra = new Galeria;
                    $cadastra->ExeCreate($galeriaData);

                    if ($cadastra->getResult()):
                        header("Location: painel.php?exe=galerias/lista");
                    else:
                        $erro = $cadastra->getErro();
                        KLErro($erro[0], $erro[1]);
                    endif;
                endif;
                ?>
                <form role="form" class="form-horizontal" name="formGaleria" action="" method="post" id="frm" enctype="multipart/form-data">                          

                    <div class="form-group">  
                        <label for="cms_id" class="col-sm-2 control-label">Pagina Cms</label>
                        <div class="col-xs-5">
                            <select class="form-control" name="cms_id" id="cms_id">
                                <option value="NULL">Pagina</option>

                                <?php
                                $readCms = new Read;
                                $readCms->ExeRead("kl_cms", "WHERE cms_status = 'S' ORDER BY cms_nome ASC");

                                if (!$readCms->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes uma pagina Cms</option>';
                                else:
                                    foreach ($readCms->getResult() as $Cms):
                                        echo "<option value=\"{$Cms['cms_id']}\" ";
                                        if (empty($galeriaData['cms_id']) && $galeriaData['cms_id'] == $Cms['cms_id']):
                                            echo ' selected = "selected" ';
                                        endif;
                                        echo ">{$Cms['cms_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>
                            </select>
                            <p class="help-block"><small>Informe a Pagina Cms.</small></p>
                        </div>
                    </div>

                    <div class="form-group">    
                        <label for="galeria_nome" class="col-sm-2 control-label">Galeria Nome</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="galeria_nome" id="galeria_nome" 
                                placeholder="Nome da galeria" 
                                value="<?php if (!empty($galeriaData['galeria_nome'])) echo $galeriaData['galeria_nome']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o Nome da Galeria.</small></p>
                        </div>
                    </div>

                    <!-- <div class="form-group">                        
                        <input type="hidden" name="nome_data" value="<?php //echo date("Y-m-d H:i:s");   ?>">                        
                    </div>-->

                    <!--                    <div class="form-group">
                                            <label for="cms_conteudo" class="col-sm-2 control-label">Conteúdo da Página</label>
                                            <div class="col-lg-8">
                                                <textarea class="form-control" rows="3" name="cms_conteudo" id="cms_conteudo"><?php //echo $CmsData['cms_conteudo'];  ?></textarea>                                                        
                                                <p class="help-block"><small>Informe o Conteúdo da página Cms.</small></p>
                                            </div>
                                        </div>-->

                    <div class="form-group">                        
                        <!--<input type="hidden" name="categoria_registrado" value="<?php //echo date("Y-m-d H:i:s");     ?>">-->
                        <input type="hidden" name="galeria_status" value="S">
                    </div>

                    <div class="form-group">     
                        <label for="galeria_ordem" class="col-sm-2 control-label">Ordem</label>
                        <div class="col-lg-2">
                            <input 
                                type="text" 
                                class="form-control" 
                                name="galeria_ordem" 
                                id="galeria_ordem" 
                                placeholder="Ordem" 
                                value="<?php if (!empty($galeriaData['galeria_ordem'])) echo $galeriaData['galeria_ordem']; ?>" 
                                required
                                >
                            <p class="help-block"><small>Informe a Ordem Galeria.</small></p>
                        </div>
                    </div>                     

                    <div class="form-group">
                        <label for="galeria_img_capa" class="col-sm-2 control-label">Imagem Capa</label>
                        <div class="col-lg-4">
                            <input type="file" id="galeria_img_capa" name="galeria_img_capa" value="">
                            <p class="help-block">Informe uma imagem de capa em .png ou .jpg</p>
                        </div>
                        <div class="col-lg-4">
                            <img src="<?php echo INCLUDE_PATH; ?>/images/semLogo.jpg" width="70%">
                        </div>
                    </div>

                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-success" name="sendCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=galerias/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
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