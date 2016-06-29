<?php

abstract class ContratanteFactory {
    
    static public function criarContratante() {                
        $contratante = new Contratante();
        $contratante->setId(0);
        return $contratante;
    }
    
    static public function atribuirValores(Contratante &$contratante, $dados) {

        $pessoa = ($contratante->getPessoa() != null) ? $contratante->getPessoa() : PessoaFactory::criarPessoa();
        
        if (isset($dados)) {
            
            $id = isset($dados['id_contratante']) && ($dados['id_contratante'] != "") ? $dados['id_contratante'] : null;
            $dataDeCadastro = date(Utilitarios::FORMAT_DMYY);            
            $idPessoa = isset($dados['id_pessoa']) ? $dados['id_pessoa'] : null;             
			$alias = isset($dados['alias']) ? $dados['alias'] : null;             
            
			// Carrega os dados se a pessoa existir
            if (! Utilitarios::estaInserindo($idPessoa)) {
                $driver = DAOFactory::getDAO()->getDriver();
                $pessoaDAO = PessoaFactory::criarPessoaDAO($driver);
                $pessoa->setId($idPessoa);
                $pessoaDAO->load($pessoa);				
            }
            
			// Se estiver inserindo o contratante, o alias deve ser criado automaticamente
			// Inicialmente o alias será um hash md5 do próprio email do usuário,
			// podendo ser alterado posteriormente pelo próprio contratante.
			if (Utilitarios::estaInserindo($id)) {
				$alias = md5($pessoa->getEmail());
				$contratante->setAlias($alias);			
			}
			
            PessoaFactory::atribuirValores($pessoa, $dados);
			
            $contratante->setId($id);
            $contratante->setPessoa($pessoa);
            $contratante->setDataDeCadastro($dataDeCadastro);
			                            
        }
        
    }
    
    static public function criarContratanteDAO($driver) {
        return new ContratanteDAO($driver);
    }
    
    static public function criarContratanteBusiness(ContratanteDAO $contratanteDAO) {
        return new ContratanteBusiness($contratanteDAO);
    }
    
}