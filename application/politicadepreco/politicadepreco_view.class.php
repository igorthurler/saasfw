<?php

abstract class PoliticaDePrecoView {

    public static function montarFormulario(PoliticaDePreco $politicaDePreco, $planosDeAdesao, $visualizacao = false) {

        $id = isset($politicaDePreco) ? $politicaDePreco->getId() : 0;
        $data = isset($politicaDePreco) ? $politicaDePreco->getData() : null;
        $strData = isset($data) ? Utilitarios::dataFormatada($data) : "";
        $planoDeAdesao = isset($politicaDePreco) ? $politicaDePreco->getPlanoDeAdesao() : null;
        $valor = isset($politicaDePreco) ? $politicaDePreco->getValor() : 0;
        $desabilita = $visualizacao ? 'disabled' : '';

        $onSubmit = "onsubmit = \"return processarFormPoliticaDePreco(this, 'politicadepreco', 'salvar');\"";
        $form = new PfwForm();
        $form->setFormHeader('post', '?', array('name' => 'frmPoliticaDePreco',
            'id' => 'frmPoliticaDePreco', 'class' => 'form-horizontal'), $onSubmit, "");

        $form->addChild(new UIInputHiddenElement(array('name' => 'id_politicapreco',
            'id' => 'id_politicapreco', 'value' => $id)));
        
        $cbbPlano = new UISelectElement(array('name' => 'id_planoadesao', 'id' => 'id_planoadesao', 'class' => 'form-control'), $desabilita);
        $cbbPlano->addOption('', 'Selecione um plano de adesão');
        foreach ($planosDeAdesao as $plano) {
            $selecionado = $plano->equals($planoDeAdesao);
            $cbbPlano->addOption($plano->getId(), utf8_decode($plano->getDescricao()), $selecionado);
        }
        $form->addControlGroup(new UILabelElement('Plano de Adesão *', array('for' => 'id_planoadesao', 'class' => 'control-label')), $cbbPlano);        
        
        $form->addControlGroup(new UILabelElement('Valor *', array('for' => 'valor', 'class' => 'control-label')), new UIInputTextElement(array('name' => 'valor', 'id' => 'valor', 'value' => $valor,
            'class' => 'form-control'), $desabilita . ' ' . "onkeypress=\" mascara(this, valor);\""));            

        $form->addControlGroup(new UILabelElement('Data *', array('for' => 'data', 'class' => 'control-label')), new UIInputTextElement(array('name' => 'data', 'id' => 'data', 'maxlength' => '10',
            'class' => 'form-control', 'value' => $strData), 'onkeyup="formataData(this,event);" ' . $desabilita));

        $form->addControlGroup(new PfwBtnConfirma($desabilita));

        $fieldSet = new UIFieldSetElement('Cadastro de política de preço');
        $fieldSet->addChild('<div class="col-lg-5">');
        $planoDeAdesaoAssociado = $politicaDePreco->getPlanoDeAdesao();
        if (isset($planoDeAdesaoAssociado)) {
            if (!$planoDeAdesaoAssociado->isAtivo()) {
                $cancelamento = $planoDeAdesaoAssociado->getCancelamento();
                CancelamentoView::mostarInfo($cancelamento, $fieldSet);
            }
        }
        $fieldSet->addChild($form);
        $fieldSet->addChild('</div>');
        $fieldSet->show();
    }

    public static function montarLista($politicas) {

        $imagens = Utilitarios::arrayImagens();

        $tabela = new UITableElement(array('id' => 'tblPoliticasDePreco',
            'class' => 'table table-bordered table-hover table-striped tablesorter'));
        $tabela->addTableHeader(new UITableHeaderElement('Id', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Data', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Plano de Adesão', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Valor  Mensal', array('align' => 'center')));        
        $tabela->addTableHeader(new UITableHeaderElement('Grátis', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Ativo', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Visualizar', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Editar', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Excluir', array('align' => 'center')));

        $linha = 1;
        foreach ($politicas as $politica) {
            $id = $politica->getId();
            $data = Utilitarios::dataFormatada($politica->getData());
            $plano = utf8_decode($politica->getPlanoDeAdesao()->getDescricao());
            $valor = Utilitarios::valorFormatado($politica->getValor(), 2);
            $gratis = $politica->getPlanoDeAdesao()->isGratis() ?
                    Utilitarios::buscarImagem($imagens['IMG_TRUE']) :
                    Utilitarios::buscarImagem($imagens['IMG_FALSE']);
            $ativo = $politica->getPlanoDeAdesao()->isAtivo() ?
                    Utilitarios::buscarImagem($imagens['IMG_TRUE']) :
                    Utilitarios::buscarImagem($imagens['IMG_FALSE']);

            $visualizar = new UILinkElement('javascript:void(0);', Utilitarios::buscarImagem($imagens['IMG_VISUALIZAR']), 
				array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Visualizar'), 
				"onclick=\"ajax.init( 'includes/PfwController.php?app=politicadepreco&action=visualizar&id_politicapreco={$id}', 'viewers' );\"");

            $editar = new UILinkElement('javascript:void(0);', Utilitarios::buscarImagem($imagens['IMG_EDITAR']), 
				array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Editar'), 
				"onclick=\"ajax.init( 'includes/PfwController.php?app=politicadepreco&action=editar&id_politicapreco={$id}', 'viewers' );\"");

            $excluir = new UILinkElement('javascript:void(0);', Utilitarios::buscarImagem($imagens['IMG_EXCLUIR']), 
				array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Excluir'), 
				"onclick=\"executarFuncaoComConfirmacao('Deseja realmente excluir a política de preço selecionada?', 
                'includes/PfwController.php?app=politicadepreco&action=deletar&id_politicapreco={$id}');\"");

            $tabela->addTableRow($linha, new UITableDataElement($id, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($data, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($plano));
            $tabela->addTableRow($linha, new UITableDataElement($valor, array('align' => 'right')));            
            $tabela->addTableRow($linha, new UITableDataElement($gratis, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($ativo, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($visualizar, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($editar, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($excluir, array('align' => 'center')));
            $linha++;
        }

        self::showList($tabela);
    }

    private static function showList($tabela) {
        $inserir = new UILinkElement('javascript:void(0);', 'Cadastrar Política de Preço', array('alt' => 'Cadastrar'), 
			"onclick=\"ajax.init( 'includes/PfwController.php?app=politicadepreco&action=inserir', 'viewers' );\"");
        $fieldSet = new UIFieldSetElement('Cadastro de Política de Preço');
        $fieldSet->addChild($inserir);
        
        if ($tabela->rowCount() > 0) {
            echo "<script>
                $(document).ready(function() {
                    $('#tblPoliticasDePreco').tablesorter( {
                        sortList: [[0,0]], headers: { 4:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}, 8:{sorter: false}}
                    }); 
                });
            </script>";        
            $fieldSet->addChild($tabela);
            $fieldSet->show();
        } else {
            $fieldSet->show();
            Utilitarios::exibirMensagemAVISO('Nenhuma política de preço cadastrada.');
        }
    }

    public static function exibirPaginacao($total, $limite, $pagina) {
        $action = "onclick=\"ajax.init( 'includes/PfwController.php?app=politicadepreco&action=listar&pag=:pag', 'viewers' );\"";
        UIPagination::paginar($total, $pagina, $action, $limite);
    }

}