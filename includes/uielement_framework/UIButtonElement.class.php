<?php
class UIButtonElement extends UIElement {    

    /**
     * Construtor da classe UIButtonElement
     *
     * @param array $properties - Array contendo as propriedades do objeto 'nome da propriedade' => 'valor'
     * @param string $extraTxt - Outras informações relevantes para a construção do objeto
     *
     * */    
    function __construct($properties = array(), $extraTxt = '') {        
        parent::init($properties, $extraTxt);
    }        

    /**
     * Gera a saída HTML para o objeto
     */    
    public function toHTML() {
        return "<input type=\"button\" {$this->getProperties()}/>";
    }    
}