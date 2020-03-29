<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Gráfico por Região <small>Listagem</small></h1>
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
                    <!--mensagem de erro-->
                    <?php
                    //area debug
                    $di = filter_input(INPUT_GET, "di", FILTER_DEFAULT);
                    $df = filter_input(INPUT_GET, "df", FILTER_DEFAULT);
                    
                    if (!empty($df)):
                        //$termo = "WHERE calldate BETWEEN ('{$di}') AND ('{$df}') ";
                        $termo = "WHERE calldate >= '{$di}' AND calldate <= '{$df}' ";
                        $sum = "SUM(toNorte) as toNorte, SUM(toNordeste) as toNordeste, SUM(toCentroOeste) as toCentroOeste, SUM(toSudeste) as toSudeste, SUM(toSul) as toSul";                        
                         
                        /**
                         * Conecta com o banco do servidor local: informar:
                         * host, usuario, senha, e o banco. 
                         * WHERE calldate like '{$dataAnterior}%' AND disposition = 'ANSWERED'  ORDER BY calldate ASC 
                         */
                        $server = new Select;
                        $server->ExeSelect("cdr_regiao", $sum, $termo);
                        $dados = $server->getResult();
                        
                        //converte a data
                        $dataBr = (empty($df) ? date('d/m/Y', strtotime($di)) : date('d/m/Y', strtotime($di)) . ' até ' . date('d/m/Y', strtotime($df)) );

                    else:
                        $termo = "WHERE calldate = '{$di}'";
                        /**
                         * Conecta com o banco do servidor local: informar:
                         * host, usuario, senha, e o banco. 
                         * WHERE calldate like '{$dataAnterior}%' AND disposition = 'ANSWERED'  ORDER BY calldate ASC 
                         */
                        $server = new Read;
                        $server->ExeRead("cdr_regiao", $termo);
                        $dados = $server->getResult();

                        //converte a data
                        $dataBr = date('d/m/Y', strtotime($di));

                    endif;
                    ?>
                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Nova Busca</h3>    
                        <a class="btn btn-warning nb" href="?exe=relatorio/graficos/regiao/lista" title="Nova Busca" data-toggle="tooltip" data-placement="top">Realizar uma Nova Busca</a> 
                    </div>
                    <!--well botão-->
                    <div class="well text-right">                                                   
                        <a onclick="print();" href="" role="button" data-toggle="tooltip" title="Imprimir Gráfico" class="btnFormat pull-left" ><span class="glyphicon glyphicon-print " aria-hidden="true"></span></a>
                        <a class="voltar" href="painel.php" role="button" title="Voltar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>
                    <!--RELATORIO-->    
                    <?php
                  
                    foreach ($dados as $val):
                        extract($val);
                    endforeach;
                    
                    //Total geral de todas operadoras
                    if (!empty($dados)):
                        $totalGeral = $toNorte + $toNordeste + $toCentroOeste + $toSudeste + $toSul;
                    endif;
                    
                    ?>
                    <!--CONTEUDO DO GRÁFICO-->
                    <div  class="container-fluid">
                        <div class="row">
                            <div id="print" >
                                <div class="top">
                                    <h3><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Tráfego por Região data: <?php echo (!empty($dados) ? $dataBr : ""); ?></h3>        
                                </div>
                                <div class="corFundo"> 
                                    <?php
                                    if (!empty($totalGeral)):
                                        echo '<div class="col-sm-12 pd20B">';
                                        echo '<div class="row">';
                                        echo '<div id="piechart_3d" style="width: 100%; height: 500px;"></div>';
                                        echo '</div>';
                                        echo '</div>';
                                    else:
                                        KLErro("Não existe gráfico para esta data: {$dataBr}!", KL_INFOR);
                                    endif;
                                    ?>  
                                    <div class="clear"></div>
                                </div>    
                            </div>
                        </div>
                    </div>
                </div>
            </div><!--panel-body-->
        </div>
    </div>
</div>

<!--Grafico google--> 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
                            google.charts.load("current", {packages: ["corechart"]});
                            google.charts.setOnLoadCallback(drawChart);
                            function drawChart() {
                                var data = google.visualization.arrayToDataTable([
                                    ['Task', 'Total de Ligaçoes por região'],
                                    ['Norte', <?php echo $toNorte; ?>],
                                    ['Nordeste', <?php echo $toNordeste; ?>],
                                    ['Centro-Oeste', <?php echo $toCentroOeste; ?>],
                                    ['Sudeste', <?php echo $toSudeste; ?>],
                                    ['Sul', <?php echo $toSul; ?>]
                                ]);

                                var options = {
                                    //width: 400,
                                    //height: 240,
                                    title: 'Total Geral: . <?php echo $totalGeral; ?>',
                                    is3D: true,
                                };

                                var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                                chart.draw(data, options);
                            }
</script>
<!--fim grafico-->