<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Categorias e Subcategorias <small>Atualizar!</small></h1>
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
                // realiza a alteração
                $CategoriaData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $cat_id = filter_input(INPUT_GET, "categoria_id", FILTER_VALIDATE_INT);

                if (isset($CategoriaData["categoriaUpdate"])):
                    unset($CategoriaData["categoriaUpdate"]);

                    $update = new Categories;
                    $update->ExeUpdate($CategoriaData);

                    if (!$update->getResult()):
                        $erro = $update->getErro();
                        KLErro($erro[0], $erro[1]);
                    else:
                        $erro = $update->getErro();
                        KLErro($erro[0], $erro[1]);
                    endif;
                else:

                    //Busca os dados na tabela                    
                    $readCat = new Read;
                    $readCat->ExeRead("kl_categorias", "WHERE categoria_id = :id", "id={$cat_id}");
                    if (!$readCat->getResult()):
                        header("Location: painel.php?exe=categorias/categorias&update=false");
                    else:
                        $res = $readCat->getResult();                        
                        $Data = $res[0];     
                        var_dump($Data['categoria_parente']);
                    endif;

                endif;
                ?>
                <form role="form" class="form-horizontal" name="formCategoria" action="" method="post" id="frm">                          

                    <div class="form-group">    
                        <label for="categoria_nome" class="col-sm-2 control-label">Categoria/Subcategoria</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="categoria_nome" id="categoria_nome" 
                                placeholder="Nome Categoria ou SubCategoria" 
                                value="<?php
                                if (isset($Data['categoria_nome'])): 
                                    echo $Data['categoria_nome'];
                                else: 
                                    echo $CategoriaData['categoria_nome'];
                                endif;
                                ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o Nome para Categoria ou Subcategoria.</small></p>
                        </div>
                    </div>

                    <div class="form-group">  
                        <label for="categoria_parente" class="col-sm-2 control-label">Categoria Parente</label>
                        <div class="col-xs-5">
                            <select class="form-control" name="categoria_parente" id="categoria_parente">
                                <option value="NULL">Cateroria Parente</option>

                                <?php
                                $readParente = new Read;
                                $readParente->ExeRead("kl_categorias", "WHERE categoria_parente = NULL ORDER BY categoria_nome");
                                
                                if (!$readParente->getResult()):
                                    echo '<option disabled="disabled" value="NULL">Cadastre antes uma Categoria</option>';
                                else:
                                    
                                    foreach ($readParente->getResult() as $parente):
                                       
                                        echo "<option value=\"{$parente['categoria_id']}\" ";                                        
                                        if ($parente['categoria_id'] == $Data['categoria_parente']):
                                            echo ' selected = "selected" ';                                      
                                        endif;
                                        echo ">{$parente['categoria_nome']}</option>";
                                        
                                    endforeach;
                                    
                                endif;
                                ?>
                            </select>

                            <p class="help-block"><small>Informe Categoria Parente.</small></p>
                        </div>
                    </div>

                    <div class="form-group">     
                        <label for="categoria_ordem" class="col-sm-2 control-label">Ordem</label>
                        <div class="col-lg-3">
                            <input 
                                type="text" 
                                class="form-control" 
                                name="categoria_ordem" 
                                id="categoria_login" 
                                placeholder="Ordem" 
                                value="<?php
                                if (isset($Data['categoria_ordem'])): 
                                    echo $Data['categoria_ordem'];
                                else: 
                                    echo $CategoriaData['categoria_ordem'];
                                endif;
                                ?>" 
                                required
                                >
                            <p class="help-block"><small>Informe a Ordem das Categorias ou SubCategorias.</small></p>
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="categoria_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-lg-4">
                            <?php
                            if (isset($Data['categoria_status']) == "S"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="categoria_status" id="status1" value="S" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="categoria_status" id="status2" value="N"> Inativo
                                </label>                                    
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="categoria_status" id="status1" value="S" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="categoria_status" id="status2" value="N" checked="checked"> Inativo
                                </label>                                        
                            <?php
                            endif;
                            ?>
                        </div>                        
                    </div>
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-warning" name="categoriaUpdate" value="Atualizar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=categorias/categorias" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->

