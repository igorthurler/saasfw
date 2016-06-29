<?php
class PfwMainMenu {

    private $cssMenu;

    public function __construct($cssMainMenu) {
        $this->cssMenu = $cssMainMenu;
    }

    public function retornarMenu() {
        $dados = $this->retornarDadosDoMenu();

        $menu = $this->retornarEstruturaDoMenu($dados);

        return $menu;				
    }    

    private function retornarDadosDoMenu() {        
	
        $sql = "select M.id, M.app, M.acao, M.label, M.ordem, M.img, M.menuPrincipal
				  from Menu M 
				 order by M.ordem";        
        $driver = DAOFactory::getDAO()->getDriver();        
        $menus = $driver->fetchAssoc($sql);
        
        return $menus;	
		
    }		

    protected function retornarEstruturaDoMenu(array $dados) {
        $menu = '';

        if (isset($dados) && (count($dados)  > 0)) {

                $menu = "<ul {$this->cssMenu->retornarCssUlClassMenu()}>";

                foreach ($dados as $item) {				

                        if ($item['menuPrincipal'] == null) {
                                $descricao = $item['label'];

            $subMenu = $this->retornarSubMenu($item['id'], $dados);
            $possuiSubMenu = (isset($subMenu) && (count($subMenu)  > 0));

            if ($possuiSubMenu) {
                $menu .= "<li {$this->cssMenu->retornarCssLiDropDownClass()}>";
            } else {
                $menu .= '<li>';    
            }

            $class = $this->cssMenu->retornarCssDropDownClass();

                                $dropDown = $possuiSubMenu ? $this->cssMenu->retornarCssDropDownImage() : '';

                                if ($item['app'] == null) {
                $menu .= "<a href=\"javascript:void(0);\" {$class}> <i class=\"{$item['img']}\"></i> {$descricao} {$dropDown} </a>";                    
                                } else {                        
                $evento = "onclick=\"ajax.init( 'includes/PfwController.php?app={$item['app']}&action={$item['acao']}', 'viewers' );\"";

                $menu .= "<a href=\"javascript:void(0);\" {$evento} {$class}> <i class=\"{$item['img']}\"></i> {$descricao} {$dropDown} </a>";                    
                                }                 

                                if ($possuiSubMenu) {                        
                                        $menu .= $this->retornarEstruturaDoSubMenu($subMenu);
                                }

                                $menu .= '</li>';	
                        }							

                }

                $menu .= '</ul>';	

        }
        return $menu;			
    }	

    protected function retornarSubMenu($idMenuPrincipal, &$dados) {
        // Percorrer o array, e retornar apenas os itens cujo atributo menuPrincipal seja igual ao idMenuPrincipal
        $subMenu = array();

        foreach ($dados as $item) {
                if ($item['menuPrincipal'] == $idMenuPrincipal) {
                        $subMenu[] = $item;
                }
        }

        return $subMenu;
    }	

    protected function retornarEstruturaDoSubMenu($subMenu) {
        // Aqui tenho que utilizar a recursividade para montagem da estrutura
        $strSubMenu = "<ul {$this->cssMenu->retornarCssUlClassSubMenu()}>";

        foreach ($subMenu as $item) {
                $descricao = $item['label'];
                $strSubMenu .= "<li>";
                $strSubMenu .= "<a href=\"javascript:void(0);\" onclick=\"ajax.init( 'includes/PfwController.php?app={$item['app']}&action={$item['acao']}', 'viewers' );\"> 
                        <i class=\"{$item['img']}\"></i> {$descricao} </a>";
                $strSubMenu .= "</li>";
                // Se tiver subMenu, começar de novo
                $sm = $this->retornarSubMenu($item['id'], $subMenu);
                if (isset($sm) && (count($sm)  > 0)) {
                        $strSubMenu .= $this->retornarEstruturaDoSubMenu($sm);
                }		
        }

        $strSubMenu .= "</ul>";

        return $strSubMenu;
    }	

}	