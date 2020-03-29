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
        <h1 class="tit">Carga por Tempo <small>Listagem</small></h1>
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
                    $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);

                    if (!empty($busca['btnBusca'])):
                        unset($busca['btnBusca']);

                        $dataIni = trim($busca['dataInicio']);

                        // Se a data fim for diferente de vazio adiciona mais um dia
                        if (!empty($busca['dataFim'])):
                            $dataFim = trim($busca['dataFim']);
                        else:
                            unset($busca['dataFim']);
                        endif;

                        // Verifico se foi digitado os parametros data inicial e a data final para busca.
                        if (!empty($dataIni) && !empty($dataFim)):
                            header("Location: ?exe=relatorio/graficos/tempo/busca&di={$dataIni}&df={$dataFim}");
                            exit();
                        elseif (!empty($dataIni) && empty($dataFim)):
                            header("Location: ?exe=relatorio/graficos/tempo/busca&di={$dataIni}");
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
                    $server->ExeRead("cdr_tempo", "WHERE calldate = '{$dataAnterior}'");
                    $dados = $server->getResult();

                    foreach ($dados as $val):
                        $arr[] = $val;
                    endforeach;

                    //converte data
                    $dataBr = (!empty($calldate) ? date('d/m/Y', strtotime($calldate)) : null);
                    ?>
                    <!--CONTEUDO DO GRÁFICO-->
                    <div  class="container-fluid">
                        <div class="row">
                            <div id="print" >
                                <div class="top">
                                    <h3><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Carga por Hora data: <?php echo (!empty($row) ? $dataBr : $dataAnterior); ?></h3>        
                                </div>
                                <div class="corFundo"> 
                                    <?php
                                    if (!empty($dados)):
                                        echo '<div class="col-sm-12 pd20B">';
                                        echo '<div class="row">';
                                        echo '<div id="chart_div" style="width: 100%; height: 500px;"></div>';
                                        echo '</div>';
                                        echo '</div>';
                                    else:
                                        KLErro("Não existe gráfico para esta data: {$dataAnterior}!", KL_INFOR);
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
                            google.charts.load('current', {'packages': ['corechart']});
                            google.charts.setOnLoadCallback(drawChart);

                            function drawChart() {
                                var data = google.visualization.arrayToDataTable([
                                    ['Horario', 'Completadas', 'Cancelada'],
                                    ['06:00 até 08:00', <?php echo $arr[0]["calltotal"]; ?>, <?php echo $arr[16]["calltotal"]; ?>],
                                    ['08:01 até 09:00', <?php echo $arr[1]["calltotal"]; ?>, <?php echo $arr[17]["calltotal"]; ?>],
                                    ['09:01 até 10:00', <?php echo $arr[2]["calltotal"]; ?>, <?php echo $arr[18]["calltotal"]; ?>],
                                    ['10:01 até 11:00', <?php echo $arr[3]["calltotal"]; ?>, <?php echo $arr[19]["calltotal"]; ?>],
                                    ['11:01 até 12:00', <?php echo $arr[4]["calltotal"]; ?>, <?php echo $arr[20]["calltotal"]; ?>],
                                    ['12:01 até 13:00', <?php echo $arr[5]["calltotal"]; ?>, <?php echo $arr[21]["calltotal"]; ?>],
                                    ['13:01 até 14:00', <?php echo $arr[6]["calltotal"]; ?>, <?php echo $arr[22]["calltotal"]; ?>],
                                    ['14:01 até 15:00', <?php echo $arr[7]["calltotal"]; ?>, <?php echo $arr[23]["calltotal"]; ?>],
                                    ['15:01 até 16:00', <?php echo $arr[8]["calltotal"]; ?>, <?php echo $arr[24]["calltotal"]; ?>],
                                    ['16:01 até 17:00', <?php echo $arr[9]["calltotal"]; ?>, <?php echo $arr[25]["calltotal"]; ?>],
                                    ['17:01 até 18:00', <?php echo $arr[10]["calltotal"]; ?>, <?php echo $arr[26]["calltotal"]; ?>],
                                    ['18:01 até 19:00', <?php echo $arr[11]["calltotal"]; ?>, <?php echo $arr[27]["calltotal"]; ?>],
                                    ['19:01 até 20:00', <?php echo $arr[12]["calltotal"]; ?>, <?php echo $arr[28]["calltotal"]; ?>],
                                    ['20:01 até 21:00', <?php echo $arr[13]["calltotal"]; ?>, <?php echo $arr[29]["calltotal"]; ?>],
                                    ['21:01 até 22:00', <?php echo $arr[14]["calltotal"]; ?>, <?php echo $arr[30]["calltotal"]; ?>],
                                    ['22:01 até 23:00', <?php echo $arr[15]["calltotal"]; ?>, <?php echo $arr[31]["calltotal"]; ?>]
                                ]);

                                var options = {
                                    title: 'Carga por Hora',
                                    colors: ['#003399', 'red'],
                                    hAxis: {title: 'Horas', titleTextStyle: {color: '#333'}},
                                    vAxis: {minValue: 0},
                                    backgroundColor: '#dae5ea',
                                    chartArea: {top: 85, left: 160, width: '75%', height: '55%'},
                                    legend: {position: 'top'}                                 
                                    
                                };

                                var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                                chart.draw(data, options);
                            }
</script>
<!--fim grafico-->
