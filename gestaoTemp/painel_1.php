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
        <link href="../libs/css/local.css" rel="stylesheet">

    </head>
    <body>
        <div id="wrapper">
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
                    <ul id="active" class="nav navbar-nav side-nav">
                        <li class="selected"><a href="painel.php"><i class="fa fa-home"></i> Menu Principal</a></li>
                        <li><a class="btn-1" data-toggle="collapse" data-target="#submenu1" aria-expanded="false" href="#"><i class="fa fa-cog"></i> Configuração </span> <b class="caret"></b></a>
                            <ul class="nav collapse" id="submenu1" role="menu" aria-labelledby="btn-1">
                                <li><a  href="painel.php?exe=configSite/configSite"><i class="fa fa-list-alt"></i> Configuração do Site</a></li>
                                <li><a  href="painel.php?exe=categorias/categorias"><i class="fa fa-list"></i> Categorias</a></li>
                                <li><a  href="painel.php?exe=cms/cms"><i class="fa fa-puzzle-piece"></i> Paginas CMS</a></li>
                                <li><a  href="painel.php?exe=sociais/sociais"><i class="fa fa-bullseye"></i> Rede Sociais</a></li> 
                                <li><a  href="painel.php?exe=users/users"><i class="fa fa-user"></i> Usuários</a></li> 
                            </ul>
                        </li> 
                        <li><a href="painel.php?exe=galerias/galerias"><i class="fa fa-picture-o"></i> Galerias</a></li>
                        <li><a href="painel.php?exe=videos/videos"><i class="fa fa-video-camera"></i> Videos</a></li>
                        <li><a href="painel.php?exe=banners/banners"><i class="fa fa-bookmark"></i> Banners</a></li>
                        <li><a href="painel.php?exe=arquivos/arquivos"><i class="fa fa-archive"></i> Arquivos</a></li>

                        <li><a class="btn-2" data-toggle="collapse" data-target="#submenu2" aria-expanded="false" href="#submenu2"><i class="fa fa-rss"></i> Comunicados <b class="caret"></b></a>
                            <ul class="nav collapse" id="submenu2" role="menu" aria-labelledby="btn-2">
                                <li><a href="painel.php?exe=informativos/informativos"><i class="fa fa-bullhorn"></i> Informativos</a></li>  
                                <li><a href="painel.php?exe=depoimentos/depoimentos"><i class="fa fa-comment"></i> Depoimentos</a></li>  
                                <li><a href="painel.php?exe=noticias/categoriaNoticias"><i class="fa fa-rss-square"></i> Categoria Noticias</a></li>  
                                <li><a href="painel.php?exe=noticias/noticias"><i class="fa fa-rss-square"></i> Noticias</a></li>
                            </ul>
                        </li>
                        
                        
                    </ul>
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
<!--                        <li class="divider-vertical"></li>
                        <li>
                            <form class="navbar-search">
                                <input type="text" placeholder="Search" class="form-control">
                            </form>
                        </li>-->
                    </ul>
                </div>
            </nav>
            <!--CONTEUDO-->
            <div id="page-wrapper">
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
            <!--fim page-wrapper-->
        </div>
        <!-- /#wrapper -->

        <!-- jQuery  -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
        <!-- js/bootstrap -->
        <script src="../libs/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>