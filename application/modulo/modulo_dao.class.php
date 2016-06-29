<?php
class ModuloDAO extends DAO {
    
    function identificadorCadastrado(Modulo $modulo) {

        $filter = $this->getFilterFor("Modulo");
        $filter->add(FilterParameter::eq("identificador", $modulo->getIdentificador()));
                
        if ($modulo->getId() != 0) {                      
           $filter->add(FilterParameter::ne("id", $modulo->getId()));
        }               

        return ($filter->getCount() > 0);
        
    }
    
    function descricaoCadastrada(Modulo $modulo) {
        
        $filter = $this->getFilterFor("Modulo");
        $filter->add(FilterParameter::eq("descricao", $modulo->getDescricao()));
                
        if ($modulo->getId() != 0) {                      
           $filter->add(FilterParameter::ne("id", $modulo->getId()));
        }               

        return ($filter->getCount() > 0);
        
    }
    
    function associadoAUmPlanoDeAdesao(Modulo $modulo) {
        
        $sql = "select count(planodeadesao) as quant
                  from PlanoDeAdesaoModulo
                 where modulo = :MODULO";
        $sql = str_replace(":MODULO", $modulo->getId(), $sql);
        
        $driver = DAOFactory::getDAO()->getDriver();
        
        $retorno = $driver->fetchAssoc($sql);
        
        $d = $retorno[0];
        
        return ($d['quant'] > 0);
        
    }
    
    function buscarAtivos() {

        $filter = $this->getFilterFor("Modulo");
        $filter->add(FilterParameter::isNull("cancelamento"));        
        
        return $filter->getList();
        
    }
    
    public function totalDeRegistros() {
        
        $filter = $this->getFilterFor('Modulo');
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        return $filter->getCount();
        
    }    
    
    public function buscarTodos() {

        $filter = $this->getFilterFor("Modulo");
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        
        return $filter->getList();        
        
    }
    
    public function buscarModulos($inicio, $limite) {
        
        $filter = $this->getFilterFor("Modulo");
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        $filter->limit($limite);
        $filter->offset($inicio);
        
        return $filter->getList();
        
    }
    
    public function buscarModulosPeloId($idDosModulos) {
        
        $filter = $this->getFilterFor("Modulo");
        $filter->add(FilterParameter::isIn("id", $idDosModulos));
        $filter->addOrderBy(FilterOrderBy::asc("id"));                
        
        $modulos = $filter->getList();
		
	return $modulos;
        
    }    

}