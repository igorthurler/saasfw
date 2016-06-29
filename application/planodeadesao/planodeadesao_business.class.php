<?php

class PlanoDeAdesaoBusiness extends PfwValidator {

    private $dao;

    function __construct(PlanoDeAdesaoDAO $dao) {
        $this->dao = $dao;
    }

    function validar(PlanoDeAdesao $planoDeAdesao) {
        /* validacao dos dados recebidos */
        parent::validateGeneral($planoDeAdesao->getDuracao(), 'Informe a dura��o');
        parent::validateTextMinLength($planoDeAdesao->getDuracao(), "A dura��o deve ser maior que zero");
        parent::validateGeneral($planoDeAdesao->getDescricao(), 'Informe a descricao');

        if ($this->dao->descricaoCadastrada($planoDeAdesao)) {
            parent::addError("J� existe um plano de ades�o cadastrado com a descri��o informada");
        }

        parent::validateGeneral($planoDeAdesao->isGratis(), 'Informe se o plano de ades�o � gr�tis ou n�o');        

        parent::validateGeneral($planoDeAdesao->getQuantUsuario(), 'Informe a quantidade de usu�rios');

        if ($planoDeAdesao->getQuantUsuario() <= 0) {
            parent::addError("A quantidade de usu�rios deve ser maior que zero");
        }

        if ($planoDeAdesao->getModulos()->size() == 0) {
            parent::addError("Informe pelo menos 1 m�dulo");
        }        
        
        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }
    }

    function validarModulosAtivosNaInclusao($modulos) {
                     
        if (count($modulos) == 0) {
            parent::addError("N�o � possivel cadastrar um plano de ades�o sem a exist�ncia de m�dulos ativos");            
        }
        
        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }        
        
    }    
    
    function validarPlanoDeAdesaoAtivo(PlanoDeAdesao $planoDeAdesao) {

        if (!$planoDeAdesao->isAtivo()) {
            parent::addError("N�o � poss�vel manipular os dados de um plano de ades�o desativado");
        }

        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }
    }

    function validarAoDeletar(PlanoDeAdesao $planoDeAdesao) {

        if ($this->dao->associadoAUmaPoliticaDePreco($planoDeAdesao)) {
            parent::addError("N�o � poss�vel excluir um plano de ades�o associado a uma pol�tica de pre�o");
        }

        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }
    }

    function validarAoDesativar(PlanoDeAdesao $planoDeAdesao) {

        if (!$planoDeAdesao->isAtivo()) {
            parent::addError("N�o � poss�vel desativar um plano de ades�o desativado");
        }

        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }
    }

}