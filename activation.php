<?php
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
        <?php
//        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
//        if (!empty($data)) {
//            unset($data['btnLogin']);
        $activation = new Activation();
        $activation->CheckActivation();
        $checkActivation = $activation->getResult();

        $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
        if (!empty($data)) {
            unset($data['btnLogin']);
            $licensa = base64_encode($data['key']);
            if ($licensa == $checkActivation['licensa']) {
                $up = array('status' => 1);
                $activation->ExeUpdate($checkActivation['id'], $up);
                ?>        

                <div class="container">

                    <!-- FORM LOGIN -->
                    <form class="form-signin" method="post" action="">
                        <h2 class="form-signin-heading logo">
                            <img src="images/logo1.png" width="230" alt="">
                        </h2> 

                        <p class="trigger alert alert-info">Ativação Realizada com Sucesso, estamos redirecionando você para pagina de login, AGUARDE.!<span class="ajax_close"> </span></p>

                    </form>
                </div> <!-- /container -->

                <?php
                unset($data);
                header("refresh: 2; index.php");
                exit();
            } else {
                KLErro('<b>Oppsss:</b> Esta Key não esta correta: ' . $data['key'], KL_INFOR);
//                header("refresh: 2; activation.php");
            }
        }
        ?>
        <div class="container">

            <!-- FORM LOGIN -->
            <form class="form-signin" method="post" action="">
                <h2 class="form-signin-heading logo">
                    <img src="images/logo1.png" width="230" alt="">
                </h2> 

                <p class="trigger alert alert-info">Estamos quase lá! Digite no campo abaixo a KEY que recebeu em seu e-mail.<span class="ajax_close"> </span></p>
                <label for="login" class="sr-only">Key para Ativação</label>
                <input type="text" id="login"  name="key" class="form-control" placeholder="Key para Ativação" required autofocus>
                <input class="btn btn-lg btn-primary btn-block" name="btnLogin" type="submit" value="Cadastrar">

                <!-- Redirecionamento -->



                <?php
//                header("refresh: 4; index.php");
                ?>

                <!-- IMPUTS -->

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
