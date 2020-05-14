<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Imagens Galeria <small>Cadastro!</small></h1>
    </div>       

    <!--Inicio do Painel-->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> Painel de Cadastro</h3>
        </div>
        <div class="panel-body">
            <div id="shieldui-grid1">
                <!--FORMULARIO-->
                <!--<div class="col-lg-10">-->
                <?php
                //Pega o id da galeria
                $galeria_id = filter_input(INPUT_GET, "galeria_id", FILTER_VALIDATE_INT);
                $img_id = filter_input(INPUT_GET, "img_id", FILTER_VALIDATE_INT);
                $acao = filter_input(INPUT_GET, "Acao", FILTER_DEFAULT);                
                $imagemData = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                if (isset($imagemData) && $imagemData["sendCreate"]):
                    unset($imagemData["sendCreate"]);

                    if (!empty($_FILES['img_imagem']['tmp_name'])):

                        $sendImage = new ImageGalery;
                        $sendImage->ExeCreate($galeria_id, $_FILES['img_imagem']);

                        if ($sendImage->getResult()):
                            $erro = $sendImage->getErro();
                            KLErro($erro[0], $erro[1]);                            
                        endif;

                    endif;
                endif;
                
                //Atualiza status
                if (!empty($acao) && $acao == "Update" && !empty($img_id)):
                    $readSts = new Read;
                    $readSts->ExeRead("kl_imagem", "WHERE img_id = :imgId AND galeria_id = :gId", "imgId={$img_id}&gId={$galeria_id}");
                    $status = $readSts->getResult();
                    extract($status[0]);

                    if ($img_status == "S"):
                        $Data["img_status"] = "N";
                    else:
                        $Data["img_status"] = "S";
                    endif;

                    $upImage = new ImageGalery;
                    $upImage->ExeUpdate($img_id, $Data);

                    if (!$upImage->getResult()):
                        $erro = $upImage->getErro();
                        KLErro($erro[0], $erro[1]);                        
                    endif;    
                endif;
                
                //Apagar imagem
                if (!empty($acao) && $acao == "Del" && !empty($img_id)):
                    
                    $upImage = new ImageGalery;
                    $upImage->ExeDelete($img_id);

                    if ($upImage->getResult()):
                        //$erro = $upImage->getErro();
                        //KLErro($erro[0], $erro[1]);
                        KLErro("Erro, Não foi possivel alterar o status da imagem", KL_ERROR);
                    endif;    
                endif;
                
                
                ?>
                <form role="form" class="form-horizontal" name="formImagem" action="" method="post" id="frm" enctype="multipart/form-data">                          
                    <div class="form-group">
                        <label for="img_imagem" class="col-sm-2 control-label">Imagens </label>
                        <div class="col-lg-5">
                            <input type="file" multiple id="img_imagem" name="img_imagem[]" value="">
                            <p class="help-block">Informe a imagem ou imagens em formato .png ou .jpg</p>
                        </div>                        
                    </div>

                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-success" name="sendCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=galerias/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form> <!--fim formulario-->               

            </div>
        </div>
    </div> <!-- fim painel -->
    <br>
    <!--Painel de Imagens-->
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> Painel de Imagens</h3>
        </div>
        <div class="panel-body">
            <div id="shieldui-grid1">
                <!--IMAGENS-->
                <?php
                if (!empty($galeria_id)):
                    $readImg = new Read;
                    $readImg->ExeRead("kl_imagem", "WHERE galeria_id = :id ORDER BY img_id DESC", "id={$galeria_id}");
                    $contaImg = $readImg->getRowCount();
                    $dir = INCLUDE_PATH . "/";  
                    if ($contaImg > 0):
                        foreach ($readImg->getResult() as $img):
                            extract($img);                                                      
                            ?>
                            <div class="col-md-2">                   
                                <!--<div class="row">-->
                                <div class="well"> 
                                    <div class="thumbImg">
                                        <div class="thumbnail">
                                            <img class="img-responsive" alt="<?php echo $img_nome; ?>" src="<?php echo $dir . $img_dir . $img_imagem; ?>" height="180">
                                        </div>
                                    </div>
                                    <div class="caption text-center">
                                        <!--<form role="form" class="form-horizontal" name="frmUpdate" action="" method="post" id="frmUpdate">-->
                                        <p><?php echo $img_nome; ?></p>
                                        <p> 
                                            <?php
                                            if ($img_status == "S"):
                                                ?>
                                                <a href="painel.php?exe=galerias/imagens&galeria_id=<?php echo $galeria_id ?>&img_id=<?php echo $img_id ?>&Acao=Update" class="btn btn-default" aria-label="Left Align" title="Desativar">
                                                    <span class="glyphicon glyphicon-ok-sign corGrem" aria-hidden="true"></span>
                                                </a> 
                                                <?php
                                            else:
                                                ?>
                                                <a href="painel.php?exe=galerias/imagens&galeria_id=<?php echo $galeria_id ?>&img_id=<?php echo $img_id ?>&Acao=Update" class="btn btn-default" aria-label="Left Align" title="Ativar">
                                                    <span class="glyphicon glyphicon-info-sign corRed" aria-hidden="true"></span>
                                                </a>
                                            <?php
                                            endif;
                                            ?>
                                            <a href="painel.php?exe=galerias/imagens&galeria_id=<?php echo $galeria_id ?>&img_id=<?php echo $img_id ?>&Acao=Del" class="btn btn-default" aria-label="Left Align" title="Apagar">
                                                <span class="glyphicon glyphicon-trash corRed" aria-hidden="true"></span>
                                            </a> 
                                        </p>
                                        <!--</form>-->
                                    </div>
                                </div>
                                <!--</div>fecha a row -->
                            </div> <!--fecha a col -->
                            <?php
                        endforeach;
                    else:
                        KLErro("Não existe imagens cadastrado para esta Galeria!", KL_ERROR);
                    endif;
                endif; //if galeria
                ?>    
            </div>
        </div>  
    </div> <!-- fim painel -->
</div>
