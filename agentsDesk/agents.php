<?php
session_start();

ini_set('display_errors', 'On');
error_reporting(E_ALL);

include "phpagi-asmanager.php";
require('../_app/Config.inc.php');

$sessionAgent = $_SESSION['agentlogin'];
/* Se não existir uma sessão volta para o login */
if (empty($sessionAgent)):
    header("location: index.php");
endif;
extract($sessionAgent);

/* Instancia da class AGI */
$asmanager = new AGI_AsteriskManager;
$conectaServidor = $conectaServidor = $asmanager->connect('localhost', 'proBilling', 'proBilling');

/**
 * Instancia da classe Agentsstatus
 */
$agentSstatus = new Agentsstatus;
$agentSstatus->exeAgents($agent_id, $agent_user, $asmanager);
$result = $agentSstatus->getAgents();

// tipo da pause armazenado no agents
$tipo_pause = $result;

/* Recebe a ação do botão desligar */
$acao = filter_input(INPUT_GET, "acao", FILTER_DEFAULT);

if (!empty($acao) && $acao == "Desligar"):

    $acao = "";
    // Select para o channel do agents
    $selectCanal = new Select;
    $selectCanal->ExeSelect("agents_status", "channel", "WHERE agente = :ag", "ag={$agent_user}");
    $arrChannel = $selectCanal->getResult();
    $channel = $arrChannel[0];

    //Comando Desliga no Asterisk
    $server = $asmanager->Command("hangup request {$channel['channel']}");
    header("location: agents.php");

endif;

if (!empty($acao) && $acao == "Despausar"):
    # Zera a ação
    $acao = "";

    # Array Pause null
    $DataP['agent_pause'] = "";

    # Comando asterisk para despausar agent
    $result = $asmanager->Command("queue pause member Local/$agent_user@agents");

    # instancia do objeto agent
    $updateTipo = new Agent;
    $updateTipo->ExeUpdate($agent_id, $DataP);

    if ($updateTipo->getResult()):
        header("Location: agents.php");
    endif;

endif;
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

        <!--TIPOGRAFIA-->
        <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">

        <!--css-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">             
        <link href="css/geralTorpedo.css" rel="stylesheet">

        <!--JS-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 

        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>       


        <title>ProBilling - Agentes</title>

        <script type="text/javascript">
            setInterval("atualiza();", 1000);
            function atualiza() {
                $('#status').load(location.href + " #status");
            }
        </script> 

    </head>
    <body>

        <!--CONTAINER-->
        <div class="topo">
            <div class="container-fluid">
                <!--LOGO-->
                <div class="row">
                    <div class="col-md-2">
                        <img class="img-responsive" src="../images/logo.png" title="proBilling" alt="" width="100%">
                    </div>                
                </div>
            </div>
        </div>
        <div class="base grSeashore">
            <div class="container-fluid">
                <!--AGENTS USERS-->
                <div class="row pb-2">
                    <div class="col">
                        <div class="col my-2"><i class="fa fa-user"></i><strong> <?php echo $agent_user; ?> </strong></div>
                        <div class="col my-1"><i class="fa fa-users"></i> Atendimento</div>
                        <div class="col mt-2">
                            <div class="row">
                                <div class="col">
                                    <i class="fa fa-fax"></i><strong> <?php echo $agent_ramal; ?></strong></div>
                                <div class="col"><i class="fa fa-clock-o"></i> 00:00</div>
                            </div>
                        </div>
                    </div>    
                </div> 
            </div>
        </div>
              
        <div class="base grBase">
            <div class="container-fluid">        
                    <div class="row ">
                    <div class="col">
                        <!--GESTÃO DE PAUSE-->
                        <!--<div class="mt-2">-->
                        <h5 class="mt-3">Gestão de pause</h5>
                        <!--</div>-->
                        <?php
                        /* Debug do controlador de pause */
                        $Data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                        if (!empty($Data['btnPause'])):

                            $agent_pause = $Data['tipo'];
                            unset($Data['btnPause'], $Data['tipo']);

                            $Data['agent_pause'] = ($agent_pause == "Livre" || $agent_pause == "Escolher" ) ? "" : trim($agent_pause);

                            $updateTipo = new Agent;
                            $updateTipo->ExeUpdate($agent_id, $Data);

                            if ($updateTipo->getResult()):
                                header("Location: agents.php");
                            endif;

                        endif;
                        ?>
                        <form  name="frm" action="" method="post">
                            <!--gestão de pause-->
                            <div class="form-row">
                                <div class="col">                                    

                                    <select class="form-control form-control-sm my-2" name="tipo" id="tipo">
                                        <option value="">Escolher</option>
                                        <?php
                                        /* Populando a select com os tipos de pause */
                                        $pause = new Read;
                                        $pause->ExeRead("agents_pause");
                                        if (!$pause->getResult()):
                                            echo '<option disabled="disabled" value="NULL">Cadastre antes uma Pausa!</option>';
                                        else:
                                            foreach ($pause->getResult() as $tipoPause):

                                                echo "<option value=\"{$tipoPause['tipo']}\"";

                                                if ($tipo_pause == $tipoPause['tipo']):
                                                    echo ' selected = "selected" ';
                                                elseif (!empty($Data['tipo']) && $Data['tipo'] == $tipoPause['tipo']):
                                                    echo ' selected = "selected" ';
                                                endif;

                                                echo ">{$tipoPause['tipo']}</option>";

                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                </div>  

                                <div class="col-auto">
                                    <button type="submit" class="btn btn-primary btn-sm my-2" name="btnPause" value="pause"><i class="fa fa-pause"></i></button>                                   
                                </div>
                            </div>
                        </form>
                        
                        <div class="col">
                            <div class="row mb-2">
                                <div class="col pl-0"><i class="fa fa-history"></i> 00:00:00</div>
                                <div class="col pl-0"><i class="fa fa-hourglass-end"></i> 00:00:00</div>
                            </div>
                        </div>
                        
                    </div>                        
                </div>                
            </div>            
        </div>
        
        <!--BOTÃO DE SAIDA-->
        <a class="btn btn-light btn-sm btn-block" href="login.php?acao=close" title="Sair do Sistema"><i class="fa fa-sign-out"></i> Sair do Sistema</a>  
        
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-3 px-0">  

                    <div id="status">                        
                        <?php
                        $agentSstatus->exeAgentStatus();
                        $arrs[] = $agentSstatus->getResult();

                        foreach ($arrs as $arr):

                            if (is_null($arr)):
                                continue;
                            else:
                               extract($arr);
                            endif
                            
                            ?>

                            <!--Barra de status-->
                            <div  class="status text-center py-2"  style="<?php echo $fundo; ?>">                     
                                <h2> <?php echo $status; ?> </h2>                    
                            </div>

                            <!--Conteudo do status-->
                            <div class="card">                    
                                <div class="card-header ">    
                                    <!--CLIENTE-->
                                    <?php
                                    if (!empty($nome)):
                                        ?>
                                        <div class="col-12">Nome: <strong><?php echo $nome; ?></strong></div>                        
                                        <div class="col-12">Numero: <strong><?php echo $numero; ?></strong></div>                        
                                        <div class="col-12">
                                            <div> Codigo: <strong><?php echo $codigo; ?></strong></div>
                                        </div>   
                                        <?php
                                    endif;

                                    if (!empty($tipo_pause)):
                                        ?>
                                        <div class="text-center mt-5">

                                            <a href="?acao=Despausar"><img src="../icones/play.png" class="img-responsive" width="15%"></a>
                                        </div>
                                        <?php
                                    else:
                                        ?>
                                        <div class="text-center mt-5">
                                            <?php
                                            if (!empty($status) && $status === "OCUPADO"):
                                                ?>
                                                <a href="?acao=Desligar"><img src="../icones/btn-fone-off.png" class="img-responsive" width="15%"></a>
                                                <?php
                                            else:
                                                ?>
                                                <a href=""><img src="../icones/btn-fone-null.png" class="img-responsive" width="15%"></a>
                                            <?php
                                            endif;
                                            ?>
                                        </div>
                                    <?php
                                    endif;
                                    ?>
                                </div>
                            </div><!--fim card-->
                            <?php
                        endforeach;
                        ?>
                    </div>

                </div>                
            </div>
        </div>
 
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>       
        <!--LIGAÇOES ATIVAS-->  
    </body>
</html>
