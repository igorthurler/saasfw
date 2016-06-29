<?php
abstract class ConfigAdminView {   
        
    public static function montarFormulario(ConfigAdmin $configAdmin) {
        
        $id = $configAdmin->getId();
        $diasTolerancia = $configAdmin->getDiasDeToleranciaParaPagamento();
        $diasCobranca = $configAdmin->getDiasParaEnvioDaCobrancaDePagamentosPendentes();
        $enviaEmailCobranca = $configAdmin->isEnviaEmailDeCobrancaParaPagamentosEmAtraso() ? 'checked' : '';
        $finalizaContrato = $configAdmin->isFinalizarContratosAutomaticamente() ? 'checked' : '';
        $tiposDePagamento = TipoDePagamento::getArray();
        $tipoPgtoGratis = ($configAdmin->getTipoPgtoGratis() != null) ? $configAdmin->getTipoPgtoGratis() : null;
        $formasDePagamento = FormaDePagamento::getArray();
        $formaPgtoGratis = ($configAdmin->getFormaPgtoGratis() != null) ? $configAdmin->getFormaPgtoGratis() : null;
                
        $onSubmit = "onsubmit = \"return processarFormConfigAdmin(this, 'configadmin', 'salvar');\"";
        $form = new PfwForm();
        $form->setFormHeader('post', '?', array('name'=>'frmConfigAdmin', 'id'=>'frmConfigAdmin', 'class'=>'form-horizontal'), 
            $onSubmit, "");
            
        $form->addChild(new UIInputHiddenElement(array('name'=>'id_configadmin', 'id'=>'id_configadmin','value'=>$id)));
        
        //$form->addChild("<div id=\"configTarefas\" class=\"tab-pane\">");
        
		$form->addControlGroup(new UILabelElement('Tolerância para pagamento *', array('for'=>'diasDeToleranciaParaPagamento', 'class'=>'control-label')),
			new UIInputTextElement(array('name'=>'diasDeToleranciaParaPagamento', 'id'=>'diasDeToleranciaParaPagamento', 'class'=>'form-control',
            'maxlength'=>'3', 'value'=>$diasTolerancia), 'onkeypress="return somenteNumero(event);"'));
       
	    $form->addControlGroup(new UILabelElement('Dias para cobrança de pagamentos *', array('for'=>'diasCobrancaPagamentos', 'class'=>'control-label')),
			new UIInputTextElement(array('name'=>'diasCobrancaPagamentos', 'class'=>'form-control',
            'id'=>'diasCobrancaPagamentos', 'maxlength'=>'100', 'value'=>$diasCobranca), 
            'onkeypress="return somenteNumero(event);"'));

		$form->addControlGroup(new UILabelElement('Envia email de cobrança para pagamentos em atraso?', 
            array('for'=>'enviaEmailDeCobrancaParaPagamentosEmAtraso', 'class'=>'control-label')),
				new UIInputCheckBoxElement('',array('name'=>'enviaEmailDeCobrancaParaPagamentosEmAtraso', 
            'id'=>'enviaEmailDeCobrancaParaPagamentosEmAtraso', 'value'=>'1'), $enviaEmailCobranca));

		$form->addControlGroup(new UILabelElement('Finaliza contrato automaticamente?', 
            array('for'=>'finalizarContratosAutomaticamente', 'class'=>'control-label')),
			new UIInputCheckBoxElement('', array('name'=>'finalizarContratosAutomaticamente', 
            'id'=>'finalizarContratosAutomaticamente', 'value'=>'1'), $finalizaContrato));        
                
        $cbbFormaPgto = new UISelectElement(array('name'=>'formadepagamento', 'id'=>'formadepagamento',  'class'=>'form-control'));
        $cbbFormaPgto->addOption('', 'Selecione uma forma de pagamento');
        foreach ($formasDePagamento as $formaDePagamento) {
            $idFormaPgto = $formaDePagamento->ordinal();
            $descFormaPgto = $formaDePagamento->getValue();
            $checked = $formaDePagamento->equalsByOrdinal($formaPgtoGratis);
            $cbbFormaPgto->addOption($idFormaPgto, $descFormaPgto, $checked);
        }        
		$form->addControlGroup(new UILabelElement('Forma de Pagamento Contrato Gratuito', array('for'=>'formadepagamento', 
			'id'=>'lblFormaDePagamento', 'class'=>'control-label')), $cbbFormaPgto);

        $cbbTipoPgto = new UISelectElement(array('name'=>'tipodepagamento', 'id'=>'tipodepagamento', 'class'=>'form-control'));
        $cbbTipoPgto->addOption('', 'Selecione um tipo de pagamento');
        foreach ($tiposDePagamento as $tipoDePagamento) {
            $idTipoPgto = $tipoDePagamento->ordinal();
            $descTipoPgto = $tipoDePagamento->getValue();
            $checked = $tipoDePagamento->equalsByOrdinal($tipoPgtoGratis);
            $cbbTipoPgto->addOption($idTipoPgto, $descTipoPgto, $checked);
        }
        $form->addControlGroup(new UILabelElement('Tipo de Pagamento Contrato Gratuito', array('for'=>'tipodepagamento', 
			'id'=>'lblTipoDePagamento', 'class'=>'control-label')), $cbbTipoPgto);		                
                
        //$form->addChild("</div>");
        
        $form->addControlGroup(new PfwBtnConfirma());
       
        
        /*echo "<script type=\"text/javascript\">
        $(document).ready(function(){ 
            $('#tabConfig li:eq(0) a').tab('show');
        });
        </script>";       */
        
        $fieldSet = new UIFieldSetElement('Configurações Administrativas');
        $fieldSet->addChild('<div class="col-lg-5">');       
        /*$fieldSet->addChild("<ul class=\"nav nav-tabs\" id=\"tabConfig\">");
        $fieldSet->addChild("<li><a data-toggle=\"tab\" href=\"#configTarefas\">Automatização de tarefas</a></li>");
        $fieldSet->addChild("<li><a data-toggle=\"tab\" href=\"#configPerfis\">Perfis</a></li>");
        $fieldSet->addChild("</ul>");        */
        $fieldSet->addChild($form);  
        $fieldSet->addChild("</div>");
        $fieldSet->show();                 
        
    }
    
}