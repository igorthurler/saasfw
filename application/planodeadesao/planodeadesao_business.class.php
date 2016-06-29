<?php

class PlanoDeAdesaoBusiness extends PfwValidator {

    private $dao;

    function __construct(PlanoDeAdesaoDAO $dao) {
        $this->dao = $dao;
    }

    function validar(PlanoDeAdesao $planoDeAdesao) {
        /* validacao dos dados recebidos */
        parent::validateGeneral($planoDeAdesao->getDuracao(), 'Informe a duração');
        parent::validateTextMinLength($planoDeAdesao->getDuracao(), "A duração deve ser maior que zero");
        parent::validateGeneral($planoDeAdesao->getDescricao(), 'Informe a descricao');

        if ($this->dao->descricaoCadastrada($planoDeAdesao)) {
            parent::addError("Já existe um plano de adesão cadastrado com a descrição informada");
        }

        parent::validateGeneral($planoDeAdesao->isGratis(), 'Informe se o plano de adesão é grátis ou não');        

        parent::validateGeneral($planoDeAdesao->getQuantUsuario(), 'Informe a quantidade de usuários');

        if ($planoDeAdesao->getQuantUsuario() <= 0) {
            parent::addError("A quantidade de usuários deve ser maior que zero");
        }

        if ($planoDeAdesao->getModulos()->size() == 0) {
            parent::addError("Informe pelo menos 1 módulo");
        }        
        
        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }
    }

    function validarModulosAtivosNaInclusao($modulos) {
                     
        if (count($modulos) == 0) {
            parent::addError("Não é possivel cadastrar um plano de adesão sem a existência de módulos ativos");            
        }
        
        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }        
        
    }    
    
    function validarPlanoDeAdesaoAtivo(PlanoDeAdesao $planoDeAdesao) {

        if (!$planoDeAdesao->isAtivo()) {
            parent::addError("Não é possível manipular os dados de um plano de adesão desativado");
        }

        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }
    }

    function validarAoDeletar(PlanoDeAdesao $planoDeAdesao) {

        if ($this->dao->associadoAUmaPoliticaDePreco($planoDeAdesao)) {
            parent::addError("Não é possível excluir um plano de adesão associado a uma política de preço");
        }

        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }
    }

    function validarAoDesativar(PlanoDeAdesao $planoDeAdesao) {

        if (!$planoDeAdesao->isAtivo()) {
            parent::addError("Não é possível desativar um plano de adesão desativado");
        }

        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }
    }

}