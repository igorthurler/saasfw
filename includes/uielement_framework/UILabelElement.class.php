<?php
class UILabelElement extends UIElement {
    
    private $label;
    
    function __construct($label = '', $properties = array(), $extraTxt = '') {        
        parent::init($properties, $extraTxt);
        $this->label = $label;            
    }

    public function toHTML() {        
        $value = "<label {$this->getProperties()}>" . "\n" .
            $this->label . 
            $this->getAdded() .
            "\n" .  "</label>";        
        return $value;
    }
}