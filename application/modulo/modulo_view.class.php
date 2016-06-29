<?php
abstract class ModuloView {
        
    public static function montarFormulario(Modulo $modulo, $visualizacao = false) {         
        $id = $modulo->getId();
        $descricao = utf8_decode($modulo->getDescricao());        
        $identificador = utf8_decode($modulo->getIdentificador());
        
        if (! $modulo->isAtivo()) {            
            $visualizacao = true;        
        }
        
        $desabilita = $visualizacao ? 'disabled' : '';
                
        $onSubmit = "onsubmit = \"return processarFormModulo(this, 'modulo', 'salvar');\"";
        $form = new PfwForm();
        $form->setFormHeader('post', '?', array('name'=>'frmModulo', 'id'=>'frmModulo', 
			'class'=>'form-horizontal'), $onSubmit, "");        
			
        $form->addChild(new UIInputHiddenElement(array('name'=>'id_modulo', 'id'=>'id_modulo', 'value'=>$id)));                

        $form->addControlGroup(new UILabelElement('Identificador *', array('for'=>'identificador', 
                                        'id'=>'lbl', 'class'=>'control-label')),
                new UIInputTextElement(array('name'=>'identificador', 'id'=>'identificador', 'class'=>'form-control',
                'maxlength'=>'20', 'value'=>$identificador), $desabilita));
		
        $form->addControlGroup(new UILabelElement('Descrição *', array('for'=>'descricao', 
                                        'id'=>'lbl', 'class'=>'control-label')),
                new UIInputTextElement(array('name'=>'descricao', 'id'=>'descricao', 'class'=>'form-control',
                'maxlength'=>'50', 'value'=>$descricao), $desabilita));
        
        $form->addControlGroup(new UISubmitElement(array('id'=>'confirmar', 'class'=>'btn btn-primary',
            'value'=>'confirmar'), $desabilita));
        
        $fieldSet = new UIFieldSetElement('Cadastro de Módulo');                
        $fieldSet->addChild('<div class="col-lg-5">');        
        if (! $modulo->isAtivo()) {            
            $cancelamento = $modulo->getCancelamento();
            CancelamentoView::mostarInfo($cancelamento, $fieldSet);
        }        				
        $fieldSet->addChild($form);
        $fieldSet->addChild('</div>');        			        
        
        $fieldSet->show();
    }
        
    public static function montarLista($modulos) {                          
        $imagens = Utilitarios::arrayImagens();       

        $tabela = new UITableElement(array('id'=>'tblModulos', 
            'class'=>'table table-bordered table-hover table-striped tablesorter'));
        $tabela->addTableHeader(new UITableHeaderElement('Id', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Identificador', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Descrição', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Ativo', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Visualizar', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Editar', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Desativar', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Excluir', array('align'=>'center')));                
        
        $linha = 1;
        foreach ($modulos as $modulo) {
            $id = $modulo->getId();
            $identificador = utf8_decode($modulo->getIdentificador());
            $descricao = utf8_decode($modulo->getDescricao());
            $ativo = $modulo->isAtivo() ? 
                Utilitarios::buscarImagem($imagens['IMG_TRUE']) : 
                Utilitarios::buscarImagem($imagens['IMG_FALSE']);
            
            $visualizar = new UILinkElement('javascript:void(0);',
                Utilitarios::buscarImagem($imagens['IMG_VISUALIZAR']),
                array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Visualizar'),
                "onclick=\"ajax.doGet( 'includes/PfwController.php?app=modulo&action=visualizar&id_modulo={$id}', 'viewers', true );\"");

            $editar = new UILinkElement('javascript:void(0);',
                Utilitarios::buscarImagem($imagens['IMG_EDITAR']),
                array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Editar'),
                "onclick=\"ajax.doGet( 'includes/PfwController.php?app=modulo&action=editar&id_modulo={$id}', 'viewers', true );\"");                    
                    
            $desativar = new UILinkElement('javascript:void(0);',
                Utilitarios::buscarImagem($imagens['IMG_DESATIVAR']),
                array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Desativar'),
                "onclick=\"executarFuncaoComConfirmacao('Deseja realmente desativar o módulo selecionado?', 
                'includes/PfwController.php?app=cancelamento&registro={$id}&redirecionar=modulo&titulo=Cancelamento do Módulo');\"");
                        
            $excluir = new UILinkElement('javascript:void(0);',
                Utilitarios::buscarImagem($imagens['IMG_EXCLUIR']),
                array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Desativar'),
                "onclick=\"executarFuncaoComConfirmacao('Deseja realmente excluir o módulo selecionado?', 
                'includes/PfwController.php?app=modulo&action=deletar&id_modulo={$id}');\"");            
            
            $tabela->addTableRow($linha, new UITableDataElement($id, array('align'=>'center')));
            $tabela->addTableRow($linha, new UITableDataElement($identificador));
            $tabela->addTableRow($linha, new UITableDataElement($descricao));
            $tabela->addTableRow($linha, new UITableDataElement($ativo, array('align'=>'center')));
            $tabela->addTableRow($linha, new UITableDataElement($visualizar, array('align'=>'center')));
            $tabela->addTableRow($linha, new UITableDataElement($editar, array('align'=>'center')));
            $tabela->addTableRow($linha, new UITableDataElement($desativar, array('align'=>'center')));
            $tabela->addTableRow($linha, new UITableDataElement($excluir, array('align'=>'center')));
            $linha++;
        }

        $fieldSet = new UIFieldSetElement('Cadastro de Módulo');            
        $toolBar = self::criarToolBar();
        $fieldSet->addChild($toolBar);        
            		
        if ($tabela->rowCount() == 0) {
            $fieldSet->show();
            Utilitarios::exibirMensagemAVISO("Nenhum módulo cadastrado.");		
        } else {        
            echo "<script>
                $(document).ready(function() {
                    $('#tblModulos').tablesorter({
                        sortList: [[0,0]], headers: { 3:{sorter: false}, 4:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}
                    });
                });
            </script>";
            $fieldSet->addChild($tabela);           
            $fieldSet->show();            
        }			
    }
    
    private static function criarToolBar() {

        $linkCadastro = new UILinkElement('javascript:void(0);', 'Cadastrar Módulo', array('alt' => 'Cadastrar'), 
        "onclick=\"ajax.init( 'includes/PfwController.php?app=modulo&action=inserir', 'viewers' );\"");
        
        $linkBar = new UIControlBarElement(array('id'=>'linkbar','class'=>'btn-group'));        
        $linkBar->addChild($linkCadastro);
        return $linkBar;

    }    
    
    public static function exibirPaginacao($total, $limite, $pagina) {        
        $action = "onclick=\"ajax.init( 'includes/PfwController.php?app=modulo&action=listar&pag=:pag', 'viewers' );\"";
        UIPagination::paginar($total, $pagina, $action, $limite);        
    }
    
}