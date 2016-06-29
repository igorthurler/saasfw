<?php
class EnderecoDAO extends DAO {

    public function buscarEstados() {        
        $filter = $this->getFilterFor("Estado");
        $filter->addOrderBy(FilterOrderBy::asc("id"));        
        return $filter->getList();                        
    }    
 
    public function buscarEstado($idEstado) {
        $filter = $this->getFilterFor("Estado");
        $filter->add(FilterParameter::eq("id", $idEstado));        
        return $filter->getUnique();
    }
    
    public function buscarCidades(Estado $estado = null) {
		if ($estado == null) {
			return new Collection();
		}
	
        $filter = $this->getFilterFor("Cidade");
        $filter->add(FilterParameter::eq("estado", $estado->getId()));        
        return $filter->getList();                
    }
    
}