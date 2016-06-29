<?php
/**
 * @Entity
 * @Table(name="Pagamento")
 */
class Pagamento {
    
    /**
     *
     * @var int
     * @Id(strategy=GenerationType.AUTO) 
     * @Column(name="id")
     */    
    private $id;
    
    /**
     * @var Contrato
     * @Column(name="contrato") 
     * @ManyToOne(fetch=FetchType.LAZY, cascade=CascadeType.NONE)         
     */    
    private $contrato;
    
    /**
     *
     * @var date
     * @Column(name="dataDeCriacao")
     */            
    private $dataDeCriacao;
    
    /**
     *
     * @var date
     * @Column(name="dataDePagamento")
     */            
    private $dataDePagamento;

    /**
     *
     * @var date
     * @Column(name="dataDeConfirmacao")
     */            
    private $dataDeConfirmacao;
    
    /**
     *
     * @var date
     * @Column(name="dataDeVencimento")
     */            
    private $dataDeVencimento;
    
    /**
     *
     * @var Cancelamento
     * @Column(name="cancelamento") 
     * @OneToOne(fetch=FetchType.LAZY, cascade=CascadeType.ALL)
     */        
    private $cancelamento;
    
    /**
     *
     * @var float
     * @Column(name="valor") 
     */        
    private $valor;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getContrato() {
        return $this->contrato;
    }

    public function setContrato(Contrato $contrato) {
        $this->contrato = $contrato;
    }

    public function getDataDeCriacao() {
        return $this->dataDeCriacao;
    }

    public function setDataDeCriacao($dataDeCriacao) {
        $this->dataDeCriacao = $dataDeCriacao;
    }

    public function getDataDePagamento() {
        return $this->dataDePagamento;
    }

    public function setDataDePagamento($dataDePagamento) {
        $this->dataDePagamento = $dataDePagamento;
    }

    public function getDataDeConfirmacao() {
        return $this->dataDeConfirmacao;
    }

    public function setDataDeConfirmacao($dataDeConfirmacao) {
        $this->dataDeConfirmacao = $dataDeConfirmacao;
    }    
    
    public function getDataDeVencimento() {
        return $this->dataDeVencimento;
    }

    public function setDataDeVencimento($dataDeVencimento) {
        $this->dataDeVencimento = $dataDeVencimento;
    }

    public function getCancelamento() {
        return $this->cancelamento;
    }

    public function setCancelamento(Cancelamento $cancelamento) {
        $this->cancelamento = $cancelamento;
    }

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }

    public function status() {
       
        if ($this->cancelamento != null) {
            return StatusDoPagamento::CANCELADO; 
        }        
        
        if ($this->dataDePagamento != null) {
            return StatusDoPagamento::PAGO;
        }

        $dataDoDia = date(Utilitarios::FORMAT_DMYY);
        if (Utilitarios::dataMenor($this->dataDeVencimento, $dataDoDia)) {
            return StatusDoPagamento::EM_ATRASO;
        }
        
        return StatusDoPagamento::PENDENTE;
    }
    
    public function ehPrimeiroPagamentoDoContrato() {

            if ($this->contrato == null) {
                    return false;
            }

            $contratoAssociado = $this->getContrato();

            $primeiroPagamento = $contratoAssociado->getPagamentos()->get(0);

            return $primeiroPagamento->equals($this);

    }
	
    public function equals(Pagamento $object) {
        
        if (! isset($object)) {
            return false;
        }

        if ($object == $this) {
                return true;
        }

        return ($object->getId() == $this->id);        
        
    }
    
}