<?php
class PfwCampoDocumento extends UIElement {
    
    function __construct($valor, $desabilitado = '') {                
        $label = new UILabelElement('Documento *', array('for'=>'documento', 'id'=>'lblDocumento', 'class'=>'control-label'));
        $input = new UIInputTextElement(array('name'=>'documento', 'id'=>'documento', 'class'=>'form-control', 
                'maxlength'=>'14', 'value'=>$valor), "onkeypress=\"return somenteNumero(event);\"
                onChange=\"buscarDadosDaPessoa(this.value);\" " . $desabilitado);
        $this->addChild("<div class=\"form-group\">");            
        $this->addChild($label);
        $this->addChild($input);
        $this->addChild("</div>");	
    }      
    
    public function toHTML() {
       return $this->getAdded();        
    }    
}