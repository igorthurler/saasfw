<?php
class UIInputTextElement extends UIElement {   
    
    function __construct($properties = array(), $extraTxt = '') {        
        parent::init($properties, $extraTxt);        
    }    

    public function toHTML() {
        return "<input type='text' {$this->getProperties()}/>";
    }    
}