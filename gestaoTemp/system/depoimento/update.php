<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
extract($_SESSION['userlogin']);
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Depoimento <small>Atualizar!</small></h1>
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
                //VERIFICA NIVEL
                $nivel = new Check();
                $nivel->nivel($user_nivel);
                
                // realiza a alteração
                $Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                $dep_id = filter_input(INPUT_GET, "dep_id", FILTER_VALIDATE_INT);

                if (!empty($Data["sendUpdate"])):
                    unset($Data["sendUpdate"]);

                    $update = new Depoimento;
                    $update->ExeUpdate($dep_id, $Data);
                    
                    if (!$update->getResult()):
                        $erro = $update->getErro();
                        KLErro($erro[0], $erro[1]);
                    else:
                        header("Location: painel.php?exe=depoimento/lista");
                    endif;

                else:

                    //Busca os dados na tabela                    
                    $read = new Read;
                    $read->ExeRead("kl_depoimento", "WHERE dep_id = :id", "id={$dep_id}");
                    if (!$read->getResult()):
                        header("Location: painel.php?exe=depoimento/lista&update=false");
                    else:
                        $res = $read->getResult();
                        $data = $res[0];
                    endif;

                endif;
                ?>
                <form role="form" class="form-horizontal" name="formDepoimento" action="" method="post" id="frm">                          

                    <div class="form-group">    
                        <label for="dep_nome" class="col-sm-2 control-label">Nome</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="dep_nome" id="dep_nome" 
                                placeholder="Nome" 
                                value="<?php
                                if (isset($data['dep_nome'])):
                                    echo $data['dep_nome'];
                                else:
                                    echo $Data['dep_nome'];
                                endif;
                                ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o Nome.</small></p>
                        </div>
                    </div>
                    
                    <div class="form-group">    
                        <label for="dep_email" class="col-sm-2 control-label">E-mail</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="dep_email" id="dep_email" 
                                placeholder="E-mail" 
                                value="<?php
                                if (isset($data['dep_email'])):
                                    echo $data['dep_email'];
                                else:
                                    echo $Data['dep_email'];
                                endif;
                                ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o Email.</small></p>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="dep_depoimento" class="col-sm-2 control-label">Depoimento</label>
                        <div class="col-lg-8">
                            <textarea class="form-control" rows="3" name="dep_depoimento" id="dep_depoimento"><?php if (isset($data)): echo $data['dep_depoimento'];
                                else: echo $Data['dep_depoimento'];
                                endif; ?></textarea>                                                        
                            <p class="help-block"><small>Informe o Depoimento.</small></p>
                        </div>
                    </div>
                    
                    <div class="form-group">     
                        <label for="dep_data" class="col-sm-2 control-label">Data</label>
                        <div class="col-lg-3">
                            <input 
                                type="date" 
                                class="form-control" 
                                name="dep_data" 
                                id="dep_data" 
                                placeholder="Data" 
                                value="<?php                                
                                if (isset($data['dep_data'])):
                                    echo $data['dep_data'];
                                else:
                                    echo $Data['dep_data'];
                                endif;
                                ?>" 
                                required
                                >
                            <!--<p class="help-block"><small>Informe a Ordem das Categorias ou SubCategorias.</small></p>-->
                        </div>
                    </div> 
                    <div class="form-group">
                        <label for="dep_status" class="col-sm-2 control-label">Status</label>
                        <div class="col-lg-4">
                            <?php
                            if (isset($data) && $data['dep_status'] == "S"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="dep_status" id="status1" value="S" checked="checked"> Ativo
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="dep_status" id="status2" value="N"> Inativo
                                </label>                                    
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="dep_status" id="status1" value="S" > Ativo
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="dep_status" id="status2" value="N" checked="checked"> Inativo
                                </label>                                        
                            <?php
                            endif;
                            ?>
                        </div>                        
                    </div>
                    <div class="well centralizaTxt">
                        <input type="submit" class="btn btn-warning" name="sendUpdate" value="Atualizar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=depoimento/lista" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
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

