<?php
abstract class ConfigAdminFactory {
    
    static function criarConfigAdmin() {
        
        return new ConfigAdmin();
        
    }
	
    static function atribuirValores(ConfigAdmin &$configAdmin, $dados) {
             
        $id = isset($dados) ? $dados['id_configadmin'] : null;
        $diasCobrancaPagamentos = isset($dados['diasCobrancaPagamentos']) ? 
                $dados['diasCobrancaPagamentos'] : 0;         
        $toleranciaParaPagamento = isset($dados['diasDeToleranciaParaPagamento']) ? 
				$dados['diasDeToleranciaParaPagamento'] : 0;
        $enviaEmailDeCobrancaParaPagamentosEmAtraso = isset($dados['enviaEmailDeCobrancaParaPagamentosEmAtraso']) &&
                ($dados['enviaEmailDeCobrancaParaPagamentosEmAtraso'] == 1);
        $finalizarContratosAutomaticamente = isset($dados['finalizarContratosAutomaticamente']) &&
                ($dados['finalizarContratosAutomaticamente'] == 1);        
        if ($dados['formadepagamento'] != '') {        
            $formaDePagamento = new FormaDePagamento(FormaDePagamento::getFormaDePagamento($dados['formadepagamento']));            
        } else {
            $formaDePagamento = null;
        }
        if ($dados['tipodepagamento'] != '') {
            $tipoDePagamento = new TipoDePagamento(TipoDePagamento::getTipoDePagamento($dados['tipodepagamento']));            
        } else {
            $tipoDePagamento = null;
        }
                        
        $configAdmin->setId($id);        
        $configAdmin->setDiasParaEnvioDaCobrancaDePagamentosPendentes($diasCobrancaPagamentos);
        $configAdmin->setDiasDeToleranciaParaPagamento($toleranciaParaPagamento);
        $configAdmin->setEnviaEmailDeCobrancaParaPagamentosEmAtraso($enviaEmailDeCobrancaParaPagamentosEmAtraso);
        $configAdmin->setFinalizarContratosAutomaticamente($finalizarContratosAutomaticamente); 
        $configAdmin->setFormaPgtoGratis($formaDePagamento);
        $configAdmin->setTipoPgtoGratis($tipoDePagamento);
        
    }
    
    static public function criarConfigAdminDAO($driver) {
	
        return new ConfigAdminDAO($driver);
		
    }
    
    static public function criarConfigAdminBusiness() {
	
        return new ConfigAdminBusiness();
		
    }    
}