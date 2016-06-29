<?php
class UIInputHiddenElement extends UIElement {   
    
    function __construct($properties = array(), $extraTxt = '') {        
        parent::init($properties, $extraTxt);        
    }    

    public function toHTML() {
        return "<input type='hidden' {$this->getProperties()}/>";
    }    
}