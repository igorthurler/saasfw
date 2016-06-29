<?php

abstract class PlanoDeAdesaoView {

    public static function montarFormulario(PlanoDeAdesao $planoDeAdesao, $modulos = null, $visualizacao = false) {

        $id = $planoDeAdesao->getId();
        $duracao = $planoDeAdesao->getDuracao();
        $descricao = utf8_decode($planoDeAdesao->getDescricao());
        $desabilita = $visualizacao ? 'disabled' : '';
        $usuarios = $planoDeAdesao->getQuantUsuario();
        $arraySimNao = Utilitarios::arraySimNao();

        $onSubmit = "onsubmit = \"return processarFormPlanoDeAdesao(this, 'planodeadesao', 'salvar');\"";
        $form = new PfwForm();
        $form->setFormHeader('post', '?', array('name' => 'frmPlanoDeAdesao',
            'id' => 'frmPlanoDeAdesao', 'class' => 'form-horizontal'), $onSubmit, "");

        $form->addChild(new UIInputHiddenElement(array('name' => 'id_planoadesao', 'id' => 'id_planoadesao', 'value' => $id)));
            
        $form->addControlGroup(new UILabelElement('Duração *', array('for' => 'duracao', 'class' => 'control-label')), 
			new UIInputTextElement(array('name' => 'duracao', 'id' => 'duracao', 'class' => 'form-control',
            'maxlength' => '2', 'value' => $duracao), $desabilita . ' ' .
                "onkeypress=\"return somenteNumero(event);\""));

        $form->addControlGroup(new UILabelElement('Descrição *', array('for' => 'descricao', 'id' => 'lbl', 'class' => 'control-label')), 
			new UIInputTextElement(array('name' => 'descricao', 'id' => 'descricao', 'class' => 'form-control',
            'maxlength' => '50', 'value' => $descricao), $desabilita));            
            
        $cbbGratis = new UISelectElement(array('name' => 'gratis', 'id' => 'gratis', 'class' => 'form-control',
            'maxlength' => '50'), $desabilita);
        $cbbGratis->addOption('', 'Selecione a opção de gratuidade', true);
        foreach ($arraySimNao as $valor => $texto) {
            $checked = $planoDeAdesao->isGratis() == $valor;
            $cbbGratis->addOption($valor, $texto, $checked);
        }
        $form->addControlGroup(new UILabelElement('Gratis *', array('for' => 'gratis', 
            'class' => 'control-label')), $cbbGratis);

        $form->addControlGroup(new UILabelElement('Quant. Usuarios *', 
            array('for' => 'quantusuario', 'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'quantusuario', 
            'id' => 'quantusuario', 'class' => 'form-control',
            'maxlength' => '50', 'value' => $usuarios), $desabilita . ' ' .
                "onkeypress=\"return somenteNumero(event);\""));

		$form->addChild("<div class=\"form-group\">");	
	    $form->addChild('<p><strong>Módulos</strong></p>');			
        foreach ($modulos as $modulo) {
            $estaSelecionado = $planoDeAdesao->moduloAssociado($modulo);
            if ($estaSelecionado || $modulo->isAtivo()) {
                $checked = $estaSelecionado ? 'checked' : '';
                $chkModulo = new UIInputCheckBoxElement(utf8_decode($modulo->getDescricao()), 
                    array('name'=>'modulos[]', 'id'=>'modulos', 'value'=>"{$modulo->getId()}"), 
                    $desabilita . ' ' . $checked);                    
                $form->addChild($chkModulo);
            }                        
        }	   		
		$form->addChild("</div>");                
                
        $form->addControlGroup(new PfwBtnConfirma($desabilita));

        $fieldSet = new UIFieldSetElement('Cadastro de Plano de Adesão');
        $fieldSet->addChild('<div class="col-lg-5">');
        if (!$planoDeAdesao->isAtivo()) {
            $cancelamento = $planoDeAdesao->getCancelamento();
            CancelamentoView::mostarInfo($cancelamento, $fieldSet);
        }
        $fieldSet->addChild($form);
        $fieldSet->addChild('</div>');
        $fieldSet->show();
    }

    public static function montarLista($planosDeAdesao) {
        $imagens = Utilitarios::arrayImagens();

        $tabela = new UITableElement(array('id' => 'tblPlanoDeAdesao',
            'class' => 'table table-bordered table-hover table-striped tablesorter'));
        $tabela->addTableHeader(new UITableHeaderElement('Id', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Duração(Meses)', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Descrição', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Usuários', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Grátis', array('align' => 'center')));        
        $tabela->addTableHeader(new UITableHeaderElement('Ativo', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Visualizar', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Editar', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Desativar', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Excluir', array('align' => 'center')));

        $linha = 1;
        foreach ($planosDeAdesao as $planoDeAdesao) {
            $id = $planoDeAdesao->getId();
            $duracao = $planoDeAdesao->getDuracao();
            $descricao = utf8_decode($planoDeAdesao->getDescricao());
            $gratis = $planoDeAdesao->isGratis() ?
                    Utilitarios::buscarImagem($imagens['IMG_TRUE']) :
                    Utilitarios::buscarImagem($imagens['IMG_FALSE']);
            $usuarios = $planoDeAdesao->getQuantUsuario();
            $ativo = $planoDeAdesao->isAtivo() ?
                    Utilitarios::buscarImagem($imagens['IMG_TRUE']) :
                    Utilitarios::buscarImagem($imagens['IMG_FALSE']);

            $visualizar = new UILinkElement('javascript:void(0);', Utilitarios::buscarImagem($imagens['IMG_VISUALIZAR']), 
				array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Visualizar'), 
				"onclick=\"ajax.init( 'includes/PfwController.php?app=planodeadesao&action=visualizar&id_planoadesao={$id}', 'viewers' );\"");

            $editar = new UILinkElement('javascript:void(0);', Utilitarios::buscarImagem($imagens['IMG_EDITAR']), 
				array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Editar'), 
				"onclick=\"ajax.init( 'includes/PfwController.php?app=planodeadesao&action=editar&id_planoadesao={$id}', 'viewers' );\"");

            $desativar = new UILinkElement('javascript:void(0);', Utilitarios::buscarImagem($imagens['IMG_DESATIVAR']), array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Desativar'), "onclick=\"executarFuncaoComConfirmacao('Deseja realmente desativar o plano de adesão selecionado?', 
                'includes/PfwController.php?app=cancelamento&registro={$id}&redirecionar=planodeadesao&titulo=Cancelamento do Plano de Adesão');\"");
				
            $excluir = new UILinkElement('javascript:void(0);', Utilitarios::buscarImagem($imagens['IMG_EXCLUIR']), 
				array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Excluir'), 
				"onclick=\"executarFuncaoComConfirmacao('Deseja realmente excluir o plano de adesão selecionado?', 
                'includes/PfwController.php?app=planodeadesao&action=deletar&id_planoadesao={$id}');\"");

            $tabela->addTableRow($linha, new UITableDataElement($id, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($duracao, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($descricao));
            $tabela->addTableRow($linha, new UITableDataElement($usuarios, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($gratis, array('align' => 'center')));            
            $tabela->addTableRow($linha, new UITableDataElement($ativo, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($visualizar, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($editar, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($desativar, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($excluir, array('align' => 'center')));
            $linha++;
        }

        self::showList($tabela);
    }

    private static function showList($tabela) {
        $inserir = new UILinkElement('javascript:void(0);', 'Cadastrar Plano de Adesão', array('alt' => 'Cadastrar'), 
		"onclick=\"ajax.init( 'includes/PfwController.php?app=planodeadesao&action=inserir', 'viewers' );\"");
        $fieldSet = new UIFieldSetElement('Cadastro de Plano de Adesão');
        $fieldSet->addChild($inserir);

        if ($tabela->rowCount() == 0) {
            $fieldSet->show();
            Utilitarios::exibirMensagemAVISO('Nenhum plano de adesão cadastrado.');        
        } else {
            echo "<script>
                $(document).ready(function() {
                    $('#tblPlanoDeAdesao').tablesorter( {
                        sortList: [[0,0]], headers: { 4:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}, 
						8:{sorter: false}, 9:{sorter: false}}
                    }); 
                });
            </script>";        
            $fieldSet->addChild($tabela);
            $fieldSet->show();        
        }
    }

    public static function exibirPaginacao($total, $limite, $pagina) {
        $action = "onclick=\"ajax.init( 'includes/PfwController.php?app=planodeadesao&action=listar&pag=:pag', 'viewers' );\"";
        UIPagination::paginar($total, $pagina, $action, $limite);
    }

}