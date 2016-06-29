<?php

class ContratanteBusiness extends PessoaBusiness {

    private $dao;
    
    function __construct(ContratanteDAO $dao) {
        $this->dao = $dao;
    }
    
    function validar(Contratante $contratante) {
        
        $pessoa = $contratante->getPessoa();
        
        if (isset($pessoa)) {
            parent::validarPessoa($pessoa);
        } else {
            parent::addError('Informe a pessoa');
        }
         
        if ($this->dao->aliasCadastrado($contratante)) {
            parent::addError("Já existe um contratante cadastrado com o alias informado");
        }	
		 
        if ($this->dao->documentoCadastrado($contratante)) {
            parent::addError("Já existe um contratante cadastrado com o documento informado");
        }
        
        if ($this->dao->emailCadastrado($contratante)) {
            parent::addError("Já existe um contratante cadastrado com o email informado");
        }        
        
        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }
        
    }
    
    public function validarAoExcluir(contratante $contratante) {
        
        if ($this->dao->possuiContrato($contratante)) {
            throw new Exception('Não é possível excluir os dados de um contratante que possui contrato');
        }
        
    }       
}