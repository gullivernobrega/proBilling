<?php
# Inicializa o Buffer
ob_start();
# Inicializa a sessão
session_start();
require('./_app/Config.inc.php');

ini_set('memory_limit', '-1');

$usuario = new Login(1);

# Filtra a solicitação de saida do sitema
$logoff = filter_input(INPUT_GET, 'logoff', FILTER_DEFAULT);
$getexe = filter_input(INPUT_GET, 'exe', FILTER_DEFAULT);

$nv = $usuario->CheckLogin();
if (!$nv):
    unset($_SESSION['userlogin']);
    header('Location: index.php?exe=restrito');
else:
    $userlogin = $_SESSION['userlogin'];
endif;

if ($logoff):
    unset($_SESSION['userlogin']);
    header('Location: index.php?exe=logoff');
endif;
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="<?php echo SITEDESC; ?>">
        <meta name="author" content="Kleber de Souza">
        <title><?php echo SITENAME . " " . SITEVERSION . " - " . SITEDESC; ?></title>
        <link rel="icon" href="icones/favicon.ico">

        <!-- Bootstrap -->
        <link href="_app/Library/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="_app/Library/bootstrap/duallistbox/bootstrap-duallistbox.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">        
        <link href="css/jquery.datetimepicker.min.css" rel="stylesheet"> 

        <!--EZ CSS-->
        <!--<link rel="stylesheet" href="Monitor/web/css/utilities.css" type="text/css">-->
        <link rel="stylesheet" href="Monitor/web/css/frontend.css" type="text/css">
        <!---------->

        <link href="css/geral.css" rel="stylesheet" type="text/css">
    </head>
    <body>                
        <!--CONTAINER GERAL--> 
        <div class="container-fluid">
            <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="painel.php"><img src="images/logo.png" width="50%" class="img-responsive"> </a>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">

                    <ul class="nav navbar-nav navbar-right navbar-user">
                        <li class="dropdown messages-dropdown">
                            <a href="painel.php" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> Messagens <span class="badge">2</span> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-header">Novas Mensagens</li>
                                <li class="message-preview">
                                    <a href="#">
                                        <span class="avatar"><i class="fa fa-bell"></i></span>
                                        <span class="message">Em construção</span>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li class="message-preview">
                                    <a href="#">
                                        <span class="avatar"><i class="fa fa-bell"></i></span>
                                        <span class="message">Em construção</span>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="#">Caixa de Entrada <span class="badge">2</span></a></li>
                            </ul>
                        </li>

                        <!--USUARIOS-->
                        <li class="dropdown user-dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user"></i> <?php echo $userlogin['user_nome']; ?> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="painel.php?exe=users/profile"><i class="fa fa-user"></i> Perfil</a></li>
                                <?php
                                extract($userlogin);
                                if ($user_nivel != "1"):
                                    echo '<li><a href="painel.php?exe=users/users"><i class="fa fa-users"></i> Usuarios</a></li>';
                                    echo '<li><a href="painel.php?exe=config/config"><i class="fa fa-cogs"></i> Configurações</a></li>';
                                endif;
                                ?>
                                <li class="divider"></li>
                                <li><a href="painel.php?logoff=true"><i class="fa fa-power-off"></i> Sair</a></li>
                            </ul>
                        </li>
                        <!--fim usuario-->
                    </ul>
                </div>
            </nav>
            <!--fim nav-->

            <div id="base">
                <div class="row">
                    <div class="col-md-2">
                        <!--MENU VERTICAL-->
                        <div id="MainMenu">
                            <div class="list-group panel">
                                <a href="painel.php" class="list-group-item list-group-item-default active" data-parent="#MainMenu"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Inicio</a>

                                <!--FERRAMENTAS-->
<!--                                <a href="#config" class="list-group-item list-group-item-default" data-toggle="collapse" data-parent="#MainMenu"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Ferramentas <i class="fa fa-caret-down"></i></a>
                                <div class="collapse" id="config">
                                    <a href="painel.php?exe=ferramentas/lista" class="list-group-item-sub"><i class="fa fa-list-alt"></i> 1 Em construção</a>
                                    <a href="painel.php?exe=ferramentas/lista" class="list-group-item-sub"><i class="fa fa-list"></i> 2 Em construção</a>
                                    <a href="painel.php?exe=ferramentas/lista" class="list-group-item-sub"><i class="fa fa-puzzle-piece"></i> 3 Em construção</a>                                    
                                </div>   -->

                                <!--GERENCIAMENTO-->
                                <a href="#rotas" class="list-group-item list-group-item-default" data-toggle="collapse" data-parent="#MainMenu"><span class="glyphicon glyphicon-certificate" aria-hidden="true"></span> Gerenciamento <i class="fa fa-caret-down"></i></a>
                                <div class="collapse" id="rotas">
                                    <!--Audio-->
                                    <a href="painel.php?exe=gerenciamento/audios/lista" class="list-group-item-sub"><i class="fa fa-file-audio-o"></i> Audio</a>
                                    <!--Agents-->
                                    <a href="painel.php?exe=gerenciamento/agentes/lista" class="list-group-item-sub"><i class="fa fa-user-circle-o"></i> Agentes</a>
                                    <!--Ramais-->
                                    <a href="#ramal" class="list-group-item list-group-item-sub" data-toggle="collapse" data-parent="#rotas"><i class="fa fa-list-alt"></i> Ramais <i class="fa fa-caret-down"></i></a>
                                    <div class="collapse" id="ramal">
                                        <a href="painel.php?exe=gerenciamento/ramal/sip/lista" class="list-group-item-sub "><i class="fa fa-phone"></i> SIP</a>
                                        <a href="painel.php?exe=gerenciamento/ramal/iax/lista" class="list-group-item-sub "><i class="fa fa-phone-square"></i> IAX</a>
                                    </div>
                                    <!--Tronco-->
                                    <a href="painel.php?exe=gerenciamento/tronco/lista" class="list-group-item-sub"><i class="fa fa-list"></i> Troncos</a>
<!--                                    <a href="#tronco" class="list-group-item list-group-item-sub" data-toggle="collapse" data-parent="#rotas"><i class="fa fa-list"></i> Troncos <i class="fa fa-caret-down"></i></a>
                                    <div class="collapse" id="tronco">
                                        <a href="painel.php?exe=gerenciamento/tronco/sip/lista" class="list-group-item-sub "><i class="fa fa-phone"></i> SIP</a>
                                        <a href="painel.php?exe=gerenciamento/tronco/iax/lista" class="list-group-item-sub "><i class="fa fa-phone-square"></i> IAX</a>
                                    </div>-->
                                    <!--Rotas-->
                                    <a href="painel.php?exe=gerenciamento/rotas/create" class="list-group-item-sub"><i class="fa fa-random"></i> Rotas</a>
                                    <!--URA-->
                                    <a href="painel.php?exe=gerenciamento/ura/lista" class="list-group-item-sub"><i class="fa fa-cogs"></i> Ura</a>
                                    <!--Filas-->
                                    <a href="painel.php?exe=gerenciamento/filas/lista" class="list-group-item-sub"><i class="fa fa-th"></i> Filas</a>
                                    <!--Did-->
                                    <a href="painel.php?exe=gerenciamento/did/lista" class="list-group-item-sub"><i class="fa fa-users"></i> Did</a>
                                </div>

                                <!--CAMPANHAS-->
                                <a href="#campanhas" class="list-group-item list-group-item-default" data-toggle="collapse" data-parent="#MainMenu"><span class="glyphicon glyphicon-menu-hamburger" aria-hidden="true"></span> Campanhas <i class="fa fa-caret-down"></i></a>
                                <div class="collapse" id="campanhas">
                                    <a href="painel.php?exe=campanhas/cadcampanha/lista" class="list-group-item-sub"><i class="fa fa-stack-exchange"></i> Cadastrar Campanhas</a>
                                    <a href="painel.php?exe=campanhas/agenda/lista" class="list-group-item-sub"><i class="fa fa-newspaper-o"></i> Agenda</a>
                                    <a href="painel.php?exe=campanhas/numeros/lista" class="list-group-item-sub"><i class="fa fa-sort-numeric-asc"></i> Números</a>
                                </div>

                                <!--SMS CAMPANHAS-->
                                <a href="#smscampanhas" class="list-group-item list-group-item-default" data-toggle="collapse" data-parent="#MainMenu"><i class="fa fa-rocket" aria-hidden="true"></i> SMS Campanhas <i class="fa fa-caret-down"></i></a>
                                <div class="collapse" id="smscampanhas">
                                    <a href="painel.php?exe=campanhasms/cadcampanhasms/lista" class="list-group-item-sub"><i class="fa fa-commenting"></i> Cadastrar SMS Campanhas</a>
                                    <a href="painel.php?exe=campanhasms/agendasms/lista" class="list-group-item-sub"><i class="fa fa-list-alt"></i> SMS Agenda</a>
                                    <a href="painel.php?exe=campanhasms/numerossms/lista" class="list-group-item-sub"><i class="fa fa-sort-numeric-asc"></i> SMS Números</a>
                                </div>

                                <!--LINHAS IP-->
<!--                                <a href="#linhasip" class="list-group-item list-group-item-default" data-toggle="collapse" data-parent="#MainMenu"><span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Linha IP <i class="fa fa-caret-down"></i></a>
                                <div class="collapse" id="linhasip">
                                    <a href="painel.php?exe=linhasip/lista" class="list-group-item-sub"><i class="fa fa-list-alt"></i> 1 Em construção</a>
                                    <a href="painel.php?exe=linhasip/lista" class="list-group-item-sub"><i class="fa fa-list"></i> 2 Em construção</a>
                                    <a href="painel.php?exe=linhasip/lista" class="list-group-item-sub"><i class="fa fa-puzzle-piece"></i> 3 Em construção</a>
                                </div>-->

                                <!--RELATORIOS-->
                                <a href="#relatorio" class="list-group-item list-group-item-default" data-toggle="collapse" data-parent="#MainMenu"><span class="glyphicon glyphicon-list" aria-hidden="true"></span> Relatórios <i class="fa fa-caret-down"></i></a>
                                <div class="collapse" id="relatorio">
                                    <!--CALLCENTER-->
                                    <a href="#callcenter" class="list-group-item list-group-item-sub" data-toggle="collapse" data-parent="#relatorio"><i class="fa fa-th" aria-hidden="true"></i> Call Center <i class="fa fa-caret-down"></i></a>
                                    <div class="collapse" id="callcenter">
                                        <a href="painel.php?exe=relatorio/callcenter/dashboard/lista" class="list-group-item-sub"><i class="fa fa-list-alt"></i> Dashboard</a>
<!--                                        <a href="painel.php?exe=call/graficos/tempo/lista" class="list-group-item-sub"><i class="fa fa-area-chart"></i> Carga por Hora</a>
                                        <a href="painel.php?exe=call/graficos/desligamento/lista" class="list-group-item-sub"><i class="fa fa-signal"></i> Causas de Desligamento</a>-->
                                    </div>
                                    <!--SMS-->
                                    <a href="#sms" class="list-group-item list-group-item-sub" data-toggle="collapse" data-parent="#relatorio"><i class="fa fa-exchange" aria-hidden="true"></i> SMS <i class="fa fa-caret-down"></i></a>
                                    <div class="collapse" id="sms">
                                        <a href="painel.php?exe=relatorio/sms/enviados/lista" class="list-group-item-sub"><i class="fa fa-long-arrow-right"></i> Enviados</a>
                                        <a href="painel.php?exe=relatorio/sms/respostas/lista" class="list-group-item-sub"><i class="fa fa-long-arrow-left"></i> Recebidos</a>                                        
                                        <a href="painel.php?exe=relatorio/sms/dashboard/lista" class="list-group-item-sub"><i class="fa fa-list-alt"></i> Dashboard</a>                                        
                                    </div>

                                    <a href="painel.php?exe=relatorio/extrato/periodo/lista" class="list-group-item-sub"><i class="fa fa-file-text"></i> Extrato por Período</a>
                                    <a href="painel.php?exe=relatorio/extrato/rperdida/lista" class="list-group-item-sub"><i class="fa fa-file-text-o"></i> Recebidas Perdidas</a>

                                    <a href="#graficos" class="list-group-item list-group-item-sub" data-toggle="collapse" data-parent="#relatorio"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Gráficos <i class="fa fa-caret-down"></i></a>
                                    <div class="collapse" id="graficos">
                                        <a href="painel.php?exe=relatorio/graficos/regiao/lista" class="list-group-item-sub"><i class="fa fa-pie-chart"></i> Carga por Região</a>
                                        <a href="painel.php?exe=relatorio/graficos/tempo/lista" class="list-group-item-sub"><i class="fa fa-area-chart"></i> Carga por Hora</a>
                                        <a href="painel.php?exe=relatorio/graficos/desligamento/lista" class="list-group-item-sub"><i class="fa fa-signal"></i> Causas de Desligamento</a>
                                    </div>

                                    <a href="painel.php?exe=relatorio/ligacaoativas/lista" class="list-group-item-sub"><i class="fa fa-check"></i> Ligações Ativas</a>
                                    <!--<a href="painel.php?exe=relatorio/lista" class="list-group-item-sub" ><i class="fa fa-hdd-o"></i> Gravações de Chamadas</a>-->                                    
                                </div>

                                <!--SUPORTE-->
                                <a href="#suporte" class="list-group-item list-group-item-default" data-toggle="collapse" data-parent="#MainMenu"><span class="glyphicon glyphicon-wrench" aria-hidden="true"></span> Suporte  <i class="fa fa-caret-down"></i></a>
                                <div class="collapse" id="suporte">
                                    <a href="painel.php?exe=suporte/lista" class="list-group-item-sub"><i class="fa fa-ticket"></i> Tiquetes</a>
                                    <a href="painel.php?exe=suporte/lista" class="list-group-item-sub"><i class="fa fa-comment"></i> Contato</a>
                                </div>  
                                <!--RODAPE-->
                                <div class="copy txtCenter size12">
                                    <!--<small>© 2018 - By BRAZISTELECOM</small>-->
                                    <small> © 2018 / <?php echo date("Y"); ?> - By BRAZISTELECOM</small>
                                </div>

                                <!--BLOQUEADO MENU COM SUBMENU-->
<!--                                <a href="#demo3" class="list-group-item list-group-item-default" data-toggle="collapse" data-parent="#MainMenu">Item 3 <i class="fa fa-caret-down"></i></a>
                                <div class="collapse" id="demo3">
                                    <a href="#SubMenu1" class="list-group-item" data-toggle="collapse" data-parent="#SubMenu1">Subitem 1 <i class="fa fa-caret-down"></i></a>
                                    <div class="collapse list-group-submenu" id="SubMenu1">
                                        <a href="#" class="list-group-item" data-parent="#SubMenu1">Subitem 1 a</a>
                                        <a href="#" class="list-group-item" data-parent="#SubMenu1">Subitem 2 b</a>
                                        <a href="#SubSubMenu1" class="list-group-item" data-toggle="collapse" data-parent="#SubSubMenu1">Subitem 3 c <i class="fa fa-caret-down"></i></a>
                                        <div class="collapse list-group-submenu list-group-submenu-1" id="SubSubMenu1">
                                            <a href="#" class="list-group-item" data-parent="#SubSubMenu1">Sub sub item 1</a>
                                            <a href="#" class="list-group-item" data-parent="#SubSubMenu1">Sub sub item 2</a>
                                        </div>
                                        <a href="#" class="list-group-item" data-parent="#SubMenu1">Subitem 4 d</a>
                                    </div>
                                    <a href="javascript:;" class="list-group-item">Subitem 2</a>
                                    <a href="javascript:;" class="list-group-item">Subitem 3</a>
                                </div>   -->
                                <!--BLOQUEADO-->

                            </div>
                        </div>
                    </div>

                    <!--CONTEUDO-->
                    <div class="col-md-10">
                        <div id="conteudo">
                            <div class="container-fluid">  
                                <?php
                                //QUERY STRING
                                $Dir = dirname(__FILE__);
                                if (!empty($getexe)):
                                    $includepatch = $Dir . '/system/' . strip_tags(trim($getexe) . '.php');
                                else:
                                    $includepatch = $Dir . '/system/home.php';
                                endif;
                                if (file_exists($includepatch)):
                                    require_once($includepatch);
                                else:
                                    echo "<div class=\"content notfound\">";
                                    KLErro("<b>Erro ao incluir tela:</b> Erro ao incluir o controller / {$getexe}.php!", KL_ERROR);
                                    echo "</div>";
                                endif;
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
                <!--fim row-->
            </div>
            <!--fim base-->
        </div>
        <!--fim container-fluid-->
        <!-- jQuery  -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> 
        <!-- js/bootstrap -->
        <script src="_app/Library/bootstrap/js/bootstrap.min.js"></script>
        <script src="_app/Library/bootstrap/duallistbox/jquery.bootstrap-duallistbox.js"></script>
        <script src="_cdn/MaskedInput.js"></script>
        <script src="_cdn/geral-masks.js"></script>
        <script src="_cdn/tootips.js"></script>
        <script src="_cdn/jdestino.js"></script>
        <script src="_cdn/jquery.datetimepicker.full.min.js"></script>
        <script src="_cdn/geralDataTimePicker.js"></script>
        <script src="_cdn/impressao.js"></script>
        <script src="_cdn/selectRamais.js"></script>
        <script src="_cdn/ramalAgents.js"></script>
        <!--EZ MONITOR-->
        <script src="Monitor/js/plugins/jquery.knob.js" type="text/javascript"></script>
        <script src="./Monitor/js/esm.js" type="text/javascript"></script>

        <script>
            $(function () {
                $('.gauge').knob({
                    'fontWeight': 'normal',
                    'format': function (value) {
                        return value + '%';
                    }
                });

                $('a.reload').click(function (e) {
                    e.preventDefault();
                });

                esm.getAll();

            });
        </script>



    </body>
</html>
