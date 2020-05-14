<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Rede Social <small>Atualização!</small></h1>
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
                $social_id = filter_input(INPUT_GET, "social_id", FILTER_VALIDATE_INT);

                if (!empty($Data["sendUpdate"])):
                    unset($Data["sendUpdate"]);
                    $Data['social_image'] = ($_FILES['social_image']['tmp_name'] ? $_FILES['social_image'] : null);

                    $update = new RedeSocial;
                    $update->ExeUpdate($social_id, $Data);

                    if ($update->getResult()):
                        header("Location: painel.php?exe=redesocial/lista");
                    else:
                        $erro = $update->getErro();
                        KLErro($erro[0], $erro[1]);
                    endif;

                else:

                    //Busca os dados na tabela                    
                    $read = new Read;
                    $read->ExeRead("kl_sociais", "WHERE social_id = :id", "id={$social_id}");
                    if (!$read->getResult()):
                        header("Location: painel.php?exe=redesocial/lista&update=false");
                    else:
                        $res = $read->getResult();
                        $data = $res[0];
                    endif;

                endif;
                ?>
                <!--INICIO DO FORM-->
                <form role="form" class="form-horizontal" name="formSocial" action="" method="post" id="frm" enctype="multipart/form-data">                          

                    <div class="form-group">    
                        <label for="social_nome" class="col-sm-2 control-label">Rede Social</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="social_nome" id="social_nome" 
                                placeholder="Nome da Rede social" 
                                value="<?php
                                if (empty($Data)): echo $data['social_nome'];
                                else: echo $Data['social_nome'];
                                endif;
                                ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o Nome da Rede social.</small></p>
                        </div>
                    </div>

                    <div class="form-group">    
                        <label for="social_url" class="col-sm-2 control-label">URL Social</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="social_url" id="social_url" 
                                placeholder="Nome da url social" 
                                value="<?php
                                if (empty($Data)): echo $data['social_url'];
                                else: echo $Data['social_url'];
                                endif;
                                ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o URL da Rede social.</small></p>
                        </div>
                    </div>

                    <div class="form-group"> 
                        <label for="social_status" class="col-sm-2 control-label">Status</label>
                        <?php
                        if (isset($data['social_status']) && $data['social_status'] == "S"):
                            ?>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="social_status" id="status1" value="S" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="social_status" id="status2" value="N"> Inativo
                                </label>    
                            </div>
                            <?php
                        else:
                            ?>
                            <div class="col-lg-4">
                                <label class="radio-inline">
                                    <input type="radio" name="social_status" id="status1" value="S" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="social_status" id="status2" value="N" checked="checked"> Inativo
                                </label>
                            </div>
                        <?php
                        endif;
                        ?>
                    </div>

                    <div class="form-group">     
                        <label for="social_ordem" class="col-sm-2 control-label">Ordem</label>
                        <div class="col-lg-3">
                            <input 
                                type="text" 
                                class="form-control" 
                                name="social_ordem" 
                                id="social_ordem" 
                                placeholder="Ordem" 
                                value="<?php
                                if (empty($Data)): echo $data['social_ordem'];
                                else: echo $Data['social_ordem'];
                                endif;
                                ?>" 
                                required
                                >
                            <p class="help-block"><small>Informe a Ordem da rede social.</small></p>
                        </div>
                    </div> 

                    <div class="form-group">
                        <label for="social_image" class="col-sm-2 control-label">Imagem</label>
                        <div class="col-lg-4">
                            <input type="file" id="social_image" name="social_image" value="">
                            <p class="help-block">Informe uma imagem em .png ou .jpg tamanho de 128 px.</p>
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label"><span class="glyphicon glyphicon-picture" aria-hidden="true"></span></label>
                        <div class="col-lg-4">
                            <?php
                            if (empty($data['social_image'])):
                                $imagem = INCLUDE_PATH . "/images/semLogo.jpg";
                                echo "<img src=\"{$imagem}\" width=\"32\">";
                            //echo $Data['cms_ordem']; 
                            else:
                                $imagem = INCLUDE_PATH . "/images";
                                echo "<img src=\"{$imagem}/{$data['social_image']}\" width=\"32\">";
//                                echo "<br><br><a data-toggle=\"tooltip\" data-placement=\"right\" href=\"painel.php?exe=redesocial/update&social_id={$data['social_id']}\" title=\"Apagar Imagem\"><span class=\"glyphicon glyphicon-trash corRed\" aria-hidden=\"true\"></span></a>";
                            endif;
                            ?>
                        </div>
                    </div>

                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-warning" name="sendUpdate" value="Atualizar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=redesocial/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
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

