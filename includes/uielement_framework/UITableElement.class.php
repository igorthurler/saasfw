<?php
class UITableElement extends UIElement {
       
    private $tableHeader;
    private $tableRow;
    
    function __construct($properties = array(), $extraTxt = '') {        
        parent::init($properties, $extraTxt);
    }            
    
    public function addTableHeader(UITableHeaderElement $tableHeader) {
        $this->tableHeader[] = $tableHeader;
    }
    
    public function addTableRow($rowNumber, UITableDataElement $rowElement) {
        $this->tableRow[$rowNumber][] = $rowElement;
    }
    
	public function rowCount() {
		return count($this->tableRow);
	}
	
    public function toHTML() { 
        $result = "<table {$this->getProperties()}>\n<thead>";        

        if (isset($this->tableHeader)) {
            $result .= "<tr>\n";
            foreach ($this->tableHeader as $header) {
                $result .= $header->toHTML();
            }
            $result .= "</tr>\n";
        }
        
        $result .= "</thead>\n<tbody>\n";
        
        $quantRegistros = isset($this->tableRow) ? count($this->tableRow) : 0;
        
        if ($quantRegistros > 0) {
            for ($i = 0; $i <= (count($this->tableRow) - 1); $i++) {
                $result .= "<tr>\n";
                $datas = $this->tableRow[$i + 1];
                foreach ($datas as $data) {
                    $result .= $data->toHTML();
                }            
                $result .= "</tr>\n";   
            }                        
        }
                             
        $result .= "</tbody>\n</table>";
        
        return $result;
    }    
}