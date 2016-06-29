<?php
/**
 * @Entity
 * @Table(name="Contrato")
 */
class Contrato {
	
    /**
     *
     * @var int
     * @Id(strategy=GenerationType.AUTO) 
     * @Column(name="id")
     */
    private $id;

    /**
     *
     * @var int
     * @Column(name="codigo")
     */    
    private $codigo;
    
    /**
     *
     * @var date
     * @Column(name="dataDeCriacao")
     */    
    private $dataDeCriacao;    
    
    /**
     *
     * @var PoliticaDePreco
     * @OneToOne(targetEntity="PoliticaDePreco", fetch=FetchType.FETCH, cascade=CascadeType.NONE)
     * @Column(name="politicaDePreco")
     */    
    private $politicaDePreco;
    
    /**
     *
     * @var FormaDePagamento 
     * @Column(name="formaDePagamento")
     */
    private $formaDePagamento;

    /**
     *
     * @var TipoDePagamento 
     * @Column(name="tipoDePagamento")
     */    
    private $tipoDePagamento;        
    
    /**
     *
     * @var Contratante
     * @OneToOne(targetEntity="Contratante", fetch=FetchType.FETCH, cascade=CascadeType.SAVE)
     * @Column(name="contratante")
     */
    private $contratante;

    /**
     *
     * @var date
     * @Column(name="dataDeAtivacao")
     */        
    private $dataDeAtivacao;
    
    /**
     *
     * @var date
     * @Column(name="dataDeFinalizacao")
     */        
    private $dataDeFinalizacao;
    
    /**
     *
     * @var Cancelamento
     * @Column(name="cancelamento") 
     * @OneToOne(fetch=FetchType.LAZY, cascade=CascadeType.SAVE)
     */    
    private $cancelamento;
    
    /**
     *
     * @var Collection
     * @OneToMany(fetch=FetchType.FETCH, cascade=CascadeType.ALL,
     *      mappedBy="contrato", targetEntity="Pagamento")
     */   	    
    private $pagamentos;

    public function __construct() {
        $this->pagamentos = new Collection();
    }    
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getDataDeCriacao() {
        return $this->dataDeCriacao;
    }

    public function setDataDeCriacao($dataDeCriacao) {
        $this->dataDeCriacao = $dataDeCriacao;
    }

    public function getPoliticaDePreco() {
        return $this->politicaDePreco;
    }

    public function setPoliticaDePreco(PoliticaDePreco $politicaDePreco) {
        $this->politicaDePreco = $politicaDePreco;
    }

    public function getFormaDePagamento() {
        return $this->formaDePagamento;
    }

    public function setFormaDePagamento(FormaDePagamento $formaDePagamento) {
        $this->formaDePagamento = $formaDePagamento;
    }

    public function getTipoDePagamento() {
        return $this->tipoDePagamento;
    }

    public function setTipoDePagamento(TipoDePagamento $tipoDePagamento) {
        $this->tipoDePagamento = $tipoDePagamento;
    }

    public function getContratante() {
        return $this->contratante;
    }

    public function setContratante(Contratante $contratante) {
        $this->contratante = $contratante;
    }

    public function getDataDeAtivacao() {
        return $this->dataDeAtivacao;
    }

    public function setDataDeAtivacao($dataDeAtivacao) {
        $this->dataDeAtivacao = $dataDeAtivacao;
    }

    public function getDataDeFinalizacao() {
        return $this->dataDeFinalizacao;
    }

    public function setDataDeFinalizacao($dataDeFinalizacao) {
        $this->dataDeFinalizacao = $dataDeFinalizacao;
    }

    public function getCancelamento() {
        return $this->cancelamento;
    }

    public function setCancelamento(Cancelamento $cancelamento) {
        $this->cancelamento = $cancelamento;
    }

    public function getPagamentos() {
        return $this->pagamentos;
    }

    public function setPagamentos(Collection $pagamentos) {
        $this->pagamentos = $pagamentos;
    }
               
    public function valorTotal() {
        
        $valorTotal = 0;
        
        if ($this->politicaDePreco != null) {
            
            $duracao = $this->politicaDePreco->getPlanoDeAdesao()->getDuracao();
            $valor = $this->politicaDePreco->getValor();
            
            $valorTotal = ($valor * $duracao);
            
        }
        
        return $valorTotal;
        
    }
    
    public function status() {
        
        if ($this->getDataDeFinalizacao() != null) {
            return StatusDoContrato::FINALIZADO;
        }
        
        $cancelamento = $this->getCancelamento();
        
        if (isset($cancelamento) && $cancelamento->getId() != 0) {
            return StatusDoContrato::CANCELADO;
        }
        
        if ($this->getDataDeAtivacao() != null) {
            return StatusDoContrato::ATIVO;
        }
        
        return StatusDoContrato::AGUARDANDO_ATIVACAO;
        
    }
    
    public function gratuito() {
        
        if ($this->politicaDePreco == null) {
            return false;
        }
        
        return $this->politicaDePreco->getPlanoDeAdesao()->isGratis();
        
    }
    
    public function cancelado() {
        
        return ($this->status() == StatusDoContrato::CANCELADO);
        
    }
    
    public function duracao() {
        
        if ($this->politicaDePreco == null) {
            return 0;
        }
        
        return $this->politicaDePreco->getPlanoDeAdesao()->getDuracao();
        
    }
    
    public function valorMensal() {
        
        return $this->getPoliticaDePreco()->getValor();
        
    }
    
    public function dataPrevistaParaFinalizacao() {
        
        $dataPrevistaParaFinalizacao = null;
        
        $contratoAguardandoAtivacao = ($this->status() == StatusDoContrato::AGUARDANDO_ATIVACAO);
        
        if (! $contratoAguardandoAtivacao) {
            $dataDeCriacao = $this->dataDeCriacao;
            $meses = $this->duracao();
            $dataPrevistaParaFinalizacao = Utilitarios::adicionarMeses($dataDeCriacao, $meses);
        }
        
        return $dataPrevistaParaFinalizacao;
        
    }
    
    private function cancelarPagamentosNaoQuitados(Cancelamento $cancelamento) {
        
        $pagamentosNaoQuitados = $this->pagamentosNaoQuitados();
        
        foreach ($pagamentosNaoQuitados as $pagamento) {
            $pagamento->setCancelamento($cancelamento);
        }
        
    }
    
    public function pagamentosNaoQuitados() {
        
        $pagamentosNaoQuitados = new Collection();
        
        //$pagamentosDoContrato = $this->getPagamentos()->toArray();
		$pagamentosDoContrato = $this->pagamentos;
        
        foreach ($pagamentosDoContrato as $pagamento) {
            $pagamentoPendente = ($pagamento->status() == StatusDoPagamento::PENDENTE);
            $pagamentoEmAtraso = ($pagamento->status() == StatusDoPagamento::EM_ATRASO);
            if ($pagamentoEmAtraso || $pagamentoPendente) {
                $pagamentosNaoQuitados->add($pagamento);
            }
        }
        
        return $pagamentosNaoQuitados->toArray();
        
    }
    
    public function pagamentosNaoCancelados() {

        $pagamentosCancelados = new Collection();
        
        foreach ($this->pagamentos as $pagamento) {
            $pagamentoCancelado = ($pagamento->status() == StatusDoPagamento::CANCELADO);
            if ($pagamentoCancelado) {
                $pagamentosCancelados->add($pagamento);
            }
        }
        
        return $pagamentosCancelados->toArray();        
        
    }
    
    public function pagamentosEmAtraso() {

        $pagamentosEmAtraso = new Collection();
        
        $pagamentosNaoQuitados = $this->pagamentosNaoQuitados();
        
        foreach ($pagamentosNaoQuitados as $pagamento) {
            $pagamentoEmAtraso = ($pagamento->status() == StatusDoPagamento::EM_ATRASO);
            if ($pagamentoEmAtraso) {
                $pagamentosEmAtraso->add($pagamento);
            }
        }
        
        return $pagamentosEmAtraso->toArray();                
        
    }
    
    public function pagamentosForaDaTolerancia($diasDeToleranciaParaPagamento) {
        
        $pagamentosEmAtraso = $this->pagamentosEmAtraso();
        
        $pagamentosForaDaTolerancia = new Collection();
        
        $dataDoDia = date(Utilitarios::FORMAT_DMYY);
        
        foreach ($pagamentosEmAtraso as $pagamento) {
            
            $dataDeVencimento = $pagamento->getDataDeVencimento();
            $dataLimite = Utilitarios::adicionarDias($dataDeVencimento, $diasDeToleranciaParaPagamento);
            
            if (Utilitarios::dataMenor($dataLimite, $dataDoDia)) {
                $pagamentosForaDaTolerancia->add($pagamento);
            }
            
        }
        
        return $pagamentosForaDaTolerancia->toArray();
        
    }
 
    public function adicionarPagamento(Pagamento $pagamento) {
        
        $pagamento->setContrato($this);
        
        $pagamentoAssociado = $this->pagamentos->contains($pagamento);
        
        if(! $pagamentoAssociado) {
            $this->pagamentos->add($pagamento);
        }
        
    }
 
    public function modulos() {
        
        return $this->politicaDePreco->getPlanoDeAdesao()->getModulos();
        
    }
	
	public function cancelar(Cancelamento $cancelamento) {
	
		$this->cancelamento = $cancelamento;
		
		if (! $this->gratuito()) {
			$this->cancelarPagamentosNaoQuitados($cancelamento);
		}
	
	}
	
    public function equals(Contrato $object) {
        
        if (! isset($object)) {
            return false;
        }

        if ($object == $this) {
                return true;
        }

        return ($object->getId() == $this->id);        
        
    }
	
}