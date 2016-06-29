<?php

class ConfigAdminBusiness extends PfwValidator {

    public function validar(ConfigAdmin $configAdmin) {
                               
        parent::validateGeneral($configAdmin->getDiasParaEnvioDaCobrancaDePagamentosPendentes(),
                'Informe a quantidade de dias para envio de cobran�a para pagamentos pendentes');
     
        parent::validateGeneral($configAdmin->getDiasDeToleranciaParaPagamento(),
                'Informa a toler�ncia para pagamento');
        
        if (parent::foundErrors()) {
            throw new Exception($this->listErrors('<br />'));
        }
        
    }

}