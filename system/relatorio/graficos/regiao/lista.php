<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;

$totime = strtotime("-1 days");
$dataAnterior = date("Y-m-d", $totime);
//Data provisória
//$dataAnterior = date("2018-07-17");
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
                    //BUSCA
                    //date($data, strtotime("+2 days"));
                    $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    
                    if (!empty($busca['btnBusca'])):
                        unset($busca['btnBusca']);
                        
                        $dataIni = trim($busca['dataInicio']);

                        // Se a data fim for diferente de vazio adiciona mais um dia
                        if (!empty($busca['dataFim'])):
//                            $dataFim = date('Y-m-d', strtotime("+1 days", strtotime($busca['dataFim'])));
                            $dataFim = trim($busca['dataFim']);
                        else:
                            unset($busca['dataFim']);
                        endif;

                        // Verifico se foi digitado os parametros data inicial e a data final para busca.
                        if (!empty($dataIni) && !empty($dataFim)):
                            header("Location: ?exe=relatorio/graficos/regiao/busca&di={$dataIni}&df={$dataFim}");                            
                            exit();
                        elseif (!empty($dataIni) && empty($dataFim)):
                            header("Location: ?exe=relatorio/graficos/regiao/busca&di={$dataIni}");
                            exit();
                        else:
                            KLErro("Não foi possivel realiar uma nova busca falta parametros!", KL_ALERT);
                        endif;
                    endif;
                    ?>
                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Busca</h3>    
                        <form class="form-inline" action=""  method="post" name="frmPesquisa" id="frmPesquisa" >
                            <!--<div class="loc">--> 
                            <div class="form-group form-group-sm">
                                <!--<div class="col-xs-2">-->
                                <label for="dataInicio">Data inicio </label>
                                <input 
                                    class="form-control" 
                                    name="dataInicio" 
                                    id="datetime" 
                                    type="date" 
                                    placeholder="data inicio" 
                                    value="<?php
                                    if (isset($busca['dataInicio'])): echo $busca['dataInicio'];
                                    endif;
                                    ?>" 
                                    required
                                    > 

                                <label>Data final </label>
                                <input 
                                    class="form-control" 
                                    name="dataFim" 
                                    id="datetime" 
                                    type="date" 
                                    placeholder="data final" 
                                    value="<?php
                                    if (isset($busca['dataFim'])): echo $busca['dataFim'];
                                    endif;
                                    ?>"                                     
                                    > 
                                <!--</div>-->
                            </div>   

                            <!--</button>-->                                                              
                            <button name="btnBusca" value="Buscar" type="submit" class="btn btn-info btn-sm" title="Buscar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-search" aria-hidden="true"></span> Buscar</button>

                        </form>
                    </div>

                    <!--well botão-->
                    <div class="well text-right">                                                   
                        <a onclick="print();" href="" role="button" data-toggle="tooltip" title="Imprimir Gráfico" class="btnFormat pull-left" ><span class="glyphicon glyphicon-print " aria-hidden="true"></span></a>
                        <a class="voltar" href="painel.php" role="button" title="Voltar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--RELATORIO-->    
                    <?php
                    /**
                     * Conecta com o banco do servidor local: informar:
                     * host, usuario, senha, e o banco. 
                     * WHERE calldate like '{$dataAnterior}%' AND disposition = 'ANSWERED'  ORDER BY calldate ASC 
                     */
                    $server = new Read;
                    $server->ExeRead("cdr_regiao", "WHERE calldate = '{$dataAnterior}'");
                    $dados = $server->getResult();
                    //$row = mysqli_fetch_object($dados);
                    foreach ($dados as $val):
                        extract($val);
                    endforeach;

                    //converte data
                    $dataBr = (!empty($calldate) ? date('d/m/Y', strtotime($calldate)) : null);

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
                                    <h3><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Tráfego por Região data: <?php echo (!empty($row) ? $dataBr : $dataAnterior); ?></h3>        
                                </div>

                                <div class="corFundo">  

                                    <?php
                                    if (!empty($dados)):
                                        echo '<div class="col-sm-12 pd20B">';
                                        echo '<div class="row">';
                                        echo '<div id="piechart_3d" style="width: 100%; height: 500px;"></div>';
                                        echo '</div>';
                                        echo '</div>';
                                    else:
                                        KLErro("Não existe gráfico para esta data: {$dataAnterior}", KL_INFOR);
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
                                    backgroundColor: '#dae5ea',
                                    chartArea: {top: 100, left: 130, width: '75%', height: '70%'},
                                    legend: {position: 'top', alignment: 'center' }
                                };

                                var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
                                chart.draw(data, options);
                            }
</script>
<!--fim grafico-->