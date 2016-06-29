<?php
class PfwBtnConfirma extends UIElement {
    
    function __construct($desabilida = '') {                
        $btn = new UISubmitElement(array('id' => 'confirmar', 'class' => 'btn btn-primary',
            'value' => 'confirmar'), $desabilida);
        $this->addChild($btn);
    }      
    
    public function toHTML() {
       return $this->getAdded();        
    }    
}