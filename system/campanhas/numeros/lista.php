<?php
if (!class_exists('Login')):
    header("Location: ../../painel.php");
    die;
endif;
?>
<div class="conteudo">
    <div class="top">
        <h1 class="tit">Números <small>Listagem</small></h1>
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
                    /** PAGINAÇÃO */
                    $getPage = filter_input(INPUT_GET, "pg", FILTER_VALIDATE_INT);
                    $pager = new Pager("?exe=campanhas/numeros/lista&pg=");
                    $pager->ExePager($getPage, 20);

                    //LEITURA 
                    $read = new Read;
                    $read->ExeRead("numero", "ORDER BY numero_nome ASC LIMIT :limit OFFSET :offset", "limit={$pager->getLimit()}&offset={$pager->getOffset()}");
                    $verifica = $read->getRowCount();
                    $obj = $read->getResult();

                    //RESULTADO DA JANELA MODAL
                    $dataDel = filter_input(INPUT_POST, "numero_id", FILTER_VALIDATE_INT);
                    if (!empty($dataDel)):

                        $Deletar = new Numero;
                        $Deletar->ExeDelete($dataDel);

                        if ($Deletar->getResult()):
                            header("Location: painel.php?exe=campanhas/numeros/lista");
                        else:
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                        endif;
                    endif;

                    //MODAL APAGA TUDO
                    $DeletaTudo = filter_input(INPUT_POST, 'acao', FILTER_DEFAULT);
                    if (!empty($DeletaTudo)):
                        //if (!empty($DeletaTudo['acao']) && $DeletaTudo['acao'] == "del"):

                        $Deletar = new Numero;
                        $Deletar->ExeDeleteAll($DeletaTudo);

                        if ($Deletar->getResult()):
                            header("Location: painel.php?exe=campanhas/numeros/lista");
                        else:
                            $erro = $Deletar->getErro();
                            KLErro($erro[0], $erro[1]);
                        endif;

                    //endif;
                    endif;

                    //RESULTADO DA PESQUISA
                    $busca = filter_input_array(INPUT_POST, FILTER_DEFAULT);
                    if (!empty($busca['btnBusca'])):
                        unset($busca['btnBusca']);

                        if (!empty($busca['numero_fone']) || !empty($busca['numero_nome']) || !empty($busca['agenda_id']) || !empty($busca['numero_status'])):
                            header("Location: ?exe=campanhas/numeros/busca&numero_fone={$busca['numero_fone']}&numero_nome={$busca['numero_nome']}&agenda_id={$busca['agenda_id']}&numero_status={$busca['numero_status']}");
                            exit();
                        else:

                            KLErro("Ops, Falta parametros para a pesquisa", KL_INFOR);
                        endif;

                    endif;
                    ?>

                    <!--BASE PESQUISA-->  
                    <div class="well seach">
                        <h3>Busca</h3><p>Selecione um dos itens abaixo para pesquisa</p>    
                        <form class="form-inline" action=""  method="post" name="frmPesquisa" id="frmPesquisa" >
                            <!--<div class="loc">--> 
                            <div class="form-group form-group-sm">
                                <!--<div class="col-xs-2">-->
                                <label for="numero_fone">Número</label>
                                <input 
                                    class="form-control" 
                                    name="numero_fone" 
                                    id="numero_fone" 
                                    type="text" 
                                    placeholder="DDD+Numero" 
                                    value="<?php
                    if (isset($busca['numero_fone'])): echo $busca['numero_fone'];
                    endif;
                    ?>"                                     
                                    pattern = "[0-9]+$"
                                    maxlength="12"
                                    >                                 
                            </div>

                            <!--*******-->
                            <div class="form-group form-group-sm">                                
                                <input 
                                    class="form-control" 
                                    name="numero_nome" 
                                    id="numero_nome" 
                                    type="text" 
                                    placeholder="Nome do cliente" 
                                    value="<?php
                                    if (isset($busca['numero_nome'])): echo $busca['numero_nome'];
                                    endif;
                    ?>" >                                 
                            </div>  

                            <!--*******-->
                            <div class="form-group form-group-sm"> 
                                <select class="form-control" name="agenda_id" id="agenda">
                                    <option value="">Agendas</option>
<?php
$agenda = new Read;
$agenda->ExeRead("agenda");

if (!$agenda->getResult()):
    echo '<option disabled="disabled" value="NULL">Cadastre antes uma agenda!</option>';
else:
    foreach ($agenda->getResult() as $value):
        //passa o id e o tipo 
        echo "<option value=\"{$value['agenda_id']}\" ";

        if (!empty($busca['agenda_id']) && $busca['agenda_id'] == $value['agenda_id']):
            echo ' selected = "selected" ';
        endif;

        echo ">{$value['agenda_nome']}</option>";
    endforeach;
endif;
?>               
                                </select> 

                            </div>                            
                            <!--*******-->
                            <div class="form-group form-group-sm"> 
                                <select class="form-control" name="numero_status" id="numero_status" >
                                    <option value="">Informe Status</option>
                                    <option value="A" <?php if (!empty($busca) && $busca['numero_status'] == "A"): ?> selected="selected" <?php endif; ?> >Ativo</option>
                                    <option value="I" <?php if (!empty($busca) && $busca['numero_status'] == "I"): ?> selected="selected" <?php endif; ?>>Inativo</option>
                                    <option value="P" <?php if (!empty($busca) && $busca['numero_status'] == "P"): ?> selected="selected" <?php endif; ?>>Pendente</option>
                                    <option value="E" <?php if (!empty($busca) && $busca['numero_status'] == "E"): ?> selected="selected" <?php endif; ?>>Enviado</option>                                    
                                    <option value="B" <?php if (!empty($busca) && $busca['numero_status'] == "B"): ?> selected="selected" <?php endif; ?>>Bloqueado</option>                                    
                                </select>
                            </div>

                            <!--</button>-->
                            <!--<div class="col-xs-2">-->                                    
                            <button name="btnBusca" value="Buscar" type="submit" class="btn btn-warning btn-sm" title="Buscar" data-toggle="tooltip" data-placement="top"><i class="fa fa-search"></i> <strong>Localizar</strong></button>                            
                        </form>
                    </div>

                    <!--well botão-->
                    <div class="well">                        
                        <div class="text-right">
                            <a class="btn btn-default" href="painel.php?exe=campanhas/numeros/lista" role="button" title="Refresh"><i class="fa fa-refresh"></i></a>  
                            <a class="btn btn-success" href="painel.php?exe=campanhas/numeros/create" role="button" title="Novo"><i class="fa fa-file-o"></i> Novo Numero</a>                                                
                            <a class="btn btn-info" href="painel.php?exe=campanhas/numeros/createcsv" role="button" title="Importar CSV"><i class="fa fa-file-o"></i> Importar CSV</a>                                                
                            <a class="btn btn-danger" href="" role="button" data-toggle="modal" data-target="#deletarTudo" data-placement="top" title="Apagar Tudo"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>                                               
                            <a class="voltar" href="painel.php" role="button" title="Voltar"><span class="glyphicon glyphicon-share" aria-hidden="true"></span> Voltar</a>
                        </div>
                    </div>
                    
                    <!--Total geral-->
                    <?php
                    $tot = new Select;
                    $tot->ExeSelect("numero", "COUNT(numero_fone) AS Total");
                    $total = $tot->getResult();
                    extract($total[0]);                    
                    ?>
                    <h3>Total: <span class="label label-success"><?php echo $Total;?></span></h3>                    
                    
                    <!--tabela de listagem-->
                    <table class="table table-responsive table-hover hover-color txtblue"> 
                        <thead> 
                            <tr>   
                                <th width="15%">Número</th>
                                <th>Nome</th>                                                             
                                <th>Agenda</th>                                                             
                                <th width="10%">Status</th> 
                                <th width="7%">Ações</th> 
                            </tr> 
                        </thead> 
                        <tbody> 
<?php
if ($verifica > 0):

    foreach ($obj as $numero):
        extract($numero);

        $read = new Read;
        $read->ExeRead("agenda", "WHERE agenda_id = :id", "id={$agenda_id}");

        if (!empty($read->getResult())):
            $age = $read->getResult();
            extract($age[0]);
            $nome = (!empty($agenda_nome) ? $agenda_nome : null );
        endif;
        ?>
                                    <tr>
                                        <td scope="row"><?php echo $numero_fone; ?></td>                                         
                                        <td scope="row"><?php echo $numero_nome; ?></td> 
                                        <td scope="row"><?php echo $nome; ?></td>
                                        <td>
        <?php
        if ($numero_status == 'A'):
            echo "<span class='txtVerde' aria-hidden='true'>Ativo</span>";
        elseif ($numero_status == 'I'):
            echo "<span class='txtRed' aria-hidden='true'>Inativo</span>";
        elseif ($numero_status == 'P'):
            echo "<span class='txtOrange' aria-hidden='true'>Pendente</span>";
        elseif ($numero_status == 'E'):
            echo "<span class='txtAzul' aria-hidden='true'>Enviado</span>";
        elseif ($numero_status == 'B'):
            echo "<span class='txtRoxo' aria-hidden='true'>Bloqueado</span>";
        endif;
        ?>
                                        </td>  
                                        <td>
                                            <a href="painel.php?exe=campanhas/numeros/update&numero_id=<?php echo $numero_id ?>" data-toggle="tooltip" data-placement="top" title="Editar"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
                                            <a href="" data-toggle="modal" data-target="#numero_<?php echo $numero_id; ?>" data-placement="top" title="Apagar" class="del"><span class="glyphicon glyphicon-remove size20" aria-hidden="true"></span></a>                                    
                                        </td> 
                                    </tr>

                                    <!-- JANELA MODAL -->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="numero_<?php echo $numero_id; ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Dados </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                    <div class="form-group">  
                                                        <h4>Deseja realemente apagar o número: <?php echo "<b>{$numero_fone}</b>"; ?>? Clique em apagar dados ou cancelar.</h4>
                                                        <input type="hidden" class="form-control" id="numero" name="numero_id" value="<?php echo $numero_id; ?>">
                                                    </div>                 

                                                    <button type="submit" class="btn btn-success" name="confirmaDados_<?php echo $numero_id; ?>">Apagar Dados</button>
                                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                                                </form>   

                                            </div>                                            
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal --> 

                                <!-- JANELA MODAL APAGA TUDO-->                
                                <div class="modal fade" tabindex="-1" role="dialog" id="deletarTudo">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title">Apagar Tudo </h4>
                                            </div>
                                            <div class="modal-body">

                                                <form method="post" name="frmConfirme" action="" id="frmConfirme">                    
                                                    <div class="form-group">  
                                                        <h4>Deseja realemente apagar todos número? Clique em apagar dados ou cancelar.</h4>
                                                        <input type="hidden" class="form-control" id="acao" name="acao" value="del">
                                                    </div>                 

                                                    <button type="submit" class="btn btn-danger" name="confirmarTudo">Apagar Tudo</button>
                                                    <button type="button" class="btn btn-info" data-dismiss="modal">Cancelar</button>
                                                </form>   

                                            </div>                                            
                                        </div><!-- /.modal-content -->
                                    </div><!-- /.modal-dialog -->
                                </div><!-- /.modal -->                               

        <?php
    endforeach;
else:
    KLErro("Não existe Numero Cadastrado no momento!", KL_ALERT);
endif;
?>   
                        </tbody> 
                    </table>
                    <!--fim tabela-->
                    <!--PAGINAÇÃO-->
                    <div class="well corWell text-center">                     
<?php
$pager->ExePaginator("numero");
echo $pager->getPaginator();
?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>