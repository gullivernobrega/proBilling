<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Galerias <small>Atualização!</small></h1>        
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
                $galeriaData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $galeria_id = filter_input(INPUT_GET, "galeria_id", FILTER_VALIDATE_INT);

                if (!empty($galeriaData["sendUpdate"])):
                    unset($galeriaData["sendUpdate"]);
                    $galeriaData['galeria_img_capa'] = ($_FILES['galeria_img_capa']['tmp_name'] ? $_FILES['galeria_img_capa'] : null);
                             
                    $update = new Galeria();
                    $update->ExeUpdate($galeria_id, $galeriaData);

                    if ($update->getResult()):
                        header("Location: painel.php?exe=galerias/lista");
                    else:
                        $erro = $update->getErro();
                        KLErro($erro[0], $erro[1]);
                    endif;

                else:
                    //Busca os dados na tabela                    
                    $readGaleria = new Read;
                    $readGaleria->ExeRead("kl_galeria", "WHERE galeria_id = :id", "id={$galeria_id}");
                    if (!$readGaleria->getResult()):
                        header("Location: painel.php?exe=galerias/lista&update=false");
                    else:
                        $res = $readGaleria->getResult();
                        $Data = $res[0];
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
                                        elseif (!empty($Data) && $Data['cms_id'] == $Cms['cms_id']):
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
                                value="<?php if (empty($galeriaData['galeria_nome'])): echo $Data['galeria_nome']; else: echo $galeriaData['galeria_nome']; endif;?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o Nome da Galeria.</small></p>
                        </div>
                    </div>

                    <div class="form-group"> 
                        <label for="galeria_status" class="col-sm-2 control-label">Status</label>
                        <?php
                        if (isset($Data['galeria_status']) && $Data['galeria_status'] == "S"):
                            ?>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="galeria_status" id="status1" value="S" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="galeria_status" id="status2" value="N"> Inativo
                                </label>    
                            </div>
                            <?php
                        else:
                            ?>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input type="radio" name="galeria_status" id="status1" value="S" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="galeria_status" id="status2" value="N" checked="checked" > Inativo
                                </label>
                            </div>
                        <?php
                        endif;
                        ?>
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
                                value="<?php if (empty($galeriaData['galeria_ordem'])):echo $Data['galeria_ordem']; else: echo $galeriaData['galeria_ordem']; endif;?>" 
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
                    </div>

                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span></label>
                        <div class="col-lg-4">
                            <?php
                            if (empty($Data['galeria_img_capa'])):
                                $imagem = INCLUDE_PATH . "/images/semLogo.jpg";
                                echo "<img src=\"{$imagem}\" width=\"70%\">";
                            //echo $Data['cms_ordem']; 
                            else:
                                $imagem = INCLUDE_PATH . "/images" . $Data['galeria_img_capa'];
                                echo "<img src=\"{$imagem}\" width=\"70%\">";
                                echo "<br><br><a data-toggle=\"tooltip\" data-placement=\"right\" href=\"painel.php?exe=galerias/delImg&galeria_id={$Data['galeria_id']}\" title=\"Apagar Imagem\"><span class=\"glyphicon glyphicon-trash corRed\" aria-hidden=\"true\"></span></a>";
                            endif;
                            ?>
                        </div>
                    </div>
                    
                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-success" name="sendUpdate" value="Atualizar Cadastro">                        
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
