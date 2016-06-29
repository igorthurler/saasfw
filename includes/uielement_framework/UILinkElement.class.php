<?php
class UILinkElement extends UIElement {
    
    private $href;
    private $label;
    
    function __construct($href = '#', $label = '', $properties = array(), $extraTxt = '') {        
        parent::init($properties, $extraTxt);
        $this->href =  $href;
        $this->label = $label;            
    }

    public function toHTML() {        
        $value = "<a href=\"{$this->href}\" {$this->getProperties()}>" . "\n" .
            $this->label . "\n" .  "</a>";        
        return $value;
    }
}