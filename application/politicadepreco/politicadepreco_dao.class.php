<?php
class PoliticaDePrecoDAO extends DAO {
    
    public function politicaDePrecoCadastrada(PoliticaDePreco $politicaDePreco) {        
        $filter = $this->getFilterFor("PoliticaDePreco");
        $filter->add(FilterParameter::eq("data", $politicaDePreco->getData()));
        $filter->add(FilterParameter::eq("planoDeAdesao", $politicaDePreco->getPlanoDeAdesao()->getId()));
        
        if ($politicaDePreco->getId() != 0) {
            $filter->add(FilterParameter::ne("id", $politicaDePreco->getId()));
        }        
        
        return ($filter->getCount() > 0);                        
    }
    
    public function politicaDePrecoAssociadaAUmContrato(PoliticaDePreco $politicaDePreco) {        
        $filter = $this->getFilterFor("Contrato");
        $filter->add(FilterParameter::eq("politicaDePreco", $politicaDePreco->getId()));        
        return ($filter->getCount() > 0);                        
    }
    
    public function totalDeRegistros() {        
        $filter = $this->getFilterFor('PoliticaDePreco');
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        return $filter->getCount();        
    }        
    
    public function listar($inicio, $limite) {        
        $filter = $this->getFilterFor("PoliticaDePreco");
        $filter->addOrderBy(FilterOrderBy::desc("data"));
        $filter->limit($limite);
        $filter->offset($inicio);        
        return $filter->getList();        
    }        
    
    public function buscarTodos($somenteAtivos = true) {
        $filter = $this->getFilterFor("PoliticaDePreco");
		
        if ($somenteAtivos) {
            $filter->createAlias("planoDeAdesao", "planoDeAdesao");	
            $filter->add(FilterParameter::isNull("planoDeAdesao.cancelamento"));
        }
        
        $filter->addOrderBy(FilterOrderBy::desc("data"));
        return $filter->getList();                               
    }    
    
    public function buscarPoliticasParaCriacaoDeContrato() {                
        $sql = "select p.*
                from PoliticaDePreco p
               inner join (select max(p1.data) as data,
                                  p1.PlanoDeAdesao 
                             from PoliticaDePreco p1
                            where p1.data <= CURDATE()
                            group by p1.PlanoDeAdesao)s 
                  on s.data = p.data 
                 and s.planoDeAdesao = p.planoDeAdesao
               inner join PlanoDeAdesao pa 
                  on pa.id = p.planoDeAdesao
				 and pa.cancelamento is null ";
 
        $driver = $this->getDriver();
        $resultados = $driver->fetchAssoc($sql);

        $politicas = new Collection();        
        
        foreach ($resultados as $resultado) {
            $politica = PoliticaDePrecoFactory::criarPoliticaDePreco();
            $politica->setId($resultado['id']);
            $this->load($politica); 
            $politicas->add($politica);
        }
        
        return $politicas->toArray();
    }
        
}