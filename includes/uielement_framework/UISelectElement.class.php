<?php
class UISelectElement extends UIElement{
    
    private $options;
    
    function __construct($properties = array(), $extraTxt = '') {        
        parent::init($properties, $extraTxt);
        $this->options = array();            
    }        
    
    public function addOption($value, $label, $selected = false) {
        $optionSelected = $selected ? 'selected' : '';
        $this->options[] = "<option value='{$value}' {$optionSelected}>" . $label . "</option>";        
    }

    public function toHTML() {     
        $value = "<select {$this->getProperties()}>";
	    foreach ($this->options as $option) {
            $value .= "\n" . $option;        
        }        
        $value .=  "\n" . "</select>";                                
        return $value;
    }        
}