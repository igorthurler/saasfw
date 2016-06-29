<?php
class UIFieldSetElement extends UIElement {
    
    private $legend;
    
    function __construct($legend = '', $properties = array(), $extraTxt = '') {
        parent::init($properties, $extraTxt);
        $this->legend = $legend;
    }
    
    public function toHTML() {    
        $legend = ($this->legend != '') ? "\n<legend> {$this->legend} </legend>\n" : "";
        $value = "<fieldset {$this->getProperties()}>";
        if (isset($this->legend) && ($this->legend != "")) {
            $value .= $legend;
        }
        $value .= parent::getAdded() . '</fieldset>';        
        return $value;
    }    
}