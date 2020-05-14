<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;

//$timeout = isset($_GET['timeout']) ? $_GET['timeout'] * 1000 : 5000;

include 'getcalls.php';
?>

<!--<script type="text/javascript">
    setTimeout(function () {
        location.reload(1);
    }, <?php// echo $timeout ?>);
</script>-->

<!--LIGAÇOES ATIVAS-->                    
<script type="text/javascript">
    setInterval("atualiza();", 5000);
    function atualiza() {
        $('#ativa').load(location.href + ' #ativa');
    }
</script> 

<div class="conteudo">
    <div class="top">
        <h1 class="tit">Ligações Ativas <small>Listagem</small></h1>
    </div>       
    <!--</div>-->

    <!--<div class="row">-->
    <div class="container-fluid">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> Painel de ações </h3>
            </div>
            <div class="panel-body txtblue">
                <div id="shieldui-grid1">
                    <div id="ativa">
                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th>Estado</th>                                                        
                                <th>Origem</th>                                             
                                <th>Número</th>                             
                                <th>Tempo</th>                             
                                <th>Sip Destino</th>       
                                <th>Canal</th> 
                                <th>Codec</th> 
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            $variable = getCalls();
                            $ct = 0;
                            $canal = filter_input(INPUT_GET, "canal", FILTER_DEFAULT);

                            if (!empty($canal)) {
                                hangupCalls($canal);
                            }

                            $dst = filter_input(INPUT_GET, "dst", FILTER_DEFAULT);

                            if (!empty($dst)) {
                                escutarCalls($dst);
                            }
                            ?>
                            <?php foreach ($variable as $key => $value) : ?>
                                <tr class="trc1">
                                    <td><?php
                                        if ($value['status'] == "Up"):
                                            echo "<img src='libs/images/up.png'>";
                                            $ct++;
                                        elseif ($value['status'] == "Ring" || $value['status'] == "Ringing"): echo "<img src='libs/images/ring.png'>";
                                        endif;
                                        ?>
                                    </td>
                                    <td><?php echo $value['src']; ?></td>
                                    <td><?php echo $value['dst']; ?></td>
                                    <td><?php echo $value['duration']; ?></td>
                                    <td><?php echo $value['tronco']; ?></td>
                                    <td><?php echo $value['canal']; ?></td>
                                    <td><?php echo $value['codec']; ?></td>
                                    <td>
                                        <a class="des" href="?exe=relatorio/ligacaoativas/lista&canal=<?php echo $value['canal']; ?>" data-toggle="tooltip" data-placement="top" title="Encerrar Ligação"><span class="glyphicon glyphicon-minus-sign" aria-hidden="true"></span></a>
                                        <a class="des" href="?exe=relatorio/ligacaoativas/lista&dst=<?php echo $value['dst']; ?>" data-toggle="tooltip" data-placement="top" title="Escutar Ligação"><span class="glyphicon glyphicon-volume-up" aria-hidden="true"></span></a>
                                        <a class="des" href="?exe=relatorio/ligacaoativas/lista&dst=<?php echo $value['dst']; ?>" data-toggle="tooltip" data-placement="top" title="Sussurrar"><span class="glyphicon glyphicon-bullhorn" aria-hidden="true"></span></a>
                                    </td>

                                </tr>

                                <!-- Condição ternaria -->
                                <?php
                                $ar = array($value['status']);
                                // print_r(rsort($a));
                                ?>
                            <?php endforeach; ?>       
                            <!-- CONTAGEM DE DADOS -->
                        <p class="tot">
                            Total de ligações: <span class="badge"><?php echo count($variable); ?></span>
                            Ligaçôes Ativas: <span class="badge"><?php echo $ct; ?></span>
                        <p> 
                            </tbody> 
                    </table>
                    <!--fim tabela-->
                    </div>
                </div>

                <!--PAGINAÇÃO-->
                <!--                <div class="well corWell text-center">                     
                <?php
// $pager->ExePaginator("cdr", "WHERE calldate >= '{$dataIni}' AND calldate <= '{$datafinal}' AND tipo <> '' ORDER BY calldate ASC");
//$pager->ExePaginator("cdr", "WHERE calldate >= '{$dataAtual}' AMD tipo <> '' ");
//SELECT * FROM `cdr` WHERE `calldate` >= '2018-04-09 00:00:01' AND `calldate` <= '2018-04-09 23:59:59' AND `tipo` <> ''
// echo $pager->getPaginator();
                ?>
                                </div>-->

            </div><!--panel-body-->
        </div>
    </div>
</div>