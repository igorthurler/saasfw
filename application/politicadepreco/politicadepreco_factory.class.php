<?php
abstract class PoliticaDePrecoFactory {

    static public function criarPoliticaDePreco() {
        
        return new PoliticaDePreco();
        
    }
    
    static public function atribuirValores(PoliticaDePreco &$politicaDePreco, $dados) {
        
        if (isset($dados)) {
            
            $id = isset($dados['id_politicapreco']) && ($dados['id_politicapreco'] != "") ? $dados['id_politicapreco'] : null;
            $data = isset($dados['data']) && ($dados['data'] != "") ? $dados['data'] : null;
            $valor = isset($dados['valor']) && ($dados['valor'] != "") ? $dados['valor']: 0;
            
            $politicaDePreco->setId($id);            
            $politicaDePreco->setData($data);            
            $politicaDePreco->setValor(floatval($valor));
			
            if (isset($dados['id_planoadesao']) && ($dados['id_planoadesao'] != "")) {                
                $planoDeAdesao = PlanoDeAdesaoFactory::criarPlanoDeAdesao();
                $planoDeAdesao->setId($dados['id_planoadesao']);                
                $driver = DAOFactory::getDAO()->getDriver();                
                $planoDeAdesaoDAO = PlanoDeAdesaoFactory::criarPlanoDeAdesaoDAO($driver);
                $planoDeAdesaoDAO->load($planoDeAdesao);
                
                $politicaDePreco->setPlanoDeAdesao($planoDeAdesao);                
            }
            
        }
        
    }
    
    static public function criarPoliticaDePrecoDAO($driver) {
        
        return new PoliticaDePrecoDAO($driver);
        
    }
    
    static public function criarPoliticaDePrecoBusiness(PoliticaDePrecoDAO $politicaDePrecoDAO) {
        
        return new PoliticaDePrecoBusiness($politicaDePrecoDAO);
        
    }
    
}