<?php
extract($_SESSION['userlogin']);
?>
<!--<div class="row">-->
<!--<div class="col-lg-12">-->
<div class="page-header">            
    <h1>Usuario, <small>Cadastro!</small></h1>
</div>       
<!--</div>-->

<!--<div class="row">-->
<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-users"></i> Painel de Cadastro</h3>
        </div>
        <div class="panel-body">
            <div id="shieldui-grid1">
                <!--FORMULARIO-->
                <!--<div class="col-lg-10">-->
                <?php
                $ClienteData = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                if (isset($ClienteData["userCreate"])):
                    unset($ClienteData["userCreate"]);
                    if ($ClienteData['user_senha'] == $ClienteData['replica']):
                        unset($ClienteData['replica']);
                        $read = new Read;
                        $read->ExeRead("kl_users", "WHERE user_email = :e", "e={$ClienteData['user_email']}");
                        $res = $read->getRowCount();
                        if ($res == 0):
                            $user = new Usuario();
                            $user->ExeCreate($ClienteData);
                            if (!$user->getResultado()):
                                $erro = $user->getError();
                                KLErro($erro[0], $erro[1]);
                            else:
                                header("Location: painel.php?exe=users/users");
                            endif;
                        else:
                            KLErro("<b>Erro</b>, O e-mail: {$ClienteData['user_email']}, já esta cadastrado. Verifique!", KL_ACCEPT);
                        endif;
                    else:
                        KLErro("Senhas não confere, Informe novamente!", KL_ALERT);
                    endif;
                endif;
                ?>
                <form role="form" class="form-horizontal" name="formUser" action="" method="post" id="frm">                          

                    <div class="form-group">    
                        <label for="user_name" class="col-sm-2 control-label">Nome</label>
                        <div class="col-xs-8">                                    
                            <input 
                                type="text" 
                                class="form-control" 
                                name="user_nome" id="user_nome" 
                                placeholder="Nome Usuário" 
                                value="<?php if (!empty($ClienteData['user_nome'])) echo $ClienteData['user_nome']; ?>" 
                                required 
                                autofocus
                                >
                            <p class="help-block"><small>Informe o Nome completo do Usuário.</small></p>
                        </div>
                    </div>
                    <div class="form-group">  
                        <label for="user_email" class="col-sm-2 control-label">E-mail</label>
                        <div class="col-xs-8">
                            <input 
                                type="email" 
                                class="form-control" 
                                name="user_email" 
                                id="user_email" 
                                placeholder="E-mail Usuário"  
                                value="<?php if (!empty($ClienteData['user_email'])) echo $ClienteData['user_email']; ?>" 
                                required 
                                >
                            <p class="help-block"><small>Informe um e-mail válido.</small></p>
                        </div>
                    </div>

                    <div class="form-group">     
                        <label for="user_login" class="col-sm-2 control-label">Login</label>
                        <div class="col-lg-3">
                            <input 
                                type="text" 
                                class="form-control" 
                                name="user_login" 
                                id="user_login" 
                                placeholder="Login Usuário" 
                                value="<?php if (!empty($ClienteData['user_login'])) echo $ClienteData['user_login']; ?>" 
                                required
                                >
                            <p class="help-block"><small>Informe um Login de Usuário.</small></p>
                        </div>

                    </div>
                    <div class="form-group"> 
                        <label for="user_senha" class="col-sm-2 control-label">Senha</label>
                        <div class="col-lg-3">
                            <input type="password" class="form-control" name="user_senha" id="user_senha" placeholder="Senha Usuário" pattern = ".{6,12}" value="">
                            <p class="help-block"><small>Informe a senha com minimo 6 caracters.</small></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="replica" class="col-sm-2 control-label">Confirma Senha</label>
                        <div class="col-lg-3">
                            <input type="password" class="form-control" name="replica" id="replica" placeholder="Repita aSenha" value="">
                            <p class="help-block"><small>Repita a senha.</small></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="user_nivel" class="col-sm-2 control-label">Nivel de acesso</label>
                        <div class="col-lg-4">
                            <?php
                            if (!empty($ClienteData['user_nivel']) && $ClienteData['user_nivel'] == "3"):
                                ?>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('1');" type="radio" name="user_nivel" id="nivel1" value="3" checked="checked"> Suporte
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('2');" type="radio" name="user_nivel" id="nivel2" value="2"> Administrador
                                </label>
                                <label class="radio-inline">
                                    <input onClick="return mudacor('3');" type="radio" name="user_nivel" id="nivel3" value="1"> Usuário
                                </label>
                                <?php
                            elseif (!empty($ClienteData['user_nivel']) && $ClienteData['user_nivel'] == "2"):
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="user_nivel" id="nivel1" value="3" > Suporte
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="user_nivel" id="nivel2" value="2" checked="checked"> Administrador
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="user_nivel" id="nivel3" value="1"> Usuário
                                </label>
                                <?php
                            else:
                                ?>
                                <label class="radio-inline">
                                    <input type="radio" name="user_nivel" id="nivel1" value="3" > Suporte
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="user_nivel" id="nivel2" value="2"> Administrador
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" name="user_nivel" id="nivel3" value="1" checked="checked"> Usuário
                                </label>
                            <?php
                            endif;
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <input type="hidden" name="user_status" value="S">
                        <input type="hidden" name="user_registrado" value="<?php echo date("Y-m-d H:i:s"); ?>">
                    </div>
                    <div class="well txtCenter">
                        <input type="submit" class="btn btn-success" name="userCreate" value="Salvar Cadastro">                        
                        <a class="btn btn-default" href="painel.php?exe=users/users" role="button"><i class="fa fa-arrow-left"></i> Voltar</a>
                    </div>
                </form>
                <!--</div>-->
                <!--fim formulario-->
            </div>
        </div>
    </div>
</div>
<!--</div>-->

