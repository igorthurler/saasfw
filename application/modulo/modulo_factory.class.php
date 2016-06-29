<?php
abstract class ModuloFactory {

    static public function criarModulo() {
        
        return new Modulo();
        
    }
    
    static public function atribuirValores(Modulo &$modulo, $dados) {       
        
        if (isset($dados)) {
            
            $id = isset($dados['id_modulo']) && ($dados['id_modulo'] != "") ? $dados['id_modulo'] : null;
            $identificador = isset($dados['identificador']) && ($dados['identificador'] != "") ? $dados['identificador'] : null;
            $descricao = isset($dados['descricao']) && ($dados['descricao'] != "") ? $dados['descricao'] : null;
			
            $modulo->setId($id);
            $modulo->setIdentificador($identificador);
            $modulo->setDescricao($descricao);
            
        }
    }
    
    static public function criarModuloDAO($driver) {                
        return new ModuloDAO($driver);        
    }
    
    static public function criarModuloBusiness(ModuloDAO $moduloDAO) {
        return new ModuloBusiness($moduloDAO);
    }
   
}