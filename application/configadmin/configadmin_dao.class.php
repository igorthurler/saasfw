<?php
class ConfigAdminDAO extends DAO {
	
    public function buscarConfiguracaoAdministrativa() {
        
        $filter = $this->getFilterFor('ConfigAdmin');
        return $filter->getUnique();
		
    }
    
    public function totalDeRegistros() {
        
        $filter = $this->getFilterFor('ConfigAdmin');        
        return $filter->getCount();
        
    }
    
}