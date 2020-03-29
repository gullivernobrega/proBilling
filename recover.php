<?php
session_start();
require('./_app/Config.inc.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Kleber de Souza">
        <link rel="icon" href="icones/favicon.ico">

        <title>Login Recover</title>

        <!-- Bootstrap core CSS -->
        <link href="_app/Library/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- CSS GERAL -->
        <link href="./css/signin.css" rel="stylesheet">
        <!--<link href="./css/geral.css" rel="stylesheet">-->

        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]
        <script src="../../assets/js/ie-emulation-modes-warning.js"></script>-->

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <div class="container">

            <!-- FORM LOGIN -->
            <form class="form-signin" name="frmSignin" action="" method="post">
                <h2 class="form-signin-heading logo">
                    <img src="images/logo1.png" width="230" alt=""> 
                </h2> 

                <!-- ALERTAS -->
                <?php
                $Email = filter_input(INPUT_POST, "user_email", FILTER_VALIDATE_EMAIL);
                if (!empty($Email)):

                    $check = new Check();
                    $val = $check->Email($Email);
                    if (!$val):
                        KLErro("Este e-mail é invalido, informe um email válido!", KL_ALERT);
                    else:
                        // chamada da model Usuario
                        $us = new Usuario();
                        $us->ExeRecover($Email);

                        if ($us->getResultado()):
                            $error = $us->getError();
                            KLErro($error[0], $error[1]);
                            header("refresh: 3; index.php");
                        else:
                            $error = $us->getError();
                            KLErro($error[0], $error[1]);
                            header("refresh: 3; index.php");
                        endif;

                    endif;                   

                endif;
                ?>

                <!-- IMPUTS -->
                <label for="user_email" class="sr-only">Seu Email</label>
                <input type="email" id="user_email"  name="user_email" class="form-control" placeholder="Informe Seu E-mail" required autofocus>        
                <button class="btn btn-lg btn-success btn-block" name="btnRecover" type="submit">Recover</button>

                <div class="redefine"><a href="index.php"> <label>Retornar ao Login?</label></a></div>

            </form>

            <div class="copy">	
                <small> © 2018 / <?php echo @date("Y"); ?> - CMSK v.1.2 - By BRAZISTELECOM</small>
            </div> 

        </div> <!-- /container -->

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
    </body>
</html>
