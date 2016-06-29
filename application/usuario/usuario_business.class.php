<?php
class UsuarioBusiness extends PfwValidator {

    private $dao;
    
    function __construct(UsuarioDAO $dao) {
        $this->dao = $dao;
    }        
    
    public function validar(Usuario $usuario) {
        
        parent::validateGeneral($usuario->getNome(), 'Informe o nome');
        parent::validateTextMinMaxLength($usuario->getNome(), 1, 50, "O nome do usuario deve possuir no mínimo 1 e no máximo 50 caracteres");
        
        parent::validateGeneral($usuario->getEmail(), 'Informe o email');
        parent::validateEmail($usuario->getEmail(), "Informe um email válido");        
        
        if ($this->dao->emailCadastrado($usuario)) {
            parent::addError("O email informado já está cadastrado.");
        }        
        
        parent::validateGeneral($usuario->getSenha(), 'Informe uma senha');
        
        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        } 
        
    }
    
    public function validarAoDesativar(Usuario $usuario) {
    
        if (! $usuario->isAtivo()) {
            parent::addError('O usuario informado já está desativado.');
        } 

        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }         
    
    }
    
}