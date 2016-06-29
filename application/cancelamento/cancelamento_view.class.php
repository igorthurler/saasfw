<?php
abstract class CancelamentoView {
    
    static public function mostarInfo(Cancelamento $cancelamento, UIElement &$element) {
        $data = Utilitarios::dataFormatada($cancelamento->getData());
        $responsavel = utf8_decode($cancelamento->getResponsavel()->getNome());
        $motivo = nl2br(utf8_decode($cancelamento->getMotivo()));            
		$element->addChild('<div class="alert alert-danger">');
        $element->addChild('<p><h4><u>Dados do Cancelamento</u></h4></p>');
        $element->addChild("<p>Data da desativação: <strong>{$data}</strong></p>");
		$element->addChild("<p>Responsável: <strong>{$responsavel}</strong></p>");
		$element->addChild("<p>Motivo: <strong>{$motivo}</strong></p>");
        $element->addChild('</div>');        
    }
    
    static public function mostrarForm($dados, Usuario $responsavel) {
  
        $idResponsavel = $responsavel->getId();
        $idRegistroCancelado = $dados['registro'];
        $titulo = $dados['titulo'];

        $onSubmit = "onsubmit = \"return processarFormCancelamento(this, '{$dados['redirecionar']}', 'desativar');\"";
        $form = new PfwForm();        
        $form->setFormHeader('post', 'index.php', array('name'=>'frmCancelamento', 'class'=>'form-horizontal', 
            'id'=>'frmCancelamento'), $onSubmit, "");       
            
        $form->addChild(new UIInputHiddenElement(array('name'=>'id_responsavel', 
            'id'=>'id_responsavel', 'value'=>$idResponsavel)));                        
        $form->addChild(new UIInputHiddenElement(array('name'=>'id_registrocancelado', 
            'id'=>'id_registrocancelado', 'value'=>$idRegistroCancelado)));                                        

        $form->addControlGroup(new UILabelElement('Motivo *', array('for'=>'motivo', 'class'=>'control-label')),
            new UITextAreaElement("", array('name'=>'motivo', 'id'=>'motivo', 'rows'=>'5', 'cols'=>'5', 
			'maxlength'=>'500', 'class'=>'form-control')));

        $form->addControlGroup(new PfwBtnConfirma());	               
        
        $form->showForm(utf8_decode($titulo));
   
    }
}