<?php
extract($_SESSION['userlogin']);
$nivel = $user_nivel;
//echo "<p>";
//KLErro("Algum erro", KL_INFOR);
//echo "</p>";
?>
<!--<div class="row">-->
<!--<div class="col-lg-12">-->
<div class="page-header">            
    <h1>Usuário, <small>atualização do perfíl!</small></h1>
</div>       
<!--</div>-->

<!--<div class="row">-->
<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-user-md"></i> Painel de Atulização</h3>
        </div>
        <div class="panel-body">
            <div id="shieldui-grid1">
                <!--FORMULARIO-->
                <!--<div class="col-lg-10">-->
                <?php
                $id = filter_input(INPUT_GET, "user_id", FILTER_VALIDATE_INT);
                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                //BUSCA OS DADOS O ID (metodo Get)
                $read = new Read;
                $read->ExeRead("kl_users", "WHERE user_id = :id", "id={$id}");
                $usuario = $read->getResult();
                //extract($usuario);         
                foreach ($usuario as $user):
                    extract($user);
                endforeach;

                //DADOS DO FORMULÁRIO
                if (isset($dados['userUpdate'])):
                    unset($dados['userUpdate']);
                    //Verefica se existe senha e replica da senha
                    if (empty($dados['user_senha']) && empty($dados['user_senha'])):
                        //se não tiver a senha e a replica elimina os campos
                        unset($dados['user_senha']);
                        unset($dados['replica']);

                        $user = new Usuario;
                        $user->ExeUpdate($id, $dados);
                        if ($user->getResultado()):
                            header("Location: painel.php?exe=users/users&result={$user->getResultado()}");
                        else:
                            $erro = array($user->getError());
                            KLErro($erro[0], $erro[1]);
                        endif;

                    else:

                        //Se tiver a senha verico se confere com a replica
                        if ($dados['user_senha'] == $dados['replica']):
                            unset($dados['replica']);
                            //processamento
                            $user = new Usuario;
                            $user->ExeUpdate($id, $dados);
                            if ($user->getResultado()):
                                header("Location: painel.php?exe=users/users&result={$user->getResultado()}");
                            else:
                                $erro = array($user->getError());
                                KLErro($erro[0], $erro[1]);
                            endif;
                        else:
                            KLErro("<strong>Ops,<strong> Senhas não confere, Informe novamente!", KL_ALERT);
                        endif;

                    endif;
                endif;
                ?>
                <form role="form" class="form-horizontal" name="formUser" action="" method="post" id="frm">                          

                    <div class="form-group">    
                        <label for="user_name" class="col-sm-2 control-label">Nome</label>
                        <div class="col-xs-8">                                    
                            <input type="text" class="form-control" name="user_nome" id="user_nome" placeholder="Nome Usuário" value="<?php echo $user_nome; ?>" required autofocus>
                            <p class="help-block"><small>Informe o Nome completo do Usuário.</small></p>
                        </div>
                    </div>
                    <div class="form-group">  
                        <label for="user_email" class="col-sm-2 control-label">E-mail</label>
                        <div class="col-xs-8">
                            <input type="email" class="form-control" name="user_email" id="user_email" placeholder="E-mail Usuário"  value="<?php echo $user_email; ?>" required >
                            <p class="help-block"><small>Informe um e-mail válido.</small></p>
                        </div>
                    </div>

                    <div class="form-group">     
                        <label for="user_login" class="col-sm-2 control-label">Login</label>
                        <div class="col-lg-3">
                            <input type="text" class="form-control" name="user_login" id="user_login" placeholder="Login Usuário" value="<?php echo $user_login; ?>" required>
                            <p class="help-block"><small>Informe um Login de Usuário.</small></p>
                        </div>

                    </div>
                    <div class="form-group"> 
                        <label for="user_senha" class="col-sm-2 control-label">Senha</label>
                        <div class="col-lg-3">
                            <input 
                                type="password" 
                                class="form-control" 
                                name="user_senha" 
                                id="user_senha" 
                                placeholder="Senha Usuário" 
                                pattern = ".{6,12}"                                
                                >
                            <p class="help-block"><small>Informe a senha com minimo 6 caracters.</small></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="replica" class="col-sm-2 control-label">Confirma Senha</label>
                        <div class="col-lg-3">
                            <input type="password" class="form-control" name="replica" id="replica" placeholder="Repita aSenha" >
                            <p class="help-block"><small>Repita a senha.</small></p>
                        </div>
                    </div>
                    <?php if ($nivel == 3): ?>
                        <div class="form-group">
                            <label for="user_nivel" class="col-sm-2 control-label">Nivel de acesso</label>
                            <div class="col-lg-4">
                                <?php
                                if ($user_nivel == "3"):
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
                                elseif ($user_nivel == "2"):
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
                        

                            <!--STATUS-->    
                            <div class="form-group">
                                <label for="user_status" class="col-sm-2 control-label">Status</label>
                                <div class="col-lg-4">
                                    <?php
                                    if ($user_status == "S"):
                                        ?>
                                        <label class="radio-inline">
                                            <input onClick="return mudacor('1');" type="radio" name="user_status" id="status1" value="S" checked="checked"> Ativo
                                        </label>
                                        <label class="radio-inline">
                                            <input onClick="return mudacor('2');" type="radio" name="user_status" id="status2" value="N"> Inativo
                                        </label>                                    
                                        <?php
                                    else:
                                        ?>
                                        <label class="radio-inline">
                                            <input type="radio" name="user_status" id="status1" value="S" > Ativo
                                        </label>
                                        <label class="radio-inline">
                                            <input type="radio" name="user_status" id="status2" value="N" checked="checked"> Inativo
                                        </label>                                        
                                    <?php
                                    endif;
                                    ?>
                                </div>
                            </div>                                        
                            <div class="form-group">    

                            <?php endif; ?>    
<!--<input type="hidden" name="user_status" value="S">-->
<!--<input type="hidden" name="user_registrado" value="<?php //echo date("Y-m-d H:i:s");      ?>">-->
                        </div>
                        <div class="well txtCenter">
                            <input type="submit" class="btn btn-success" name="userUpdate" value="Salvar Alteração">
                            <!--<button type="reset" class="btn btn-default" value="Voltar"><i class="fa fa-arrow-left"></i> Voltar</button>-->
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

<!--</div>-->



<!--<div class="content form_create">

    <article>        

<?php
//        EchoMsg("Erro ao atualizar", "E-mail Informado não tem um formáto válido", INFOR);
//        EchoMsg("Erro ao atualizar", "Senha deve ter entre 6 e 12 caracteres!", INFOR);
//        EchoMsg("Erro ao atualizar", "Existem campos em branco, todos são obrigatórios!", ALERT);
//        EchoMsg("Erro ao atualizar", "E-mail informado está em uso por outra conta!", ERROR);
//        EchoMsg("Ok", "Seus dados foram atualizados com sucess!", ACCEPT);
?>

        <form action = "" method = "post" name = "UserEditForm">

            <label class="label">
                <span class="field">Nome:</span>
                <input
                    type = "text"
                    name = "user_name"
                    value = "<? //$user_name; ?>"
                    title = "Informe seu primeiro nome"
                    required
                    />
            </label>

            <label class="label">
                <span class="field">Sobrenome:</span>
                <input
                    type = "text"
                    name = "user_lastname"
                    value = "<? //$user_lastname; ?>"
                    title = "Informe seu sobrenome"
                    required
                    />
            </label>

            <label class="label">
                <span class="field">E-mail:</span>
                <input
                    type = "email"
                    name = "user_email"
                    value = "<? //$user_email; ?>"
                    title = "Informe seu e-mail"
                    required
                    />
            </label>

            <div class="label_line">

                <label class="label_medium">
                    <span class="field">Senha:</span>
                    <input
                        type = "password"
                        name = "user_password"
                        value = "<? //$user_password; ?>"
                        title = "Informe sua senha [ de 6 a 12 caracteres! ]"
                        pattern = ".{6,12}"
                        required
                        />
                </label>


                <label class="label_medium">
                    <span class="field">Nível:</span>
                    <select name = "user_level" title = "Selecione o nível de usuário" required >
                        <option value = "">Selecione o Nível</option>
                        <option value = "1" <?php //if ($user_level == 1) echo 'selected="selected"';         ?>>User</option>
                        <option value="2" <?php //if ($user_level == 2) echo 'selected="selected"';         ?>>Editor</option>
                        <option value="3" <?php //if ($user_level == 3) echo 'selected="selected"';         ?>>Admin</option>
                    </select>
                </label>

            </div>

            <input type="submit" name="UserUpdate" value="Atualizar Usuário" class="btn green" />

        </form>


    </article>

    <div class="clear"></div>
</div>  content home -->
