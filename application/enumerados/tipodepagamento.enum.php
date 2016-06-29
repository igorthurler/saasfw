<?php
class TipoDePagamento extends Enumeration {
    
    /**
     * var string
     */
    const AVISTA = "À Vista";
    
    /**
     * var string
     */    
    const PARCELADO = "Parcelado";
    
    /**
     * var string
     */    
    const ISENTO = "Isento";
    
    public function equalsByOrdinal(TipoDePagamento $object = null) {
        
        if ($object == null) {
            return false;
        }
        
        if ($object->ordinal() == $this->ordinal()) {
            return true;
        }
        
    }
    
    static public function getArray() {
        
        $arr = array();
        $arr[] = new TipoDePagamento(TipoDePagamento::AVISTA);
        $arr[] = new TipoDePagamento(TipoDePagamento::PARCELADO);
        $arr[] = new TipoDePagamento(TipoDePagamento::ISENTO);
        
        return $arr;
        
    }        
    
    static public function getTipoDePagamento($ordinal) {
        
        switch ($ordinal) {
            case 0: return static::AVISTA;
            return;
            case 1: return static::PARCELADO;
            return;            
            case 2: return static::ISENTO;
            return;                        
        }
        
    }
    
}
?>