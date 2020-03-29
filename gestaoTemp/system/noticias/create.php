<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Categorias Noticias <small>Cadastro!</small></h1>
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
                $CategoriaData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if (isset($CategoriaData["categoriaCreate"])):
                    unset($CategoriaData["categoriaCreate"]);

                    $cadastra = new CategoriesNews;
                    $cadastra->ExeCreate($CategoriaData);

                    if (!$cadastra->getResult()):
                        $erro = $cadastra->getErro();
                        KLErro($erro[0], $erro[1]);
                    else:
                        //$erro = $cadastra->getErro();                        
                        //KLErro($erro[0], $erro[1]);
                        header("Location: painel.php?exe=noticias/lista");
                    endif;

                endif;
                ?>
                <form role="form" class="form-horizontal" name="formCategoria" action="" method="post" id="frm">                          

                    <div class="form-group">    
                        <label for="cat_descricao" class="col-sm-2 control-label">Categoria Noticia</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="cat_descricao" id="cat_descricao" 
                                placeholder="Nome Categoria Noticia" 
                                value="<?php if (!empty($CategoriaData['cat_descricao'])) echo $CategoriaData['cat_descricao']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o Nome para Categoria Noticia.</small></p>
                        </div>
                    </div>

                    <div class="form-group">     
                        <label for="cat_posicao" class="col-sm-2 control-label">Posição</label>
                        <div class="col-lg-1">
                            <input 
                                type="text" 
                                class="form-control" 
                                name="cat_posicao" 
                                id="categoria_login" 
                                placeholder="Posição" 
                                value="<?php if (!empty($CategoriaData['cat_posicao'])) echo $CategoriaData['cat_posicao']; ?>" 
                                required
                                >
                            <p class="help-block"><small>Informe a Posição das Categorias Noticias.</small></p>
                        </div>
                    </div> 
                    <div class="form-group">   
                        <input type="hidden" name="cat_status" value="S">
                    </div>
                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-success" name="categoriaCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=noticias/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>                
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
