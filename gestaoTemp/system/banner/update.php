<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Banner <small>Atualização!</small></h1>
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
                $Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $ban_id = filter_input(INPUT_GET, "ban_id", FILTER_VALIDATE_INT);

                if (!empty($Data["sendUpdate"])):
                    unset($Data["sendUpdate"]);
                    $Data['ban_image'] = ($_FILES['ban_image']['tmp_name'] ? $_FILES['ban_image'] : null);

                    $update = new Banner;
                    $update->ExeUpdate($ban_id, $Data);

                    if ($update->getResult()):
                        header("Location: painel.php?exe=banner/lista");
                    else:
                        $erro = $update->getErro();
                        KLErro($erro[0], $erro[1]);
                    endif;

                else:

                    //Busca os dados na tabela                    
                    $read = new Read;
                    $read->ExeRead("kl_banner", "WHERE ban_id = :id", "id={$ban_id}");
                    if (!$read->getResult()):
                        header("Location: painel.php?exe=banner/lista&update=false");
                    else:
                        $res = $read->getResult();
                        $data = $res[0];
                    endif;

                endif;
                ?>
                <!--INICIO DO FORM-->
                <form role="form" class="form-horizontal" name="formBanner" action="" method="post" id="frm" enctype="multipart/form-data">                          
                    
                     <div class="form-group">    
                        <label for="ban_titulo" class="col-sm-2 control-label">Titulo</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="ban_titulo" id="cms_nome" 
                                placeholder="Titulo do Banner" 
                                value="<?php if (empty($Data)): echo $data['ban_titulo']; else: echo $Data['ban_titulo']; endif;?>" 
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
                                value="<?php if (empty($Data['ban_link'])): echo $data['ban_link']; else: echo $Data['ban_titulo']; endif; ?>"                                  
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
                                <?php 
                                if (!empty($data['ban_posicao'])):
                                ?>
                                <option value = "1" <?php echo ($data['ban_posicao'] == 1) ? 'selected="selected"' : null; ?>>Principal</option>
                                <option value="2" <?php echo ($data['ban_posicao'] == 2) ? 'selected="selected"' : null; ?>>Lado esquerdo</option>
                                <option value="3" <?php echo ($data['ban_posicao'] == 3) ? 'selected="selected"' : null; ?>>Lado direito</option>
                                <option value="3" <?php echo ($data['ban_posicao'] == 4) ? 'selected="selected"' : null; ?>>Em baixo</option>
                                <?php 
                                else:
                                ?>
                                <option value = "1" <?php echo ($Data['ban_posicao'] == 1) ? 'selected="selected"' : null; ?>>Principal</option>
                                <option value="2" <?php echo ($Data['ban_posicao'] == 2) ? 'selected="selected"' : null; ?>>Lado esquerdo</option>
                                <option value="3" <?php echo ($Data['ban_posicao'] == 3) ? 'selected="selected"' : null; ?>>Lado direito</option>
                                <option value="3" <?php echo ($Data['ban_posicao'] == 4) ? 'selected="selected"' : null; ?>>Em baixo</option>
                                <?php 
                                endif;
                                ?>
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
                                value="<?php if (empty($Data['ban_ordem'])): echo $data['ban_ordem']; else: echo $Data['ban_ordem']; endif;?>" 
                                required
                                >
<!--                            <p class="help-block"><small>Informe a Ordem.</small></p>-->
                        </div>   
                    </div>   

                     <div class="form-group">
                    <label for="ban_status" class="col-sm-2 control-label">Status:</label>
                    <div class="col-lg-4">
                        <?php
                        if (!empty($data['ban_status']) && $data['ban_status'] == "S"):
                            ?>
                            <label class="radio-inline">
                                <input onClick="return mudacor('1');" type="radio" name="ban_status" id="status1" value="S" checked="checked"> Ativo
                            </label>
                            <label class="radio-inline">
                                <input onClick="return mudacor('2');" type="radio" name="ban_status" id="status2" value="N"> Inativo
                            </label>                                    
                            <?php
                        else:
                            ?>
                            <label class="radio-inline">
                                <input type="radio" name="ban_status" id="status1" value="S" > Ativo
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="ban_status" id="status2" value="N" checked="checked"> Inativo
                            </label>                                        
                        <?php
                        endif;
                        ?>
                    </div>                        
                </div>
                    <div class="form-group">
                        <label for="ban_image" class="col-sm-2 control-label">Imagem</label>
                        <div class="col-lg-4">
                            <input type="file" id="ban_image" name="ban_image" value="">
                            <p class="help-block">Informe uma imagem em .png ou .jpg</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span></label>
                        <div class="col-lg-4">
                            <?php
                            if (empty($data['ban_image'])):
                                $imagem = INCLUDE_PATH . "/images/semLogo.jpg";
                                echo "<img src=\"{$imagem}\" width=\"200\">";
                            //echo $Data['cms_ordem']; 
                            else:
                                $imagem = INCLUDE_PATH . "/images";
                                echo "<img src=\"{$imagem}/{$data['ban_image']}\" width=\"200\">";
//                                echo "<br><br><a data-toggle=\"tooltip\" data-placement=\"right\" href=\"painel.php?exe=redesocial/update&social_id={$data['social_id']}\" title=\"Apagar Imagem\"><span class=\"glyphicon glyphicon-trash corRed\" aria-hidden=\"true\"></span></a>";
                            endif;
                            ?>
                        </div>
                    </div>

                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-warning" name="sendUpdate" value="Atualizar Cadastro">                        
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

