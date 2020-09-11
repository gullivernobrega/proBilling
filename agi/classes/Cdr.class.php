<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cdr
 *
 * @author Gulliver Nóbrega
 */
class Cdr {

    private $cdrCallerId;
    private $cdrSrc;
    private $cdrDst;
    private $tronco;
    private $cdrTipo;
    private $cdrDcontext;
    private $cdrChannel;
    private $cdrDstchannel;
    private $cdrLastapp;
    private $cdrLastdata;
    private $cdrStart;
    private $cdrAnswer;
    private $cdrEnd;
    private $cdrDuration;
    private $cdrBillsec;
    private $cdrDisposition;
    private $cdrAmaflags;
    private $cdrAccountcode;
    private $cdrUniqueid;
    private $cdrUserfield;
    private $insertCdr;
    

    public function ExeCdr($agi, $tempoFalado = NULL, $agent = NULL, $tronco = NULL, $number = NULL, $src = NULL) {

        $cdrCallerId = $agi->get_variable("CDR(clid)");
        $this->cdrCallerId = $cdrCallerId['data'];

        if (!empty($agent)) {
            $this->cdrSrc = $agent;
        } elseif (!empty($src)) {
            $this->cdrSrc = $src;
        } else {
          $cdrSrc = $agi->get_variable("CDR(src)");
            $this->cdrSrc = $cdrSrc['data'];  
        }
            
        

        if (!empty($number)){
            $this->cdrDst = $number;
        } else {
           $cdrDst = $agi->get_variable("CDR(dst)");
           $this->cdrDst = $cdrDst['data']; 
        }
        
        
        
        
        if ($tronco) {
            $this->tronco = $tronco;   
        } else {
            $this->tronco = 'Unknow';
        }
        
        $cdrTipo = $agi->get_variable("CDR(tipo)");
        $this->cdrTipo = $cdrTipo['data'];
        $cdrDcontext = $agi->get_variable("CDR(dcontext)");
        $this->cdrDcontext = $cdrDcontext['data'];
        $cdrChannel = $agi->get_variable("CDR(channel)");
        $this->cdrChannel = $cdrChannel['data'];
        $cdrDstchannel = $agi->get_variable("CDR(dstchannel)");
        $this->cdrDstchannel = $cdrDstchannel['data'];
        $cdrLastapp = $agi->get_variable("CDR(lastapp)");
        $this->cdrLastapp = $cdrLastapp['data'];
        $cdrLastdata = $agi->get_variable("CDR(lastdata)");
        $this->cdrLastdata = $cdrLastdata['data'];
        $cdrStart = $agi->get_variable("CDR(start)");
        $this->cdrStart = $cdrStart['data'];
        $cdrAnswer = $agi->get_variable("CDR(answer)");
        $this->cdrAnswer = $cdrAnswer['data'];
        $cdrEnd = $agi->get_variable("CDR(end)");
        $this->cdrEnd = $cdrEnd['data'];
        $cdrDuration = $agi->get_variable("CDR(duration)");
        $this->cdrDuration = $cdrDuration['data'];

        if (!empty($tempoFalado)) {
            $this->cdrBillsec = $tempoFalado;
        } else {
            $cdrBillsec = $agi->get_variable("CDR(billsec)");
            $this->cdrBillsec = $cdrBillsec['data'];
        }

        $cdrDisposition = $agi->get_variable("CDR(disposition)");
        $this->cdrDisposition = $cdrDisposition['data'];
        $cdrAmaflags = $agi->get_variable("CDR(amaflags)");
        $this->cdrAmaflags = $cdrAmaflags['data'];
        $cdrAccountcode = $agi->get_variable("CDR(accountcode)");
        $this->cdrAccountcode = $cdrAccountcode['data'];
        $cdrUniqueid = $agi->get_variable("CDR(uniqueid)");
        $this->cdrUniqueid = $cdrUniqueid['data'];
        $cdrUserfield = $agi->get_variable("CDR(userfield)");
        $this->cdrUserfield = $cdrUserfield['data'];
        
        $this->insertCdr = "INSERT INTO cdr (calldate,clid,src,dst,tipo,tronco,dcontext,channel,dstchannel,lastapp,lastdata,duration,billsec,disposition,amaflags,accountcode,uniqueid,userfield) VALUES "
                . "('{$this->cdrStart}','{$this->cdrCallerId}', '{$this->cdrSrc}', '{$this->cdrDst}', '{$this->cdrTipo}', '{$this->tronco}', '{$this->cdrDcontext}', '{$this->cdrChannel}', "
                . "'{$this->cdrDstchannel}', '{$this->cdrLastapp}', '{$this->cdrLastdata}', $this->cdrDuration, $this->cdrBillsec, '{$this->cdrDisposition}', 3, '"
                . "{$this->cdrAccountcode}', '{$this->cdrUniqueid}', '{$this->cdrUserfield}')";

                    
        return $this->insertCdr;
    }

    public function getCdr() {
        return $this->insertCdr;
    }

}
