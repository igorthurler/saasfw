<?php
class PessoaDAO extends DAO {

    public function retornarAutenticado($identificador, $senha) {        
        $filter = $this->getFilterFor("Pessoa");
        $filter->add(FilterParameter::eq("senha", $senha));                
        $condicaoDocumento = FilterParameter::eq("documento", $identificador);
        $condicaoEmail = FilterParameter::eq("email", $identificador);
        $filter->add(FilterParameter::orConditions($condicaoDocumento, $condicaoEmail));                
        return $filter->getUnique();                
    }         
    
    public function buscarPeloDocumento($documento) {        
        $filter = $this->getFilterFor("Pessoa");
        $filter->add(FilterParameter::eq("documento", $documento));        
        return $filter->getUnique();        
    }
    
    public function documentoCadastrado(Pessoa $pessoa) {
        
        $id = ($pessoa->getId()!= "") ? $pessoa->getId() : 0;
        
        $filter = $this->getFilterFor("Pessoa");
        $filter->add(FilterParameter::eq("documento", $pessoa->getDocumento()));
        $filter->add(FilterParameter::ne("id", $id));
        
        return ($filter->getCount() > 0);
        
    }    
    
    public function emailCadastrado(Pessoa $pessoa) {
        
        $id = ($pessoa->getId()!= "") ? $pessoa->getId() : 0;
        
        $filter = $this->getFilterFor("Pessoa");
        $filter->add(FilterParameter::eq("email", $pessoa->getEmail()));
        $filter->add(FilterParameter::ne("id", $id));
        
        return ($filter->getCount() > 0);        
        
    }    
    
    // Retornar os dados referentes a contratantes necessários para o preenchimento do contrato.
    // Retorna um array
    public function buscarDadosPessoaContrato($documento) {
    
        $sql = "select p.id as id_pessoa, p.documento, p.nome, p.estado, p.cidade, p.email,
                       p.cep, p.logradouro, p.numero, p.complemento, p.bairro,
                       c.id as id_contratante
                  from Pessoa p
                  left join Contratante c
                    on c.pessoa = p.id
                 where p.documento = '{$documento}'";
        
        $driver = DAOFactory::getDAO()->getDriver();
        
        return $driver->fetchAssoc($sql);
    
    }
    
}