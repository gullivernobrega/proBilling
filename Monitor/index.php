<?php
require 'autoload.php';
$Config = new Config();
$update = $Config->checkUpdate();
?>

<!--BLOCO 1-->
<div class="row mg10B">
    <!--SYSTEM-->
    <div class="col-md-6 col-lg-6">
        <div class="row">

            <div class="panel panel-primary panelMg">
                <div class="panel-heading">
                    <span class="size20">System</span> 
                    <a href="#" class="pull-right monitor" onclick="esm.reloadBlock('system');"><i class="fa fa-refresh size20"></i></a>
                </div>
                <div class="panel-body mg10B">

                    <table class="table table-striped table-hover table-condensed">
                        <tbody>
                            <tr>
                                <td>Hostname</td>
                                <td id="system-hostname"></td>
                            </tr>
                            <tr>
                                <td>OS</td>
                                <td id="system-os"></td>
                            </tr>
                            <tr>
                                <td>Kernel version</td>
                                <td id="system-kernel"></td>
                            </tr>
                            <tr>
                                <td>Uptime</td>
                                <td id="system-uptime"></td>
                            </tr>
                            <tr>
                                <td>Last boot</td>
                                <td id="system-last_boot"></td>
                            </tr>
                            <tr>
                                <td>Current user(s)</td>
                                <td id="system-current_users"></td>
                            </tr>
                            <tr>
                                <td>Server date & time</td>
                                <td id="system-server_date"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

        </div><!--fim row-->
    </div>

    <!--LOAD AVERAGE-->
    <div class="col-md-6">                        
        <div class="row">

            <div class="panel panel-primary panelMg">
                <div class="panel-heading">
                    <span class="size20">Load Average</span> 
                    <a href="#" class="pull-right monitor" onclick="esm.reloadBlock('load_average');"><i class="fa fa-refresh size20"></i></a>  
                </div>
                <div class="panel-body mg10B">

                    <div class="col-md-4">
                        <div class="text-center">
                            <h3>1 min</h3>                                        
                            <input type="text" class="gauge" id="load-average_1" value="0" data-height="100" data-width="150" data-min="0" data-max="100" data-readOnly="true" data-fgColor="#07c357" data-angleOffset="-90" data-angleArc="180">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h3>15 min</h3>                                        
                            <input type="text" class="gauge" id="load-average_15" value="0" data-height="100" data-width="150" data-min="0" data-max="100" data-readOnly="true" data-fgColor="#07c357" data-angleOffset="-90" data-angleArc="180">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <h3>5 min</h3>                                        
                            <input type="text" class="gauge" id="load-average_5" value="0" data-height="100" data-width="150" data-min="0" data-max="100" data-readOnly="true" data-fgColor="#07c357" data-angleOffset="-90" data-angleArc="180">
                        </div>
                    </div>

                </div>
            </div>


        </div><!--fim row-->
    </div>  
</div>

<!--BLOCO 2-->
<div class="row mg10B">
    <!--NETWORK USAGE-->
    <div class="col-md-6">
        <div class="row">    
            <div class="panel panel-primary panelMg">
                <div class="panel-heading">
                    <span class="size20">Network usage</span>
                    <a href="#" class="pull-right monitor" onclick="esm.reloadBlock('network');"><i class="fa fa-refresh size20"></i></a>
                </div>
                <div class="panel-body mg10B">

                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr>
                                <th class="w15p">Interface</th>
                                <th class="w20p">IP</th>
                                <th>Receive</th>
                                <th>Transmit</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
        </div>

    </div>
    <!--CPU-->
    <div class="col-lg-6">
        <div class="row">    
            <div class="panel panel-primary panelMg">
                <div class="panel-heading">
                    <span class="size20">CPU</span>
                    <a href="#" class="pull-right monitor" onclick="esm.reloadBlock('cpu');"><i class="fa fa-refresh size20"></i></a>                                        
                </div>
                <div class="panel-body mg10B">

                    <table class="table table-striped table-hover table-condensed">
                        <tbody>
                            <tr>
                                <td>Model</td>
                                <td id="cpu-model"></td>
                            </tr>
                            <tr>
                                <td>Cores</td>
                                <td id="cpu-num_cores"></td>
                            </tr>
                            <tr>
                                <td>Speed</td>
                                <td id="cpu-frequency"></td>
                            </tr>
                            <tr>
                                <td>Cache</td>
                                <td id="cpu-cache"></td>
                            </tr>
                            <tr>
                                <td>Bogomips</td>
                                <td id="cpu-bogomips"></td>
                            </tr>
                            <?php if ($Config->get('cpu:enable_temperature')): ?>
                                <tr>
                                    <td>Temperature</td>
                                    <td id="cpu-temp"></td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</div>

<!--BLOCO 3-->
<div class="row mg10B">
    <!--DISK USAGE-->
    <div class="col-lg-12">
        <div class="row">    
            <div class="panel panel-primary panelMg">
                <div class="panel-heading">
                    <span class="size20">Disk usage</span>                                        
                    <a href="#" class="pull-right monitor" onclick="esm.reloadBlock('disk');"><i class="fa fa-refresh size20"></i></a>
                </div>
                <div class="panel-body mg10B">

                    <table class="table table-striped table-hover table-condensed">
                        <thead>
                            <tr>
                                <?php if ($Config->get('disk:show_filesystem')): ?>
                                    <th class="w10p filesystem">Filesystem</th>
                                <?php endif; ?>
                                <th class="w20p">Mount</th>
                                <th>Use</th>
                                <th class="w15p">Free</th>
                                <th class="w15p">Used</th>
                                <th class="w15p">Total</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</div>

<!--BLOCO 4-->
<div class="row mg10B">
    <!--CPU-->
    <div class="col-lg-6">
        <div class="row">    
            <div class="panel panel-primary panelMg">
                <div class="panel-heading">
                    <span class="size20">Memory</span>
                    <a href="#" class="pull-right monitor" onclick="esm.reloadBlock('memory');"><i class="fa fa-refresh size20"></i></a>                                        
                </div>
                <div class="panel-body mg10B">

                    <table class="table table-striped table-hover table-condensed">
                        <tbody>
                            <tr>
                                <td class="w20p">Used %</td>
                                <td><div class="progressbar-wrap"><div class="progressbar" style="width: 0%;">0%</div></div></td>
                            </tr>
                            <tr>
                                <td class="w20p">Used</td>
                                <td id="memory-used"></td>
                            </tr>
                            <tr>
                                <td class="w20p">Free</td>
                                <td id="memory-free"></td>
                            </tr>
                            <tr>
                                <td class="w20p">Total</td>
                                <td id="memory-total"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!--SWAP-->
    <div class="col-lg-6">
        <div class="row">    
            <div class="panel panel-primary panelMg">
                <div class="panel-heading">
                    <span class="size20">Swap</span>
                    <a href="#" class="pull-right monitor" onclick="esm.reloadBlock('swap');"><i class="fa fa-refresh size20"></i></a>                                        
                </div>
                <div class="panel-body mg10B">

                    <table class="table table-striped table-hover table-condensed">
                        <tbody>
                            <tr>
                                <td class="w20p">Used %</td>
                                <td><div class="progressbar-wrap"><div class="progressbar" style="width: 0%;">0%</div></div></td>
                            </tr>
                            <tr>
                                <td class="w20p">Used</td>
                                <td id="swap-used"></td>
                            </tr>
                            <tr>
                                <td class="w20p">Free</td>
                                <td id="swap-free"></td>
                            </tr>
                            <tr>
                                <td class="w20p">Total</td>
                                <td id="swap-total"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<!--BLOCO 5-->
<div class="row mg10B">

    <!--LAST LOGIN-->
    <div class="col-lg-4">
        <div class="row">    
            <div class="panel panel-primary panelMg">
                <div class="panel-heading">
                    <span class="size20">Last login</span>
                    <a href="#" class="pull-right monitor" onclick="esm.reloadBlock('last_login');"><i class="fa fa-refresh size20"></i></a>
                </div>
                <div class="panel-body mg10B">

                    <?php if ($Config->get('last_login:enable') == true): ?>
                        <table>
                            <tbody></tbody>
                        </table>
                    <?php else: ?>
                        <p>Disabled</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <!--PING-->
    <div class="col-lg-4">
        <div class="row">    
            <div class="panel panel-primary panelMg">
                <div class="panel-heading">
                    <span class="size20">Ping</span>
                    <a href="#" class="pull-right monitor" onclick="esm.reloadBlock('ping');"><i class="fa fa-refresh size20"></i></a>
                </div>
                <div class="panel-body mg10B">

                    <table class="table table-striped table-hover table-condensed">
                        <tbody></tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

    <!--SERVICES STATUS-->
    <div class="col-lg-4">
        <div class="row">    
            <div class="panel panel-primary panelMg">
                <div class="panel-heading">
                    <span class="size20">Services status</span>
                    <a href="#" class="pull-right monitor" onclick="esm.reloadBlock('services');"><i class="fa fa-refresh size20"></i></a>
                </div>
                <div class="panel-body mg10B">

                    <table class="table table-striped table-hover table-condensed">
                        <tbody></tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

</div>
