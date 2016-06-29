<?php
class PfwCampoEstadoCidade extends UIElement {
    
    function __construct($estados, $estadoSelecionado, $cidades, $cidadeSelecionada, $desabilitado = '') {                       
                
        $txtEstado = new UISelectElement(array('name'=>'estado', 'id'=>'estado', 'class'=>'form-control', 
            'maxlength'=>'50'), "onChange=\"preencherCidades(this.value, true);\" " . $desabilitado);
        $txtEstado->addOption('', 'Selecione um estado');
        foreach ($estados as $estado) {
            $checked = $estado->equals($estadoSelecionado);
            $txtEstado->addOption($estado->getId(), utf8_decode($estado->getNome()), $checked);
        }        
        
        $this->addChild("<div class=\"form-group\">");          
		$this->addChild(new UILabelElement('Estado *', array('for'=>'estado', 'id'=>'lblEstado', 'class'=>'control-label')));
        $this->addChild($txtEstado);        
        $this->addChild("</div>");	
       		
        $txtCidade = new UISelectElement(array('name'=>'cidade', 'id'=>'cidade', 'class'=>'form-control', 
            'maxlength'=>'50'), $desabilitado);
        if (isset($cidades)) {
            $txtCidade->addOption('', 'Selecione uma cidade');
            foreach ($cidades as $cidade) {
                $checked = $cidade->equals($cidadeSelecionada);
                $txtCidade->addOption($cidade->getId(), utf8_decode($cidade->getNome()), $checked);
            }               
        }
        
        $this->addChild("<div class=\"form-group\">");          
        $this->addChild(new UILabelElement('Cidade *', array('for'=>'cidade', 'id'=>'lblCidade', 'class'=>'control-label')));
        $this->addChild($txtCidade);    
        $this->addChild("</div>");	
    
    }      
    
    public function toHTML() {
       return $this->getAdded();        
    }    
}