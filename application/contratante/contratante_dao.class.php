<?php

class ContratanteDAO extends DAO {    

    public function retornarContratante($idPessoa) {

        $filter = $this->getFilterFor("Contratante");
        $filter->createAlias("pessoa", "pessoa");
        $filter->add(FilterParameter::eq("pessoa.id", $idPessoa));                
        
        return $filter->getUnique();
    
    }  
    
    public function buscarPeloDocumento($documento) {

        $filter = $this->getFilterFor("Contratante");
        $filter->createAlias("pessoa", "pessoa");
        $filter->add(FilterParameter::eq("pessoa.documento", $documento));        
        
        return $filter->getUnique();
        
    }
    
    public function aliasCadastrado(Contratante $contratante) {
        
        $id = ($contratante->getId()!= "") ? $contratante->getId() : 0;
        
        $filter = $this->getFilterFor("Contratante");
        $filter->add(FilterParameter::eq("alias", $contratante->getAlias()));
        $filter->add(FilterParameter::ne("id", $id));
        
        return ($filter->getCount() > 0);
        
    }    
    		
    public function documentoCadastrado(Contratante $contratante) {
        
        $id = ($contratante->getId()!= "") ? $contratante->getId() : 0;
        
        $filter = $this->getFilterFor("Contratante");
        $filter->createAlias("pessoa", "pessoa");
        $filter->add(FilterParameter::eq("pessoa.documento", $contratante->getPessoa()->getDocumento()));
        $filter->add(FilterParameter::ne("id", $id));
        
        return ($filter->getCount() > 0);
        
    }    
    
    public function emailCadastrado(Contratante $contratante) {
        
        $id = ($contratante->getId()!= "") ? $contratante->getId() : 0;
        
        $filter = $this->getFilterFor("Contratante");
        $filter->createAlias("pessoa", "pessoa");
        $filter->add(FilterParameter::eq("pessoa.email", $contratante->getPessoa()->getEmail()));
        $filter->add(FilterParameter::ne("id", $id));
        
        return ($filter->getCount() > 0);        
        
    }
    
    public function possuiContrato(Contratante $contratante) {
        
        $id = ($contratante->getId()!= "") ? $contratante->getId() : 0;
        
        $filter = $this->getFilterFor("Contrato");
        $filter->add(FilterParameter::eq("contratante", $id));
        
        return ($filter->getCount() > 0);        
        
    }
    
    public function totalDeRegistros() {
        
        $filter = $this->getFilterFor('Contratante');
        return $filter->getCount();
        
    }    
    
    public function buscarTodos() {

        $filter = $this->getFilterFor("Contratante");
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        
        return $filter->getList();        
        
    }
    
    public function buscarContratantes($inicio, $limite) {
        
        $filter = $this->getFilterFor("Contratante");
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        $filter->limit($limite);
        $filter->offset($inicio);
        
        return $filter->getList();
        
    }    
 
}