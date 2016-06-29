<?php
class UITableDataElement extends UIElement {
       
    private $value;
    
    function __construct($value, $properties = array(), $extraTxt = '') {        
        parent::init($properties, $extraTxt);
        $this->value = $value;
    }            
    
    public function toHTML() { 
        $result = "<td {$this->getProperties()}>";                
        if (is_object($this->value)) {
            $result .= $this->value->toHTML();
        } else {
            $result .= $this->value;
        }
        $result .= parent::getAdded();
        $result .= '</td>';        
        return $result;
    }    
}