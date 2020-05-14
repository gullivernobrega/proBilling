<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;

## Informe se o tipo para url (0 ou 1): 0 com proBilling no URL e 1 sem proBilling. ##
$urlTipo = 0;
if ($urlTipo == 0):
    $graficoTotalStatus = "http://" . IP . "/graficos/grfStatusAgents.php";
else:
    $graficoTotalStatus = "http://" . IP . "/proBilling/graficos/grfStatusAgents.php";
endif;

## instancia do obj call Center ##
$callCenter = new Callcenter;
?>

<!--ATUALIZAÇOES DO CALLCENTER-->                    
<script type="text/javascript">

    setInterval("atualizaGrafico();", 3000);
    function atualizaGrafico() {
        //$('#ativa').load(location.href + ' #ativa');
        $('#status').load(location.href + ' #status');
        $('#status1').load(location.href + ' #status1');
        $('#status2').load(location.href + ' #status2');
        $('#status3').load(location.href + ' #status3');
        $('#espera').load(location.href + ' #espera');
        $('#grafico').load(location.href + drawChartTeste());
    }

</script> 

<div class="conteudo">
    <div class="top">
        <h1 class="tit">Call Center <small>Listagem</small></h1>
    </div> 
    <div class="container-fluid">

        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-list"></i> Dashboard </h3>
            </div>
            <div class="panel-body txtblue mg10B">                

                <!--FORMULARIO DE CAMPANHAS-->
                <div class="well baseA pd5B">
                    <form  class="form-horizontal" name="dashboard" method="post" action="?exe=relatorio/callcenter/dashboard/listaB">
                        <div class="form-group"> 
                            <div class="col-md-6"> 
                                <select class="form-control" name="campanha" id="campanha" required>
                                    <option value="">Campanhas</option>
                                    <?php
                                    $campanha = new Read;
                                    $campanha->ExeRead("campanha", "WHERE campanha_tipo = 'D' AND campanha_status = 'A'");

                                    if (!$campanha->getResult()):
                                        echo '<option disabled="disabled" value="NULL">Cadastre antes uma Campanha!</option>';
                                    else:
                                        foreach ($campanha->getResult() as $value):
                                            //passa o id e o tipo 
                                            echo "<option value=\"{$value['campanha_id']}\" ";

                                            if (!empty($DataCampanha['campanha']) && $DataCampanha['campanha'] == $value['campanha_id']):
                                                echo ' selected = "selected" ';
                                            endif;

                                            echo ">{$value['campanha_nome']}</option>";
                                        endforeach;
                                    endif;
                                    ?>               
                                </select>
                            </div> 
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-warning" name="btnCampanha" value="btnCampanha">Selecianar Campanha</button>
                            </div>
                        </div>

                    </form> 
                </div>

                <?php
                // if (empty($objQueues)):           
                ## Busca resultados das chamadas em espera (Filas); ##
                $chamdaEmEspera = $callCenter->getFilas();
                ## busca dos Satatus dos agentes ##
                $objStatus = $callCenter->getStatus();
                ## Retorno do total dos estatus agents ##
                $objTotalStatus = $callCenter->getTotalPause();
                ?>

                <div class="row">
                    <div class="col-md-12">
                        <div class="well base pd0T pd0B border3a">
                            <div class="panel panel-default ">
                                <!-- Default panel contents -->
                                <div class="panel-heading ">
                                    <div class="container">
                                        <div class="row">
                                            <span class="titPanel">Estatísticas</span>   
                                            <ul class="nav nav-tabs pull-right">
                                                <li class="active"><a data-toggle="tab" href="#grupo">Grupo</a></li>
                                                <li><a data-toggle="tab" href="#fila">Fila</a></li>                                            
                                            </ul>                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="panel-body scroll">
                                    <!--DE GRUPO-->
                                    <div class="tab-content">
                                        <div id="grupo" class="tab-pane fade in active">
                                            <!--Tabela de grupo-->                                            
                                            <table class="table table-hover table-condensed " name="tabGrupo">
                                                <caption>Estatística de Grupo.</caption>
                                                <thead>
                                                    <tr>
                                                        <th>Fila</th>
                                                        <th>Prioridade</th>
                                                        <th>Ch. Atendidas</th>
                                                        <th>Ch. sem Resp.</th>
                                                        <th>Nível de Serv.</th>
                                                        <th>Periodo</th>
<!--                                                            <th>% Atend. </th>
                                                        <th>Aband. D-KPI</th>
                                                        <th>Aband. F-KPI</th>
                                                        <th>Aband. Total</th>
                                                        <th>% Aband. </th>
                                                        <th>Transb. </th>
                                                        <th>TMA </th>
                                                        <th>TME AT </th>
                                                        <th>TME AB </th>-->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    ## Alimentando Estatísticas Queue##

                                                    $Filas = new Read;
                                                    $Filas->ExeRead("queues", "");
                                                    $resultFilas = $Filas->getResult();
                                                    
                                                    foreach ($resultFilas as $value) {

                                                        $queue_Asterisk = new AsteriskAgi();
                                                        $queue_Asterisk->QueueAsteriskStatistic($value['queue_name']);
                                                        $resultQueue = $queue_Asterisk->getResult();
                                                   
                                                     ?>
                                                    
                                                    
                                                    <tr>
                                                        <th scope="row"><?php echo $value['queue_name']; ?></th>
                                                        <td><?php $prio = explode(':', $resultQueue[2]);
                                                    echo $prio[1];
                                                    ?></td>
                                                        <td><?php $aten = explode(':', $resultQueue[3]);
                                                    echo $aten[1];
                                                    ?></td>
                                                        <td><?php $semRes = explode(':', $resultQueue[4]);
                                                    echo $semRes[1];
                                                    ?></td>
                                                        <td><?php
                                                            $nvelServ = explode(':', $resultQueue[5]);
                                                            $nvelServ = explode('within', $nvelServ[1]);
                                                            echo $nvelServ[0];
                                                            ?></td>
                                                        <td><?php echo $nvelServ[1];?></td>

                                                    </tr> <?php  } ?>   
                                                </tbody>
                                            </table>
                                        </div>
                                        <!--DE FILA-->
                                        <div id="fila" class="tab-pane fade ">
                                            <!--Tabela de filas-->
                                            <table class="table table-hover table-condensed" name="tabFila">
                                                <caption>Estatística de Fila.</caption>
                                                <thead>
                                                    <tr>
                                                        <th>Fila</th>
                                                        <th>% NS</th>
                                                        <th>Receb.</th>
                                                        <th>Atend. D-KPI</th>
                                                        <th>Atend. F-KPI</th>
                                                        <th>Atend. Total</th>
                                                        <th>% Atend. </th>
                                                        <th>Aband. D-KPI</th>
                                                        <th>Aband. F-KPI</th>
                                                        <th>Aband. Total</th>
                                                        <th>% Aband. </th>
                                                        <th>Transb. </th>
                                                        <th>TMA </th>
                                                        <th>TME AT </th>
                                                        <th>TME AB </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>Mark</td>
                                                        <td>Otto</td>
                                                        <td>@mdo</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>                                        
                                    </div>  
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>
                <!--CLIENTES EM ESPERA-->
                <div class="row">
                    <div class="col-md-6">
                        <div class="well base pd0T pd0B border3a">
                            <div class="panel panel-default">
                                <!-- Default panel contents -->
                                <div class="panel-heading titPanel">Clientes em Espera</div>
                                <div class="panel-body scroll">

                                    <ul class="nav nav-tabs nav-justified">
                                        <li  class="active"><a data-toggle="tab" href="#detFila">Detalhado por Fila</a></li>
                                        <li><a data-toggle="tab" href="#agrGrupo">Agrupado por Grupo</a></li>
                                        <li><a data-toggle="tab" href="#agrFila">Agrupado por Fila</a></li>
                                    </ul>
                                    <!--DETALHADO POR FILA-->
                                    <div class="tab-content">                                        
                                        <div id="detFila" class="tab-pane fade in active">
                                            <div id="espera">
                                                <!--Tabela de filas-->
                                                <table class="table table-hover table-condensed" name="cliEspera">   
                                                    <caption>Detalhado por Fila.</caption>
                                                    <thead>
                                                        <tr>
                                                            <th>Fila</th>                                                        
                                                            <th>Telefone</th>
                                                            <th>Espera</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
<?php
if (!empty($chamdaEmEspera)):
    foreach ($chamdaEmEspera as $ceVal):
        extract($ceVal);
        ?>
                                                                <tr >
                                                                    <td><?php echo $fila; ?></th>
                                                                    <td><?php echo $numero; ?></td>
                                                                    <td class="txtVerde"><?php echo "<strong>{$tempo}</strong>"; ?></td>                                                        
                                                                </tr>
        <?php
    endforeach;
endif;
?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!--AGRUPADO POR GRUPO-->
                                        <div id="agrGrupo" class="tab-pane fade">

                                            <!--Tabela de filas-->
                                            <table class="table table-hover table-condensed" name="agrGrupo">  
                                                <caption>Agrupado por Grupo.</caption>
                                                <thead>
                                                    <tr>
                                                        <th>Grupo</th>
                                                        <th>Quantidade</th>
                                                        <th>Espera Máxima</th>                                                        
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>Mark</td>
                                                        <td>Otto</td>                                                        
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <!--AGRUPADO POR FILA-->
                                        <div id="agrFila" class="tab-pane fade">
                                            <!--Tabela de filas-->
                                            <table class="table table-hover table-condensed" name="agrFila">  
                                                <caption>Agrupado por Fila</caption>
                                                <thead>
                                                    <tr>
                                                        <th>Fila</th>
                                                        <th>Quantidade</th>
                                                        <th>Espera Máxima</th>  
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>Mark</td>
                                                        <td>Otto</td>                                                        
                                                    </tr>
                                                    <tr>
                                                        <th scope="row">1</th>
                                                        <td>Mark</td>
                                                        <td>Otto</td>                                                        
                                                    </tr>                                                
                                                </tbody>
                                            </table>
                                        </div>
                                    </div><!--fecha a tab content-->
                                </div><!--fecha a panel body-->                            
                            </div>
                        </div>
                    </div>
                    <!--GRAFICO STATUS DOS AGENTS-->
                    <div class="col-md-6">
                        <div class="well base pd0T pd0B border3a">
                            <div class="panel panel-default">
                                <!-- Default panel contents -->
                                <div class="panel-heading titPanel">Status dos Agents</div>
                                <div class="panel-body scroll">       
                                    <div id="piechart_3d" style="width: 100%; height: 220px;"></div> 
                                </div>                            
                            </div>
                        </div>
                    </div>
                </div>
                <!--ESTADO DOS AGENTS-->                
                <div class="row">
                    <div class="col-md-12">
                        <div class="well base pd0T pd0B border3a">
                            <div class="panel panel-default ">
                                <!-- Default panel contents -->
                                <div class="panel-heading titPanel">Estado dos Agents</div>
                                <div class="panel-body ">

                                    <!--Links agents-->
                                    <ul class="nav nav-pills mg10B">
                                        <li class="active">
                                            <a data-toggle="tab" href="#total" title="Total"><i class="fa fa-text-width size15"></i></a>
                                        </li>
                                        <li>                                            
                                            <a data-toggle="tab" href="#falando" title="Agents Falando" ><i class="fa fa-volume-up size20"></i></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#livre" title="Agentes Livres" ><i class="fa fa-volume-off size20"></i></a>
                                        </li>
                                        <li>
                                            <a data-toggle="tab" href="#pause" title="Agents em Pause"><i class="fa fa-pause size15"></i></a>
                                        </li>
                                    </ul>


                                    <!--Inicio da Content-->
                                    <div class="tab-content">

                                        <!--Agents Total-->
                                        <div class="tab-pane fade in active" id="total">                                           
                                            <div class=" baseTable scrollA" >
                                                <div id="status">
                                                    <!--Tabela de filas-->
                                                    <table width="100%" class="table table-hover table-condensed tabela" name="tabAgents">
                                                        <!--<caption>Estatística de Fila.</caption>-->
                                                        <thead>
                                                            <tr>                                                    
                                                                <th>Agente</th>
                                                                <th>Ramal</th>
                                                                <th>Status</th>
                                                                <th>Pausa Manual</th> 
                                                                <th>Fila</th>
                                                                <th>Telefone</th>
                                                                <th>Cliente</th>
                                                                <th>Codigo</th>                                                    
                                                                <th>Duração</th>
                                                                <th>Tempo Logado</th>                             
                                                                <th width="68">Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody >
<?php
if (!empty($objStatus)):

    foreach ($objStatus as $agsVal):
        extract($agsVal);

        if ($status === "Deslogado"):
            $cor = "trRoxo";
        elseif ($status === "Disponível"):
            $cor = "trVerde";
        elseif ($status === "Em Atendimento"):

            //$cor = "trAzul";
            if ($tempo >= "0h5m00s"): //00:05:00                                                                            
                $cor = "trAqua";
            else:
                $cor = "trAzul";
            endif;

        elseif ($status === "Em Pausa"):
            //$cor = "trRed"; //para pause

            if ($pausa_manual == "Evento"):
                $cor = "trLaranja";
            elseif ($pausa_manual == "Banheiro"):
                $cor = "trPupura";
            elseif ($pausa_manual == "Lanche"):
                $cor = 'trAmarelo';
            elseif ($pausa_manual == "Descompressão"):
                $cor = 'trVerdeAgua';
            elseif ($pausa_manual == "Retorno"):
                $cor = 'trVerdeLimao';
            elseif ($pausa_manual == "Acordo"):
                $cor = 'trVerdeMusgo';
            elseif ($pausa_manual == "Intervalo-banco de horas"):
                $cor = 'trLilas';
            else:
                $cor = "trRed";
            endif;



        endif;
        ?>
                                                                    <tr>
                                                                        <td class="<?php echo $cor; ?>"><?php echo $agente; ?></td>
                                                                        <td class="<?php echo $cor; ?>"><?php echo $ramal; ?></td>
                                                                        <td class="<?php echo $cor; ?>"><?php echo $status; ?></td>
                                                                        <td class="<?php echo $cor; ?>"><?php echo $pausa_manual; ?></td> 
                                                                        <td class="<?php echo $cor; ?>"><?php echo $fila; ?></td>
                                                                        <td class="<?php echo $cor; ?>"><?php echo $numero; ?></td>
                                                                        <td class="<?php echo $cor; ?>"><?php echo $nome; ?></td>
                                                                        <td class="<?php echo $cor; ?>"><?php echo $codigo; ?></td>
                                                                        <td class="<?php echo $cor; ?>"><?php echo $pause_date; ?></td>
                                                                        <td class="<?php echo $cor; ?>"><?php echo $tempo_logado; ?></td>                                                                        
                                                                        <!--ações-->
                                                                        <td>                                                                
                                                                            <a data-toggle="tooltip" data-placement="left" href="" title="Deslogar"><i class="fa fa-sign-out"></i></a>
                                                                            <a data-toggle="tooltip" data-placement="left" href="" title="Monitoria Sigilosa"><i class="fa fa-headphones"></i></a>
                                                                            <a data-toggle="tooltip" data-placement="left" href="" title="Monitoria Interativa"><i class="fa fa-microphone"></i></a>
                                                                            <a data-toggle="tooltip" data-placement="left" href="" title="Desbloquear Pausa"><i class="fa fa-unlock-alt"></i></a>
                                                                        </td>
                                                                    </tr>
        <?php
    endforeach;
else:
    KLErro("<b>Total</b> - Não Existe Dados Status Agents!", KL_ALERT);
endif;
?>
                                                        </tbody>
                                                    </table>
                                                    <!--fim tabela-->
                                                </div>
                                            </div>
                                        </div>

                                        <!--Agents Falando-->
                                        <div class="tab-pane fade" id="falando" >
                                            <div class=" baseTable scrollA">
                                                <div id="status1">

                                                    <p class="">Total em Atendimento: <span class="label label-primary"><?php echo $objTotalStatus['tEmAtendimento']; ?></span></p>
                                                    <!--Tabela de filas-->
                                                    <table width="100%" class="table table-hover table-condensed tabela" name="tabAgents">
                                                        <!--<caption>Estatística de Fila.</caption>-->
                                                        <thead>
                                                            <tr>                                                    
                                                                <th>Agente</th>
                                                                <th>Ramal</th>
                                                                <th>Status</th>
                                                                <th>Pausa Manual</th>
                                                                <th>Fila</th>
                                                                <th>Telefone</th>
                                                                <th>Cliente</th>
                                                                <th>Codigo</th>                                                    
                                                                <th>Duração</th>
                                                                <th>Tempo Logado</th>                              
                                                                <th width="68">Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody >
<?php
if (!empty($objStatus)):

    foreach ($objStatus as $agsVal):
        extract($agsVal);
        if ($status === "Em Atendimento"):

            //$novoTempo =  DateTime::createFromFormat('H:i:s', $tempo); 
            if ($tempo >= "0h5m00s"): //00:05:00                                                                            
                $cor = "trAqua";
            else:
                $cor = "trAzul";
            endif;
            ?>
                                                                        <tr>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $agente; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $ramal; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $status; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $pausa_manual; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $fila; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $numero; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $nome; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $codigo; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $tempo; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $tempo_logado; ?></td>                                                                         
                                                                            <!--ações-->
                                                                            <td>                                                                
                                                                                <a data-toggle="tooltip" data-placement="left" href="" title="Deslogar"><i class="fa fa-sign-out"></i></a>
                                                                                <a data-toggle="tooltip" data-placement="left" href="" title="Monitoria Sigilosa"><i class="fa fa-headphones"></i></a>
                                                                                <a data-toggle="tooltip" data-placement="left" href="" title="Monitoria Interativa"><i class="fa fa-microphone"></i></a>
                                                                                <a data-toggle="tooltip" data-placement="left" href="" title="Desbloquear Pausa"><i class="fa fa-unlock-alt"></i></a>
                                                                            </td>
                                                                        </tr>
            <?php
        endif;
    endforeach;
endif;
?>
                                                        </tbody>
                                                    </table>
                                                    <!--fim tabela-->
                                                </div>
                                            </div>
                                        </div>

                                        <!--Agents Livres-->
                                        <div class="tab-pane fade" id="livre" >
                                            <div class=" baseTable scrollA">
                                                <div id="status2">

                                                    <p>Total Disponivel: <span class="label label-success"><?php echo $objTotalStatus['tDisponivel']; ?></span></p>
                                                    <!--Tabela de filas-->
                                                    <table width="100%" class="table table-hover table-condensed " name="tabAgents">
                                                        <!--<caption>Estatística de Fila.</caption>-->
                                                        <thead>
                                                            <tr>                                                    
                                                                <th>Agente</th>
                                                                <th>Ramal</th>
                                                                <th>Status</th>
                                                                <th>Duração</th>
                                                                <th>Tempo Logado</th>                              
                                                                <th width="68">Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody >
<?php
if (!empty($objStatus)):
    foreach ($objStatus as $agsVal):
        extract($agsVal);
        if ($status === "Disponível"):
            $cor = "trVerde";
            ?>
                                                                        <tr>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $agente; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $ramal; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $status; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $tempo; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $tempo_logado; ?></td>                                                                         
                                                                            <!--ações-->
                                                                            <td>                                                                
                                                                                <a data-toggle="tooltip" data-placement="left" href="" title="Deslogar"><i class="fa fa-sign-out"></i></a>
                                                                                <a data-toggle="tooltip" data-placement="left" href="" title="Monitoria Sigilosa"><i class="fa fa-headphones"></i></a>
                                                                                <a data-toggle="tooltip" data-placement="left" href="" title="Monitoria Interativa"><i class="fa fa-microphone"></i></a>
                                                                                <a data-toggle="tooltip" data-placement="left" href="" title="Desbloquear Pausa"><i class="fa fa-unlock-alt"></i></a>
                                                                            </td>
                                                                        </tr>
            <?php
        endif;
    endforeach;
endif;
?>
                                                        </tbody>
                                                    </table>
                                                    <!--fim tabela-->

                                                </div>
                                            </div>
                                        </div>

                                        <!-------------------------------------------------->
                                        <!----------------Agents Em Pause------------------->
                                        <!-------------------------------------------------->
                                        <div class="tab-pane fade" id="pause" >
                                            <div class=" baseTable scrollA">                                                
                                                <div id="status3">

                                                    <p class="">Total em Pause: <span class="label label-danger"><?php echo $objTotalStatus['tPause']; ?></span></p> 
                                                    <!--Tabela de filas-->
                                                    <table width="100%" class="table table-hover table-condensed" name="tabAgents">
                                                        <!--<caption>Estatística de Fila.</caption>-->
                                                        <thead>
                                                            <tr>                                                    
                                                                <th>Agente</th>
                                                                <th>Ramal</th>
                                                                <th>Status</th>
                                                                <th>Pausa Manual</th> 
                                                                <th>Duração</th>
                                                                <th>Tempo Logado</th>                            
                                                                <th width="68">Ações</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody >
<?php
if (!empty($objStatus)):
    //varDump::exeVD($objStatus);
    foreach ($objStatus as $agsVal):
        extract($agsVal);

        if ($status === "Em Pausa"):
            //$cor = "trRed"; //para pause   

            if ($pausa_manual == "Evento"):
                $cor = "trLaranja";
            elseif ($pausa_manual == "Banheiro"):
                $cor = "trPupura";
            elseif ($pausa_manual == "Lanche"):
                $cor = 'trAmarelo';
            elseif ($pausa_manual == "Descompressão"):
                $cor = 'trVerdeAgua';
            elseif ($pausa_manual == "Retorno"):
                $cor = 'trVerdeLimao';
            elseif ($pausa_manual == "Acordo"):
                $cor = 'trVerdeMusgo';
            elseif ($pausa_manual == "Intervalo-banco de horas"):
                $cor = 'trLilas';
            else:
                $cor = "trRed";
            endif;
            ?>
                                                                        <tr>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $agente; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $ramal; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $status; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $pausa_manual; ?></td> 
                                                                            <td class="<?php echo $cor; ?>"><?php echo $pause_date; ?></td>
                                                                            <td class="<?php echo $cor; ?>"><?php echo $tempo_logado; ?></td>                                                                        
                                                                            <!--ações-->
                                                                            <td>                                                                
                                                                                <a data-toggle="tooltip" data-placement="left" href="" title="Deslogar"><i class="fa fa-sign-out"></i></a>
                                                                                <a data-toggle="tooltip" data-placement="left" href="" title="Monitoria Sigilosa"><i class="fa fa-headphones"></i></a>
                                                                                <a data-toggle="tooltip" data-placement="left" href="" title="Monitoria Interativa"><i class="fa fa-microphone"></i></a>
                                                                                <a data-toggle="tooltip" data-placement="left" href="" title="Desbloquear Pausa"><i class="fa fa-unlock-alt"></i></a>
                                                                            </td>
                                                                        </tr>
            <?php
        endif;
    endforeach;
endif;
?>
                                                        </tbody>
                                                    </table>
                                                    <!--fim tabela-->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!--fim da Content-->
                                </div>                            
                            </div>
                        </div>

                        <!--Legendas das pauses-->
                        <p class="text-left">
                            <i class="fa fa-circle legenda6"></i> Livre |
                            <i class="fa fa-circle legenda4"></i> Em atendimento |
                            <i class="fa fa-circle legenda5"></i> Em atendimento (>= 5 min.) |
                            <i class="fa fa-circle legenda"></i> Lanche |
                            <i class="fa fa-circle legenda1"></i> Banheiro |
                            <i class="fa fa-circle legenda7"></i> Evento |
                            <i class="fa fa-circle legenda8"></i> Descompressão |                                        
                            <i class="fa fa-circle legenda9"></i> Retorno | 
                            <i class="fa fa-circle legenda10"></i> Acordo | 
                            <i class="fa fa-circle legenda11"></i> Intervalo-banco de horas |
                            <i class="fa fa-circle legenda3"></i> Deslogado 
                        </p>

                    </div>  
                </div>




                <!--ESTATISTICA-->                
            </div><!--panel-body-->
        </div> <!--fecha painel-->
    </div>
</div>

<!--Grafico piechart_ google--> 
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<!--SCRIPT DO GRAFICO PIECHART_3D-->
<script type="text/javascript">

    // Load the Visualization API and the piechart package.
    //google.charts.load('current', {'packages': ['corechart']);
    google.charts.load('visualization', '1', {'packages': ['corechart']});
    // Set a callback to run when the Google Visualization API is loaded.
    google.charts.setOnLoadCallback(drawChartTeste);
    function drawChartTeste() {
        var jsonData = $.ajax({
            url: "<?php echo $graficoTotalStatus; ?>",
            dataType: "json",
            async: false
        }).responseText;
        var options = {
            title: 'Status dos Agents',
            backgroundColor: '#edf0fd',
            is3D: true,
            legend: {position: 'right', alignment: 'right'},
            chartArea: {left: '5%', right: 0, width: '80%', height: '75%'}
        };
        // Create our data table out of JSON data loaded from server.
        var data = new google.visualization.DataTable(jsonData);
        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
    }
</script>