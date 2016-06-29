<?php
class PfwCampoTelefone extends UIElement {
    
    function __construct($label, $identificador, $valor, $desabilitado = '') {                
        $label = new UILabelElement($label, array('for'=>$identificador, 'class'=>'control-label'));
        $input = new UIInputTextElement(array('name'=>$identificador, 'id'=>$identificador, 'class'=>'form-control', 
                'maxlength'=>'11', 'value'=>$valor), "onkeypress=\"return somenteNumero(event);\"". $desabilitado);
                
        $this->addChild("<div class=\"form-group\">");                    
        $this->addChild($label);
        $this->addChild($input);
        $this->addChild("</div>");
    }      
    
    public function toHTML() {
       return $this->getAdded();        
    }    
}