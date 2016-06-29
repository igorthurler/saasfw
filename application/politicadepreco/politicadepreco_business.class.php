<?php
class PoliticaDePrecoBusiness extends PfwValidator {

    private $politicaDePrecoDAO;
    
    function __construct(PoliticaDePrecoDAO $politicaDePrecoDAO) {
        $this->politicaDePrecoDAO = $politicaDePrecoDAO;
    }
    
    public function validar(PoliticaDePreco $politicaDePreco) {
        /* validacao dos dados recebidos */
        parent::validateGeneral($politicaDePreco->getData(), 'Informe a data');
        parent::validateDate($politicaDePreco->getData(), 'Informe uma data v�lida');
        parent::validateGeneral($politicaDePreco->getValor(), 'Informe o valor');
        parent::validateNumber($politicaDePreco->getValor(), 'Informe um valor num�rico');

        $planoDeAdesao = $politicaDePreco->getPlanoDeAdesao();        
		
        if (! isset($planoDeAdesao)) {
		
            parent::addError('Informe o plano de ades�o associado');
			
        } else {
		
			if ($this->politicaDePrecoDAO->politicaDePrecoCadastrada($politicaDePreco)) {
				parent::addError("J� existe uma pol�tica de pre�o para o plano de ades�o na data informada");
				throw new Exception($this->listErrors('<br />'));
			}
			
			if (! $politicaDePreco->getPlanoDeAdesao()->isAtivo()) {
				parent::addError("N�o � poss�vel executar opera��es em uma pol�tica de pre�o associada a um plano de ades�o inativo");
			}

			if ($politicaDePreco->getPlanoDeAdesao()->isGratis()) {

				  if ($politicaDePreco->getValor() != 0) {
						parent::addError('O valor da pol�tica de pre�o deve ser zero quando o plano de ades�o for gr�tis');
				  }              
				
			} else {
				
				  if ($politicaDePreco->getValor() == 0) {
						parent::addError('O valor da pol�tica de pre�o deve ser maior que zero quando o plano de ades�o n�o for gr�tis');
				  }              
				
			}		
		
		}
                                             
        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }        
    }
    
    public function validarParaAlteracao(PoliticaDePreco $politicaDePreco) {

        if (! $politicaDePreco->getPlanoDeAdesao()->isAtivo()) {
            throw new Exception("N�o � poss�vel executar opera��es em uma pol�tica de pre�o associada a um plano de ades�o inativo.");
        }
        
    }
    
    public function validarAoExcluir(PoliticaDePreco $politicaDePreco) {
        
       $associadaAUmContrato = $this->politicaDePrecoDAO->politicaDePrecoAssociadaAUmContrato($politicaDePreco);
        
       if ($associadaAUmContrato) {
           throw new Exception("N�o � poss�vel deletar os dados de uma pol�tica de pre�o associada a um contrato.");
       }
       
    }

}