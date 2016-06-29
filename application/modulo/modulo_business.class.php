<?php
class ModuloBusiness extends PfwValidator {

    private $dao;
    
    function __construct(ModuloDAO $dao) {
        $this->dao = $dao;
    }
    
    function validar(Modulo $modulo) {
        
        parent::validateGeneral($modulo->getDescricao(), 'Informe a descrição');
        
        if ($this->dao->identificadorCadastrado($modulo)) {
            parent::addError("Já existe um módulo cadastrado com o identificador cadastrado.");            
        }
        
        if ($this->dao->descricaoCadastrada($modulo)) {            
            parent::addError("Já existe um módulo cadastrado com a descrição informada.");            
        }
        
        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }
        
    }
    
    function validarAoEditar(Modulo $modulo) {

        if (! $modulo->isAtivo()) {
            parent::addError("Não é possível editar um módulo desativado.");
        }

        if (parent::foundErrors()) {            
            throw new Exception($this->listErrors('<br />'));
        }        
    }

    function validarAoDesativar(Modulo $modulo) {        
        
        if (! $modulo->isAtivo()) {
            parent::addError("Não é possível desativar um módulo desativado.");
        }

        if (parent::foundErrors()) {            
            throw new Exception($this->listErrors('<br />'));
        }        
    }    
    
    function validarAoDeletar(Modulo $modulo) {
        
        if ($this->dao->associadoAUmPlanoDeAdesao($modulo)) {
            parent::addError("Não é possível deletar um módulo associado a um plano de adesão.");
        }
        
        if (parent::foundErrors()) {            
            throw new Exception($this->listErrors('<br />'));
        }
        
    }

}
?>