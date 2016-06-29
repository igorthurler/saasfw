<?php

class PfwForm extends UIFormElement
{
	function addControlGroup($controlLabelElement = null, $controlElement = null) {
        
        $this->addChild("<div class=\"form-group\">");
		if ($controlLabelElement != null) {		
			$this->addChild($controlLabelElement);
		}
		if ($controlElement != null) {
			$this->addChild($controlElement);
		}        
        $this->addChild("</div>");	
        
	}	
    
    function showForm($caption) {
    
        $fieldSet = new UIFieldSetElement($caption);
        $fieldSet->addChild('<div class="col-lg-5">');
        $fieldSet->addChild($this);
        $fieldSet->addChild('</div>');
        $fieldSet->show();    
    
    }
}