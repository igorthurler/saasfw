<?php
class ContratoDAO extends DAO {

    public function totalDeRegistros() {
        
        $filter = $this->getFilterFor('Contrato');
        return $filter->getCount();
        
    }    
    
    public function buscarTodos() {

        $filter = $this->getFilterFor("Contrato");
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        
        return $filter->getList();        
        
    }
    
    public function buscarContratos($inicio, $limite) {
        
        $filter = $this->getFilterFor("Contrato");
        $filter->addOrderBy(FilterOrderBy::asc("id"));
        $filter->limit($limite);
        $filter->offset($inicio);
        
        return $filter->getList();
        
    }    
    
    public function retornarContratoAtivo(Contratante $contratante) {
                        
        $filter = $this->getFilterFor("Contrato");
        $filter->add(FilterParameter::eq("contratante", $contratante->getId()));
        $filter->add(FilterParameter::isNull("cancelamento"));
        $filter->add(FilterParameter::isNull("dataDeFinalizacao"));
        $filter->add(FilterParameter::isNotNull("dataDeAtivacao"));
        
        return $filter->getUnique();
        
    }    
    
    public function contratoAtivoOuAguardandoAtivacao(Contratante $contratante) {

        $filterAtivo = $this->getFilterFor("Contrato");
        $filterAtivo->add(FilterParameter::eq("contratante", $contratante->getId()));
        $filterAtivo->add(FilterParameter::isNull("cancelamento"));
        $filterAtivo->add(FilterParameter::isNull("dataDeFinalizacao"));
        $filterAtivo->add(FilterParameter::isNotNull("dataDeAtivacao"));

	$filterAguardando = $this->getFilterFor("Contrato");
        $filterAguardando->add(FilterParameter::eq("contratante", $contratante->getId()));
        $filterAguardando->add(FilterParameter::isNull("cancelamento"));
        $filterAguardando->add(FilterParameter::isNull("dataDeFinalizacao"));
        $filterAguardando->add(FilterParameter::isNull("dataDeAtivacao"));		
		
        $ativo = ($filterAtivo->getCount() > 0);         
        $aguardando = ($filterAguardando->getCount() > 0); 
        
        return ($ativo || $aguardando);
        
    }
	
    public function buscarContratosAtivos() {

        $filterAtivo = $this->getFilterFor("Contrato");
        $filterAtivo->add(FilterParameter::isNull("cancelamento"));
        $filterAtivo->add(FilterParameter::isNull("dataDeFinalizacao"));
        $filterAtivo->add(FilterParameter::isNotNull("dataDeAtivacao"));

        return $filterAtivo->getList();

    }
	
    public function possuiContratoGratuito(Contratante $contratante) {        
        
	$filter = $this->getFilterFor("Contrato");
        $filter->createAlias("politicaDePreco", "politicaDePreco");
        $filter->createAlias("politicaDePreco.planoDeAdesao", "planoDeAdesao");
        $filter->add(FilterParameter::eq("contratante", $contratante->getId()));
        $filter->add(FilterParameter::eq("planoDeAdesao.gratis", true));
        
        return ($filter->getCount() > 0);      
		
    }    
	
	public function buscarContratosParaFinalizar($dataDaFinalizacao) {
	
            $contratosAtivos = self::buscarContratosAtivos();

            $contratosParaFinalizar = new ArrayList();

            foreach ($contratosAtivos as $contrato) {		
                    if (Utilitarios::dataMenor($contrato->dataPrevistaParaFinalizacao(), $dataDaFinalizacao)) {
                            $contratosParaFinalizar->add($contrato);
                    }					
            }

            return $contratosParaFinalizar->toArray();
	
	}        
    
}