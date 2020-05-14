<?php
session_start();
include "phpagi-asmanager.php";
$asmanager = new AGI_AsteriskManager;
$conectaServidor = $conectaServidor = $asmanager->connect('localhost', 'proBilling', 'proBilling');

require('../_app/Config.inc.php');
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!--<meta name="viewport" content="width=device-width, initial-scale=1">-->
        <meta name="viewport" content="width=device-width, minimum-scale=0.5, maximum-scale=4">
        <meta name="description" content="">
        <meta name="author" content="Kleber de Souza">

        <!--css-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <!--<link href="../_app/Library/bootstrap/css/bootstrap.min.css" rel="stylesheet">-->
        <link href="css/jquery.datetimepicker.min.css" rel="stylesheet">        
        <link href="css/geralTorpedo.css" rel="stylesheet">

        <!--TIPOGRAFIA-->
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

        <!--JS-->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>        
                <!--<script src="../_cdn/jquery.js"></script>-->
        <script src="../_cdn/MaskedInput.js"></script>
        <script src="js/jquery.datetimepicker.full.min.js"></script>
        <script src="js/geralDataTimePicker.js"></script>

        <title>ProBilling - Agentes</title>
    </head>
    <body>
        <!--CONTAINER-->
        <div class="topo">
            <div class="container">
                <!--LOGO-->
                <div class="row">
                    <div class="col-md-2">
                        <img class="img-responsive" src="../images/logo.png" title="proBilling" alt="Logo Brazistelecom" width="100%">
                    </div>                
                </div>
            </div>
        </div>

        <section class="Login">
            <div class="container">
                <div class="row my-3 justify-content-md-center ">
                    <div class="col-md-4"
                         <!-- FORM LOGIN -->
                         <form class="form-signin mt-5" method="post" action="">
                            <h2 class="form-signin-heading txtblue mb-3">Agent Login</h2> 

                            <!-- ALERTAS -->
                            <?php
                            $login = new LoginAgent;

                            $acao = filter_input(INPUT_GET, 'acao', FILTER_DEFAULT);
                            if (!empty($acao) && $acao == "close"):
                                $agent = $_SESSION['agentlogin'];
                                $close = $asmanager->Command("agent logoff {$agent['agent_user']} soft");                                
                                unset($_SESSION['agentlogin']);
                                session_destroy();
                                header("location: index.php");

                            else:

                                if ($login->CheckLogin()):
                                    header('Location: agents.php');

                                endif;

                                $Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                                if (!empty($Data['btnLogin'])):
                                    unset($Data['btnLogin']);

                                    $login = new LoginAgent;
                                    $login->ExeLogin($Data);

                                    if ($login->getResult()):
                                       $sessionAgent = $_SESSION['agentlogin']; 
					$liga = $asmanager->Command("channel originate SIP/{$sessionAgent['agent_ramal']} extension {$sessionAgent['agent_user']}@fila");
                                        header("location: agents.php");

                                    else:
                                        $err = $login->getError();
                                        KLErro($err[0], $err[1]);
                                        header("refresh: 3; index.php");
                                    endif;

                                endif;
                            endif;
//
//                            $get = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);
//                            if (!empty($get)):
//                                if ($get == 'restrito'):
//                                    KLErro('<b>Oppsss:</b> Acesso negado. Favor efetue login para acessar o painel!', KL_INFOR);
//                                    header("refresh: 3; index.php");
//                                elseif ($get == 'logoff'):
//                                    KLErro('<b>Sucesso ao deslogar:</b> Sua sessão foi finalizada. Volte Sempre!', KL_ACCEPT);
//                                    header("refresh: 3; index.php");
//                                endif;
//                            endif;
                            ?>

                            <!-- IMPUTS -->
                            <label for="agent_user" class="sr-only">Agente</label>
                            <input type="text" id="agent_user"  name="agent_user" class="form-control mb-3" placeholder="Usuário Agente" required autofocus>
                            <label for="agent_pass" class="sr-only">Senha Agente</label>
                            <input type="password" id="agent_pass" name="agent_pass" class="form-control mb-3" placeholder="Senha Agente" required>
                            <label for="ramal" class="sr-only">Ramal Agente</label>
                            <input type="text" id="ramal" name="ramal" class="form-control mb-3" placeholder="Ramal Agente" required>
                            <input class="btn btn-md btn-primary btn-block" name="btnLogin" type="submit" value="Logar">

                            <!--<div class="redefine"><a href="recover.php"> <label>Redefinir Senha?</label></a></div>-->

                        </form>

                        <div class="copy text-center">	
                            <small> © 2017 / <?php echo date("Y"); ?> - CMSK v.1.2 - By BRAZISTELECOM</small>
                        </div> 


                    </div>
                </div>
        </section>

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>       



    </body>
</html>
