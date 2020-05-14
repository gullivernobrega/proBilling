<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Categorias Noticias <small>Atualizar!</small></h1>
    </div>       
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> Painel de Cadastro</h3>
        </div>
        <div class="panel-body">
            <div id="shieldui-grid1">
                <!--FORMULARIO-->                
                <?php
                // realiza a alteração
                $CategoriaData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $cat_id = filter_input(INPUT_GET, "cat_id", FILTER_VALIDATE_INT);

                if (!empty($CategoriaData["sendUpdate"])):
                    unset($CategoriaData["sendUpdate"]);
                
                    $update = new CategoriesNews;
                    $update->ExeUpdate($cat_id, $CategoriaData);
                    
                    if (!$update->getResult()):
                        $erro = $update->getErro();
                        KLErro($erro[0], $erro[1]);
                    else:
                        header("Location: painel.php?exe=noticias/lista");
                    endif;

                else:

                    //Busca os dados na tabela                    
                    $readCat = new Read;
                    $readCat->ExeRead("kl_noticia_categoria", "WHERE cat_id = :id", "id={$cat_id}");
                    if (!$readCat->getResult()):
                        header("Location: painel.php?exe=noticias/lista&update=false");
                    else:
                        $res = $readCat->getResult();
                        $Data = $res[0];
                    endif;

                endif;
                ?>
                <form role="form" class="form-horizontal" name="formCategoria" action="" method="post" id="frm">                          

                    <div class="form-group">    
                        <label for="cat_descricao" class="col-sm-2 control-label">Categoria Descrição</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="cat_descricao" id="cat_descricao" 
                                placeholder="Nome Categoria Noticia" 
                                value="<?php
                                if (isset($Data['cat_descricao'])):
                                    echo $Data['cat_descricao'];
                                else:
                                    echo $CategoriaData['cat_descricao'];
                                endif;
                                ?>" 
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
                                value="<?php
                                if (isset($Data['cat_posicao'])):
                                    echo $Data['cat_posicao'];
                                else:
                                    echo $CategoriaData['cat_posicao'];
                                endif;
                                ?>" 
                                required
                                >                            
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="categoria_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-lg-4">
                            <?php
                            if (isset($Data) && $Data['cat_status'] == "S"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="cat_status" id="status1" value="S" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="cat_status" id="status2" value="N"> Inativo
                                </label>                                    
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="cat_status" id="status1" value="S" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="cat_status" id="status2" value="N" checked="checked"> Inativo
                                </label>                                        
                            <?php
                            endif;
                            ?>
                        </div>                        
                    </div>
                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-warning" name="sendUpdate" value="Atualizar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=noticias/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

