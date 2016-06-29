<?php
class UIInputRadioElement extends UIElement {
    
    private $label;
    
    function __construct($label = '', $properties = array(), $extraTxt = '') {        
        parent::init($properties, $extraTxt);
        $this->label = $label;            
    }    
    
    public function toHTML() {
        $value = "<input type=\"radio\" {$this->getProperties()}>" . "\n";
        $value .= $this->label;
        $value .= "\n" . "</input>";        
        return $value;
    }    
}