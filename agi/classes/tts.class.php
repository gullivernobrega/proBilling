<?php
spl_autoload_register(function ($class_name) {
    require 'classes/' . $class_name . '.class.php';
});
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tts
 *
 * @author Gulliver Nóbrega
 */
class tts extends Conn {

    private $data;
    private $idKey;
    private $secretKey;
    private $provider;
    private $result;

    public function Exectts($text) {
        $this->data = $text;
       
        $this->setData();
        if ($this->provider == 'aws') {
            $this->setAws();
        } else {
            $this->Setibm();
        }
    }

    private function setData() {

//        Pegando dados do provider de TTS
        $Query = "SELECT config_tts_provider, config_tts_id, config_tts_secret FROM config";
        $result = $this->Consultar($Query);
        $result = $result[0];
        
//       Setando dados nas variaveis.
        $this->provider = $result['config_tts_provider'];
        $this->idKey = $result['config_tts_id'];
        $this->secretKey = $result['config_tts_secret'];
    }

    private function setAws() {
              
        require_once '/var/www/html/proBilling/_app/vendor/autoload.php';
        $credentials = new \Aws\Credentials\Credentials($this->idKey, $this->secretKey);
        $client = new \Aws\Polly\PollyClient([
            'version' => 'latest',
            'credentials' => $credentials,
            'region' => 'sa-east-1',
            'engine' => 'neural'
        ]);
        $result = $client->synthesizeSpeech([
            'OutputFormat' => 'mp3',
            'Text' => $this->data,
            'TextType' => 'text',
            'VoiceId' => 'Camila',
        ]);

        $resultData = $result->get('AudioStream')->getContents();

        header('Content-Transfer-Encoding: binary');
        header('Content-Type: audio/mpeg');
        header('Content-length: ' . strlen($resultData));
//header('Content-Disposition: attachment; filename="pollyTTS.mp3"');
        header('X-Pad: avoid browser bug');
        header('Cache-Control: no-cache');
        $this->result = $resultData;
    }

    private function Setibm() {
        
    }

    public function getResult() {
    
        return $this->result;
    }

}
