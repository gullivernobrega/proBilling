<?php
//Inicio2
session_start();
date_default_timezone_set('America/Sao_Paulo');
require('./_app/Config.inc.php');
?>

<!DOCTYPE html>
<html lang="pt-br">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="Operadora Voip">
        <meta name="author" content="Gulliver Nóbrega">
        <link rel="icon" href="icones/favicon.ico">

        <title>Login Administrador de conteudo</title>

        <!-- Bootstrap core CSS -->
        <link href="_app/Library/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- CSS GERAL -->
        <link href="css/signin.css" rel="stylesheet">
        <!--<link href="../gestao/css/geral.css" rel="stylesheet">-->

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]
        <script src="../../assets/js/ie-emulation-modes-warning.js"></script>-->

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>    
        <script type="text/javascript" src="./_cdn/jquery.maskedinput-1.3.1.min.js"></script>

        <script>
            jQuery(function ($) {
                $.mask.definitions['~'] = '[+-]';
                //Inicio Mascara Telefone
                $('input[type=tel]').mask("(99) 99999-9999").ready(function (event) {
                    var target, phone, element;
                    target = (event.currentTarget) ? event.currentTarget : event.srcElement;
                    phone = target.value.replace(/\D/g, '');
                    element = $(target);
                    element.unmask();
                    if (phone.length > 10) {
                        element.mask("(99) 99999-999?9");
                    }
                });
                //Fim Mascara Telefone
                $("#cpf").mask("999.999.999-99");
            });
        </script>



    </head>

    <body>

        <div class="container">

            <!-- FORM LOGIN -->
            <form class="form-signin" method="post" action="">
                <h2 class="form-signin-heading logo">
                    <img src="images/logo1.png" width="230" alt="">

                </h2> 

                <!-- ALERTAS -->
                <?php
                $activation = new Activation();
                $activation->CheckActivation();
                $checkActivation = $activation->getResult();
                                
                if ($checkActivation['status'] == 1) {
                    ?>
                    <?php
                    $login = new Login(1);
                    if ($login->CheckLogin()):
                        header('Location: painel.php');
                        exit();
                    endif;

                    $dataLogin = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($dataLogin['btnLogin'])):
                        unset($dataLogin['btnLogin']);

                        $login->ExeLogin($dataLogin);
                        if ($login->getResult()):
                            header('Location: painel.php');
                        else:
                            $err = $login->getError();
                            KLErro($err[0], $err[1]);
                            header("refresh: 3; index.php");
                        endif;
                    endif;

                    $get = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
                    if (!empty($get)):
                        if ($get == 'restrito'):
                            KLErro('<b>Oppsss:</b> Acesso negado. Favor efetue login para acessar o painel!', KL_INFOR);
                            header("refresh: 3; index.php");
                        elseif ($get == 'logoff'):
                            KLErro('<b>Sucesso ao deslogar:</b> Sua sessão foi finalizada. Volte Sempre!', KL_ACCEPT);
                            header("refresh: 3; index.php");
                        endif;
                    endif;
                    ?>

                    <!-- IMPUTS -->


                    <label for="login" class="sr-only">Seu Login</label>
                    <input type="text" id="login"  name="login" class="form-control" placeholder="Seu Login" required autofocus>
                    <label for="senha" class="sr-only">Sua Senha</label>
                    <input type="password" id="senha" name="senha" class="form-control" placeholder="Sua Senha" required>
                    <input class="btn btn-lg btn-primary btn-block" name="btnLogin" type="submit" value="Logar">

                    <div class="redefine"><a href="recover.php"> <label>Redefinir Senha?</label></a></div>


                    <?php
                }elseif ($checkActivation && !$checkActivation['status'] = 0) {
                    header("Location: activation.php");
                    
                }else {
                    $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if ($data['btnLogin']) {
                        unset($data['btnLogin']);
                        $mac = new mac();
                        $mac->mac();
                        $macResult = $mac->resultMac();
                        $data['mac'] = $macResult[0];
                        $key = $activation->keyGen();
                        $data['licensa'] = $key;
                        $activation->ExeCreate($data);
                        header("Location: activation.php");
                    }

                    /*
                     * Codigo para fazer ativação do sistema.
                     */
                    ?>
                    <!-- IMPUTS -->
                    <h4> <p class="text-center text-warning" style="margin: 30px 0px">Usuario novo, faça seu cadastro para ter acesso total ao sistema!</p></h4>

                    <label for="login" class="sr-only">Nome Completo</label>
                    <input type="text" id="login"  name="name" class="form-control" placeholder="Nome Completo" required autofocus>

                    <label for="login" class="sr-only">Telefone</label>
        <!--                <input type="text" id="login"  name="telefone" class="form-control" placeholder="telefone" required autofocus>-->
                    <input type="tel" id="celular" name="phone" class="form-control" placeholder="Número Celular" required autofocus >
        <!--                <input type="text" id="telefone" name="telefone" class="form-control" placeholder="telefone" required autofocus>-->

                    <label for="login" class="sr-only">E-mail</label>
                    <input type="email" id="login"  name="email" class="form-control" placeholder="E-mail" required autofocus>

                    <!--                <label for="senha" class="sr-only">Sua Senha</label>
                                    <input type="password" id="senha" name="senha" class="form-control" placeholder="Sua Senha" required>-->


                    <input class="btn btn-lg btn-primary btn-block" name="btnLogin" type="submit" value="Cadastrar">







                    <?php
                }
                ?>
            </form>

            <div class="copy">	
                <small> © 2017 / <?php echo date("Y"); ?> - CMSK v.1.2 - By BRAZISTELECOM</small>
            </div> 

        </div> <!-- /container -->

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="./assets/js/ie10-viewport-bug-workaround.js"></script>
        <script src="_cdn/corRadio.js"></script>
        
    </body>
</html>
