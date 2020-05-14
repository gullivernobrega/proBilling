<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Páginas CMS <small>Atualização!</small></h1>
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
                // realiza a alteração
                $CmsData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $cms_id = filter_input(INPUT_GET, "cms_id", FILTER_VALIDATE_INT);                
                
                if (!empty($CmsData["sendUpdate"])):
                    unset($CmsData["sendUpdate"]);
                    $CmsData['cms_imagem'] = ($_FILES['cms_imagem']['tmp_name'] ? $_FILES['cms_imagem'] : null);

                    $update = new Paginas();
                    $update->ExeUpdate($cms_id, $CmsData);

                    if ($update->getResult()):
                        header("Location: painel.php?exe=paginas/lista");
                    else:
                        $erro = $update->getErro();
                        KLErro($erro[0], $erro[1]);
                    endif;

                else:

                    //Busca os dados na tabela                    
                    $readCms = new Read;
                    $readCms->ExeRead("kl_cms", "WHERE cms_id = :id", "id={$cms_id}");
                    if (!$readCms->getResult()):
                        header("Location: painel.php?exe=paginas/lista&update=false");
                    else:
                        $res = $readCms->getResult();
                        $Data = $res[0];
                    endif;

                endif;                
                ?>
                <!--INICIO DO FORM-->
                <form role="form" class="form-horizontal" name="formCms" action="" method="post" id="frm" enctype="multipart/form-data">                          

                    <div class="form-group">    
                        <label for="cms_nome" class="col-sm-2 control-label">Página Cms</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="cms_nome" id="categoria_nome" 
                                placeholder="Nome da pagina Cms" 
                                value="<?php
                                if (empty($CmsData)): echo $Data['cms_nome'];
                                else: echo $CmsData['cms_nome'];
                                endif;
                                ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o Nome da Pagina Cms.</small></p>
                        </div>
                    </div>
                    <div class="form-group">  
                        <label for="categoria_id" class="col-sm-2 control-label">Categoria do Cms</label>
                        <div class="col-xs-5">
                            <select class="form-control" name="categoria_id" id="categoria_id">
                                <option value="0">Cateroria</option>
                                <?php
                                $status = "S";
                                $readCat = new Read;
                                $readCat->ExeRead("kl_categorias", "WHERE categoria_status = :s ORDER BY categoria_nome ASC", "s={$status}");

                                if (!$readCat->getResult()):
                                    echo '<option disabled="disabled" value="0">Cadastre antes uma Categoria</option>';
                                else:
                                    foreach ($readCat->getResult() as $Categoria):
                                        echo "<option value=\"{$Categoria['categoria_id']}\" ";
                                        if (!empty($CmsData['categoria_id']) && $CmsData['categoria_id'] == $Categoria['categoria_id']):
                                            echo ' selected = "selected" ';
                                        elseif (!empty($Data) && $Data['categoria_id'] == $Categoria['categoria_id']):
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
                            <textarea class="form-control" rows="3" name="cms_conteudo" id="cms_conteudo"><?php
                                if (empty($CmsData)): echo $Data['cms_conteudo'];
                                else: echo $CmsData['cms_conteudo'];
                                endif;
                                ?></textarea>                                                        
                            <p class="help-block"><small>Informe o Conteúdo da página Cms.</small></p>
                        </div>
                    </div>

                    <div class="form-group">    
                        <label for="cms_link" class="col-sm-2 control-label">Link Cms</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="cms_link" id="categoria_nome" 
                                placeholder="Link da pagina Cms" 
                                value="<?php
                                if (empty($CmsData)): echo $Data['cms_link'];
                                else: echo $CmsData['cms_link'];
                                endif;
                                ?>"
                                >
                            <p class="help-block"><small>Informe o Link da Pagina ou deixe em branco caso não possua link.</small></p>
                        </div>
                    </div>

                    <div class="form-group"> 
                        <label for="cms_status" class="col-sm-2 control-label">Status</label>
                        <?php
                        if (isset($Data['cms_status']) && $Data['cms_status'] == "S"):
                            ?>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="cms_status" id="status1" value="S" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="cms_status" id="status2" value="N"> Inativo
                                </label>    
                            </div>
                            <?php
                        else:
                            ?>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input type="radio" name="cms_status" id="status1" value="S" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="cms_status" id="status2" value="N" checked="checked" > Inativo
                                </label>
                            </div>
                        <?php
                        endif;
                        ?>
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
                                value="<?php
                                if (empty($CmsData)): echo $Data['cms_ordem'];
                                else: echo $CmsData['cms_ordem'];
                                endif;
                                ?>" 
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
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span></label>
                        <div class="col-lg-4">
                            <?php
                            if (empty($Data['cms_imagem'])):
                                $imagem = INCLUDE_PATH . "/images/semLogo.jpg";
                                echo "<img src=\"{$imagem}\" width=\"70%\">";
                            //echo $Data['cms_ordem']; 
                            else:
                                $imagem = INCLUDE_PATH . "/images" . $Data['cms_imagem'];
                                echo "<img src=\"{$imagem}\" width=\"70%\">";
                                echo "<br><br><a data-toggle=\"tooltip\" data-placement=\"right\" href=\"painel.php?exe=paginas/delImg&cms_id={$Data['cms_id']}\" title=\"Apagar Imagem\"><span class=\"glyphicon glyphicon-trash corRed\" aria-hidden=\"true\"></span></a>";                            
                            endif;
                            ?>
                        </div>
                    </div>

                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-warning" name="sendUpdate" value="Atualizar Cadastro">                        
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

