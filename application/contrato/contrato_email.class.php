<?php
class ContratoEmail extends EnviaEmail {
		
    public function notificarCriacaoDoContrato(Contrato $contrato) {       
        $nome = $contrato->getContratante()->getPessoa()->getNome();
        $codContrato = $contrato->getCodigo(); 
        $statusContrato = $contrato->status();
        $formaDePagamento = $contrato->getFormaDePagamento()->getValue();
        $tipoDePagamento = $contrato->getTipoDePagamento()->getValue();
        $emailDoContratante = $contrato->getContratante()->getPessoa()->getEmail();		
	$senhaProvisoria = $contrato->getContratante()->getPessoa()->getSenhaDescriptografada();
        
	$mensagem = "<h1>Cria��o de contrato.</h1>";
	$mensagem .= "<p>";
	$mensagem .= "Prezado(a) Sr.(a) $nome,<br/><br/>";
	$mensagem .= "Seu contrato foi criado com sucesso. Os dados do seu contrato s�o:<br/>";
	$mensagem .= "Contrato: $codContrato<br/>";
	$mensagem .= "Forma de pagamento: $formaDePagamento<br/>";
	$mensagem .= "Tipo de pagamento: $tipoDePagamento<br/>";
        if (isset($senhaProvisoria) && $senhaProvisoria != '') {
            $mensagem .= "<strong>Senha Provis�ria: $senhaProvisoria</strong><br/>";
        }        
	$mensagem .= "</p><br/>";
        $mensagem .="<strong>O status atual do seu contrato �: $statusContrato.</strong><br/>";
	$mensagem .= "<hr/>";
	$mensagem .= "<br/>"+
	$mensagem .= "Este � um e-mail autom�tico disparado pelo sistema.";
        
        $this->enviarEmail($emailDoContratante, "Cria��o do contrato", $mensagem);        
    }

    public function notificarDesativacaoDoContrato(Contrato $contrato) {
        $nome = $contrato->getContratante()->getPessoa()->getNome();
        $codContrato = $contrato->getCodigo(); 
        $statusContrato = $contrato->status();
        $cancelamento = $contrato->getCancelamento();
        $dataDeCancelamento = Utilitarios::dataFormatada($cancelamento->getData());
        $motivoCancelemento = utf8_decode($cancelamento->getMotivo());
        $emailDoCliente = $contrato->getContratante()->getPessoa()->getEmail();		
        
	$mensagem = "<h1>Cancelamento de contrato.</h1>";
	$mensagem .= "<p>";
	$mensagem .= "Prezado(a) Sr.(a) $nome,<br/><br/>";
	$mensagem .= "O contrato $codContrato foi cancelado em $dataDeCancelamento pelo seguinte motivo:<br/>";
	$mensagem .= "$motivoCancelemento<br/>";
	$mensagem .= "Entre em contato com a administra��o do sistema. ";
	$mensagem .= "</p><br/>";
        $mensagem .="<strong>O status atual do seu contrato �: $statusContrato.</strong><br/>";
	$mensagem .= "<hr/>";
	$mensagem .= "<br/>";
	$mensagem .= "Este � um e-mail autom�tico disparado pelo sistema.";       
        
        $this->enviarEmail($emailDoCliente, "Cancelamento do contrato", $mensagem);
    }    
    
    public function notificarFinalizacaoDoContrato(Contrato $contrato) {
        $nome = $contrato->getContratante()->getPessoa()->getNome();
        $codContrato = $contrato->getCodigo(); 
        $statusContrato = $contrato->status();
        $dataDeFinalizacao = Utilitarios::dataFormatada($contrato->getDataDeFinalizacao());
        $emailDoCliente = $contrato->getContratante()->getPessoa()->getEmail();		
        
	$mensagem = "<h1>Finaliza��o de contrato.</h1>";
	$mensagem .= "<p>";
	$mensagem .= "Prezado(a) Sr.(a) $nome,<br/><br/>";
	$mensagem .= "O contrato $codContrato foi finalizado em $dataDeFinalizacao.<br/>";
	$mensagem .= "Renove seu contrato ou entre em contato com a administra��o.";
	$mensagem .= "</p><br/>";
        $mensagem .="<strong>O status atual do seu contrato �: $statusContrato.</strong><br/>";
	$mensagem .= "<hr/>";
	$mensagem .= "<br/>";
	$mensagem .= "Este � um e-mail autom�tico disparado pelo sistema.";
               
        $this->enviarEmail($emailDoCliente, "Finaliza��o do contrato", $mensagem);        
    }    
    
    public function notificarConfirmacaoDoPagamento(Pagamento $pagamento) {
        $contrato = $pagamento->getContrato();
        $nome = $contrato->getContratante()->getPessoa()->getNome();
        $codContrato = $contrato->getCodigo(); 
        $statusContrato = $contrato->status();
        $vencimento = Utilitarios::dataFormatada($pagamento->getDataDeVencimento());
        $valor = Utilitarios::valorFormatado($pagamento->getValor(), 2);
	$emailDoCliente = $contrato->getContratante()->getPessoa()->getEmail();
        
	$mensagem ="<h1>Confirma��o de pagamento.</h1>";
	$mensagem .="<p>";
	$mensagem .="Prezado(a) Sr.(a) $nome,<br/><br/>";
	$mensagem .="Recebemos o seu pagamento referente ao:<br/>";
	$mensagem .="Contrato: $codContrato<br/>";
	$mensagem .="Vencimento : $vencimento<br/>";
	$mensagem .="Valor: R$$valor<br/><br/>";                
	$mensagem .="Atenciosamente.<br/>";
	$mensagem .="</p><br/>";
        $mensagem .="<strong>O status atual do seu contrato �: $statusContrato.</strong><br/>";
	$mensagem .="<hr/>";
	$mensagem .="<br/>";
	$mensagem .="Este � um e-mail autom�tico disparado pelo sistema, favor n�o responder";
                
        $this->enviarEmail($emailDoCliente, "Confirma��o de pagamento", $mensagem);
    }
	
    public function notificarCobrancaDePagamento($pagamento) {

        $contrato = $pagamento->getContrato();
        $nome = $contrato->getContratante()->getPessoa()->getNome();
        $codContrato = $contrato->getCodigo(); 
        $statusContrato = $contrato->status();
        $vencimento = Utilitarios::dataFormatada($pagamento->getDataDeVencimento());
        $valor = Utilitarios::valorFormatado($pagamento->getValor(), 2);
        $pgtoEmAtraso = ($pagamento->status() == StatusDoPagamento::EM_ATRASO);	
        $emailDoCliente = $contrato->getContratante()->getPessoa()->getEmail();		
		
	$mensagem ="<h1>Cobran�a de pagamento.</h1>";
	$mensagem .="<p>";
	$mensagem .="Prezado(a) Sr.(a) $nome,<br/><br/>";
	if ($pgtoEmAtraso) {
		$mensagem .= "At� o momento n�o confirmamos o pagamento do seu contrato.<br/>";
	} else {
		$mensagem .= "Segue abaixo os dados de cobran�a para os servi�os prestados:<br/>";
	}
	$mensagem .="<strong>Dados do pagamento:</strong><br/>";
	$mensagem .="Contrato: $codContrato<br/>";
	$mensagem .="Vencimento : $vencimento<br/>";
	$mensagem .="Valor: R$$valor<br/><br/>";                
	$mensagem .="Atenciosamente.<br/>";
	$mensagem .="</p><br/>";
        $mensagem .="<strong>O status atual do seu contrato �: $statusContrato.</strong><br/>";
	$mensagem .="<hr/>";
	$mensagem .="<br/>";
	$mensagem .="Este � um e-mail autom�tico disparado pelo sistema, favor n�o responder";
        
        $this->enviarEmail($emailDoCliente, "Cobran�a de pagamento", $mensagem);	
    }
	
}