<?php
class PagamentoDAO extends DAO {
 
	public function buscarPagamentosPendentesNoPeriodo($dataInicial, $dataFinal) {
	
            $filter = $this->getFilterFor("Pagamento");
            $filter->createAlias("contrato", "contrato");

            $filter->add(FilterParameter::isNull("contrato.dataDeFinalizacao"));
            $filter->add(FilterParameter::isNull("contrato.cancelamento"));
		
            $condicaoDtMaiorOuIgual = FilterParameter::ge("dataDeVencimento", $dataInicial);
            $condicaoDtMenorOuIgual = FilterParameter::le("dataDeVencimento", $dataFinal);
            $condicaoDtEntre = FilterParameter::andConditions($condicaoDtMaiorOuIgual, $condicaoDtMenorOuIgual);

            $filter->add($condicaoDtEntre);
            $filter->add(FilterParameter::isNull("dataDePagamento"));

            return $filter->getList();        
    
	}
	
	public function buscarPagamentosEmAtraso($data) {
	
	        $filter = $this->getFilterFor("Pagamento");
        	$filter->createAlias("contrato", "contrato");
		
		// Contrato ativo não está finalizado, não está cancelado e está ativo		
		$filter->add(FilterParameter::isNull("contrato.dataDeFinalizacao"));
		$filter->add(FilterParameter::isNull("contrato.cancelamento"));
		$filter->add(FilterParameter::isNotNull("contrato.dataDeAtivacao"));
		
		$filter->add(FilterParameter::isNull("dataDePagamento"));
		$filter->add(FilterParameter::lt("dataDeVencimento", $data));
		
                $pagamentos = $filter->getList();
                                
		return $pagamentos;
    
	}

}