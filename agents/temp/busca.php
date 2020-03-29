<?php
session_start();

ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);

include './phpagi.php';
include "./phpagi-asmanager.php";
include_once './funcoes/getcalls.php';
include_once '../torpedo/classes/Busca.class.php';

$timesetion = 6;
$timeout = isset($timesetion) ? $timesetion * 500 : 10000;

$userRamal = $_SESSION['RAMAL'];
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="Kleber de Souza">

        <!--css-->
        <link href="../_app/Library/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/jquery.datetimepicker.min.css" rel="stylesheet">
        <link href="css/jPages.css" rel="stylesheet">
        <link href="css/animate.css" rel="stylesheet">
        <link href="css/geralTorpedo.css" rel="stylesheet">

        <title>Active Calls Torpedo</title>

        <script type="text/javascript">
            setTimeout(function () {
                location.reload(1);
            }, <?php echo $timeout ?>);
        </script>

    </head>
    <body>

        <!--CONTAINER-->
        <div class="topo">
            <div class="container">
                <!--LOGO-->
                <div class="row">
                    <div class="col-md-2">
                        <img class="img-responsive" src="../images/logo.png" title="proBilling" alt="" >
                    </div>                
                    <div class="clear">
                        <div class="cClose"> <a class="right txtAzulC" href="close.php" title="Fechar Tela"><span class="glyphicon glyphicon-off" aria-hidden="true"></span></a> </div>
                    </div>              
                </div>
            </div>
        </div>

        <section>
            <div class="container mgTop100">
                <?php
                $variable = getCalls();

                //RESULTADO DA PESQUISA
                $termo = "WHERE ramal = '{$userRamal}'";

                $busca = new Busca();
                $busca->exeBusca("activecalls_torpedo", $termo);

                if (!$busca->getResult()):
                    echo '<div class="alert alert-info" role="alert">Ops! Não existe active calls torpedo cadastrado!</div>';
                else:
                    foreach ($busca->getResult() as $val):
                        extract($val);
                    endforeach;
                endif;
                ?>
                <div class="row">
                    <div class="col-md-8 col-md-offset-2"><h1>Atendimento Áudio Retorno</h1></div>
                    <div class="col-md-8 col-md-offset-2">

                        <div class="base">

                            <div class="at">
                                <p>Ramal: <span class="size30"><strong> <?php echo (!empty($ramal) ? $ramal : null); ?></strong></span><br>
                                    Tempo: <span class="size20"> <?php echo (!empty($duracao) ? $duracao : null); ?></span></p>
                            </div>     

                            <div class="cli">
                                <p><span class="size20"><?php echo (!empty($nomedocliente) ? utf8_decode($nomedocliente) : 'Cliente'); ?></span><br>
                                    Cpf/Cnpj: <span class="size18"><b> <?php echo (!empty($cpf_cnpj) ? $cpf_cnpj : null); ?></b></span><br>
                                    fone: <span class="size18"> <?php echo (!empty($numero) ? $numero : null); ?></span></p>
                            </div>                                    

                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!--<script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>--> 
        <!--JS-->
        <script src="../_cdn/jquery.js"></script>
        <script src="../_cdn/MaskedInput.js"></script>
        <script src="js/jquery.datetimepicker.full.min.js"></script>
        <script src="js/jPages.min.js"></script>
        <script src="js/paginacao.js"></script>        
        <script src="js/geralDataTimePicker.js"></script>
    </body>
</html>