<?php
class PfwCssMainForm implements ICssMainForm {

	public function retornarCssUlClassMenu() {
		return 'class="nav navbar-nav side-nav"';
	}	
	
	public function retornarCssLiDropDownClass() {
		return 'class="dropdown"';
	}
	
	public function retornarCssDropDownClass() {
		return 'class="dropdown-toggle" data-toggle="dropdown"';
	}		
		
	public function retornarCssUlClassSubMenu() {
		return 'class="dropdown-menu"';
	}
		
	public function retornarCssDropDownImage() {
		return '<b class="caret"></b>';
	}		
	
	public function retornarCssImageClass() {
		return '';
	}	
			
}	