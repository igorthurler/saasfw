<?php

 /**
  * Manejar Sessoes, usuarios logados
  * Essa e uma adaptacao da classe original
  * fonte : http://www.intranetjournal.com ( direitos a eles reservados )  
  * @author http://www.intranetjournal.com
  *
  * Obs.: Foram alteradas algumas coisas para adeguar ao PHP atual
  * e tambem para adeguar ao sistema
  * 
  **/

class PfwSession
{
    /**
     * Construtor - Inicia sessao e seta Cache
     *       
     **/
    public function __construct()
    {
	session_start();
    	header("Cache-control: private");
    }

    /**
     * close, Destroi a sessao
     * @return bool true caso tudo ocorra bem
     **/
    function close()
    {
        $_SESSION = array();
	session_destroy();
	return true;
    }
	
    function setSessionValue($sessionName, $value) 
    {	
        $_SESSION[$sessionName] = $value;
    }

    function getSessionValue($sessionName)
    {
        return $_SESSION[$sessionName];
    }    
    
    function sessionValueExists($name) {
        return isset($_SESSION[$name]) && ! empty($_SESSION[$name]);
    }
}
