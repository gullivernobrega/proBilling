<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Rede Sociais <small>Cadastro!</small></h1>
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
                if (isset($Data) && $Data["sendCreate"]):
                    unset($Data["sendCreate"]);
                    $Data['social_image'] = ($_FILES['social_image']['tmp_name'] ? $_FILES['social_image'] : null); 
                    
                    $cadastra = new RedeSocial;
                    $cadastra->ExeCreate($Data);

                    if ($cadastra->getResult()):
                        header("Location: painel.php?exe=redesocial/lista");
                    else:
                        $erro = $cadastra->getErro();
                        KLErro($erro[0], $erro[1]);
                    endif;
                endif;
                ?>
                <form role="form" class="form-horizontal" name="formSocial" action="" method="post" id="frm" enctype="multipart/form-data">                          

                    <div class="form-group">    
                        <label for="cms_nome" class="col-sm-2 control-label">Rede Social</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="social_nome" id="social_nome" 
                                placeholder="Nome da rede social" 
                                value="<?php if (!empty($Data['social_nome'])) echo $Data['social_nome']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o Nome da Rede Social.</small></p>
                        </div>
                    </div>
                    
                    <div class="form-group">    
                        <label for="cms_nome" class="col-sm-2 control-label">URL Rede Social</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="social_url" id="social_url" 
                                placeholder="URL da rede social" 
                                value="<?php if (!empty($Data['social_url'])) echo $Data['social_url']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o URL da Rede Social.</small></p>
                        </div>
                    </div>
                    
                     <div class="form-group">                        
                        <!--<input type="hidden" name="categoria_registrado" value="<?php //echo date("Y-m-d H:i:s");   ?>">-->
                        <input type="hidden" name="social_status" value="S">
                    </div>
                    
                    <div class="form-group">     
                        <label for="social_ordem" class="col-sm-2 control-label">Ordem</label>
                        <div class="col-lg-3">
                            <input 
                                type="text" 
                                class="form-control" 
                                name="social_ordem" 
                                id="social_ordem" 
                                placeholder="Ordem" 
                                value="<?php if (!empty($Data['social_ordem'])) echo $Data['social_ordem']; ?>" 
                                required
                                >
                            <p class="help-block"><small>Informe a Ordem da Rede social.</small></p>
                        </div>
                    </div> 
                    
                    <div class="form-group">
                        <label for="social_image" class="col-sm-2 control-label">Imagem Icon</label>
                        <div class="col-lg-4">
                            <input type="file" id="social_image" name="social_image" value="">
                            <p class="help-block">Informe uma imagem em .png ou .jpg tamanho 128 pixels</p>
                        </div>
                        <div class="col-lg-4">
                            <img src="<?php echo INCLUDE_PATH; ?>/images/semIco.jpg" width="20%">
                        </div>
                    </div>
                    
                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-success" name="sendCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=redesocial/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
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

