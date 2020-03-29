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

        <!--css-->
        <link href="../_app/Library/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="css/jquery.datetimepicker.min.css" rel="stylesheet">        
        <link href="css/geralTorpedo.css" rel="stylesheet">

        <!--JS-->
        <script src="../_cdn/jquery.js"></script>
        <script src="../_cdn/MaskedInput.js"></script>
        <script src="js/jquery.datetimepicker.full.min.js"></script>
        <script src="js/geralDataTimePicker.js"></script>

        <title>Torpedos</title>

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
                </div>
            </div>
        </div>

        <section class="mgTop100">
            <div class="container">
                <div class="row">
                    <div class="col-md-6 col-md-offset-3">
                        <hgroup>                            
                            <h1 class="free">Active Calls Torpedo</h1>
                        </hgroup>

                        <?php
                        //RESULTADO DA PESQUISA
                        $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                        if (!empty($busca['btnConfirma'])):
                            unset($busca['btnConfirma']);

                            if (!empty($busca['ramal'])):
                                $_SESSION['RAMAL'] = $busca['ramal'];
                                header('Location: busca.php');
                                exit();
                            else:
                                unset($_SESSION['RAMAL']);
                            endif;

//                            if (!empty($busca['ramal'])):
//
//                                header("Location: busca.php?ramal={$busca['ramal']}");
//                                exit();
//                            else:                                
//                                echo '<div class = "Ops, Falta ramal para a pesquisa" role = "alert">...</div>';
//                            endif;

                        endif;
                        ?>
                        <div class="well corWell">
                            <form action="" name="formTorpedo" method="post" id="frm">
                                <div class="input-group">
                                    <input class="btn btn-lg" name="ramal" id="ramal" type="text" placeholder="Ramal" value="<?php if (isset($busca['ramal'])): echo $busca['ramal'];
                        endif;
                        ?>"  required autofocus>
                                    <button name="btnConfirma" class="btn btn-info btn-lg" value="confirma" type="submit">Confirmar</button>
                                </div>
                            </form>
                        </div>
                        <div class="centraliza">
                            <small class="promise"><em>Informe um ramal de ligação.</em></small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>       


    </body>
</html>
