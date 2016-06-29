<?php
class ContratoBusiness extends PfwValidator {

    const QUANTIDADE_PAGAMENTOS_CONTRATOS_AVISTA = 1;
    
    private $dao;

    function __construct(ContratoDAO $dao) {
        $this->dao = $dao;
    }    
    
    function validar(Contrato $contrato) {
        
        parent::validateDate($contrato->getDataDeCriacao(), "Informe a data de cria��o");
        
        $politicaDePreco = $contrato->getPoliticaDePreco();
        parent::validateIssetObject($politicaDePreco, "Informe a pol�tica de pre�o");
        
        $formaDePagamento = $contrato->getFormaDePagamento();
        parent::validateIssetObject($formaDePagamento, "Informe a forma de pagamento");
        
        $tipoDePagamento = $contrato->getTipoDePagamento();
        parent::validateIssetObject($tipoDePagamento, "Informe o tipo de pagamento");        

        $contratante = $contrato->getContratante();
        $contratanteAssociado = parent::validateIssetObject($contratante, "Informe o contratante");                
        
        if ($contratanteAssociado) {
            $estaInserindoContratante = Utilitarios::estaInserindo($contratante->getId());
            if ($estaInserindoContratante) {
                $this->validarDadosDoContratante($contratante);                   
            }            
        }        
        
        if (Utilitarios::estaInserindo($contrato->getId())) {
            $this->aplicarRegrasDeNegocioNaCricao($contrato);
        }
        
        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }        
        
    }
    
    private function validarDadosDoContratante(Contratante $contratante) {

        $driver = DAOFactory::getDAO()->getDriver();
        $contratanteDAO = ContratanteFactory::criarContratanteDAO($driver);
        $contratanteBusiness = ContratanteFactory::criarContratanteBusiness($contratanteDAO);
        try {
            $contratanteBusiness->validar($contratante);                        
        } catch(Exception $e) {
            parent::addError($e->getMessage());
        }        
        
    }
    
    private function aplicarRegrasDeNegocioNaCricao(Contrato $contrato) {
        
        $this->validarSeExisteContratoEmAbertoParaOContratante($contrato);
        
        if ($contrato->gratuito()) {            
            $this->validarCriacaoDeUmContratoGratuito($contrato);
        } else {
            $this->validarCriacaoDeUmContratoPago($contrato);            
        };
        
    }
    
    private function validarSeExisteContratoEmAbertoParaOContratante(Contrato $contrato) {        
	
        $contratante = $contrato->getContratante();
        
        if ($contratante->getId() != "" && $contratante->getId() != 0) {
         
            $contratoEmAberto = $this->dao->contratoAtivoOuAguardandoAtivacao($contratante);

            if ($contratoEmAberto) {
                parent::addError("J� existe um contrato ativo ou aguardando ativa��o para o contratante.");
            }            
            
        }        
		
    }
    
    private function validarCriacaoDeUmContratoGratuito(Contrato $contrato) {        
	
        $this->validarSeEhOPrimeiroContratoGratuito($contrato->getContratante());
        $this->validarPagamentosDeUmContratoGratuito($contrato->getPagamentos());
        $this->validarTipoDePagamentoDeUmContratoGratuito($contrato->getTipoDePagamento());
        $this->validarFormaDePagamentoDeUmContratoGratuito($contrato->getFormaDePagamento());        
		
    }
    
    private function validarSeEhOPrimeiroContratoGratuito(Contratante $contratante) {
        
        if ($contratante->getId() != "" && $contratante->getId() != 0) {        
            $possuiContratoGratuito = $this->dao->possuiContratoGratuito($contratante);

            if ($possuiContratoGratuito) {
                parent::addError("Somente o primeiro contrato de cada contratante pode ser gratuito");
            }
        }
        
    }
    
    private function validarPagamentosDeUmContratoGratuito(Collection $pagamentos) {
	
        if (Utilitarios::listaValida($pagamentos->toArray())) {
            parent::addError("Contratos gratuitos n�o podem possuir pagamentos");
        }
		
    }
    
    private function validarTipoDePagamentoDeUmContratoGratuito(TipoDePagamento $tipoDePagamento) {
			
        if ($tipoDePagamento->getValue() != TipoDePagamento::ISENTO) {
            parent::addError("Contratos gratuitos devem possuir tipo de pagamento isento");
        }
		
    }
    
    private function validarFormaDePagamentoDeUmContratoGratuito(FormaDePagamento $formaDePagamento) {
			
        if ($formaDePagamento->getValue() != FormaDePagamento::ISENTO) {
            parent::addError("Contratos gratuitos devem possuir forma de pagamento isento");
        }
		
    }
    
    private function validarCriacaoDeUmContratoPago(Contrato $contrato) {
	
        if (! Utilitarios::listaValida($contrato->getPagamentos()->toArray())) {
            parent::addError("Contratos pagos devem possuir pagamentos");
        }		
        $this->validarValorTotalDoContrato($contrato);
        $this->validarValorDosPagamentos($contrato);
        $this->validarTipoDePagamentoDeUmContratoPago($contrato->getTipoDePagamento());
        $this->validarFormaDePagamentoDeUmContratoPago($contrato->getFormaDePagamento());
        $this->validarAQuantidadeDePagamentos($contrato);
		
    }
    
    private function validarValorTotalDoContrato(Contrato $contrato) {
        
        $valorTotalDosPagamentos = 0;
        
        $pagamentos = $contrato->getPagamentos()->toArray();
        
        foreach ($pagamentos as $pagamento) {
            $valorTotalDosPagamentos += $pagamento->getValor();
        }
        
        if ($valorTotalDosPagamentos != $contrato->valorTotal()) {
            parent::addError("A soma dos valores de todos os pagamentos deve ser igual ao valor total do contrato");
        }
        
    }
    
    private function validarValorDosPagamentos(Contrato $contrato) {
        
        $contratoAvista = ($contrato->getTipoDePagamento()->getValue() == TipoDePagamento::AVISTA);
        if ($contratoAvista) {
            $valor = $contrato->valorTotal();
        } else {
            $valor = $contrato->valorMensal();
        }
                
        $pagamentos = $contrato->getPagamentos()->toArray();
        
        foreach ($pagamentos as $pagamento) {
            
            if ($pagamento->getValor() != $valor) {
                parent::addError("O valor de cada pagamento deve ser igual ao valor determinado na pol�tica de pre�o");
                break;
            }
            
        }
    }
    
    private function validarTipoDePagamentoDeUmContratoPago(TipoDePagamento $tipoDePagamento) {
        
        if ($tipoDePagamento->getValue() == TipoDePagamento::ISENTO) {
            parent::addError("Contratos pagos n�o podem ser isentos de pagamento");
        }
        
    }
    
    private function validarFormaDePagamentoDeUmContratoPago(FormaDePagamento $formaDePagamento) {
        
        if ($formaDePagamento->getValue() == FormaDePagamento::ISENTO) {
            parent::addError("Contratos pagos n�o podem possuir forma de pagamento isento");
        }
        
    }
    
    private function validarAQuantidadeDePagamentos(Contrato $contrato) {
        
        $quantPagamentos = $contrato->getPagamentos()->size();
        $contratoAvista = $contrato->getTipoDePagamento()->getValue() == TipoDePagamento::AVISTA;
        
        if ($contratoAvista) {
            
            if ($quantPagamentos != ContratoBusiness::QUANTIDADE_PAGAMENTOS_CONTRATOS_AVISTA) {
                parent::addError("Contratos � vista devem possuir apenas " . 
                    ContratoBusiness::QUANTIDADE_PAGAMENTOS_CONTRATOS_AVISTA . " pagamento");
            }
            
        } else {
            
            $duracaoDoContrato = $contrato->duracao();
            
            if ($quantPagamentos != $duracaoDoContrato) {
                parent::addError("A quantidade de pagamentos esperados � " . $duracaoDoContrato);
            }
            
        }
        
    }
    
    public function validarParaInsercao() {
	
        $driver = DAOFactory::getDAO()->getDriver();
        $politicaDePrecoDAO = PoliticaDePrecoFactory::criarPoliticaDePrecoDAO($driver); 
        $politicasAtivas = $politicaDePrecoDAO->buscarTodos(true);
        $possuiPoliticasAtivas = (count($politicasAtivas) > 0);
        if (! $possuiPoliticasAtivas) {
            throw new Exception('N�o � poss�vel cadastrar um contrato sem a exist�ncia de uma pol�tica de pre�o ativa.');
        }
		
    }
	
    public function validarAoFinalizar(Contrato $contrato) {
        
        $contratoAtivo = ($contrato->status() == StatusDoContrato::ATIVO); 
        if (! $contratoAtivo) {
		
            throw new Exception("Somente contratos ativos podem ser finalizados.");
			
        } else {

            $dataPrevistaParaFinalizacao = $contrato->dataPrevistaParaFinalizacao();
            $dataDoDia = date(Utilitarios::FORMAT_DMYY);
            
            if (Utilitarios::dataMenor($dataDoDia, $dataPrevistaParaFinalizacao)) {
                throw new Exception("N�o � poss�vel finalizar um contrato antes da data prevista para finaliza��o.");
            }

            if (count($contrato->pagamentosNaoQuitados()) > 0) {
                throw new Exception("N�o � poss�vel finalizar contratos com pend�ncia de pagamentos.");
            }            

        }
        
    }
    
    public function validarAoCancelar(Contrato $contrato) {
        
        if (! isset($contrato)) {
            throw new Exception("Informe um contrato");
        }
        
        $contratoInativo = ($contrato->status() != StatusDoContrato::ATIVO);
        $contratoNaoEstaAguardandoAtivacao = ($contrato->status() != StatusDoContrato::AGUARDANDO_ATIVACAO);
        
        if($contratoInativo && $contratoNaoEstaAguardandoAtivacao) {
            throw new Exception("Somente contratos ativos ou que est�o aguardando ativa��o podem ser cancelados");
        }
        
    }
}