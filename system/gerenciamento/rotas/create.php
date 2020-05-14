<?php
if (!class_exists('Login')) :
    header('Location: ../../painel.php');
    die;
endif;
?>

<div class="page-header">
    <h1>Rotas<small> Cadastro e Alteração!</small></h1>
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
                $Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if (isset($Data["rotaCreate"])):
                    unset($Data["rotaCreate"]);

                    $cadastra = new Rotas();
                    $cadastra->ExeCreate($Data);
                    if ($cadastra->getResult()):                        
                        //header("Location: painel.php?exe=gerenciamento/rotas/create");
                        KLErro("Rota salva com sucesso!", KL_ACCEPT);
                        header("refresh: 2; painel.php?exe=gerenciamento/rotas/create");                        
                    else:
                        KLErro("Ops, não foi possivel realizar o cadastro!", KL_ERROR);
                    endif;

                endif;

                //Busca os dados na tabela para listagem                   
                $read = new Read;
                $read->ExeRead("rotas");

                if ($read->getRowCount() > 0):
                    $Data = $read->getResult();
                    extract($Data[0]);
                    ?>
                    <!--******************************** FORMULARO 2 ********************************-->

                    <form role="form" class="form-horizontal txtblue" name="formRotas" action="" method="post" id="frm">                          

                        <div class="container-fluid">
                            <!--LINHA 1-->
                            <div class="row">                                
                                <!--COLUNA MASTER-->
                                <div class="col-sm-12">
                                    <h3>MASTER</h3>
                                    <div class="col-sm-4">
                                        <p>FIXO</p>
                                        <!--FIXO MASTER-->
                                        <div class="form-group">                                                    
                                            <select class="form-control" name="rota_tronco_fixo_m" id="tronco" required>
                                                <option value="">Master</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                        if ($rota_tronco_fixo_m == $value['tronco_nome']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <p>MOVEL</p>                                        
                                        <!--MOVEL MASTER-->
                                        <div class="form-group">  
                                            <div class="col-xs-12"> 
                                                <select class="form-control" name="rota_tronco_movel_m" id="tronco" required>
                                                    <option value="">Master</option>
                                                    <?php
                                                    $teste = new Read;
                                                    $teste->ExeRead("tronco");

                                                    if (!$teste->getResult()):
                                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                    else:
                                                        foreach ($teste->getResult() as $value):
                                                            //passa o id e o tipo 
                                                            echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                            if ($rota_tronco_movel_m == $value['tronco_nome']):
                                                                echo ' selected = "selected" ';
                                                            endif;

                                                            echo ">{$value['tronco_nome']}</option>";
                                                        endforeach;
                                                    endif;
                                                    ?>               
                                                </select> 
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-4">
                                        <p>INTERNACIONAL</p> 
                                        <!--INTER MASTER-->
                                        <div class="form-group">  
                                            <div class="col-xs-12"> 
                                                <select class="form-control" name="rota_tronco_inter_m" id="tronco" required>
                                                    <option value="">Master</option>
                                                    <?php
                                                    $teste = new Read;
                                                    $teste->ExeRead("tronco");

                                                    if (!$teste->getResult()):
                                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                    else:
                                                        foreach ($teste->getResult() as $value):
                                                            //passa o id e o tipo 
                                                            echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                            if ($rota_tronco_inter_m == $value['tronco_nome']):
                                                                echo ' selected = "selected" ';
                                                            endif;

                                                            echo ">{$value['tronco_nome']}</option>";
                                                        endforeach;
                                                    endif;
                                                    ?>               
                                                </select> 
                                            </div>
                                        </div>                                        
                                    </div>

                                </div> 
                            </div><!--fim row-->

                            <!--LINHA 2-->
                            <div class="row">                                
                                <!--COLUNA BACKUP1-->
                                <div class="col-sm-12">
                                    <h3>BACKUP 1</h3>
                                    <div class="col-sm-4">                                        
                                        <!--FIXO-->
                                        <div class="form-group">                                                    
                                            <select class="form-control" name="rota_tronco_fixo_b1" id="tronco" >
                                                <option value="">Backup 1</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                        if ($rota_tronco_fixo_b1 == $value['tronco_nome']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                        </div>
                                    </div>

                                    <div class="col-sm-4">                                                                               
                                        <!--MOVEL-->
                                        <div class="form-group">  
                                            <div class="col-xs-12"> 
                                                <select class="form-control" name="rota_tronco_movel_b1" id="tronco" >
                                                    <option value="">Backup 1</option>
                                                    <?php
                                                    $teste = new Read;
                                                    $teste->ExeRead("tronco");

                                                    if (!$teste->getResult()):
                                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                    else:
                                                        foreach ($teste->getResult() as $value):
                                                            //passa o id e o tipo 
                                                            echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                            if ($rota_tronco_movel_b1 == $value['tronco_nome']):
                                                                echo ' selected = "selected" ';
                                                            endif;

                                                            echo ">{$value['tronco_nome']}</option>";
                                                        endforeach;
                                                    endif;
                                                    ?>               
                                                </select> 
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-4">                                        
                                        <!--INTER-->
                                        <div class="form-group">  
                                            <div class="col-xs-12"> 
                                                <select class="form-control" name="rota_tronco_inter_b1" id="tronco" >
                                                    <option value="">Backup 1</option>
                                                    <?php
                                                    $teste = new Read;
                                                    $teste->ExeRead("tronco");

                                                    if (!$teste->getResult()):
                                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                    else:
                                                        foreach ($teste->getResult() as $value):
                                                            //passa o id e o tipo 
                                                            echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                            if ($rota_tronco_inter_b1 == $value['tronco_nome']):
                                                                echo ' selected = "selected" ';
                                                            endif;

                                                            echo ">{$value['tronco_nome']}</option>";
                                                        endforeach;
                                                    endif;
                                                    ?>               
                                                </select> 
                                            </div>
                                        </div>                                        
                                    </div>

                                </div> 
                            </div><!--fim row-->

                            <!--LINHA 3-->
                            <div class="row">                                
                                <!--COLUNA BACKUP1-->
                                <div class="col-sm-12">
                                    <h3>BACKUP 2</h3>
                                    <div class="col-sm-4">                                        
                                        <!--FIXO-->
                                        <div class="form-group">                                                    
                                            <select class="form-control" name="rota_tronco_fixo_b2" id="tronco" >
                                                <option value="">Backup 2</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                        if ($rota_tronco_fixo_b2 == $value['tronco_nome']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                        </div>
                                    </div>

                                    <div class="col-sm-4">                                                                               
                                        <!--MOVEL-->
                                        <div class="form-group">  
                                            <div class="col-xs-12"> 
                                                <select class="form-control" name="rota_tronco_movel_b2" id="tronco" >
                                                    <option value="">Backup 2</option>
                                                    <?php
                                                    $teste = new Read;
                                                    $teste->ExeRead("tronco");

                                                    if (!$teste->getResult()):
                                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                    else:
                                                        foreach ($teste->getResult() as $value):
                                                            //passa o id e o tipo 
                                                            echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                            if ($rota_tronco_movel_b2 == $value['tronco_nome']):
                                                                echo ' selected = "selected" ';
                                                            endif;

                                                            echo ">{$value['tronco_nome']}</option>";
                                                        endforeach;
                                                    endif;
                                                    ?>               
                                                </select> 
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-4">                                        
                                        <!--INTER-->
                                        <div class="form-group">  
                                            <div class="col-xs-12"> 
                                                <select class="form-control" name="rota_tronco_inter_b2" id="tronco" >
                                                    <option value="">Backup 2</option>
                                                    <?php
                                                    $teste = new Read;
                                                    $teste->ExeRead("tronco");

                                                    if (!$teste->getResult()):
                                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                    else:
                                                        foreach ($teste->getResult() as $value):
                                                            //passa o id e o tipo 
                                                            echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                            if ($rota_tronco_inter_b2 == $value['tronco_nome']):
                                                                echo ' selected = "selected" ';
                                                            endif;

                                                            echo ">{$value['tronco_nome']}</option>";
                                                        endforeach;
                                                    endif;
                                                    ?>               
                                                </select> 
                                            </div>
                                        </div>                                        
                                    </div>

                                </div> 
                            </div><!--fim row-->                            

                        </div>         

                        <!--BOTÕES-->
                        <div class="well txtCenter">                            
                            <input type="submit" class="btn btn-success" name="rotaCreate" value="Salvar Rotas">                        
                            <a class="btn btn-default" href="painel.php" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                        </div>
                    </form>

                <?php else: ?>
                    <!--******************************** FORMULARO 2 ********************************-->

                    <form role="form" class="form-horizontal txtblue" name="formRotas" action="" method="post" id="frm">                          

                        <div class="container-fluid">
                            <!--LINHA 1-->
                            <div class="row">                                
                                <!--COLUNA MASTER-->
                                <div class="col-sm-12">
                                    <h3>MASTER</h3>
                                    <div class="col-sm-4">
                                        <p>FIXO</p>
                                        <!--FIXO MASTER-->
                                        <div class="form-group">                                                    
                                            <select class="form-control" name="rota_tronco_fixo_m" id="tronco" required>
                                                <option value="">Master</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['rota_tronco_fixo_m'] == $value['tronco_nome']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                        </div>
                                    </div>

                                    <div class="col-sm-4">
                                        <p>MOVEL</p>                                        
                                        <!--MOVEL MASTER-->
                                        <div class="form-group">  
                                            <div class="col-xs-12"> 
                                                <select class="form-control" name="rota_tronco_movel_m" id="tronco" required>
                                                    <option value="">Master</option>
                                                    <?php
                                                    $teste = new Read;
                                                    $teste->ExeRead("tronco");

                                                    if (!$teste->getResult()):
                                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                    else:
                                                        foreach ($teste->getResult() as $value):
                                                            //passa o id e o tipo 
                                                            echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                            if ($Data['rota_tronco_movel_m'] == $value['tronco_nome']):
                                                                echo ' selected = "selected" ';
                                                            endif;

                                                            echo ">{$value['tronco_nome']}</option>";
                                                        endforeach;
                                                    endif;
                                                    ?>               
                                                </select> 
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-4">
                                        <p>INTERNACIONAL</p> 
                                        <!--INTER MASTER-->
                                        <div class="form-group">  
                                            <div class="col-xs-12"> 
                                                <select class="form-control" name="rota_tronco_inter_m" id="tronco" required>
                                                    <option value="">Master</option>
                                                    <?php
                                                    $teste = new Read;
                                                    $teste->ExeRead("tronco");

                                                    if (!$teste->getResult()):
                                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                    else:
                                                        foreach ($teste->getResult() as $value):
                                                            //passa o id e o tipo 
                                                            echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                            if ($Data['rota_tronco_inter_m'] == $value['tronco_nome']):
                                                                echo ' selected = "selected" ';
                                                            endif;

                                                            echo ">{$value['tronco_nome']}</option>";
                                                        endforeach;
                                                    endif;
                                                    ?>               
                                                </select> 
                                            </div>
                                        </div>                                        
                                    </div>

                                </div> 
                            </div><!--fim row-->

                            <!--LINHA 2-->
                            <div class="row">                                
                                <!--COLUNA BACKUP1-->
                                <div class="col-sm-12">
                                    <h3>BACKUP 1</h3>
                                    <div class="col-sm-4">                                        
                                        <!--FIXO-->
                                        <div class="form-group">                                                    
                                            <select class="form-control" name="rota_tronco_fixo_b1" id="tronco" >
                                                <option value="">Backup 1</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['rota_tronco_fixo_b1'] == $value['tronco_nome']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                        </div>
                                    </div>

                                    <div class="col-sm-4">                                                                               
                                        <!--MOVEL-->
                                        <div class="form-group">  
                                            <div class="col-xs-12"> 
                                                <select class="form-control" name="rota_tronco_movel_b1" id="tronco" >
                                                    <option value="">Backup 1</option>
                                                    <?php
                                                    $teste = new Read;
                                                    $teste->ExeRead("tronco");

                                                    if (!$teste->getResult()):
                                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                    else:
                                                        foreach ($teste->getResult() as $value):
                                                            //passa o id e o tipo 
                                                            echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                            if ($Data['rota_tronco_movel_b1'] == $value['tronco_nome']):
                                                                echo ' selected = "selected" ';
                                                            endif;

                                                            echo ">{$value['tronco_nome']}</option>";
                                                        endforeach;
                                                    endif;
                                                    ?>               
                                                </select> 
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-4">                                        
                                        <!--INTER-->
                                        <div class="form-group">  
                                            <div class="col-xs-12"> 
                                                <select class="form-control" name="rota_tronco_inter_b1" id="tronco" >
                                                    <option value="">Backup 1</option>
                                                    <?php
                                                    $teste = new Read;
                                                    $teste->ExeRead("tronco");

                                                    if (!$teste->getResult()):
                                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                    else:
                                                        foreach ($teste->getResult() as $value):
                                                            //passa o id e o tipo 
                                                            echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                            if ($Data['rota_tronco_inter_b1'] == $value['tronco_nome']):
                                                                echo ' selected = "selected" ';
                                                            endif;

                                                            echo ">{$value['tronco_nome']}</option>";
                                                        endforeach;
                                                    endif;
                                                    ?>               
                                                </select> 
                                            </div>
                                        </div>                                        
                                    </div>

                                </div> 
                            </div><!--fim row-->

                            <!--LINHA 3-->
                            <div class="row">                                
                                <!--COLUNA BACKUP2-->
                                <div class="col-sm-12">
                                    <h3>BACKUP 2</h3>
                                    <div class="col-sm-4">                                        
                                        <!--FIXO-->
                                        <div class="form-group">                                                    
                                            <select class="form-control" name="rota_tronco_fixo_b2" id="tronco" >
                                                <option value="">Backup 2</option>
                                                <?php
                                                $teste = new Read;
                                                $teste->ExeRead("tronco");

                                                if (!$teste->getResult()):
                                                    echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                else:
                                                    foreach ($teste->getResult() as $value):
                                                        //passa o id e o tipo 
                                                        echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                        if ($Data['rota_tronco_fixo_b2'] == $value['tronco_nome']):
                                                            echo ' selected = "selected" ';
                                                        endif;

                                                        echo ">{$value['tronco_nome']}</option>";
                                                    endforeach;
                                                endif;
                                                ?>               
                                            </select> 
                                        </div>
                                    </div>

                                    <div class="col-sm-4">                                                                               
                                        <!--MOVEL-->
                                        <div class="form-group">  
                                            <div class="col-xs-12"> 
                                                <select class="form-control" name="rota_tronco_movel_b2" id="tronco" >
                                                    <option value="">Backup 2</option>
                                                    <?php
                                                    $teste = new Read;
                                                    $teste->ExeRead("tronco");

                                                    if (!$teste->getResult()):
                                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                    else:
                                                        foreach ($teste->getResult() as $value):
                                                            //passa o id e o tipo 
                                                            echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                            if ($Data['rota_tronco_movel_b2'] == $value['tronco_nome']):
                                                                echo ' selected = "selected" ';
                                                            endif;

                                                            echo ">{$value['tronco_nome']}</option>";
                                                        endforeach;
                                                    endif;
                                                    ?>               
                                                </select> 
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-sm-4">                                        
                                        <!--INTER-->
                                        <div class="form-group">  
                                            <div class="col-xs-12"> 
                                                <select class="form-control" name="rota_tronco_inter_b2" id="tronco" >
                                                    <option value="">Backup 2</option>
                                                    <?php
                                                    $teste = new Read;
                                                    $teste->ExeRead("tronco");

                                                    if (!$teste->getResult()):
                                                        echo '<option disabled="disabled" value="NULL">Cadastre antes um Tronco!</option>';
                                                    else:
                                                        foreach ($teste->getResult() as $value):
                                                            //passa o id e o tipo 
                                                            echo "<option value=\"{$value['tronco_nome']}-{$value['tronco_tipo']}\" ";

                                                            if ($Data['rota_tronco_inter_b2'] == $value['tronco_nome']):
                                                                echo ' selected = "selected" ';
                                                            endif;

                                                            echo ">{$value['tronco_nome']}</option>";
                                                        endforeach;
                                                    endif;
                                                    ?>               
                                                </select> 
                                            </div>
                                        </div>                                        
                                    </div>

                                </div> 
                            </div><!--fim row-->                            

                        </div>         

                        <!--BOTÕES-->
                        <div class="well txtCenter">                            
                            <input type="submit" class="btn btn-success" name="rotaCreate" value="Salvar Rotas">                        
                            <a class="btn btn-default" href="painel.php" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                        </div>
                    </form>


                <?php endif; ?>

                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                             