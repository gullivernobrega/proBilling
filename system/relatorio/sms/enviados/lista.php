<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;

//Define o valor do SMS
define("VALORSMS", 0.032);
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">SMS Enviados <small>Listagem</small></h1>
    </div>       

    <div class="container-fluid">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> Painel de ações </h3>
            </div>
            <div class="panel-body txtblue">
                <div id="shieldui-grid1">                    
                    <?php
                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                    $pager = new Pager("?exe=relatorio/sms/enviados/lista&pg=");
                    $pager->ExePager($getPage, 20);

                    //LEITURA DOS DADOS  
                    $dt = date("Y-m-d");
                    //$dt = date("2019-07-15");
                    $dataIni = "{$dt} 00:00:01";
                    $datafinal = "{$dt} 23:59:59";

                    $campos = "sms_id, sms_date, sms_date_atualizacao, sms_campanha, sms_operadora, sms_numero, sms_msg, sms_status, sms_lote";

                    $read = new Select;
                    //$read->ExeSelect("cdr", $campos ,"WHERE calldate >= '{$dataIni}' AND calldate <= '{$datafinal}' AND tipo <> '' ORDER BY calldate ASC");
                    $read->ExeSelect("cdr_sms", $campos, "WHERE sms_date >= '{$dataIni}' AND sms_date <= '{$datafinal}' ORDER BY sms_date ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $verifica = $read->getRowCount();

                    //RESULTADO DA PESQUISA
                    $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($busca['btnBusca'])):
                        unset($busca['btnBusca']);                   
                        
                        if (!empty($busca['dataInicio']) && !empty($busca['dataFim']) || !empty($busca['sms_numero']) || !empty($busca['sms_campanha']) || !empty($busca['sms_operadora']) || !empty($busca['sms_status']) || !empty($busca['sms_lote'])):
                            
                            $dateDi = new DateTime($busca['dataInicio']);
                            $di = date_format($dateDi, 'Y-m-d H:m:s');
                            
                            $dateDf = new DateTime($busca['dataFim']);
                            $df = date_format($dateDf, 'Y-m-d H:m:s');                            
                            
//                            header("Location: ?exe=relatorio/sms/enviados/busca&di={$busca['dataInicio']}&df={$busca['dataFim']}&sms_numero={$busca['sms_numero']}&sms_campanha={$busca['sms_campanha']}&sms_operadora={$busca['sms_operadora']}&sms_status={$busca['sms_status']}&sms_lote={$busca['sms_lote']}");
                            header("Location: ?exe=relatorio/sms/enviados/busca&di={$di}&df={$df}&sms_numero={$busca['sms_numero']}&sms_campanha={$busca['sms_campanha']}&sms_operadora={$busca['sms_operadora']}&sms_status={$busca['sms_status']}&sms_lote={$busca['sms_lote']}");
                            exit();

                        else:

                            KLErro("Ops, Falta parametros para a pesquisa", KL_INFOR);

                        endif;

                    endif;
                    ?>
                    <!--BASE PESQUISA-->
                    <div class="well seach">
                        <h3>Busca</h3>    
                        <form class="form-inline" action=""  method="post" name="frmPesquisa" id="frmPesquisa" >

                            <div class="form-group form-group-sm">                                
                                <label for="dataInicio">Data inicio </label>
                                <input 
                                    class="form-control" 
                                    name="dataInicio" 
                                    id="datetimeIni" 
                                    type="text" 
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
                                    id="datetimeFim" 
                                    type="text" 
                                    placeholder="data final" 
                                    value="<?php
                                    if (isset($busca['dataFim'])): echo $busca['dataFim'];
                                    endif;
                                    ?>" 
                                    required
                                    >                                 
                            </div>
                            <br>
                            <br>
                            <!--*******-->                            
                            <div class="form-group form-group-sm">                                
                                <input 
                                    class="form-control" 
                                    name="sms_numero" 
                                    id="sms_numero" 
                                    type="text" 
                                    placeholder="Numero" 
                                    value="<?php
                                    if (isset($busca['sms_numero'])): echo $busca['sms_numero'];
                                    endif;
                                    ?>" >                                
                            </div>                   
                            
                            <!--*******-->
                            <div class="form-group form-group-sm">                                
                                <input 
                                    class="form-control" 
                                    name="sms_campanha" 
                                    id="sms_campanha" 
                                    type="text" 
                                    placeholder="Nome da campanha" 
                                    value="<?php
                                    if (isset($busca['sms_campanha'])): echo $busca['sms_campanha'];
                                    endif;
                                    ?>" >                                
                            </div>                   
                            
                            <!--*******-->
                            <div class="form-group form-group-sm">                                
                                <input 
                                    class="form-control" 
                                    name="sms_lote" 
                                    id="sms_lote" 
                                    type="text" 
                                    placeholder="Nome do Lote" 
                                    value="<?php
                                    if (isset($busca['sms_lote'])): echo $busca['sms_lote']; endif;?>"
                                    >                                
                            </div>  
                                                                                  
                            <!--*******-->
                            <div class="form-group form-group-sm"> 
                                <select class="form-control" name="sms_operadora" id="sms_operadora" >
                                    <option value="">Operadoras</option>
                                    <option value="TIM" <?php if (!empty($busca) && $busca['sms_operadora'] == "TIM"): ?> selected="selected" <?php endif; ?> >Tim</option>
                                    <option value="VIVO" <?php if (!empty($busca) && $busca['sms_operadora'] == "VIVO"): ?> selected="selected" <?php endif; ?>>Vivo</option>
                                    <option value="OI" <?php if (!empty($busca) && $busca['sms_operadora'] == "OI"): ?> selected="selected" <?php endif; ?>>Oi</option>
                                    <option value="CLARO" <?php if (!empty($busca) && $busca['sms_operadora'] == "CLARO"): ?> selected="selected" <?php endif; ?>>Claro</option>
                                    <option value="OUTROS" <?php if (!empty($busca) && $busca['sms_operadora'] == "OUTROS"): ?> selected="selected" <?php endif; ?>>Outros</option>
                                </select>
                            </div>

                            <!--*******-->
                            <div class="form-group form-group-sm"> 
                                <select class="form-control" name="sms_status" id="sms_status" >
                                    <option value="">Status</option>
                                    <option value="ACCEPTED" <?php if (!empty($busca) && $busca['sms_status'] == "ANSWERED"): ?> selected="selected" <?php endif; ?> >Inserido</option>
                                    <option value="PAYREQUIRED" <?php if (!empty($busca) && $busca['sms_status'] == "PAYREQUIRED"): ?> selected="selected" <?php endif; ?>>Sem Saldo</option>
                                    <option value="SENT" <?php if (!empty($busca) && $busca['sms_status'] == "SENT"): ?> selected="selected" <?php endif; ?>>Enviado</option>
                                    <option value="DELIVERED" <?php if (!empty($busca) && $busca['sms_status'] == "DELIVERED"): ?> selected="selected" <?php endif; ?>>Entregue</option>
                                    <option value="FAILED" <?php if (!empty($busca) && $busca['sms_status'] == "FAILED"): ?> selected="selected" <?php endif; ?>>Falha</option>
                                    <option value="UNKNOWN" <?php if (!empty($busca) && $busca['sms_status'] == "UNKNOWN"): ?> selected="selected" <?php endif; ?>>Não Entregue</option>
                                    <option value="EXPIRED" <?php if (!empty($busca) && $busca['sms_status'] == "EXPIRED"): ?> selected="selected" <?php endif; ?>>Expirado</option>
                                    <option value="DELETED" <?php if (!empty($busca) && $busca['sms_status'] == "DELETED"): ?> selected="selected" <?php endif; ?>>Deletado</option>
                                    <option value="REJECTED" <?php if (!empty($busca) && $busca['sms_status'] == "REJECTED"): ?> selected="selected" <?php endif; ?>>Rejeitado</option>
                                    <option value="UNDELIVERABLE" <?php if (!empty($busca) && $busca['sms_status'] == "UNDELIVERABLE"): ?> selected="selected" <?php endif; ?>>Não Entregável</option>

                                </select>
                            </div>
                            
                            <!--</button>-->                                                            
                            <!--<div class="text-center mg10T">-->
                                <button name="btnBusca" value="Buscar" type="submit" class="btn btn-info btn-sm" title="Buscar" data-toggle="tooltip" data-placement="top"><i class="fa fa-search"></i> Buscar</button>                         
                            <!--</div>-->
                        </form>
                    </div>

                    <!--Gráficos-->
                    <?php
                    if($verifica > 0):
                    ?>
                    <div class="row mg10B">
                        <div class="col-md-5">
                            <div id="donutchart" style="width: 100%; height: 250px;"></div>
                        </div>
                        <div class="col-md-7">
                            <div id="columnchart_values" style="width: 100%; height: 250px;"></div>
                        </div>
                    </div>
                    <?php
                    endif;
                    ?>
                    <!--Fim gráficos-->

                    <!--well botão-->
                    <div class="well text-right">                                                  
                        <a class="pull-left" href="system/relatorio/sms/enviados/relatorio_sms_excel.php" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>                                
                        <a class="pull-left" href="system/relatorio/sms/enviados/relatorio_sms_pdf.php" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>                        
                        <a class="voltar" href="painel.php" role="button" title="Voltar" data-toggle="tooltip" data-placement="top"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                    </div>

                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th>#</th>                                                        
                                <th>Data</th>                                             
                                <th>Atualização</th>                                             
                                <th>Lote</th>                                             
                                <th>Campanha</th>                                             
                                <th>Operadora</th>                                             
                                <th>Número</th>                                                             
                                <th>Status</th> 
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
                            <?php
                            if ($verifica > 0):                                

                                //Inicialização das variaveis
                                $inserido = 0;
                                $enviado = 0;
                                $entregue = 0;
                                $falha = 0;
                                $semSaldo = 0;
                                $naoEntregue = 0;
                                $expirado = 0;
                                $deletado = 0;
                                $rejeitado = 0;
                                $naoEntregavel = 0;
                                
                                $tim = 0;
                                $vivo = 0;
                                $oi = 0;
                                $claro = 0;
                                $outros = 0;
                                
                                //Busca de total de registros
                                $total = new Select();
                                $total->ExeSelect("cdr_sms", $campos, "WHERE sms_date >= '{$dataIni}' AND sms_date <= '{$datafinal}' ORDER BY sms_date ASC");
                                $registros = $total->getRowCount();
                                foreach ($total->getResult() as $vTotal):
                                    
                                    //Status de SMS
                                    if ($vTotal['sms_status'] == 'ACCEPTED'):
                                        $status = "Inserido";
                                        $inserido = $inserido + 1;
                                    elseif ($vTotal['sms_status'] == 'PAYREQUIRED'):
                                        $status = "Sem Saldo";
                                        $semSaldo = $semSaldo + 1;
                                    elseif ($vTotal['sms_status'] == 'SENT'):
                                        $status = "Enviado";
                                        $enviado = $enviado + 1;
                                    elseif ($vTotal['sms_status'] == 'DELIVERED'):
                                        $status = "Entregue";
                                        $entregue = $entregue + 1;
                                    elseif ($vTotal['sms_status'] == 'FAILED'):
                                        $status = "Falha";
                                        $falha = $falha + 1;
                                    elseif ($vTotal['sms_status'] == 'UNKNOWN'):
                                        $status = "Não Entregue";
                                        $naoEntregue = $naoEntregue + 1;
                                    elseif ($vTotal['sms_status'] == 'EXPIRED'):
                                        $status = "Expirado";
                                        $expirado = $expirado + 1;
                                    elseif ($vTotal['sms_status'] == 'DELETED'):
                                        $status = "Deletado";
                                        $deletado = $deletado + 1;
                                    elseif ($vTotal['sms_status'] == 'REJECTED'):
                                        $status = "Rejeitado";
                                        $rejeitado = $rejeitado + 1;
                                    elseif ($vTotal['sms_status'] == 'UNDELIVERABLE'):
                                        $status = "Não Entregável";
                                        $naoEntregavel = $naoEntregavel + 1;
                                    else:
                                        $status = "INVALIDO";
                                    endif;     
                                    
                                    //Relaciona quantidade de operadoras
                                    if($vTotal['sms_operadora'] == 'TIM'):
                                        $tim = $tim + 1;
                                    endif;
                                    if($vTotal['sms_operadora'] == 'VIVO'):
                                        $vivo = $vivo + 1;
                                    endif;
                                    if($vTotal['sms_operadora'] == 'OI'):
                                        $oi = $oi + 1;
                                    endif;
                                    if($vTotal['sms_operadora'] == 'CLARO'):
                                        $claro = $claro + 1;
                                    endif;
                                    if($vTotal['sms_operadora'] == 'OUTROS'):
                                        $outros = $outros + 1;
                                    endif;
                                    
                                    //Calcula o valor total de SMS
                                    $valor = number_format(($enviado + $entregue + $naoEntregavel) * VALORSMS, 3, ",", ".");
                                    $falhado = $semSaldo + $falha + $naoEntregue + $expirado + $deletado + $rejeitado;
                                    
                                endforeach;                                
                                
                                echo "<h4>Total de registros encontrados: <b>{$registros}</b></h4>";

                                foreach ($read->getResult() as $cdrSms):
                                    
                                    extract($cdrSms);
                                    $data = explode(" ", $sms_date);
                                    $dt = explode("-", $data[0]);
                                    $dataAtual = "$dt[2]-$dt[1]-$dt[0]";
                                    
                                    //Status de SMS
                                    if ($sms_status == 'ACCEPTED'):
                                        $status = "Inserido";                                        
                                    elseif ($sms_status == 'PAYREQUIRED'):
                                        $status = "Sem Saldo";                                        
                                    elseif ($sms_status == 'SENT'):
                                        $status = "Enviado";                                       
                                    elseif ($sms_status == 'DELIVERED'):
                                        $status = "Entregue";                                        
                                    elseif ($sms_status == 'FAILED'):
                                        $status = "Falha";                                       
                                    elseif ($sms_status == 'UNKNOWN'):
                                        $status = "Não Entregue";                                        
                                    elseif ($sms_status == 'EXPIRED'):
                                        $status = "Expirado";                                        
                                    elseif ($sms_status == 'DELETED'):
                                        $status = "Deletado";                                       
                                    elseif ($sms_status == 'REJECTED'):
                                        $status = "Rejeitado";                                        
                                    elseif ($sms_status == 'UNDELIVERABLE'):
                                        $status = "Não Entregável";                                       
                                    else:
                                        $status = "INVALIDO";
                                    endif; 
                                    ?>

                                    <tr>
                                        <td scope="row"><?php echo $sms_id; ?></td> 
                                        <td scope="row"><?php echo $sms_date; ?></td>
                                        <td scope="row"><?php echo $sms_date_atualizacao; ?></td>
                                        <td scope="row"><?php echo $sms_lote; ?></td>
                                        <td scope="row"><?php echo $sms_campanha; ?></td>
                                        <td><?php echo $sms_operadora; ?></td> 
                                        <td><?php echo $sms_numero; ?></td> 
                                        <td><?php echo $status; ?></td> 
                                        <td>                                            
                                            <a href="" data-toggle="modal" data-target="#sms_<?php echo $sms_id; ?>" data-placement="top" title="Ver Mensagem" class="del"><i class="fa fa-eye size20" aria-hidden="true"></i></a> 
                                        </td> 
                                    </tr>

                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="sms_<?php echo $sms_id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Mensagem Eviada </h4>
                                            </div>
                                            <div class="modal-body">                                                    
                                                <?php
                                                echo "<h4>{$sms_msg}</h4>";
                                                ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-info" data-dismiss="modal">Fechar</button>                                               
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                                <?php
                            endforeach;
                        else:
                            KLErro("Não existe Sms Cadastrado no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                </div>

                <!--PAGINAÇÃO-->
                <div class="well corWell text-center"> 
                    <?php
                    $pager->ExePaginator("cdr_sms", $campos, "WHERE sms_date >= '{$dataIni}' AND sms_date <= '{$datafinal}' ORDER BY sms_date ASC");
                    echo $pager->getPaginator();
                    ?>
                </div>

                <!--PAINEL RESUMO ESTATISTICO-->
                <div class="col-md-6 col-md-offset-3 mg20B">
                    <div class="panel panel-primary">
                        <div class="panel-heading text-center">
                            <h3 class="panel-title">RESUMO</h3>
                        </div>
                        <div class="panel-body">
                            <!--Tabala-->                            
                            <table class="table table-condensed table-striped">
                                <!--<caption>Legenda de tabela opcional.</caption>-->
                                <thead>
                                    <tr>                                            
                                        <th>DESCRIÇÃO</th>
                                        <th>TOTAL</th>                                            
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>                                            
                                        <td>Total SMS</td>
                                        <td><?php echo (!empty($registros)) ? $registros : 0;?></td>                                           
                                    </tr>
                                    <tr>                                            
                                        <td>Entregue</td>
                                        <td><?php echo (!empty($entregue)) ? $entregue : 0; ?></td>                                            
                                    </tr>
                                    <tr>                                            
                                        <td>Enviado</td>
                                        <td><?php echo (!empty($enviado)) ? $enviado : 0; ?></td>                                            
                                    </tr>
                                    <tr>                                            
                                        <td>Inserido</td>
                                        <td><?php echo (!empty($inserido)) ? $inserido : 0; ?></td>                                            
                                    </tr>
                                    <tr>                                            
                                        <td>Nao Entregavel</td>
                                        <td><?php echo (!empty($naoEntregavel)) ? $naoEntregavel : 0; ?></td>                                            
                                    </tr>
                                    <tr>                                            
                                        <td>Falhado</td>
                                        <td><?php echo (!empty($falhado)) ? $falhado : 0; ?></td>                                            
                                    </tr>
                                    <tr>                                            
                                        <td>Valor</td>
                                        <td>R$ <?php echo (!empty($valor)) ? $valor : "0,00"; ?></td>                                            
                                    </tr>
                                </tbody>
                            </table>                            
                            <!--fim tabela-->                            
                        </div>
                    </div>
                </div>
                <!--fim do painel resumo estatistico-->

            </div><!--fim panel-body-->
        </div><!-- fim painel primary -->      
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Operadora', 'Status SMS'],
            ['Entregue', <?php echo $entregue;?>],
            ['Enviado', <?php echo $enviado;?>],
            ['Inserido', <?php echo $inserido;?>],
            ['Falhou', <?php echo $falha;?>],
            ['Expirado', <?php echo $expirado;?>],
            ['Rejeitado', <?php echo $rejeitado;?>],
            ['Deletado', <?php echo $deletado;?>],
            ['Sem Saldo', <?php echo $semSaldo;?>],
            ['Não Entregue', <?php echo $naoEntregue;?>],
            ['Não Entregavel', <?php echo $naoEntregavel;?>]
        ]);

        var options = {
            title: 'Status de SMS',
            pieHole: 0.4,
            backgroundColor: '#fff'
        };

        var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
        chart.draw(data, options);
    }
</script>

<script type="text/javascript">
    google.charts.load("current", {packages: ['corechart']});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ["Operadoras", "SMS", {role: "style"}],
            ["Tim", <?php echo $tim;?>, "#273c75"],
            ["Vivo", <?php echo $vivo;?>, "#8c7ae6"],
            ["Oi", <?php echo $oi;?>, "#fbc531"],
            ["Claro", <?php echo $claro;?>, "#e84118"],
            ["Outros", <?php echo $outros;?>, "color: #e5e4e2"]
        ]);

        var view = new google.visualization.DataView(data);
        view.setColumns([0, 1,
            {calc: "stringify",
                sourceColumn: 1,
                type: "string",
                role: "annotation"},
            2]);

        var options = {
            title: "Relatório de SMS enviados por Operadora",            
            height: 250,
            bar: {groupWidth: "30%"},
            legend: {position: "none"},
        };
        var chart = new google.visualization.ColumnChart(document.getElementById("columnchart_values"));
        chart.draw(view, options);
    }
</script>
