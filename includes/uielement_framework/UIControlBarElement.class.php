<?php
class UIControlBarElement extends UIElement {    
    
    const SEPARATOR = '|';
    
    function __construct($properties = array(), $extraTxt = '') {        
        parent::init($properties, $extraTxt);
    }        

    public function toHTML() {
        $result = "<div {$this->getProperties()}>\n";
        
        $i = 0;
        $elements = $this->getChildrenElements();
        
        foreach ($elements as $element) {
            if ($i > 0) {
                $result .= static::SEPARATOR;
            }            
            $result .= $element->toHTML();
            $i++;            
        }
        
        $result .= "</div>\n";
        
        return $result;
    }    
}