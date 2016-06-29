<?php
abstract class UIPagination {
    
    const REGISTRO_PAGINA = 10;
    const INTERVALO = 16;   
    
    public static function paginar($totalRegistros, $paginaAtual, 
            $action, $registrosPagina = null) {
        
        if ($registrosPagina <= 0) {
            $registrosPagina = self::REGISTRO_PAGINA;
        }
        
        $numeroPaginas = ceil($totalRegistros / $registrosPagina);
        
        $links = self::getLinks(
            self::getListaPaginas($paginaAtual, $numeroPaginas),
            $paginaAtual, $action); 
        
        foreach ($links as $link) {
            $link->show();
        }
        
    }
    
    private static function getListaPaginas($paginaAtual, $numeroPaginas) {
        
        $paginas = array();
        
        if ($paginaAtual > 1 && $numeroPaginas > self::INTERVALO) {
            $paginas[] = "1";
            $paginas[] = "...";
        }
        
        $ultimaPagina = $paginaAtual + (self::INTERVALO - 1);
        
        if ($ultimaPagina > $numeroPaginas) {
            $ultimaPagina = $numeroPaginas;
        }
        
        if ($numeroPaginas - $paginaAtual < self::INTERVALO) {
            $paginaAtual = $numeroPaginas - self::INTERVALO;
            if ($paginaAtual < 1) {
                $paginaAtual = 1;
            }
        }
        
        for ($pagina = $paginaAtual; $pagina <= $ultimaPagina; $pagina++) {
            $paginas[] = $pagina;
        }
        
        if ($ultimaPagina != $numeroPaginas) {
            $paginas[] = "...";
            $paginas[] = $numeroPaginas;
        }
        
        return $paginas;
        
    }
    
    private static function getLinks($paginas, $paginaAtual, $action) {
        
        $paginaAnterior = 1;
        
        foreach ($paginas as $idx=>$pagina) {
            
            $goto = ($pagina == "...") ? 
                floor(($paginas[$idx + 1] - $paginaAnterior)/2) + $paginaAnterior :
                $pagina;
            
            $pagina = ($pagina == $paginaAtual) ?
                "<span style = 'font-size:18px;".
                "vertical-align:middle;".
                "padding:2px;font-weight:bold;".
                "color:#9900033;'>{$pagina}</span>" :
                "<span style='font-size:13px;".
                "vertical-align:middle;'>{$pagina}</span>";

            $a = str_replace (":pag", $goto, $action);
            $link = new UILinkElement('javascript:void(0);',
                $pagina, array('align'=>'center', 'style'=>'text-decoration:none;'), $a);
            
            $paginas[$idx] = $link;
            
            $paginaAnterior = $goto;
                            
        }
        
        return $paginas;        
                
    }
    
}