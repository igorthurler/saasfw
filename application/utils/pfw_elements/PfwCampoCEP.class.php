<?php
class PfwCampoCEP extends UIElement {
    
    function __construct($valor,$desabilitado = '') {                
        $label = new UILabelElement('Cep *', array('for'=>'cep', 'id'=>'lblCep', 'class'=>'control-label'));
        $input = new UIInputTextElement(array('name'=>'cep', 'id'=>'cep', 'class'=>'form-control', 
            'maxlength'=>'9', 'value'=>$valor),
            "onkeypress=\"return somenteNumero(event);\" onKeyup=\"mascaraCampo('CEP',this,event);\" " . 
                $desabilitado);
                
        $this->addChild("<div class=\"form-group\">");                        
        $this->addChild($label);
        $this->addChild($input);
        $this->addChild("</div>");	
    }      
    
    public function toHTML() {
       return $this->getAdded();        
    }    
}