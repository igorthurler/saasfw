<?php
class GeraPagamentoContrato implements GeraPagamentos {

    private $contrato;
    
    public function __construct(Contrato $contrato) {
        $this->contrato = $contrato;
    }
    
    public function gerarPagamentos($dataDoPrimeiroPagamento) {
        
        $tipoDePagamento = $this->contrato->getTipoDePagamento();
        
        switch ($tipoDePagamento->getValue()) {
            
            case TipoDePagamento::AVISTA:
                $pagamentos = $this->gerarPagamentoAVista($dataDoPrimeiroPagamento);
                break;
            case TipoDePagamento::PARCELADO:
                $pagamentos = $this->getarPagamentosParcelados($dataDoPrimeiroPagamento);
                break;
            case TipoDePagamento::ISENTO:
                $pagamentos = new Collection();
                break;
        }
        
    }
    
    private function gerarPagamentoAVista($dataDoPrimeiroPagamento) {        
        
        $pagamento = new Pagamento();
        $pagamento->setContrato($this->contrato);
        $pagamento->setDataDeCriacao($this->contrato->getDataDeCriacao());
        $pagamento->setDataDeVencimento($dataDoPrimeiroPagamento);
        $pagamento->setValor($this->contrato->valorTotal());
        
        $this->contrato->adicionarPagamento($pagamento);
        
    }
    
    private function getarPagamentosParcelados($dataDoPrimeiroPagamento) {
        
        $duracao = $this->contrato->duracao();
        
        $dataDoProximoPagamento = $dataDoPrimeiroPagamento;
        
        for ($i = 0; $i <= ($duracao - 1); $i++) {
            
            clone $pagamento = new Pagamento();
            $pagamento->setContrato($this->contrato);
            $pagamento->setDataDeCriacao($this->contrato->getDataDeCriacao());
            $pagamento->setDataDeVencimento($dataDoProximoPagamento);
            $pagamento->setValor($this->contrato->valorMensal());
            
            $this->contrato->adicionarPagamento($pagamento);
            
            $dataDoProximoPagamento = Utilitarios::adicionarMeses($dataDoProximoPagamento, 1);
            
        }
        
    }
        
}