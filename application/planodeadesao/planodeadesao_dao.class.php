<?php
class PlanoDeAdesaoDAO extends DAO {
    
    function descricaoCadastrada(PlanoDeAdesao $planoDeAdesao) {

        $filter = $this->getFilterFor("PlanoDeAdesao");
        $filter->add(FilterParameter::eq("descricao", $planoDeAdesao->getDescricao())); 
                
        if ($planoDeAdesao->getId() != 0) {                      
           $filter->add(FilterParameter::ne("id", $planoDeAdesao->getId()));
        }               

        return ($filter->getCount() > 0);
        
    }

    function associadoAUmaPoliticaDePreco(PlanoDeAdesao $planoDeAdesao) {

        $filter = $this->getFilterFor("PoliticaDePreco");
        $filter->add(FilterParameter::eq("planoDeAdesao", $planoDeAdesao->getId()));

        return ($filter->getCount() > 0);
        
    }

    public function totalDeRegistros() {
        
        $filter = $this->getFilterFor('PlanoDeAdesao');
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        return $filter->getCount();
        
    }        
    
    public function buscarPlanosDeAdesaoAtivos() {

        $filter = $this->getFilterFor("PlanoDeAdesao");
        $filter->add(FilterParameter::isNull("cancelamento"));             

        return $filter->getList();                        
        
    }
    
    public function buscarPlanosDeAdesao($inicio, $limite) {
        
        $filter = $this->getFilterFor("PlanoDeAdesao");
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        $filter->limit($limite);
        $filter->offset($inicio);
        
        return $filter->getList();
        
    }    
    
    public function buscarPlanos(Projeto $projeto) {

        $filter = $this->getFilterFor("PlanoDeAdesao");
        $filter->add(FilterParameter::eq("projeto", $projeto->getId()));
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        
        return $filter->getList();
    
    }
    
    public function buscarTodos() {

        $filter = $this->getFilterFor("PlanoDeAdesao");
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        
        return $filter->getList();        
                        
    }
 
}