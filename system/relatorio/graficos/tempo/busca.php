<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Gráfico por Hora <small>Listagem</small></h1>
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
                    <!--RELATORIO-->    
                    <?php
                    //di = data inicio, df = data final
                    $di = filter_input(INPUT_GET, "di", FILTER_DEFAULT);
                    $df = filter_input(INPUT_GET, "df", FILTER_DEFAULT);
                    
                    /** Inicializa variaveis Atendidas */
                    $calltotal1 = 0;
                    $calltotal2 = 0;
                    $calltotal3 = 0;
                    $calltotal4 = 0;
                    $calltotal5 = 0;
                    $calltotal6 = 0;
                    $calltotal7 = 0;
                    $calltotal8 = 0;
                    $calltotal9 = 0;
                    $calltotal10 = 0;
                    $calltotal11 = 0;
                    $calltotal12 = 0;
                    $calltotal13 = 0;
                    $calltotal14 = 0;
                    $calltotal15 = 0;
                    $calltotal16 = 0;
                    /** Inicializa  variaveis não Atendidas */
                    $calltotal17 = 0;
                    $calltotal18 = 0;
                    $calltotal19 = 0;
                    $calltotal20 = 0;
                    $calltotal21 = 0;
                    $calltotal22 = 0;
                    $calltotal23 = 0;
                    $calltotal24 = 0;
                    $calltotal25 = 0;
                    $calltotal26 = 0;
                    $calltotal27 = 0;
                    $calltotal28 = 0;
                    $calltotal29 = 0;
                    $calltotal30 = 0;
                    $calltotal31 = 0;
                    $calltotal32 = 0;

                    if (!empty($df)):
                        /** Monta parametro para pesquisa com data inicio e data final */
                        $termo = "WHERE calldate >= '{$di}' AND calldate <= '{$df}'";
                        
                        //Data para visualizar
                        $dataTxt = $df;

                        /** Conecta com o banco do servidor local: informar: */
                        $server = new Read;
                        $server->ExeRead("cdr_tempo", $termo);
                        $dados = $server->getResult();
                        
//                        while ($row = mysqli_fetch_object($dados)):
                        foreach ($dados as $row):
                            extract($row);
                            /** Ligações atendidas */
                            if ($callparametro == 1):
                                $calltotal1 = $calltotal1 + $calltotal;
                            elseif ($callparametro == 2):
                                $calltotal2 = $calltotal2 + $calltotal;
                            elseif ($callparametro == 3):
                                $calltotal3 = $calltotal3 + $calltotal;
                            elseif ($callparametro == 4):
                                $calltotal4 = $calltotal4 + $calltotal;
                            elseif ($callparametro == 5):
                                $calltotal5 = $calltotal5 + $calltotal;
                            elseif ($callparametro == 6):
                                $calltotal6 = $calltotal6 + $calltotal;
                            elseif ($callparametro == 7):
                                $calltotal7 = $calltotal7 + $calltotal;
                            elseif ($callparametro == 8):
                                $calltotal8 = $calltotal8 + $calltotal;
                            elseif ($callparametro == 9):
                                $calltotal9 = $calltotal9 + $calltotal;
                            elseif ($callparametro == 10):
                                $calltotal10 = $calltotal10 + $calltotal;
                            elseif ($callparametro == 11):
                                $calltotal11 = $calltotal11 + $calltotal;
                            elseif ($callparametro == 12):
                                $calltotal12 = $calltotal12 + $calltotal;
                            elseif ($callparametro == 13):
                                $calltotal13 = $calltotal13 + $calltotal;
                            elseif ($callparametro == 14):
                                $calltotal14 = $calltotal14 + $calltotal;
                            elseif ($callparametro == 15):
                                $calltotal15 = $calltotal15 + $calltotal;
                            elseif ($callparametro == 16):
                                $calltotal16 = $calltotal16 + $calltotal;
                            endif;
                            /** Ligações não atendidas */
                            if ($callparametro == 17):
                                $calltotal17 = $calltotal17 + $calltotal;
                            elseif ($callparametro == 18):
                                $calltotal18 = $calltotal18 + $calltotal;
                            elseif ($callparametro == 19):
                                $calltotal19 = $calltotal19 + $calltotal;
                            elseif ($callparametro == 20):
                                $calltotal20 = $calltotal20 + $calltotal;
                            elseif ($callparametro == 21):
                                $calltotal21 = $calltotal21 + $calltotal;
                            elseif ($callparametro == 22):
                                $calltotal22 = $calltotal22 + $calltotal;
                            elseif ($callparametro == 23):
                                $calltotal23 = $calltotal23 + $calltotal;
                            elseif ($callparametro == 24):
                                $calltotal24 = $calltotal24 + $calltotal;
                            elseif ($callparametro == 25):
                                $calltotal25 = $calltotal25 + $calltotal;
                            elseif ($callparametro == 26):
                                $calltotal26 = $calltotal26 + $calltotal;
                            elseif ($callparametro == 27):
                                $calltotal27 = $calltotal27 + $calltotal;
                            elseif ($callparametro == 28):
                                $calltotal28 = $calltotal28 + $calltotal;
                            elseif ($callparametro == 29):
                                $calltotal29 = $calltotal29 + $calltotal;
                            elseif ($callparametro == 30):
                                $calltotal30 = $calltotal30 + $calltotal;
                            elseif ($callparametro == 31):
                                $calltotal31 = $calltotal31 + $calltotal;
                            endif;
                        endforeach;
                    else:
                        /** Monta parametro para pesquisa com data inicio */
                        $dataPesquisa = "WHERE calldate = '{$di}'";

                        /** Conecta com o banco do servidor local: informar: */
                        $server = new Read;
                        $server->ExeRead("cdr_tempo", $dataPesquisa);
                        $dados = $server->getResult();
                      
                        foreach ($dados as $row):
                            extract($row);                        
                            /** Ligações atendidas */
                            if ($callparametro == 1):
                                $calltotal1 = $calltotal1 + $calltotal;                            
                            elseif ($callparametro == 2):
                                $calltotal2 = $calltotal2 + $calltotal;
                            elseif ($callparametro == 3):
                                $calltotal3 = $calltotal3 + $calltotal;
                            elseif ($callparametro == 4):
                                $calltotal4 = $calltotal4 + $calltotal;
                            elseif ($callparametro == 5):
                                $calltotal5 = $calltotal5 + $calltotal;
                            elseif ($callparametro == 6):
                                $calltotal6 = $calltotal6 + $calltotal;
                            elseif ($callparametro == 7):
                                $calltotal7 = $calltotal7 + $calltotal;
                            elseif ($callparametro == 8):
                                $calltotal8 = $calltotal8 + $calltotal;
                            elseif ($callparametro == 9):
                                $calltotal9 = $calltotal9 + $calltotal;
                            elseif ($callparametro == 10):
                                $calltotal10 = $calltotal10 + $calltotal;
                            elseif ($callparametro == 11):
                                $calltotal11 = $calltotal11 + $calltotal;
                            elseif ($callparametro == 12):
                                $calltotal12 = $calltotal12 + $calltotal;
                            elseif ($callparametro == 13):
                                $calltotal13 = $calltotal13 + $calltotal;
                            elseif ($callparametro == 14):
                                $calltotal14 = $calltotal14 + $calltotal;
                            elseif ($callparametro == 15):
                                $calltotal15 = $calltotal15 + $calltotal;
                            elseif ($callparametro == 16):
                                $calltotal16 = $calltotal16 + $calltotal;
                            endif;
                            /** Ligações não atendidas */
                            if ($callparametro == 17):
                                $calltotal17 = $calltotal17 + $calltotal;
                            elseif ($callparametro == 18):
                                $calltotal18 = $calltotal18 + $calltotal;
                            elseif ($callparametro == 19):
                                $calltotal19 = $calltotal19 + $calltotal;
                            elseif ($callparametro == 20):
                                $calltotal20 = $calltotal20 + $calltotal;
                            elseif ($callparametro == 21):
                                $calltotal21 = $calltotal21 + $calltotal;
                            elseif ($callparametro == 22):
                                $calltotal22 = $calltotal22 + $calltotal;
                            elseif ($callparametro == 23):
                                $calltotal23 = $calltotal23 + $calltotal;
                            elseif ($callparametro == 24):
                                $calltotal24 = $calltotal24 + $calltotal;
                            elseif ($callparametro == 25):
                                $calltotal25 = $calltotal25 + $calltotal;
                            elseif ($callparametro == 26):
                                $calltotal26 = $calltotal26 + $calltotal;
                            elseif ($callparametro == 27):
                                $calltotal27 = $calltotal27 + $calltotal;
                            elseif ($callparametro == 28):
                                $calltotal28 = $calltotal28 + $calltotal;
                            elseif ($callparametro == 29):
                                $calltotal29 = $calltotal29 + $calltotal;
                            elseif ($callparametro == 30):
                                $calltotal30 = $calltotal30 + $calltotal;
                            elseif ($callparametro == 31):
                                $calltotal31 = $calltotal31 + $calltotal;
                            endif;
                        endforeach;
                    endif;
                    //converte a data
                    $dataBr = (empty($df) ? date('d/m/Y', strtotime($di)) : date('d/m/Y', strtotime($di)) . ' até ' . date('d/m/Y', strtotime($dataTxt)) );
                    ?>
                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Nova Busca</h3>    
                        <a class="btn btn-warning nb" href="?exe=relatorio/graficos/tempo/lista" title="Nova Busca" data-toggle="tooltip" data-placement="top">Realizar uma Nova Busca</a> 
                    </div>
                    <!--well botão-->
                    <div class="well text-right">                                                   
                        <a onclick="print();" href="" role="button" data-toggle="tooltip" title="Imprimir Gráfico" class="btnFormat pull-left" ><span class="glyphicon glyphicon-print " aria-hidden="true"></span></a>
                        <a class="voltar" href="painel.php" role="button" title="Voltar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--CONTEUDO DO GRÁFICO-->
                    <div  class="container-fluid">
                        <div class="row">
                            <div id="print" >
                                <div class="top">
                                    <h3><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Tráfego por Região data: <?php echo (!empty($dados) ? $dataBr : ""); ?></h3>        
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
                google.charts.load('current', {'packages': ['corechart']});
                google.charts.setOnLoadCallback(drawChart);

                function drawChart() {
                    var data = google.visualization.arrayToDataTable([
                        ['Horario', 'Completadas', 'Cancelada'],
                        ['06:00 até 08:00', <?php echo $calltotal1; ?>, <?php echo $calltotal17; ?>],
                        ['08:01 até 09:00', <?php echo $calltotal2; ?>, <?php echo $calltotal18; ?>],
                        ['09:01 até 10:00', <?php echo $calltotal3; ?>, <?php echo $calltotal19; ?>],
                        ['10:01 até 11:00', <?php echo $calltotal4; ?>, <?php echo $calltotal20; ?>],
                        ['11:01 até 12:00', <?php echo $calltotal5; ?>, <?php echo $calltotal21; ?>],
                        ['12:01 até 13:00', <?php echo $calltotal6; ?>, <?php echo $calltotal22; ?>],
                        ['13:01 até 14:00', <?php echo $calltotal7; ?>, <?php echo $calltotal23; ?>],
                        ['14:01 até 15:00', <?php echo $calltotal8; ?>, <?php echo $calltotal24; ?>],
                        ['15:01 até 16:00', <?php echo $calltotal9; ?>, <?php echo $calltotal25; ?>],
                        ['16:01 até 17:00', <?php echo $calltotal10; ?>, <?php echo $calltotal26; ?>],
                        ['17:01 até 18:00', <?php echo $calltotal11; ?>, <?php echo $calltotal27; ?>],
                        ['18:01 até 19:00', <?php echo $calltotal12; ?>, <?php echo $calltotal28; ?>],
                        ['19:01 até 20:00', <?php echo $calltotal13; ?>, <?php echo $calltotal29; ?>],
                        ['20:01 até 21:00', <?php echo $calltotal14; ?>, <?php echo $calltotal30; ?>],
                        ['21:01 até 22:00', <?php echo $calltotal15; ?>, <?php echo $calltotal31; ?>],
                        ['22:01 até 23:00', <?php echo $calltotal16; ?>, <?php echo $calltotal32; ?>]
                    ]);

                    var options = {
                        title: 'Tráfego por Horas',
                        hAxis: {title: 'Horas', titleTextStyle: {color: '#333'}},
                        vAxis: {minValue: 0}
                    };

                    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
                    chart.draw(data, options);
                }
</script>
<!--fim grafico-->