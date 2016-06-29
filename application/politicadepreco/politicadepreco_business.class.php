<?php
class PoliticaDePrecoBusiness extends PfwValidator {

    private $politicaDePrecoDAO;
    
    function __construct(PoliticaDePrecoDAO $politicaDePrecoDAO) {
        $this->politicaDePrecoDAO = $politicaDePrecoDAO;
    }
    
    public function validar(PoliticaDePreco $politicaDePreco) {
        /* validacao dos dados recebidos */
        parent::validateGeneral($politicaDePreco->getData(), 'Informe a data');
        parent::validateDate($politicaDePreco->getData(), 'Informe uma data válida');
        parent::validateGeneral($politicaDePreco->getValor(), 'Informe o valor');
        parent::validateNumber($politicaDePreco->getValor(), 'Informe um valor numérico');

        $planoDeAdesao = $politicaDePreco->getPlanoDeAdesao();        
		
        if (! isset($planoDeAdesao)) {
		
            parent::addError('Informe o plano de adesão associado');
			
        } else {
		
			if ($this->politicaDePrecoDAO->politicaDePrecoCadastrada($politicaDePreco)) {
				parent::addError("Já existe uma política de preço para o plano de adesão na data informada");
				throw new Exception($this->listErrors('<br />'));
			}
			
			if (! $politicaDePreco->getPlanoDeAdesao()->isAtivo()) {
				parent::addError("Não é possível executar operações em uma política de preço associada a um plano de adesão inativo");
			}

			if ($politicaDePreco->getPlanoDeAdesao()->isGratis()) {

				  if ($politicaDePreco->getValor() != 0) {
						parent::addError('O valor da política de preço deve ser zero quando o plano de adesão for grátis');
				  }              
				
			} else {
				
				  if ($politicaDePreco->getValor() == 0) {
						parent::addError('O valor da política de preço deve ser maior que zero quando o plano de adesão não for grátis');
				  }              
				
			}		
		
		}
                                             
        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }        
    }
    
    public function validarParaAlteracao(PoliticaDePreco $politicaDePreco) {

        if (! $politicaDePreco->getPlanoDeAdesao()->isAtivo()) {
            throw new Exception("Não é possível executar operações em uma política de preço associada a um plano de adesão inativo.");
        }
        
    }
    
    public function validarAoExcluir(PoliticaDePreco $politicaDePreco) {
        
       $associadaAUmContrato = $this->politicaDePrecoDAO->politicaDePrecoAssociadaAUmContrato($politicaDePreco);
        
       if ($associadaAUmContrato) {
           throw new Exception("Não é possível deletar os dados de uma política de preço associada a um contrato.");
       }
       
    }

}