<?php
extract($_SESSION['userlogin']);
$nivel = $user_nivel;
//echo "<p>";
//KLErro("Algum erro", KL_INFOR);
//echo "</p>";
?>
<div class="page-header">            
    <h1>Dados Site, <small>configuração do site!</small></h1>
</div>       

<!--<div class="row">-->
<div class="container-fluid">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-globe"></i> Painel de Atulização</h3>
        </div>
        <div class="panel-body">
            <div class="well">
                Configure os dados do Site, endereço, telefone e Seo
            </div>
            <div id="shieldui-grid1">
                <!--FORMULARIO-->
                <!--<div class="col-lg-10">-->
                <?php
//                $id = filter_input(INPUT_GET, "user_id", FILTER_VALIDATE_INT);
//                $dados = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                //BUSCA OS DADOS DO SITE
                $read = new Read;
                $read->ExeRead("kl_configuracao");
                $objSite = $read->getResult();

                foreach ($objSite as $site):
                    extract($site);
                endforeach;

//                //DADOS DO FORMULÁRIO
//                if (isset($dados['userUpdate'])):
//                    unset($dados['userUpdate']);
//                    //Verefica se existe senha e replica da senha
//                    if (empty($dados['user_senha']) && empty($dados['user_senha'])):
//                        //se não tiver a senha e a replica elimina os campos
//                        unset($dados['user_senha']);
//                        unset($dados['replica']);
//
//                        $user = new Usuario;
//                        $user->ExeUpdate($id, $dados);
//                        if ($user->getResultado()):
//                            header("Location: painel.php?exe=users/users&result={$user->getResultado()}");
//                        else:
//                            $erro = array($user->getError());
//                            KLErro($erro[0], $erro[1]);
//                        endif;
//
//                    else:
//
//                        //Se tiver a senha verico se confere com a replica
//                        if ($dados['user_senha'] == $dados['replica']):
//                            unset($dados['replica']);
//                            //processamento
//                            $user = new Usuario;
//                            $user->ExeUpdate($id, $dados);
//                            if ($user->getResultado()):
//                                header("Location: painel.php?exe=users/users&result={$user->getResultado()}");
//                            else:
//                                $erro = array($user->getError());
//                                KLErro($erro[0], $erro[1]);
//                            endif;
//                        else:
//                            KLErro("<strong>Ops,<strong> Senhas não confere, Informe novamente!", KL_ALERT);
//                        endif;
//
//                    endif;
//                endif;
                ?>
                <form role="form" class="form-horizontal" name="formSite" action="" method="post" id="frm">                          

                    <div class="form-group">    
                        <label for="conf_nome" class="col-sm-2 control-label">Nome do Site</label>
                        <div class="col-xs-8">                                    
                            <input type="text" class="form-control" name="conf_nome" id="conf_nome" placeholder="Nome do Site" value="<?php echo $conf_nome; ?>" required autofocus>
                            <p class="help-block"><small>Informe o Nome do Site.</small></p>
                        </div>
                    </div>
                    <div class="form-group">  
                        <label for="conf_email" class="col-sm-2 control-label">E-mail do Site</label>
                        <div class="col-xs-8">
                            <input type="email" class="form-control" name="conf_email" id="conf_email" placeholder="E-mail dos Site"  value="<?php echo $conf_email; ?>" required >
                            <p class="help-block"><small>Informe um e-mail válido.</small></p>
                        </div>
                    </div>
                    <div class="form-group"> 
                        <label for="conf_url" class="col-sm-2 control-label">Url do Site</label>
                        <div class="col-lg-8">
                            <input type="text" class="form-control" name="conf_url" id="conf_url" placeholder="Url do site" value="<?php echo $conf_url; ?>">
                            <p class="help-block"><small>Informe o URL do site exe:. www.site.com.br</small></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="conf_descricao" class="col-sm-2 control-label">Descrição do Site</label>
                        <div class="col-lg-8">
                            <textarea class="form-control" rows="3" name="conf_descricao" id="conf_descricao"><?php echo $conf_descricao; ?></textarea>                                                        
                            <p class="help-block"><small>Informe uma breve descrição do site para os mecanismos de busca.</small></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="conf_chave" class="col-sm-2 control-label">Palavra Chave</label>
                        <div class="col-lg-8">
                            <textarea class="form-control" rows="3" name="conf_chave" id="conf_chave"><?php echo $conf_chave; ?></textarea>                                                        
                            <p class="help-block"><small>Informe as palavras chave do site para os mecanismos de busca. Obs.: separe as palavras por virgula</small></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="conf_endereco" class="col-sm-2 control-label">Endereço</label>
                        <div class="col-lg-8">
                            <textarea class="form-control" rows="3" name="conf_endereco" id="conf_endereco"><?php echo $conf_endereco; ?></textarea>                                                        
                            <p class="help-block"><small>Informe o endereço Local válido</small></p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="conf_mapa" class="col-sm-2 control-label">Google Maps</label>
                        <div class="col-lg-8">
                            <textarea class="form-control" rows="3" name="conf_mapa" id="conf_mapa"><?php if (isset($conf_mapa)): echo $conf_mapa; else: echo ""; endif;?></textarea>                                                        
                            <p class="help-block"><small>Incorpore a localização no Google Maps</small></p>
                        </div>
                    </div>
                    <div class="form-group"> 
                        <label for="conf_fone" class="col-sm-2 control-label">Telefone</label>
                        <div class="col-lg-2">
                            <input type="text" class="form-control" name="conf_fone" id="conf_fone" placeholder="Telefone" value="<?php echo $conf_fone; ?>">
                            <p class="help-block"><small>Informe DDD + numero</small></p>
                        </div>
                    </div>
                    <div class="form-group"> 
                        <label for="conf_celular" class="col-sm-2 control-label">Celular</label>
                        <div class="col-lg-2">
                            <input type="text" class="form-control" name="conf_celular" id="conf_celular" placeholder="Celular" value="<?php echo $conf_celular; ?>">
                            <p class="help-block"><small>Informe DDD + numero</small></p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="conf_logotipo" class="col-sm-2 control-label">Logomarca</label>
                        <div class="col-lg-4">
                            <input type="file" id="conf_logotipo" name="conf_logotipo">
                            <p class="help-block">Informe o Logotipo do site em .png ou .jpg</p>
                        </div>
                        <div class="col-lg-4">
                            <img src="../../../libs/images/semLogo.jpg" width="100%">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="conf_icone" class="col-sm-2 control-label">Icon Favicon</label>
                        <div class="col-lg-4">
                            <input type="file" id="conf_icone" name="conf_icone">
                            <p class="help-block">Informe o icone do site em .ico</p>
                        </div>
                        <div class="col-lg-4">
                            <img src="../../../libs/images/semLogo.jpg" >
                        </div>
                    </div>
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