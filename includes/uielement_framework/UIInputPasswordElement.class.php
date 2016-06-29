<?php
class UIInputPasswordElement extends UIElement {   
    
    function __construct($properties = array(), $extraTxt = '') {        
        parent::init($properties, $extraTxt);        
    }    

    public function toHTML() {
        return "<input type='password' {$this->getProperties()}/>";
    }    
}