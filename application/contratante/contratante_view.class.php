<?php

abstract class ContratanteView {

    public static function montarFormulario(Contratante $contratante, $estados = null, $cidades = null, $perfil = false) {

        $id = $contratante->getId();
		$alias = $contratante->getAlias();
        $action = $perfil ? 'salvarPerfil' : 'salvar';

        $onSubmit = "onsubmit = \"return processarFormContratante(this, 'contratante', '{$action}');\"";
        $form = new PfwForm();
        $form->setFormHeader('post', '?', array('name' => 'frmContratante', 'id' => 'frmContratante', 'class' => 'form-horizontal'), $onSubmit, "");

        $form->addChild(new UIInputHiddenElement(array('name' => 'id_contratante', 'id' => 'id_contratante', 'value' => $id)));
		
		$form->addControlGroup(new UILabelElement('Alias *', array('for' => 'alias', 'id' => 'lblAlias', 'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'alias', 'id' => 'alias', 'class' => 'form-control',
            'maxlength' => '32', 'value' => $alias)));
            
        PessoaView::criarControlesNoFormularios($form, $contratante->getPessoa(), $estados, $cidades);        

        $form->addControlGroup(new PfwBtnConfirma());     

        $form->showForm('Cadastro de Contratante');            

    }

    public static function montarLista($contratantes) {
	
        $imagens = Utilitarios::arrayImagens();

        $tabela = new UITableElement(array('id' => 'tblContratantes',
            'class' => 'table table-bordered table-hover table-striped tablesorter'));
        $tabela->addTableHeader(new UITableHeaderElement('Documento', array('align' => 'center')));
		$tabela->addTableHeader(new UITableHeaderElement('Alias', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Nome', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Email', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Telefone 1', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Telefone 2', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Visualizar', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Editar', array('align' => 'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Excluir', array('align' => 'center')));

        $linha = 1;
        foreach ($contratantes as $contratante) {
            $id = $contratante->getId();
            $documento = Utilitarios::documentoFormatado($contratante->getPessoa()->getDocumento());
			$alias = utf8_decode($contratante->getAlias());
            $nome = utf8_decode($contratante->getPessoa()->getNome());
            $email = $contratante->getPessoa()->getEmail();
            $tel1 = Utilitarios::retornarTelefoneFormatado($contratante->getPessoa()->getTelefone1());
            $tel2 = Utilitarios::retornarTelefoneFormatado($contratante->getPessoa()->getTelefone2());

            $visualizar = new UILinkElement('javascript:void(0);', Utilitarios::buscarImagem($imagens['IMG_VISUALIZAR']), 
				array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Visualizar'), 
				"onclick=\"ajax.init( 'includes/PfwController.php?app=contratante&action=visualizar&id_contratante={$id}', 'viewers' );\"");

            $editar = new UILinkElement('javascript:void(0);', Utilitarios::buscarImagem($imagens['IMG_EDITAR']), 
				array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Editar'), 
				"onclick=\"ajax.init( 'includes/PfwController.php?app=contratante&action=editar&id_contratante={$id}', 'viewers' );\"");

            $excluir = new UILinkElement('javascript:void(0);', Utilitarios::buscarImagem($imagens['IMG_EXCLUIR']), 
				array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Desativar'), 
				"onclick=\"executarFuncaoComConfirmacao('Deseja realmente excluir o contratante selecionado?', 
                'includes/PfwController.php?app=contratante&action=deletar&id_contratante={$id}');\"");

            $tabela->addTableRow($linha, new UITableDataElement($documento));
			$tabela->addTableRow($linha, new UITableDataElement($alias));
            $tabela->addTableRow($linha, new UITableDataElement($nome));
            $tabela->addTableRow($linha, new UITableDataElement($email));
            $tabela->addTableRow($linha, new UITableDataElement($tel1));
            $tabela->addTableRow($linha, new UITableDataElement($tel2));
            $tabela->addTableRow($linha, new UITableDataElement($visualizar, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($editar, array('align' => 'center')));
            $tabela->addTableRow($linha, new UITableDataElement($excluir, array('align' => 'center')));
            $linha++;
        }

        self::showList($tabela);
		
    }

    private static function showList($tabela) {
        $fieldSet = new UIFieldSetElement('Cadastro de Contratante');

        echo "<script>
            $(document).ready(function() {
                $('#tblContratantes').tablesorter( {
                    sortList: [[0,0]], headers: {6:{sorter: false}, 7:{sorter: false}, 8:{sorter: false}}
                }); 
            });
        </script>";

        if ($tabela->rowCount() > 0) {
            $fieldSet->addChild($tabela);
            $fieldSet->show();
        } else {
            Utilitarios::exibirMensagemAVISO('Nenhum contratante cadastrado.');
        }
    }

    public static function montarFormUpload($id) {
        PessoaView::montarFormUpload("application/contratante/upload_imagem_perfil.php?id_contratante={$id}", 
		"includes/PfwController.php?app=contratante&action=perfil");
    }

    static public function exibirPerfil(Contratante $contratante, $executaOperacoes = false) {

        $id = isset($contratante) ? $contratante->getId() : 0;
        $dtCadastro = isset($contratante) ? Utilitarios::dataFormatada($contratante->getDataDeCadastro()) : '';
		$documento = isset($contratante) ? utf8_decode($contratante->getPessoa()->getDocumento()) : '';
		$alias = isset($contratante) ? utf8_decode($contratante->getAlias()) : '';
        $nome = isset($contratante) ? utf8_decode($contratante->getPessoa()->getNome()) : null;
        $telefone1 = isset($contratante) ? Utilitarios::retornarTelefoneFormatado($contratante->getPessoa()->getTelefone1()) : '';
        $telefone2 = isset($contratante) ? Utilitarios::retornarTelefoneFormatado($contratante->getPessoa()->getTelefone2()) : '';

        $cep = isset($contratante) ? $contratante->getPessoa()->getCep() : '';
        $logradouro = isset($contratante) ? utf8_decode($contratante->getPessoa()->getLogradouro()) : '';
        $numero = isset($contratante) ? utf8_decode($contratante->getPessoa()->getNumero()) : '';
        $complemento = isset($contratante) ? utf8_decode($contratante->getPessoa()->getComplemento()) : '';
        $bairro = isset($contratante) ? utf8_decode($contratante->getPessoa()->getBairro()) : '';
        $estado = isset($contratante) ? utf8_decode($contratante->getPessoa()->getEstado()->getNome()) : '';
        $cidade = isset($contratante) ? utf8_decode($contratante->getPessoa()->getCidade()->getNome()) : '';

        $email = isset($contratante) ? $contratante->getPessoa()->getEmail() : null;
        $imagem = isset($contratante) ? Utilitarios::retornarImagemDaPessoa($contratante->getPessoa()) : '';

        $fieldSetForm = new UIFieldSetElement('Perfil do usuário');
        $fieldSetForm->addChild('<div class="row-fluid">');
        $fieldSetForm->addChild('<div class="col-lg-2">');
        $fieldSetForm->addChild("<img src=\"{$imagem}\" class=\"img-rounded\" style=\"width:160px; height:160px;\">");
        $fieldSetForm->addChild('</div>');
        $fieldSetForm->addChild('<div class="col-lg-4">');
        $fieldSetForm->addChild('<blockquote>');
        $fieldSetForm->addChild("<p>{$nome}</p>");
        $fieldSetForm->addChild('</blockquote>');
        $fieldSetForm->addChild('<p>');
        $fieldSetForm->addChild("<i class=\"fa fa-envelope\"></i> {$email} <br/>");
		$fieldSetForm->addChild("<b>Alias:</b> {$alias} <br/>");
		$fieldSetForm->addChild("<b>Documento:</b> {$documento} <br/>");
        $fieldSetForm->addChild("<i class=\"fa fa-calendar\"></i> Cadastrado em {$dtCadastro} <br>");
        $fieldSetForm->addChild("<i class=\"fa fa-phone\"></i> <b>Telefone 1:</b> {$telefone1} <br/>");
        $fieldSetForm->addChild("<i class=\"fa fa-phone\"></i> <b>Telefone 2:</b> {$telefone2} <br/>");
        $fieldSetForm->addChild('</p>');
        $fieldSetForm->addChild('</div>');
        $fieldSetForm->addChild('<div class="col-lg-4">');
        $fieldSetForm->addChild('<p>');
        $fieldSetForm->addChild("<b>CEP</b> {$cep} <br>");
        $fieldSetForm->addChild("<b>Logradouro</b> {$logradouro} <br>");
        $fieldSetForm->addChild("<b>Número</b> {$numero} <b>Complemento</b> {$complemento} <br/>");
        $fieldSetForm->addChild("<b>Bairro</b> {$bairro} <br>");
        $fieldSetForm->addChild("<b>Cidade</b> {$cidade} <br>");
        $fieldSetForm->addChild("<b>Estado</b> {$estado} <br>");
        $fieldSetForm->addChild('</p>');
        $fieldSetForm->addChild('</div>');
        $fieldSetForm->addChild('<div class="col-lg-12">');
        if ($executaOperacoes) {
            $linkAlterar = new UILinkElement('javascript:void(0);', 'Alterar dados', 
				array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Alterar dados'), 
				"onclick=\"ajax.init( 'includes/PfwController.php?app=contratante&action=editarPerfil&id_contratante={$id}', 'viewers' );\"");

            $linkImagem = new UILinkElement('javascript:void(0);', 'Alterar Imagem', array('id' => 'altimagem'), 
				"onclick=\"ajax.init( 'includes/PfwController.php?app=contratante&action=uploadFoto', 'viewers' );\"");

            $linkBar = new UIControlBarElement(array());
            $linkBar->addChild($linkAlterar);
            $linkBar->addChild($linkImagem);
            $fieldSetForm->addChild("<br/>");
            $fieldSetForm->addChild($linkBar);
        }
        $fieldSetForm->addChild('<hr/>');
        $fieldSetForm->addChild('</div>');
        $fieldSetForm->show();
		
    }

    public static function exibirPaginacao($total, $limite, $pagina) {
        $action = "onclick=\"ajax.init( 'includes/PfwController.php?app=contratante&action=listar&pag=:pag', 'viewers' );\"";
        UIPagination::paginar($total, $pagina, $action, $limite);
    }

}