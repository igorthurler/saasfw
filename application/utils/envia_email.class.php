<?php
class EnviaEmail {

    private $emailEnvio;
    
    public function setEmailEnvio($emailDeEnvio) {
        $this->emailDeEnvio = $emailDeEnvio;
    }

    protected function enviarEmail($destino, $titulo, $mensagem) {
        $header = "MIME-Version: 1.0\r\n";
        $header .= "Content-Type: text/html; charset=iso-8859-1\r\n";
        $header .= "From: <$this->emailDeEnvio>";

        mail($destino, $titulo, $mensagem, $header);        		
    }    
    
}