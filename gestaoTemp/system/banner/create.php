<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Banner <small>Cadastro!</small></h1>
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
                if (isset($Data) && $Data["banCreate"]):
                    unset($Data["banCreate"]);
                    $Data['ban_image'] = ($_FILES['ban_image']['tmp_name'] ? $_FILES['ban_image'] : null);
                    
                    $cadastra = new Banner;
                    $cadastra->ExeCreate($Data);

                    if ($cadastra->getResult()):
                        header("Location: painel.php?exe=banner/lista");
                    else:
                        $erro = $cadastra->getErro();
                        KLErro($erro[0], $erro[1]);
                    endif;
                endif;                
                ?>
                <form role="form" class="form-horizontal" name="formBan" action="" method="post" id="frm" enctype="multipart/form-data">                          

                    <div class="form-group">    
                        <label for="ban_titulo" class="col-sm-2 control-label">Titulo</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="ban_titulo" id="cms_nome" 
                                placeholder="Titulo do Banner" 
                                value="<?php if (!empty($Data['ban_titulo'])) echo $Data['ban_titulo']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o titulo do banner.</small></p>
                        </div>
                    </div>

                    <div class="form-group">    
                        <label for="ban_link" class="col-sm-2 control-label">Link</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="ban_link" id="cms_nome" 
                                placeholder="Link do Banner" 
                                value="<?php if (!empty($Data['ban_link'])) echo $Data['ban_link']; ?>"                                  
                                autofocus
                                >
                            <p class="help-block"><small>Informe o Link do banner ou deixe em branco.</small></p>
                        </div>

                    </div>

                    <div class="form-group">                    
                        <label class="col-sm-2 control-label" for="ban_posicao">Posição:</label>
                        <div class="col-sm-3">
                            <select name="ban_posicao" class="form-control">
                                <option value = "">Selecione a Posição</option>
                                <option value = "1" <?php echo ($Data['ban_posicao'] == 1) ? 'selected="selected"' : null; ?>>Principal</option>
                                <option value="2" <?php echo ($Data['ban_posicao'] == 2) ? 'selected="selected"' : null; ?>>Lado esquerdo</option>
                                <option value="3" <?php echo ($Data['ban_posicao'] == 3) ? 'selected="selected"' : null; ?>>Lado direito</option>
                                <option value="3" <?php echo ($Data['ban_posicao'] == 4) ? 'selected="selected"' : null; ?>>Em baixo</option>
                            </select>
                        </div>
                        <label for="ban_ordem" class="col-sm-4 control-label">Ordem</label>
                        <div class="col-lg-1">
                            <input 
                                type="text" 
                                class="form-control" 
                                name="ban_ordem" 
                                id="categoria_login" 
                                placeholder="Ordem" 
                                value="<?php if (!empty($Data['ban_ordem'])) echo $Data['ban_ordem']; ?>" 
                                required
                                >
<!--                            <p class="help-block"><small>Informe a Ordem.</small></p>-->
                        </div>   
                    </div>   

                    <div class="form-group">  
                        <input type="hidden" name="ban_status" value="S">
                    </div>

                    <div class="form-group">
                        <label for="ban_image" class="col-sm-2 control-label">Imagem Banner</label>
                        <div class="col-lg-4">
                            <input type="file" id="cms_imagem" name="ban_image" value="">
                            <p class="help-block">Informe a imagem do banner em .png ou .jpg</p>
                        </div>
                        <div class="col-lg-4">
                            <img src="<?php echo INCLUDE_PATH; ?>/images/semLogo.jpg" width="70%">
                        </div>
                    </div>

                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-success" name="banCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=banner/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
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

