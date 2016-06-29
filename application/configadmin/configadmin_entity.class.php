<?php

/**
 * @Entity
 * @Table(name="ConfiguracaoAdmin")
 */
class ConfigAdmin {

    /**
     *
     * @var int
     * @Id(strategy=GenerationType.AUTO) 
     */
    private $id;

    /**
     *
     * @var int
     * @Column(name="diasParaEnvioDaCobrancaDePagamentosPendentes")
     */
    private $diasParaEnvioDaCobrancaDePagamentosPendentes;

    /**
     *
     * @var boolean 
     * @Column(name="enviaEmailDeCobrancaParaPagamentosEmAtraso")
     */
    private $enviaEmailDeCobrancaParaPagamentosEmAtraso;

    /**
     *
     * @var boolean
     * @Column(name="finalizarContratosAutomaticamente")
     */
    private $finalizarContratosAutomaticamente;

    /**
     *
     * @var int 
     * @Column(name="diasDeToleranciaParaPagamento")
     */
    private $diasDeToleranciaParaPagamento;

    /**
     *
     * @var FormaDePagamento 
     * @Column(name="formaPgtoGratis")
     */
    private $formaPgtoGratis;    
       
    /**
     *
     * @var TipoDePagamento 
     * @Column(name="tipoPgtoGratis")
     */    
    private $tipoPgtoGratis;  
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDiasParaEnvioDaCobrancaDePagamentosPendentes() {
        return $this->diasParaEnvioDaCobrancaDePagamentosPendentes;
    }

    public function setDiasParaEnvioDaCobrancaDePagamentosPendentes(
    $diasParaEnvioDaCobrancaDePagamentosPendentes) {
        $this->diasParaEnvioDaCobrancaDePagamentosPendentes =
                $diasParaEnvioDaCobrancaDePagamentosPendentes;
    }

    public function isEnviaEmailDeCobrancaParaPagamentosEmAtraso() {
        return $this->enviaEmailDeCobrancaParaPagamentosEmAtraso;
    }

    public function setEnviaEmailDeCobrancaParaPagamentosEmAtraso(
    $enviaEmailDeCobrancaParaPagamentosEmAtraso) {
        $this->enviaEmailDeCobrancaParaPagamentosEmAtraso =
                $enviaEmailDeCobrancaParaPagamentosEmAtraso;
    }

    public function isFinalizarContratosAutomaticamente() {
        return $this->finalizarContratosAutomaticamente;
    }

    public function setFinalizarContratosAutomaticamente(
    $finalizarContratosAutomaticamente) {
        $this->finalizarContratosAutomaticamente =
                $finalizarContratosAutomaticamente;
    }

    public function getDiasDeToleranciaParaPagamento() {
        return $this->diasDeToleranciaParaPagamento;
    }

    public function setDiasDeToleranciaParaPagamento($diasDeToleranciaParaPagamento) {
        $this->diasDeToleranciaParaPagamento = $diasDeToleranciaParaPagamento;
    }

    public function getFormaPgtoGratis() {
        return $this->formaPgtoGratis;
    }

    public function setFormaPgtoGratis(FormaDePagamento $formaPgtoGratis = null) {
        $this->formaPgtoGratis = $formaPgtoGratis;
    }    

    public function getTipoPgtoGratis() {
        return $this->tipoPgtoGratis;
    }

    public function setTipoPgtoGratis(TipoDePagamento $tipoPgtoGratis = null) {
        $this->tipoPgtoGratis = $tipoPgtoGratis;
    }            
    
    public function equals(ConfigAdmin $object) {

        if (!isset($object)) {
            return false;
        }

        if ($object == $this) {
            return true;
        }

        return ($object->getId() == $this->id);
    }

}