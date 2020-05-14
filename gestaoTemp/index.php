<?php
session_start();
require('../_app/Config.inc.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Kleber de Souza">
        <link rel="icon" href="../gestao/icons/favicon.ico">

        <title>Login Administrador de conteudo</title>

        <!-- Bootstrap core CSS -->
        <link href="../libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- CSS GERAL -->
        <link href="../gestao/css/signin.css" rel="stylesheet">
        <!--<link href="../gestao/css/geral.css" rel="stylesheet">-->

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]
        <script src="../../assets/js/ie-emulation-modes-warning.js"></script>-->

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
		
		<script>
			jQuery(function($){
               $("#cpf").mask("999.999.999-99");
			});
		</script>
		
    </head>

    <body>

        <div class="container">

            <!-- FORM LOGIN -->
            <form class="form-signin" method="post" action="">
                <h2 class="form-signin-heading logo">
                    <img src="images/logok.png" width="230" alt=""> 
                </h2> 

                <!-- ALERTAS -->
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
                    endif;
                endif;

                $get = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
                if (!empty($get)):
                    if ($get == 'restrito'):
                        KLErro('<b>Oppsss:</b> Acesso negado. Favor efetue login para acessar o painel!', KL_INFOR);
                    elseif ($get == 'logoff'):
                        KLErro('<b>Sucesso ao deslogar:</b> Sua sessão foi finalizada. Volte Sempre!', KL_ACCEPT);
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

            </form>

            <div class="copy">	
                <small> © 2013 / <?php echo @date("Y"); ?> - CMSK v.1.2 - By KLSDESIGNER</small>
            </div> 

        </div> <!-- /container -->

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
        <script src="../_cdn/corRadio.js"></script>
    </body>
</html>
