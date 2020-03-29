<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;

define("VALORSMS", 0.032);
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Busca SMS <small>Listagem</small></h1>
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
                    $busca = filter_input_array(INPUT_GET, FILTER_DEFAULT);
                    
                    if (!empty($busca['exe'])):
                        unset($busca['exe']);
                    
                        //Verifica os parametros da pesquisa;
                        if(empty($busca['sms_numero'])): unset($busca['sms_numero']); endif;
                        if(empty($busca['sms_campanha'])): unset($busca['sms_campanha']); endif;
                        if(empty($busca['sms_operadora'])): unset($busca['sms_operadora']); endif;
                        if(empty($busca['sms_status'])): unset($busca['sms_status']); endif;
                        if(empty($busca['sms_lote'])): unset($busca['sms_lote']); endif;                    
 
                        //Inicializa as variaveis
                        $data = "";
                        $dataRead = "";
                        
                        //loop para preparar itens da pesquisa
                        foreach ($busca as $k => $v):

                            //motagem do data link e search
                            $data .= "{$k}={$v}&";

                            //verifico se a key é do fone 
                            if ($k == "di"):                                
                                $dataRead .= "sms_date >= '{$v}' AND ";
                            elseif ($k == "df"):
                                $dataRead .= "sms_date <= '{$v}' AND ";
                            else:
                                $dataRead .= "$k = '$v' AND ";                                
                            endif;
                            
                        endforeach;
                                                
                        //retira o ultimo "&" da linha de pesquisa
                        $data = substr($data, 0, -1);
                        $dataRead = substr($dataRead, 0, -4);  
                        
                        // Monta o link e a search
                        $link = "?exe=relatorio/sms/enviados/busca&{$data}&pg=";
                        $search = "?{$data}"; 

                    endif;

                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                    $pager = new Pager($link);
                    $pager->ExePager($getPage, 20);

                    $campos = "sms_id, sms_date, sms_date_atualizacao, sms_campanha, sms_operadora, sms_numero, sms_msg, sms_status, sms_lote";

                    //LEITURA DOS DADOS
                    $read = new Select;
                    $read->ExeSelect("cdr_sms", $campos, "WHERE {$dataRead} ORDER BY sms_date LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $termo = "WHERE {$dataRead}"; 
                   
                    ?>
                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Nova Busca</h3>    
                        <a class="btn btn-warning nb" href="?exe=relatorio/sms/enviados/lista" title="Nova Busca" data-toggle="tooltip" data-placement="top">Realizar uma Nova Busca</a>
                    </div>

                    <!--Gráficos-->
                    <?php if (!empty($read->getResult())): ?>
                        <div class="row mg10B">
                            <div class="col-md-5">
                                <div id="donutchart" style="width: 100%; height: 250px;"></div>
                            </div>
                            <div class="col-md-7">
                                <div id="columnchart_values" style="width: 100%; height: 250px;"></div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <!--Fim gráficos-->

                    <!--well botão-->
                    <div class="well text-right">                                                  
                        <a class="pull-left" href="system/relatorio/sms/enviados/busca_sms_excel.php<?php echo $search; ?>" title="Exportar Excel" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_excel.png" width="25"></a>                                
                        <a class="pull-left" href="system/relatorio/sms/enviados/busca_sms_pdf.php<?php echo $search; ?>" title="Exportar PDF" target="blank" data-toggle="tooltip" data-placement="top"><img src="icones/img_pdf.png" width="25"></a>
                        <!--<a class="btn btn-success" href="painel.php?exe=gerenciamento/ramal/iax/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Ramal IAX</a>-->
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
                            if (!empty($read->getResult())):
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
                                $total->ExeSelect("cdr_sms", $campos, $termo);
                                $registros = $total->getRowCount();
                                foreach ($total->getResult() as $vTotal):
                                    
                                    // Status de SMS  
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
                                    if ($vTotal['sms_operadora'] == 'TIM'):
                                        $tim = $tim + 1;
                                    endif;
                                    if ($vTotal['sms_operadora'] == 'VIVO'):
                                        $vivo = $vivo + 1;
                                    endif;
                                    if ($vTotal['sms_operadora'] == 'OI'):
                                        $oi = $oi + 1;
                                    endif;
                                    if ($vTotal['sms_operadora'] == 'CLARO'):
                                        $claro = $claro + 1;
                                    endif;
                                    if ($vTotal['sms_operadora'] == 'OUTROS'):
                                        $outros = $outros + 1;
                                    endif;

                                    //Calcula o valor total de SMS
                                    $soma = $enviado + $entregue + $naoEntregavel;
                                    $valor = number_format($soma * VALORSMS, 3, ",", ".");
                                    $falhado = $semSaldo + $falha + $naoEntregue + $expirado + $deletado + $rejeitado;

                                endforeach;                                

                                //Inseri o total de registros encontrados
                                echo "<h4>Total de registros encontrados: <b>{$registros}</b></h4>";

                                //var_dump($read->getRowCount());
                                foreach ($read->getResult() as $cdr_sms):
                                    extract($cdr_sms);
                                    $IP = "$_SERVER[SERVER_ADDR]";
                                    $data = explode(" ", $sms_date);
                                    $dt = explode("-", $data[0]);
                                    $dataAtual = "$dt[2]-$dt[1]-$dt[0]";

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
                                        <td><?php echo $sms_operadora ?></td> 
                                        <td><?php echo $sms_numero; ?></td> 
                                        <td><?php echo $status; ?></td> 
                                        <td>
                                            <!--<a href="<?php //echo $link1       ?>" target="blank"> <span class="glyphicon glyphicon-volume-up" aria-hidden="true"></span> </a>-->
                                            <!--<a href="painel.php?exe=gerenciamento/ramal/iax/update&iax_id=<?php //echo $iax_id                      ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>-->
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
                                                <!--        <button type="button" class="btn btn-primary">Apagar</button>-->
                                            </div>
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->

                                <?php
                            endforeach;
                        else:
                            KLErro("Não existe SMS cadastradas no momento!", KL_ALERT);
                        endif;
                        ?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                </div>

                <!--PAGINAÇÃO-->
                <div class="well corWell text-center">
                    <?php
                    $pager->ExePaginator("cdr_sms", $campos, "{$termo}");
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
                                        <th>TOTAIS</th>                                            
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>                                            
                                        <td>Total SMS</td>
                                        <td><?php echo (!empty($registros)) ? $registros : 0; ?></td>                                           
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
                                        <td>Não Entregável</td>
                                        <td><?php echo (!empty($naoEntregavel)) ? $naoEntregavel : 0; ?></td>                                            
                                    </tr>
                                    <tr>                                            
                                        <td>Falhadas</td>
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

            </div><!--panel-body-->
        </div>
    </div>
</div>

<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load("current", {packages: ["corechart"]});
    google.charts.setOnLoadCallback(drawChart);
    function drawChart() {
        var data = google.visualization.arrayToDataTable([
            ['Operadora', 'Status SMS'],
            ['Entregue', <?php echo $entregue; ?>],
            ['Enviado', <?php echo $enviado; ?>],
            ['Inserido', <?php echo $inserido; ?>],
            ['Falhou', <?php echo $falha; ?>],
            ['Expirado', <?php echo $expirado; ?>],
            ['Rejeitado', <?php echo $rejeitado; ?>],
            ['Deletado', <?php echo $deletado; ?>],
            ['Sem Saldo', <?php echo $semSaldo; ?>],
            ['Não Entregue', <?php echo $naoEntregue; ?>],
            ['Não Entregavel', <?php echo $naoEntregavel; ?>]
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
            ["Tim", <?php echo $tim; ?>, "#273c75"],
            ["Vivo", <?php echo $vivo; ?>, "#8c7ae6"],
            ["Oi", <?php echo $oi; ?>, "#fbc531"],
            ["Claro", <?php echo $claro; ?>, "#e84118"],
            ["Outros", <?php echo $outros; ?>, "color: #e5e4e2"]
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