<?php
class UsuarioDAO extends DAO {
    
    public function retornarAutenticado($identificador, $senha) {        
    
        $filter = $this->getFilterFor("Usuario");
        $filter->add(FilterParameter::eq("senha", $senha));                
        $filter->add(FilterParameter::eq("email", $identificador));                
        return $filter->getUnique();                             
        
    }         
    
    public function emailCadastrado(Usuario $usuario) {
        
        $id = ($usuario->getId()!= "") ? $usuario->getId() : 0;
        
        $filter = $this->getFilterFor("Usuario");
        $filter->add(FilterParameter::eq("email", $usuario->getEmail()));
        $filter->add(FilterParameter::ne("id", $id));
        
        return ($filter->getCount() > 0);        
        
    }    
    
    public function buscar($identificador) {
    
        $filter = $this->getFilterFor("Usuario");         
        $filter->add(FilterParameter::eq("email", $identificador));
        return $filter->getUnique();
        
    }
    
    public function totalDeRegistros() {
        
        $filter = $this->getFilterFor('Usuario');
        return $filter->getCount();
        
    }    
    
    public function buscarTodos() {

        $filter = $this->getFilterFor("Usuario");
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        
        return $filter->getList();        
        
    }    
    
    public function buscarUsuarios($inicio, $limite) {        
    
        $filter = $this->getFilterFor("Usuario");
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        $filter->limit($limite);
        $filter->offset($inicio);
        
        return $filter->getList();       
        
    }    
        
}