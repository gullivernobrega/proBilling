<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Noticias <small>Cadastro!</small></h1>
    </div>       
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> Painel de Cadastro</h3>
        </div>
        <div class="panel-body">
            <div id="shieldui-grid1">
                <!--FORMULARIO-->
                <!--<div class="col-lg-10">-->
                <?php
                $cat_id = filter_input(INPUT_GET, "cat_id", FILTER_VALIDATE_INT);
                $Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                if (isset($Data["sendCreate"])):
                    unset($Data["sendCreate"]);
                    
                    $cadastra = new News;
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
                <form role="form" class="form-horizontal" name="formNoticia" action="" method="post" id="frm">                          

                    <div class="form-group">    
                        <label for="not_titulo" class="col-sm-2 control-label">Noticia Titulo</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="not_titulo" id="not_titulo" 
                                placeholder="Titulo da Noticia" 
                                value="<?php if (!empty($Data['not_titulo'])) echo $Data['not_titulo']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe um titulo para a Noticia.</small></p>
                        </div>
                    </div>

                    <div class="form-group">                        
                        <input type="hidden" name="not_data_post" value="<?php echo date("Y-m-d H:i:s");?>">                        
                    </div>
                    
                    <div class="form-group">
                        <label for="not_texto" class="col-sm-2 control-label">Conteúdo da Noticia</label>
                        <div class="col-lg-8">
                            <textarea class="form-control" rows="3" name="not_texto" id="not_texto"><?php echo $Data['not_texto']; ?></textarea>                                                        
                            <p class="help-block"><small>Informe o Conteúdo da Noticia.</small></p>
                        </div>
                    </div>

                    <div class="form-group">   
                        <input type="hidden" name="not_status" value="S">
                    </div>  
                    <div class="form-group">   
                        <input type="hidden" name="cat_id" value="<?php echo $cat_id;?>">
                    </div>
                    
                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-success" name="sendCreate" value="Publicar Noticia">                        
                        <a class="btn btn-default" href="painel.php?exe=noticias/listNoticias&cat_id=<?php echo $cat_id;?>" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>                
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>


