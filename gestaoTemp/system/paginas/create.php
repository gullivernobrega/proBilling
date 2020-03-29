<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Pàginas Cms <small>Cadastro!</small></h1>
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
                $CmsData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if (isset($CmsData) && $CmsData["cmsCreate"]):
                    unset($CmsData["cmsCreate"]);
                    $CmsData['cms_imagem'] = ($_FILES['cms_imagem']['tmp_name'] ? $_FILES['cms_imagem'] : null);

                    $cadastra = new Paginas;
                    $cadastra->ExeCreate($CmsData);

                    if ($cadastra->getResult()):
                        header("Location: painel.php?exe=paginas/lista");
                    else:
                        $erro = $cadastra->getErro();
                        KLErro($erro[0], $erro[1]);
                    endif;
                endif;
                ?>
                <form role="form" class="form-horizontal" name="formCms" action="" method="post" id="frm" enctype="multipart/form-data">                          

                    <div class="form-group">    
                        <label for="cms_nome" class="col-sm-2 control-label">Página Cms</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="cms_nome" id="cms_nome" 
                                placeholder="Nome da pagina Cms" 
                                value="<?php if (!empty($CmsData['cms_nome'])) echo $CmsData['cms_nome']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o Nome da Pagina Cms.</small></p>
                        </div>
                    </div>

                    <!--                    <div class="form-group">                        
                                            <input type="hidden" name="nome_data" value="<?php //echo date("Y-m-d H:i:s");  ?>">                        
                                        </div>-->

                    <div class="form-group">  
                        <label for="categoria_id" class="col-sm-2 control-label">Categoria do Cms</label>
                        <div class="col-xs-5">
                            <select class="form-control" name="categoria_id" id="categoria_id">
                                <option value="NULL">Cateroria</option>

                                <?php
                                $status = "S";
                                $readCat = new Read;
                                $readCat->ExeRead("kl_categorias", "WHERE categoria_status = :s ORDER BY categoria_nome ASC", "s={$status}");

                                if (!$readCat->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes uma Categoria</option>';
                                else:
                                    foreach ($readCat->getResult() as $Categoria):
                                        echo "<option value=\"{$Categoria['categoria_id']}\" ";
                                        if (empty($CmsData['categoria_id']) && $CmsData['categoria_id'] == $Categoria['categoria_id']):
                                            echo ' selected = "selected" ';
                                        endif;
                                        echo ">{$Categoria['categoria_nome']}</option>";
                                    endforeach;
                                endif;
                                ?>
                            </select>
                            <p class="help-block"><small>Informe Categoria.</small></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="cms_conteudo" class="col-sm-2 control-label">Conteúdo da Página</label>
                        <div class="col-lg-8">
                            <textarea class="form-control" rows="3" name="cms_conteudo" id="cms_conteudo"><?php echo $CmsData['cms_conteudo']; ?></textarea>                                                        
                            <p class="help-block"><small>Informe o Conteúdo da página Cms.</small></p>
                        </div>
                    </div>

                    <div class="form-group">     
                        <label for="cms_link" class="col-sm-2 control-label">Link</label>
                        <div class="col-xs-8">
                            <input 
                                type="text" 
                                class="form-control" 
                                name="cms_link" 
                                id="categoria_login" 
                                placeholder="Informe o link" 
                                value="<?php if (!empty($CmsData['cms_link'])) echo $CmsData['cms_link']; ?>"                                 
                                >
                            <p class="help-block"><small>Informe o link da Pagina Cms.</small></p>
                        </div>
                    </div> 

                    <div class="form-group">  
                        <input type="hidden" name="cms_status" value="S">
                    </div>

                    <div class="form-group">     
                        <label for="cms_ordem" class="col-sm-2 control-label">Ordem</label>
                        <div class="col-lg-3">
                            <input 
                                type="text" 
                                class="form-control" 
                                name="cms_ordem" 
                                id="categoria_login" 
                                placeholder="Ordem" 
                                value="<?php if (!empty($CmsData['cms_ordem'])) echo $CmsData['cms_ordem']; ?>" 
                                required
                                >
                            <p class="help-block"><small>Informe a Ordem das Cmss ou SubCmss.</small></p>
                        </div>
                    </div>                     

                    <div class="form-group">
                        <label for="cms_imagem" class="col-sm-2 control-label">Imagem</label>
                        <div class="col-lg-4">
                            <input type="file" id="cms_imagem" name="cms_imagem" value="">
                            <p class="help-block">Informe uma imagem em .png ou .jpg</p>
                        </div>
                        <div class="col-lg-4">
                            <img src="<?php echo INCLUDE_PATH; ?>/images/semLogo.jpg" width="70%">
                        </div>
                    </div>

                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-success" name="cmsCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=paginas/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
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

