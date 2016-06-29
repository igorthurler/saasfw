<?php
class FormaDePagamento extends Enumeration {

    /**
     * var string
     */
    const BOLETO_BANCARIO = "Boleto Bancário";

    /**
     * var string
     */    
    const CARTAO_DE_CREDITO = "Cartão de Crédito";
    
    /**
     * var string
     */    
    const DEPOSITO_CONTA = "Depósito em Conta";

    /**
     * var string
     */        
    const ISENTO = "Isento";    
       
    public function equalsByOrdinal(FormaDePagamento $object = null) {
        
        if ($object == null) {
            return false;
        }
        
        if ($object->ordinal() == $this->ordinal()) {
            return true;
        }
        
    }
    
    static public function getArray() {
        
        $arr = array();
        $arr[] = new FormaDePagamento(FormaDePagamento::BOLETO_BANCARIO);
        $arr[] = new FormaDePagamento(FormaDePagamento::CARTAO_DE_CREDITO);
        $arr[] = new FormaDePagamento(FormaDePagamento::DEPOSITO_CONTA);
        $arr[] = new FormaDePagamento(FormaDePagamento::ISENTO);
        
        return $arr;
        
    }  
    
    static public function getFormaDePagamento($ordinal) {
        
        switch ($ordinal) {
            case 0: return static::BOLETO_BANCARIO;
            break;
            case 1: return static::CARTAO_DE_CREDITO;
            break;        
            case 2: return static::DEPOSITO_CONTA;
            break;
            case 3: return static::ISENTO;
            break;                
        }
        
    }
    
}
?>