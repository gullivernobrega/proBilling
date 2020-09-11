<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of router_billing
 *
 * @author Gulliver Nóbrega
 */
class router_billing {
    //put your code here
   
    
    public function router($param) {
        
        $rota['fixo'] = array("{$param['rota_tronco_tipo_fixo_m']}/{$param['rota_tronco_fixo_m']}", "{$param['rota_tronco_tipo_fixo_b1']}/{$param['rota_tronco_fixo_b1']}", "{$param['rota_tronco_tipo_fixo_b2']}" / "{$param['rota_tronco_fixo_b2']}");
        $rota['movel'] = array("{$param['rota_tronco_tipo_movel_m']}/{$param['rota_tronco_movel_m']}", "{$param['rota_tronco_tipo_movel_b1']}/{$param['rota_tronco_movel_b1']}", "{$param['rota_tronco_tipo_movel_b2']}" / "{$param['rota_tronco_movel_b2']}");
        $rota['inter'] = array("{$param['rota_tronco_tipo_inter_m']}/{$param['rota_tronco_inter_m']}", "{$param['rota_tronco_tipo_inter_b1']}/{$param['rota_tronco_inter_b1']}", "{$param['rota_tronco_tipo_inter_b2']}" / "{$param['rota_tronco_inter_b2']}");
        //Limpando indice de array vazio
        $rota['fixo'] = array_filter($rota['fixo']);
        $rota['movel'] = array_filter($rota['movel']);
        $rota['inter'] = array_filter($rota['inter']);
       //Return para arquivo
        return $rota;;
    }
}
