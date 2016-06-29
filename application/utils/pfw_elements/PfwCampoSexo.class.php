<?php
class PfwCampoSexo extends UIElement {
    
    function __construct($arraySexo, $sexo, $desabilitado = '') {                
        $txtSexo = new UISelectElement(array('name' => 'sexo', 'id' => 'sexo', 'class' => 'form-control',
            'maxlength' => '50'));
        $txtSexo->addOption('', 'Selecione o sexo');
        foreach ($arraySexo as $valor => $texto) {
            $checked = ($valor == $sexo);
            $txtSexo->addOption($valor, utf8_decode($texto), $checked);
        }
        
        $this->addChild("<div class=\"form-group\">");
        $this->addChild(new UILabelElement('Sexo *', array('for' => 'sexo', 'id' => 'lblSexo', 'class' => 'control-label'), $desabilitado));        
        $this->addChild($txtSexo);
        $this->addChild("</div>");
    }      
    
    public function toHTML() {
       return $this->getAdded();        
    }    
}