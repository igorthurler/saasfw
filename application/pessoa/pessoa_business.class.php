<?php
class PessoaBusiness extends PfwValidator {
    
    public function validarPessoa(Pessoa $pessoa) {
        
        parent::validateGeneral($pessoa->getNome(), 'Informe o nome');
        parent::validateTextMinMaxLength($pessoa->getNome(), 1, 100, "O nome da pessoa deve possuir no m�nimo 1 e no m�ximo 100 caracteres");
        
        parent::validateGeneral($pessoa->getDocumento(), 'Informe o documento');
        parent::validateTextMinMaxLength($pessoa->getDocumento(), 11,  14, "O documento deve possuir no m�nimo 11 e no m�ximo 14 caracteres");
        parent::validateTextOnlyNumbers($pessoa->getDocumento(), 'O documento deve possuir apenas caracteres num�ricos');          
        
        $documentoValido = Utilitarios::documentoValido($pessoa->getDocumento());
        
        if (! $documentoValido) {
            parent::addError('O documento informado n�o � um CPF ou CNPJ v�lido.');
        }
        
        $driver = DAOFactory::getDAO()->getDriver();
        $pessoaDAO = PessoaFactory::criarPessoaDAO($driver);
        
        if ($pessoaDAO->documentoCadastrado($pessoa)) {
            parent::addError("O documento informado j� est� cadastrado.");
        }
                               
        parent::validateGeneral($pessoa->getLogradouro(), 'Informe o logradouro');
        parent::validateTextMinMaxLength($pessoa->getLogradouro(), 1, 100, "O logradouro deve possuir no m�nimo 1 e no m�ximo 100 caracteres");
        
        parent::validateGeneral($pessoa->getNumero(), 'Informe o n�mero do endere�o');
        parent::validateTextMinMaxLength($pessoa->getNumero(), 1, 20, "O n�mero do endere�o deve possuir no m�nimo 1 e no m�ximo 20 caracteres");        
                
        parent::validateIssetObject($pessoa->getCidade(), 'Informe a cidade');
        parent::validateIssetObject($pessoa->getEstado(), 'Informe o estado');
        
        parent::validateGeneral($pessoa->getCep(), 'Informe o CEP');
        parent::validateTextMinMaxLength($pessoa->getNumero(), 1, 8, "O CEP deve possuir no m�nimo 1 e no m�ximo 8 caracteres");                
        
        parent::validateGeneral($pessoa->getBairro(), 'Informe o bairro');
        parent::validateTextMinMaxLength($pessoa->getNumero(), 1, 50, "O bairro deve possuir no m�nimo 1 e no m�ximo 50 caracteres");                
                               
        parent::validateGeneral($pessoa->getEmail(), 'Informe o email');
        parent::validateEmail($pessoa->getEmail(), "Informe um email v�lido");        
        
        if ($pessoaDAO->emailCadastrado($pessoa)) {
            parent::addError("O email informado j� est� cadastrado.");
        }        
        
    }
    
}