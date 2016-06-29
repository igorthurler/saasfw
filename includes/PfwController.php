<?php
    /* configura cabecalho. charset latino, e evita guardar dados em cache */	
    $gmtDate = gmdate("D, d M Y H:i:s");   
    header("Expires: {$gmtDate} GMT");
    header("Last-Modified: {$gmtDate} GMT");
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header( 'Content-Type: text/html; charset=iso-8859-1', true );
	
    /* obtem paginas a ser processada */
    global $app;
    global $action;
    $app = ( isset( $_GET['app'] ) ) ? $_GET['app'] : '';
    $action = ( isset( $_GET['action'] ) ) ? $_GET['action'] : '';

    $arqController = "../application/" . $app . "/index.php";
    
    if (file_exists($arqController)) {
        
        if( !empty( $app ) )
        {
            /* usado para o form se auto-processar */
            //$self = $_SERVER["PHP_SELF"];		
            include( "../application/". $app . "/index.php" );
        }	                        
                
    } else {     

        $arqController = "../application/" . $app . "/" . $app . "_controller.class.php";        
        
        include( $arqController );
        
        $classe = $app . "Controller";
        $obj = new $classe();
        $obj->$action();        

    }   