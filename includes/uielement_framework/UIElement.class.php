<?php
abstract class UIElement {
    
    private $childrenElements;
    private $properties;
    private $extraTxt;
         
    protected function init($properties = array(), $extraTxt = '') {
        $this->childrenElements = array();
        $this->properties = $properties;        
        $this->extraTxt = $extraTxt;        
    }
    
    /**
     * Separa array em uma string contendo nome dos atributos e seus valores
     * @param array $properties - array contendo valores extras para a tag
     * */
    protected function getExtraValues($properties) {
        $inExVls = '';
        if (isset($properties) && count($properties)) {
            foreach ($properties as $atrName => $atrValue) {
                $inExVls .= $atrName . "='{$atrValue}' ";
            }
        }
        return $inExVls;
    }
    
    protected function getProperties() {
        return trim($this->getExtraValues($this->properties) . ' ' . $this->extraTxt);
    }
    
    public function addChild($uiElement) {
        $this->childrenElements[] = $uiElement;
    }
    
    public function getChildrenElements() {
        return $this->childrenElements;
    }
    
    protected function getAdded() {
        $value = "";
        if (count($this->childrenElements)) {
            foreach ($this->childrenElements as $childElement) {
                if (is_object($childElement)) {
                    $value .= $childElement->toHTML(). "\n";
                } else {
                    if (is_string($childElement) || is_numeric($childElement)) {
                        $value .= $childElement. "\n";
                    }
                }
            }
        }
        return $value;
    }
    
    abstract public function toHTML();
    
    public function show() {
        echo $this->toHTML();
    }
}