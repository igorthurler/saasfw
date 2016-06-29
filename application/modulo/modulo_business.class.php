<?php
class ModuloBusiness extends PfwValidator {

    private $dao;
    
    function __construct(ModuloDAO $dao) {
        $this->dao = $dao;
    }
    
    function validar(Modulo $modulo) {
        
        parent::validateGeneral($modulo->getDescricao(), 'Informe a descri��o');
        
        if ($this->dao->identificadorCadastrado($modulo)) {
            parent::addError("J� existe um m�dulo cadastrado com o identificador cadastrado.");            
        }
        
        if ($this->dao->descricaoCadastrada($modulo)) {            
            parent::addError("J� existe um m�dulo cadastrado com a descri��o informada.");            
        }
        
        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }
        
    }
    
    function validarAoEditar(Modulo $modulo) {

        if (! $modulo->isAtivo()) {
            parent::addError("N�o � poss�vel editar um m�dulo desativado.");
        }

        if (parent::foundErrors()) {            
            throw new Exception($this->listErrors('<br />'));
        }        
    }

    function validarAoDesativar(Modulo $modulo) {        
        
        if (! $modulo->isAtivo()) {
            parent::addError("N�o � poss�vel desativar um m�dulo desativado.");
        }

        if (parent::foundErrors()) {            
            throw new Exception($this->listErrors('<br />'));
        }        
    }    
    
    function validarAoDeletar(Modulo $modulo) {
        
        if ($this->dao->associadoAUmPlanoDeAdesao($modulo)) {
            parent::addError("N�o � poss�vel deletar um m�dulo associado a um plano de ades�o.");
        }
        
        if (parent::foundErrors()) {            
            throw new Exception($this->listErrors('<br />'));
        }
        
    }

}
?>