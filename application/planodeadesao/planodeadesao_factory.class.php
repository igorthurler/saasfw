<?php
abstract class PlanoDeAdesaoFactory {

    static public function criarPlanoDeAdesao() {
                
        return new PlanoDeAdesao();
        
    }
    
    static public function atribuirValores(PlanoDeAdesao &$planoDeAdesao, $dados) {

        if (isset($dados)) {      
            $id = isset($dados['id_planoadesao']) && ($dados['id_planoadesao'] != "") ? $dados['id_planoadesao'] : null;
            $descricao = isset($dados['descricao']) && ($dados['descricao'] != "") ? $dados['descricao'] : null;
            $duracao = isset($dados['duracao']) && ($dados['duracao'] != "") ? $dados['duracao'] : null;                  
            $gratis = isset($dados['gratis']) && ($dados['gratis'] != "") ? $dados['gratis'] : null;
            $usuarios = isset($dados['quantusuario']) && ($dados['quantusuario'] != "") ? $dados['quantusuario'] : null;
            $modulos = isset($dados['modulos']) ? $dados['modulos'] : array();
            
            $planoDeAdesao->setId($id);         
            $planoDeAdesao->setDescricao($descricao);
            $planoDeAdesao->setDuracao($duracao);
            $planoDeAdesao->setGratis($gratis);
            $planoDeAdesao->setQuantUsuario($usuarios);
            
            if (count($modulos) > 0) {                        
                $driver = DAOFactory::getDAO()->getDriver();
                $moduloDAO = ModuloFactory::criarModuloDAO($driver);                    
                $modulosRetornados = $moduloDAO->buscarModulosPeloId($modulos);                              

                for ($i = 0; $i < count($modulosRetornados); $i++) {
                    $modulo = $modulosRetornados[$i];
                    $planoDeAdesao->adicionarModulo($modulo);
                }                                                
            }
            
        }        
        
    }
    
    static public function criarPlanoDeAdesaoDAO($driver) {
        return new PlanoDeAdesaoDAO($driver);
    }
    
    static public function criarPlanoDeAdesaoBusiness(PlanoDeAdesaoDAO $planoDeAdesaoDAO) {
        return new PlanoDeAdesaoBusiness($planoDeAdesaoDAO);
    }
   
}