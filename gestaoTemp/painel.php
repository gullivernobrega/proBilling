<?php
ob_start();
session_start();
require('../_app/Config.inc.php');

$usuario = new Login(1);

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

//var_dump($userlogin);
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">    
        <title>Gestaão de Conteudo - CSMK 1.2</title>

        <!-- Bootstrap -->
        <link href="../libs/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="../libs/font-awesome/css/font-awesome.min.css" rel="stylesheet">        
        <link href="css/geral.css" rel="stylesheet">

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
                    <a class="navbar-brand" href="painel.php"><img src="images/logok.png" width="50%" class="img-responsive"> </a>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">

                    <ul class="nav navbar-nav navbar-right navbar-user">
                        <li class="dropdown messages-dropdown">
                            <a href="painel.php" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-envelope"></i> Messages <span class="badge">2</span> <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-header">2 New Messages</li>
                                <li class="message-preview">
                                    <a href="#">
                                        <span class="avatar"><i class="fa fa-bell"></i></span>
                                        <span class="message">Security alert</span>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li class="message-preview">
                                    <a href="#">
                                        <span class="avatar"><i class="fa fa-bell"></i></span>
                                        <span class="message">Security alert</span>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li><a href="#">Go to Inbox <span class="badge">2</span></a></li>
                            </ul>
                        </li>
                        <!--USUARIOS-->
                        <li class="dropdown user-dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-user"></i> <?php echo $userlogin['user_nome']; ?> <b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="painel.php?exe=users/profile"><i class="fa fa-user"></i> Perfil</a></li>
                                <li><a href="painel.php?exe=users/users"><i class="fa fa-users"></i> Usuarios</a></li>
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
                                <a href="painel.php" class="list-group-item list-group-item-default active" data-parent="#MainMenu"><span class="glyphicon glyphicon-home" aria-hidden="true"></span> Home</a>

                                <a href="#config" class="list-group-item list-group-item-default" data-toggle="collapse" data-parent="#MainMenu"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span> Configuração <i class="fa fa-caret-down"></i></a>
                                <div class="collapse" id="config">
                                    <a href="painel.php?exe=configSite/configSite" class="list-group-item-sub"><i class="fa fa-list-alt"></i> Configurar Site</a>
                                    <a href="painel.php?exe=categorias/categorias" class="list-group-item-sub"><i class="fa fa-list"></i> Categorias</a>
                                    <a href="painel.php?exe=cms/cms" class="list-group-item-sub"><i class="fa fa-puzzle-piece"></i> Paginas CMS</a>
                                    <a href="painel.php?exe=sociais/sociais" class="list-group-item-sub"><i class="fa fa-bullseye"></i> Rede Sociais</a>
                                    <a href="painel.php?exe=users/users" class="list-group-item-sub"><i class="fa fa-users"></i> Usuários</a>
                                </div>   

                                <a href="painel.php?exe=galerias/galerias" class="list-group-item list-group-item-default" data-parent="#MainMenu"><i class="fa fa-picture-o"></i> Galerias</a>
                                <a href="painel.php?exe=videos/videos" class="list-group-item list-group-item-default" data-parent="#MainMenu"><i class="fa fa-video-camera"></i> Videos</a>
                                <a href="painel.php?exe=banners/banners" class="list-group-item list-group-item-default" data-parent="#MainMenu"><i class="fa fa-bookmark"></i> Banners</a>
                                <a href="painel.php?exe=arquivos/arquivos" class="list-group-item list-group-item-default" data-parent="#MainMenu"><i class="fa fa-archive"></i> Arquivos</a>

                                <a href="#comunicado" class="list-group-item list-group-item-default" data-toggle="collapse" data-parent="#MainMenu"><span class="glyphicon glyphicon-comment" aria-hidden="true"></span> Comunicado  <i class="fa fa-caret-down"></i></a>
                                <div class="collapse" id="comunicado">
                                    <a href="painel.php?exe=informativos/informativos" class="list-group-item-sub"><i class="fa fa-bullhorn"></i> Informativos</a>
                                    <a href="painel.php?exe=depoimentos/depoimentos" class="list-group-item-sub"><i class="fa fa-comment"></i> Depoimentos</a>
                                    <a href="painel.php?exe=noticias/categoriaNoticias" class="list-group-item-sub"><i class="fa fa-rss-square"></i> Categoria Noticias</a>
                                    <a href="painel.php?exe=noticias/noticias" class="list-group-item-sub"><i class="fa fa-rss-square"></i> Noticias</a>
                                </div>                                
                                <div class="copy txtCenter size12"><small>© 2016 - By KLSDESIGNER</small></div>
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
</div>-->   
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
        <script src="../libs/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>