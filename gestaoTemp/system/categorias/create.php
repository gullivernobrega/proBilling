<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Categorias e Subcategorias <small>Cadastro!</small></h1>
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
                $CategoriaData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if (isset($CategoriaData["categoriaCreate"])):
                    unset($CategoriaData["categoriaCreate"]);
                   
                    $cadastra = new Categories;
                    $cadastra->ExeCreate($CategoriaData);

                    if (!$cadastra->getResult()):
                        $erro = $cadastra->getErro();                        
                        KLErro($erro[0], $erro[1]);
                    else:
                        $erro = $cadastra->getErro();                        
                        KLErro($erro[0], $erro[1]);
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
                                value="<?php if (!empty($CategoriaData['categoria_nome'])) echo $CategoriaData['categoria_nome']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o Nome para Categoria ou Subcategoria.</small></p>
                        </div>
                    </div>

                    <div class="form-group">                        
                        <input type="hidden" name="categoria_data" value="<?php echo date("Y-m-d H:i:s"); ?>">                        
                    </div>

                    <div class="form-group">  
                        <label for="categoria_parente" class="col-sm-2 control-label">Categoria Parente</label>
                        <div class="col-xs-5">
                            <select class="form-control" name="categoria_parente" id="categoria_parente">
                                <option value="NULL">Cateroria Parente</option>
                                
                                <?php
                                  $readParente = new Read;
                                  $readParente->ExeRead("kl_categorias", "WHERE categoria_parente = 0 ORDER BY categoria_nome");
                                  if(!$readParente->getResult()):
                                      echo '<option disabled="disabled" value="NULL">Cadastre antes uma Categoria</option>';
                                  else:
                                      foreach ($readParente->getResult() as $parente):
                                        echo "<option value=\"{$parente['categoria_id']}\" ";
                                        if($parente['categoria_id'] == $CategoriaData['categoria_parente']):
                                            echo ' selected = "selected" ';
                                        endif;
                                        echo ">{$parente['categoria_nome']}</option>";
                                      endforeach;
                                  endif;
                                
//                                $read_Cat = new Read;
//                                $read_Cat->ExeRead("kl_categorias");
//                                $lstCat = $read_Cat->getResult();
//                                foreach ($lstCat as $cat):
//
//                                    $categoria_id = $cat['menu_id'];
//
//                                    if (!empty($CategoriaData['categoria_id']) && $CategoriaData['categoria_id'] == $categoria_id):
//                                        $selecionado = "selected";
//                                    else:
//                                        $selecionado = "";
//                                    endif;
//
//                                    print"<option value= \"$categoria_id\" $selecionado> $CategoriaData[categoria_nome] </option>";
//
//                                endforeach;
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
                                value="<?php if (!empty($CategoriaData['categoria_ordem'])) echo $CategoriaData['categoria_ordem']; ?>" 
                                required
                                >
                            <p class="help-block"><small>Informe a Ordem das Categorias ou SubCategorias.</small></p>
                        </div>
                    </div> 
                    <div class="form-group">                        
                        <!--<input type="hidden" name="categoria_registrado" value="<?php //echo date("Y-m-d H:i:s");  ?>">-->
                        <input type="hidden" name="categoria_status" value="S">
                    </div>
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-success" name="categoriaCreate" value="Salvar Cadastro">                        
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

