<?php
abstract class ContratoView {
        
    public static function montarFormulario(Contrato $contrato, $visualizacao = false) {
	
        $desabilita = $visualizacao ? 'disabled' : '';
        $id = isset($contrato) ? $contrato->getId() : null;
        $contratante = $contrato->getContratante();
        $idcontratante = isset($contratante) ? $contratante->getId() : null;
        $pessoa = isset($contratante) ? $contratante->getPessoa() : null;
        $idPessoa = isset($pessoa) ? $pessoa->getId() : null;
        $documento = isset($pessoa) ? $pessoa->getDocumento() : null;
        $nome = isset($pessoa) ? utf8_decode($pessoa->getNome()) : null;
        $cep = isset($pessoa) ? $pessoa->getCep() : null;
        $logradouro = isset($pessoa) ? utf8_decode($pessoa->getLogradouro()) : null;
        $numero = isset($pessoa) ? utf8_decode($pessoa->getNumero()) : null;
        $complemento = isset($pessoa) ? utf8_decode($pessoa->getComplemento()) : null;
        $bairro = isset($pessoa) ? utf8_decode($pessoa->getBairro()) : null;        
        $estadoDaPessoa = isset($pessoa) ? $pessoa->getEstado() : null;
        $cidadeDaPessoa = isset($pessoa) ? $pessoa->getCidade() : null;		
        $email = isset($pessoa) ? $pessoa->getEmail() : null;
        $politicaDePrecoDAO = PoliticaDePrecoFactory::criarPoliticaDePrecoDAO(DAOFactory::getDAO()->getDriver());
        $politicasDePreco = $politicaDePrecoDAO->buscarPoliticasParaCriacaoDeContrato();
        $politicaDoContrato = isset($contrato) ? $contrato->getPoliticaDePreco() : null;
        $formaDePagametoDoContrato = ($contrato->getFormaDePagamento() != null) ? $contrato->getFormaDePagamento() : null;
        $formasDePagamento = FormaDePagamento::getArray();
        $tipoDePagametoDoContrato = ($contrato->getTipoDePagamento() != null) ? $contrato->getTipoDePagamento() : null;
        $tiposDePagamento = TipoDePagamento::getArray();        

        $onSubmit = "onsubmit = \"return processarFormContrato(this, 'contrato', 'salvar');\"";
        
        $form = new PfwForm();
        $form->setFormHeader('post', '?', array('name'=>'frmContrato', 'id'=>'frmContrato', 'class'=>'form-horizontal'), $onSubmit, "");        
		
        $form->addChild(new UIInputHiddenElement(array('name'=>'id_contrato', 
            'id'=>'id_contrato', 'value'=>$id)));
        $form->addChild(new UIInputHiddenElement(array('name'=>'id_contratante', 
            'id'=>'id_contratante', 'value'=>$idcontratante)));                        
        $form->addChild(new UIInputHiddenElement(array('name'=>'id_pessoa', 
            'id'=>'id_pessoa', 'value'=>$idPessoa)));                                
        $form->addChild(new UIInputHiddenElement(array('name'=>'documentoatual', 
            'id'=>'documentoatual', 'value'=>$documento)));                  
        
        $form->addControlGroup(new UILabelElement('Documento *', array('for'=>'documento', 'id'=>'lblDocumento', 'class'=>'control-label')),
                new UIInputTextElement(array('name'=>'documento', 'id'=>'documento', 'class'=>'form-control', 
                'maxlength'=>'14', 'value'=>$documento), "onkeypress=\"return somenteNumero(event);\"
                onChange=\"buscarDadosContratante(this.value);\" " . $desabilita));
                        
        $form->addControlGroup(new UILabelElement('Nome *', array('for'=>'nome', 'id'=>'lblNome', 'class'=>'control-label')),
                new UIInputTextElement(array('name'=>'nome', 'id'=>'nome',  'class'=>'form-control', 
                'maxlength'=>'50', 'value'=>$nome), $desabilita));
                
        $form->addChild(new PfwCampoCEP($cep));			                            
                
        $form->addControlGroup(new UILabelElement('Logradouro *', array('for' => 'logradouro', 'id' => 'lblLogradouro', 'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'logradouro', 'id' => 'logradouro', 'class' => 'form-control',
            'maxlength' => '50', 'value' => $logradouro)));

        $form->addControlGroup(new UILabelElement('Número *', array('for' => 'numero', 'id' => 'lblNumero', 'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'numero', 'id' => 'numero', 'class' => 'form-control',
            'maxlength' => '50', 'value' => $numero)));

        $form->addControlGroup(new UILabelElement('Complemento', array('for' => 'complemento', 'id' => 'lblComplemento', 'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'complemento', 'id' => 'complemento', 'class' => 'form-control',
            'maxlength' => '50', 'value' => $complemento)));

        $form->addControlGroup(new UILabelElement('Bairro *', array('for' => 'bairro', 'id' => 'lblBairro', 'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'bairro', 'id' => 'bairro', 'class' => 'form-control',
            'maxlength' => '50', 'value' => $bairro)));            
                
        $enderecoDAO = EnderecoFactory::criarEnderecoDAO(DAOFactory::getDAO()->getDriver());
        $estados = $enderecoDAO->buscarEstados();
        $cidades = null;
        if (isset($contratante)) {
            $cidades = $enderecoDAO->buscarCidades($contratante->getPessoa()->getEstado());        
        }                        

        $form->addChild(new PfwCampoEstadoCidade($estados, $estadoDaPessoa, $cidades, $cidadeDaPessoa));   
                
        $form->addControlGroup(new UILabelElement('Email *', array('for'=>'email', 'id'=>'lblEmail', 'class'=>'control-label')),
			new UIInputTextElement(array('name'=>'email', 'id'=>'Email', 'class'=>'form-control',  
            'maxlength'=>'100', 'value'=>$email), $desabilita));
		
        $cbbPolitica = new UISelectElement(array('name'=>'politicadepreco', 'id'=>'politicadepreco', 
            'class'=>'form-control'), "onChange=\"definirFormaTipoPgtoContratoGratis(this.value);\" " . $desabilita);
        $cbbPolitica->addOption('', 'Selecione uma política de preço');
        foreach ($politicasDePreco as $politica) {
            $idPolitica = $politica->getId();
            $descPolitica = utf8_decode($politica->getPlanoDeAdesao()->getDescricao());
            $checked = $politica->equals($politicaDoContrato);
            $cbbPolitica->addOption($idPolitica, $descPolitica, $checked);
        }
		$form->addControlGroup(new UILabelElement('PolíticaDePreço *', array('for'=>'politicadepreco', 
			'id'=>'lblPoliticaDePreco', 'class'=>'control-label')), $cbbPolitica);		
        
        $cbbFormaPgto = new UISelectElement(array('name'=>'formadepagamento', 'id'=>'formadepagamento',  'class'=>'form-control'), $desabilita);
        $cbbFormaPgto->addOption('', 'Selecione uma forma de pagamento');
        foreach ($formasDePagamento as $formaDePagamento) {
            $idFormaPgto = $formaDePagamento->ordinal();
            $descFormaPgto = $formaDePagamento->getValue();
            $checked = $formaDePagamento->equalsByOrdinal($formaDePagametoDoContrato);
            $cbbFormaPgto->addOption($idFormaPgto, $descFormaPgto, $checked);
        }        
		$form->addControlGroup(new UILabelElement('Forma de Pagamento *', array('for'=>'formadepagamento', 
			'id'=>'lblFormaDePagamento', 'class'=>'control-label')), $cbbFormaPgto);

        $cbbTipoPgto = new UISelectElement(array('name'=>'tipodepagamento', 'id'=>'tipodepagamento', 'class'=>'form-control'), $desabilita);
        $cbbTipoPgto->addOption('', 'Selecione um tipo de pagamento');
        foreach ($tiposDePagamento as $tipoDePagamento) {
            $idTipoPgto = $tipoDePagamento->ordinal();
            $descTipoPgto = $tipoDePagamento->getValue();
            $checked = $tipoDePagamento->equalsByOrdinal($tipoDePagametoDoContrato);
            $cbbTipoPgto->addOption($idTipoPgto, $descTipoPgto, $checked);
        }
        $form->addControlGroup(new UILabelElement('Tipo de Pagamento *', array('for'=>'tipodepagamento', 
			'id'=>'lblTipoDePagamento', 'class'=>'control-label')), $cbbTipoPgto);		
        
        $form->addControlGroup(new PfwBtnConfirma());  

        $form->showForm('Cadastro de Contrato'); 
		
    }
    
    public static function montarLista($contratos) {
        
        $imagens = Utilitarios::arrayImagens();
        
        $tabela = new UITableElement(array('id'=>'tblContratos', 
            'class'=>'table table-bordered table-hover table-striped tablesorter'));
        $tabela->addTableHeader(new UITableHeaderElement('Código', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Data de Criação', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Documento do Contratante', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Nome do Contratante', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Duração', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Status', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Gratuito', array('align'=>'center')));                
        $tabela->addTableHeader(new UITableHeaderElement('Previsão para Finalização', array('align'=>'center')));                
        $tabela->addTableHeader(new UITableHeaderElement('Finalizar', array('align'=>'center')));                
        $tabela->addTableHeader(new UITableHeaderElement('Cancelar', array('align'=>'center')));                
        $tabela->addTableHeader(new UITableHeaderElement('Detalhes', array('align'=>'center')));                                
        
        $linha = 1;
        foreach ($contratos as $contrato) {         
            $id = isset($contrato) ? $contrato->getId() : null;
            $codigo = isset($contrato) ? $contrato->getCodigo() : null;        
            $dtCriacao = Utilitarios::dataFormatada($contrato->getDataDeCriacao());
            $contratante = $contrato->getContratante();
            $documento = isset($contratante) ? Utilitarios::documentoFormatado($contratante->getPessoa()->getDocumento()): null;
            $nome = isset($contratante) ? utf8_decode($contratante->getPessoa()->getNome()) : null;
            $duracao =  $contrato->duracao();
            $status = $contrato->status();
            $gratuito = $contrato->gratuito() ? 
                Utilitarios::buscarImagem($imagens['IMG_TRUE']) : 
                Utilitarios::buscarImagem($imagens['IMG_FALSE']);
            $dtPrevistaParaFinalizacao = Utilitarios::dataFormatada($contrato->dataPrevistaParaFinalizacao());
            $desabilita = $contrato->cancelado() ? 'disabled' : '';

            $finalizar = new UILinkElement('javascript:void(0);',
                Utilitarios::buscarImagem($imagens['IMG_FINALIZAR']),
                array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Finalizar'),
                "onclick=\"executarFuncaoComConfirmacao('Deseja realmente finalizar o contrato selecionado?',  
                'includes/PfwController.php?app=contrato&action=finalizar&id_contrato={$id}', 'viewers' );\" " . $desabilita);

            $cancelar = new UILinkElement('javascript:void(0);',
                Utilitarios::buscarImagem($imagens['IMG_DESATIVAR']),
                array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Cancelar'),
                "onclick=\"executarFuncaoComConfirmacao('Deseja realmente cancelar o contrato selecionado?',  
                'includes/PfwController.php?app=cancelamento&registro={$id}&redirecionar=contrato&titulo=Cancelamento do Contrato');\" " . $desabilita);

            $detalhes = new UILinkElement('javascript:void(0);',
                Utilitarios::buscarImagem($imagens['IMG_VISUALIZAR']),
                array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Detalhes'),
                "onclick=\"ajax.init( 'includes/PfwController.php?app=contrato&action=visualizar&id_contrato={$id}', 'viewers' );\"");
                
            $tabela->addTableRow($linha, new UITableDataElement($codigo, array('align'=>'center')));
            $tabela->addTableRow($linha, new UITableDataElement($dtCriacao));
            $tabela->addTableRow($linha, new UITableDataElement($documento));
            $tabela->addTableRow($linha, new UITableDataElement($nome));
            $tabela->addTableRow($linha, new UITableDataElement($duracao, array('align'=>'right')));
            $tabela->addTableRow($linha, new UITableDataElement($status, array('align'=>'left')));
            $tabela->addTableRow($linha, new UITableDataElement($gratuito, array('align'=>'center')));
            $tabela->addTableRow($linha, new UITableDataElement($dtPrevistaParaFinalizacao));
            $tabela->addTableRow($linha, new UITableDataElement($finalizar, array('align'=>'center')));
            $tabela->addTableRow($linha, new UITableDataElement($cancelar, array('align'=>'center')));
            $tabela->addTableRow($linha, new UITableDataElement($detalhes, array('align'=>'center')));            
            
            $linha++;
        }                
        
        self::showList($tabela);
		
    }
 
    private static function showList($tabela) {		
			
        $toolBar = self::criarToolBar();
	
        $fieldSet = new UIFieldSetElement('Gerenciamento de contratos');                
		
        $fieldSet->addChild($toolBar);	
       
        if ($tabela->rowCount() > 0) {       
            echo "<script>
                $(document).ready(function() {
                    $('#tblContratos').tablesorter( {
                        sortList: [[0,0]], headers: { 6:{sorter: false}, 8:{sorter: false}, 9:{sorter: false}, 10:{sorter: false}}
                    }); 
                });
            </script>";                                
            $fieldSet->addChild($tabela);
            $fieldSet->show();
        } else {
			$fieldSet->show();
            Utilitarios::exibirMensagemAVISO('Nenhum contrato cadastrado.');
        }     		        
		
    }    
	
    private static function criarToolBar() {

        $linkCadastro = new UILinkElement('javascript:void(0);','Cadastrar Contrato',
            array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Cadastrar Contrato'),
            "onclick=\"ajax.init( 'includes/PfwController.php?app=contrato&action=inserir', 'viewers' );\"");    

        $linkFinalizacao = new UILinkElement('javascript:void(0);','Finalizar Contratos',
            array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Finalizar Contratos'),
            "onclick=\"executarFuncaoComConfirmacao('Deseja efetuar a finalização dos contratos?',  
                        'includes/PfwController.php?app=contrato&action=finalizarTodos');\"");    

        $linkCobranca = new UILinkElement('javascript:void(0);','Emitir Cobrança',
            array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Emitir Cobrança'),
            "onclick=\"executarFuncaoComConfirmacao('Deseja emitir cobrança aos contratos com pagamento em atraso?',  
                        'includes/PfwController.php?app=contrato&action=emitirCobrancaDePagamentos');\"");                

        $linkBar = new UIControlBarElement(array('id'=>'linkbar','class'=>'btn-group'));        
        $linkBar->addChild($linkCadastro);
        $linkBar->addChild($linkFinalizacao);
        $linkBar->addChild($linkCobranca);
        return $linkBar;

    }
    
    public static function exibirPaginacao($total, $limite, $pagina) {
        
		$action = "onclick=\"ajax.init( 'includes/PfwController.php?app=contrato&action=listar&pag=:pag', 'viewers' );\"";
        UIPagination::paginar($total, $pagina, $action, $limite);                        
		
    }    
    
    public static function exibirDetalhes(Contrato $contrato, $contratoDoContratante = false) {
        
        $imagens = Utilitarios::arrayImagens();
        $contratante = $contrato->getContratante();
        $documento = $contratante->getPessoa()->getDocumento();
        $documento = Utilitarios::documentoFormatado($documento);
        $nome = utf8_decode($contratante->getPessoa()->getNome());
        $email = $contratante->getPessoa()->getEmail();
        $codigo = $contrato->getCodigo();
        $status = $contrato->status();
        $dtCriacao = Utilitarios::dataFormatada($contrato->getDataDeCriacao());
        $planoDeAdesao = utf8_decode($contrato->getPoliticaDePreco()->getPlanoDeAdesao()->getDescricao());
        $duracao = $contrato->duracao();
        $gratis = $contrato->gratuito() ? 
            Utilitarios::buscarImagem($imagens['IMG_TRUE']) :
            Utilitarios::buscarImagem($imagens['IMG_FALSE']);
        $valor = Utilitarios::valorFormatado($contrato->valorMensal(), 2);
        $formaDePagamento = $contrato->getFormaDePagamento()->getValue();
        $tipoDePagamento = $contrato->getTipoDePagamento()->getValue();
        $dtAtivacao = Utilitarios::dataFormatada($contrato->getDataDeAtivacao());
        $dtPrevistaFinalizacao = Utilitarios::dataFormatada($contrato->dataPrevistaParaFinalizacao());
        $pagamentos = $contrato->getPagamentos();
        
        $fieldSetForm = new UIFieldSetElement('Detalhes do contrato');               
        $fieldSetForm->addChild('<div class="row-fluid">');
        $fieldSetForm->addChild('<div class="col-lg-2">');
        $fieldSetForm->addChild('<blockquote>');
        $fieldSetForm->addChild("<p>Contrato #{$codigo}</p>");
        $fieldSetForm->addChild('</blockquote>');        
        $fieldSetForm->addChild('</div>');
        $fieldSetForm->addChild('<div class="col-lg-4">');
        $fieldSetForm->addChild("<strong>Status</strong> {$status} <br>");
        $fieldSetForm->addChild("<i class=\"fa fa-calendar\"></i> <strong>Criado em</strong> {$dtCriacao} <br>");
        $fieldSetForm->addChild("<i class=\"fa fa-calendar\"></i> <strong>Ativado em</strong> {$dtAtivacao} <br>");
        $fieldSetForm->addChild("<i class=\"fa fa-calendar\"></i> <strong>Previsto para terminar em</strong> {$dtPrevistaFinalizacao} <br>");
        $fieldSetForm->addChild("<strong>Plano de adesão</strong> {$planoDeAdesao} <br>");
        $fieldSetForm->addChild("<strong>Duração</strong> {$duracao} <br>");
        $fieldSetForm->addChild("<strong>Gratuito</strong> {$gratis} <br>");
        $fieldSetForm->addChild("<strong>Valor mensal</strong> {$valor} <br>");
        $fieldSetForm->addChild("<strong>Forma de pagamento</strong> {$formaDePagamento} <br>");
        $fieldSetForm->addChild("<strong>Tipo de pagamento</strong> {$tipoDePagamento} <br>");
        $fieldSetForm->addChild('</div>');
        $fieldSetForm->addChild('<div class="col-lg-3">');
        $fieldSetForm->addChild("<strong>Dados do contratante</strong> <br>");
        $fieldSetForm->addChild("<i class=\"fa fa-user\"></i> {$nome} <br>");
        $fieldSetForm->addChild("{$documento} <br>");
        $fieldSetForm->addChild("<i class=\"fa fa-envelope\"></i> {$email} <br>");        
        $fieldSetForm->addChild('</div>');

        $fieldSetForm->addChild('<div class="col-lg-12">');               
            if ($contrato->cancelado()) {
                $fieldSetForm->addChild('<br/>');
                $cancelamento = $contrato->getCancelamento();
                CancelamentoView::mostarInfo($cancelamento, $fieldSetForm);
            }         
            
            $fieldSetForm->addChild('<hr/>');        
            
            /*Exibição dos pagamentos do contrato*/
            if (! $contrato->gratuito()) {		
                $tblPagamentos = static::retornarTabelaDePagamentos($pagamentos->toArray(), ! $contratoDoContratante, false);
                $fieldSetForm->addChild("<div class=\"form-group\">");	
                $fieldSetForm->addChild('<p><strong>Pagamentos</strong></p>');			             
                $fieldSetForm->addChild($tblPagamentos);
                $fieldSetForm->addChild("</div>");	
            }                        
        $fieldSetForm->addChild('</div>');
        
        $fieldSetForm->show();
    }
    
    public static function retornarTabelaDePagamentos($pagamentos, 
            $exibeConfirmacao = true, $exibirContrato = false) {

        $tblPagamentos = new UITableElement(array('cellpadding'=>'5', 
            'class'=>'table table-bordered table-hover table-striped tablesorter'));
        if ($exibirContrato) {
        	$tblPagamentos->addTableHeader(new UITableHeaderElement('Contrato'));
        }            
        $tblPagamentos->addTableHeader(new UITableHeaderElement('Vencimento'));
        $tblPagamentos->addTableHeader(new UITableHeaderElement('Valor'));
        $tblPagamentos->addTableHeader(new UITableHeaderElement('Status', array('align'=>'center')));
        $tblPagamentos->addTableHeader(new UITableHeaderElement('Pagamento'));
        $tblPagamentos->addTableHeader(new UITableHeaderElement('Cancelamento'));

        $linha = 1;
        foreach ($pagamentos as $pagamento) {
            $idPgto = $pagamento->getId();
            $contrato = $pagamento->getContrato()->getCodigo();
            $dtVencimentoPgto = Utilitarios::dataFormatada($pagamento->getDataDeVencimento());
            $valorPgto = Utilitarios::valorFormatado($pagamento->getValor(), 2);                
                            $status = $pagamento->status();
            $statusPgto = "<span>{$status}</span>";
            $dtPgto = Utilitarios::dataFormatada($pagamento->getDataDePagamento());
            if ($pagamento->status() == StatusDoPagamento::CANCELADO) {
                $dtCancelamentoPgto = Utilitarios::dataFormatada($pagamento->getCancelamento()->getData());
            } else {
                $dtCancelamentoPgto = "";
            }

	    if ($exibirContrato) {
	    	$tblPagamentos->addTableRow($linha, new UITableDataElement($contrato));
	    }
            $tblPagamentos->addTableRow($linha, new UITableDataElement($dtVencimentoPgto));
            $tblPagamentos->addTableRow($linha, new UITableDataElement($valorPgto, array('align'=>'right')));
            $tblPagamentos->addTableRow($linha, new UITableDataElement($statusPgto, array('align'=>'center')));
            $tblPagamentos->addTableRow($linha, new UITableDataElement($dtPgto));
            $tblPagamentos->addTableRow($linha, new UITableDataElement($dtCancelamentoPgto));
            if ($exibeConfirmacao) {
                $confirmar = new UILinkElement('javascript:void(0);','Confirmar',
                    array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Confirmar'),
                    "onclick=\"ajax.init( 'includes/PfwController.php?app=contrato&action=confirmarpagamento&id_pagamento={$idPgto}', 'viewers' );\"");                                                
                $tblPagamentos->addTableRow($linha, new UITableDataElement($confirmar));                                
            }

            $linha++;
        }        
        
        return $tblPagamentos;
        
    }
        
}