<?php
class UITableHeaderElement extends UIElement {
       
    private $value;
    
    function __construct($value = '', $properties = array(), $extraTxt = '') {        
        parent::init($properties, $extraTxt);
        $this->value = $value;
    }            
    
    public function toHTML() { 
        $result = "<th {$this->getProperties()}>";                
        $result .= $this->value;
        $result .= '</th>';       
        return $result;
    }    
}