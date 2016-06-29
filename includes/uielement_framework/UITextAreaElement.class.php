<?php
class UITextAreaElement extends UIElement {

    private $text;
    
    function __construct($text = '', $properties = array(), $extraTxt = '') {        
        parent::init($properties, $extraTxt);
        $this->text = $text;
    }            
    
    public function toHTML() {
        return "<textarea {$this->getProperties()}>{$this->text}</textarea>";
    }    
}
?>