<?php
abstract class UsuarioView {

    public static function montarLista($usuarios) {

        $imagens = Utilitarios::arrayImagens();
        
        $tabela = new UITableElement(array('id'=>'tblUsuario', 
            'class'=>'table table-bordered table-hover table-striped tablesorter'));
        $tabela->addTableHeader(new UITableHeaderElement('Nome', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Email', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Cadastro', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Desativação', array('align'=>'center')));
        $tabela->addTableHeader(new UITableHeaderElement('Ativo', array('align'=>'center')));                
        $tabela->addTableHeader(new UITableHeaderElement('Visualizar', array('align'=>'center')));                
        $tabela->addTableHeader(new UITableHeaderElement('Excluir', array('align'=>'center')));                
        $tabela->addTableHeader(new UITableHeaderElement('Desativar', array('align'=>'center')));                                
        
        $linha = 1;
        foreach ($usuarios as $usuario) {         
            $id = isset($usuario) ? $usuario->getId() : null;
            $nome = isset($usuario) ? $usuario->getNome() : null;        
            $email = isset($usuario) ? $usuario->getEmail() : null;        
            $dtCadastro = Utilitarios::dataFormatada($usuario->getDataDeCadastro());
            $dtDesativacao = Utilitarios::dataFormatada($usuario->getDataDeDesativacao());
            $ativo = $usuario->isAtivo() ? 
                Utilitarios::buscarImagem($imagens['IMG_TRUE']) : 
                Utilitarios::buscarImagem($imagens['IMG_FALSE']);

            $visualizar = new UILinkElement('javascript:void(0);',
                Utilitarios::buscarImagem($imagens['IMG_VISUALIZAR']),
                array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Detalhes'),
                "onclick=\"ajax.init( 'includes/PfwController.php?app=usuario&action=visualizar&id_usuario={$id}', 'viewers' );\"");
                
            $excluir = new UILinkElement('javascript:void(0);',
                Utilitarios::buscarImagem($imagens['IMG_EXCLUIR']),
                array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Cancelar'),
                "onclick=\"executarFuncaoComConfirmacao('Deseja realmente excluir os dados do usuário selecionado?',  
                'includes/PfwController.php?app=usuario&action=excluir&id_usuario={$id}');\"");

            $desativar = new UILinkElement('javascript:void(0);',
                Utilitarios::buscarImagem($imagens['IMG_DESATIVAR']),
                array('align'=>'center', 'style'=>'text-decoration:none;', 'alt'=>'Cancelar'),
                "onclick=\"executarFuncaoComConfirmacao('Deseja realmente desativar os dados do usuário selecionado?',  
                'includes/PfwController.php?app=usuario&action=desativar&id_usuario={$id}');\"");

            $tabela->addTableRow($linha, new UITableDataElement($nome));
            $tabela->addTableRow($linha, new UITableDataElement($email));
            $tabela->addTableRow($linha, new UITableDataElement($dtCadastro, array('align'=>'right')));
            $tabela->addTableRow($linha, new UITableDataElement($dtDesativacao, array('align'=>'right')));
            $tabela->addTableRow($linha, new UITableDataElement($ativo, array('align'=>'center')));
            $tabela->addTableRow($linha, new UITableDataElement($visualizar, array('align'=>'center')));
            $tabela->addTableRow($linha, new UITableDataElement($excluir, array('align'=>'center')));
            $tabela->addTableRow($linha, new UITableDataElement($desativar, array('align'=>'center')));
            
            $linha++;
        }                
        
        self::showList($tabela);
		
    }
 
    private static function showList($tabela) {		
			
        $inserir = new UILinkElement('javascript:void(0);', 'Cadastrar Usuario', array('alt' => 'Cadastrar'), 
                "onclick=\"ajax.init( 'includes/PfwController.php?app=usuario&action=inserir', 'viewers' );\"");
	
        $fieldSet = new UIFieldSetElement('Cadastro de Usuario');                
		
        $fieldSet->addChild($inserir);	
       
        if ($tabela->rowCount() > 0) {
        
            echo "<script>
                $(document).ready(function() {
                    $('#tblUsuario').tablesorter( {
                        sortList: [[0,0]], headers: {4:{sorter: false}, 5:{sorter: false}, 6:{sorter: false}, 7:{sorter: false}}
                    }); 
                });
            </script>";                                
        
            $fieldSet->addChild($tabela);
            $fieldSet->show();
        } else {
			$fieldSet->show();
            Utilitarios::exibirMensagemAVISO('Nenhum Usuario cadastrado.');
        }     		        
		
    } 
    
    public static function exibirPaginacao($total, $limite, $pagina) {
    
        $action = "onclick=\"ajax.init( 'includes/PfwController.php?app=usuario&action=listar&pag=:pag', 'viewers' );\"";
        UIPagination::paginar($total, $pagina, $action, $limite);  
       
    }      
    
    public static function montarFormulario(Usuario &$usuario) {

        self::montarFormularioCadastro($usuario, false);
        
    }    
    
    public static function montarFormularioEdicaoPerfil(Usuario &$usuario) {
        
        self::montarFormularioCadastro($usuario, true);
        
    }
    
    private static function montarFormularioCadastro(Usuario &$usuario, $perfil) {
             
        $idUsuario = isset($usuario) ? $usuario->getId() : 0;
        $nome = isset($usuario) ? utf8_decode($usuario->getNome()) : null;
        $email = isset($usuario) ? $usuario->getEmail() : null;
        $senha = isset($usuario) ? $usuario->getSenha() : null;        
        
        $action = $perfil ? 'salvarPerfil' : 'salvar';
        $label = $perfil ? 'Alterar Dados' : 'Cadastro de Usuario';
        $desabilita = ($idUsuario <> 0) ? 'disabled' : '';         
                
        $onSubmit = "onsubmit = \"return processarFormUsuario(this, 'usuario', '{$action}');\"";
        $form = new PfwForm();
        $form->setFormHeader('post', '?', array('name' => 'frmUsuario', 'id' => 'frmUsuario',
            'class' => 'form-horizontal'), $onSubmit, "");

        $form->addChild(new UIInputHiddenElement(array('name' => 'id_usuario',
            'id' => 'id_usuario', 'value' => $idUsuario)));
        $form->addChild(new UIInputHiddenElement(array('name' => 'senhaAtual',
            'id' => 'senhaAtual', 'value' => $senha)));

        $form->addControlGroup(new UILabelElement('Email *', array('for' => 'email', 'id' => 'lblEmail', 
            'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'email', 'id' => 'Email', 'class' => 'form-control',
            'maxlength' => '100', 'value' => $email), $desabilita));
            
        $form->addControlGroup(new UILabelElement('Nome *', array('for' => 'nome', 'id' => 'lblNome', 'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'nome', 'id' => 'nome', 'class' => 'form-control',
            'maxlength' => '50', 'value' => $nome)));
        
        if ($perfil) {
            $form->addControlGroup(new UILabelElement('Senha', array('for' => 'senha', 'id' => 'lblSenha', 
                'class' => 'control-label')), 
                new UIInputPasswordElement(array('name' => 'senha', 'id' => 'senha', 'class' => 'form-control',
                'maxlength' => '32', 'value' => '')));

            $form->addChild("<p class=\"text-warning\">
                                 <strong>AVISO: </strong>
                                 Modifique esse campo para definir uma nova senha
                                 </p>");
        }                                
        
        $form->addControlGroup(new PfwBtnConfirma());

        $form->showForm($label);
                
    }

    static function montarFormUpload($action, $redirect) {

        $frmUpload = new PfwForm();
        $frmUpload->setFormHeader('post', '?', array('name' => 'frmUpload', 'id' => 'frmUpload'), "", "");
        
        $frmUpload->addChild(new UIInputFileElement(array('name' => 'imagemperfil', 'id' => 'imagemperfil')));
        $frmUpload->addChild('<div id="recebe_up_basico" class="recebe">&nbsp;</div>');
        $frmUpload->addChild(new UIInputHiddenElement(array('name' => 'MAX_FILE_SIZE',
            'value' => '200000')));
        $aviso = "<p class=\"text-warning\">
                <strong>AVISO: </strong>
                Imagens permitidas .jpg, .jpeg, .png, .gif / Tamanho máximo: 2MB
                </p>";

        $frmUpload->addChild($aviso);
            
        $btnCarregar = new UIButtonElement(array('class' => 'btn btn-primary', 'value' => 'Carregar'), 
            "onClick=\"pfwUpload(this.form,'{$action}','recebe_up_basico','Carregando...','{$redirect}');
            return false;\"");
            
        $frmUpload->addChild($btnCarregar);

        $frmUpload->showForm('Alteração da imagem do perfil');
        
    }

    public static function exibirPerfil(Usuario $usuario, $exibeAcoes = true) {
    
        $id = isset($usuario) ? $usuario->getId() : 0;
        $nome = isset($usuario) ? utf8_decode($usuario->getNome()) : null;
        $email = isset($usuario) ? $usuario->getEmail() : null;
        $imagem = isset($usuario) ? Utilitarios::retornarImagemDoUsuario($usuario) : '';
        $dtCadastro = isset($usuario) ? Utilitarios::dataFormatada($usuario->getDataDeCadastro()) : '';
        $dtDesativacao = isset($usuario) ? Utilitarios::dataFormatada($usuario->getDataDeDesativacao()) : '';

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
        $fieldSetForm->addChild("<i class=\"fa fa-envelope\"></i> Email: {$email} <br>");
        $fieldSetForm->addChild("<i class=\"fa fa-calendar\"></i> Data de cadastro: {$dtCadastro} <br>");
        if (! $usuario->isAtivo()) {
            $fieldSetForm->addChild("<i class=\"fa fa-calendar\"></i> Data de desativação: {$dtDesativacao} <br>");        
        }
        $fieldSetForm->addChild('</p>');
        $fieldSetForm->addChild('</div>');
        $fieldSetForm->addChild('<div class="col-lg-4">');
        $fieldSetForm->addChild('</div>');
        $fieldSetForm->addChild('<div class="col-lg-12">');

        if ($exibeAcoes) {
            $linkAlterar = new UILinkElement('javascript:void(0);', 'Alterar Perfil', 
				array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Alterar senha'), 
                "onclick=\"ajax.init( 'includes/PfwController.php?app=usuario&action=alterarPerfil&id_usuario={$id}', 'viewers' );\"");

            $linkImagem = new UILinkElement('javascript:void(0);', 'Alterar Imagem', 
				array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Alterar imagem'), 
                "onclick=\"ajax.init( 'includes/PfwController.php?app=usuario&action=exibirFormUploadFoto&id_usuario={$id}', 'viewers' );\"");

            $linkBar = new UIControlBarElement(array());
            $linkBar->addChild($linkAlterar);
            $linkBar->addChild($linkImagem);
            $fieldSetForm->addChild($linkBar);
        }

        $fieldSetForm->addChild('<hr/>');
        $fieldSetForm->addChild('</div>');
        $fieldSetForm->show();
        
    }     
         
}