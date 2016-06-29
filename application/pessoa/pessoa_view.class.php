<?php

abstract class PessoaView {

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

    public static function exibirPerfil(Pessoa $pessoa) {
    
        $id = isset($pessoa) ? $pessoa->getId() : 0;
        $nome = isset($pessoa) ? utf8_decode($pessoa->getNome()) : null;
        $telefone1 = isset($pessoa) ? Utilitarios::retornarTelefoneFormatado($pessoa->getTelefone1()) : '';
        $telefone2 = isset($pessoa) ? Utilitarios::retornarTelefoneFormatado($pessoa->getTelefone2()) : '';
        $cep = isset($pessoa) ? $pessoa->getCep() : '';
        $logradouro = isset($pessoa) ? utf8_decode($pessoa->getLogradouro()) : '';
        $numero = isset($pessoa) ? utf8_decode($pessoa->getNumero()) : '';
        $complemento = isset($pessoa) ? utf8_decode($pessoa->getComplemento()) : '';
        $bairro = isset($pessoa) ? utf8_decode($pessoa->getBairro()) : '';
        $estado = isset($pessoa) ? utf8_decode($pessoa->getEstado()->getNome()) : '';
        $cidade = isset($pessoa) ? utf8_decode($pessoa->getCidade()->getNome()) : '';
        $email = isset($pessoa) ? $pessoa->getEmail() : null;
        $imagem = isset($pessoa) ? Utilitarios::retornarImagemDaPessoa($pessoa) : '';

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
        $fieldSetForm->addChild("<i class=\"fa fa-envelope\"></i> {$email} <br>");
        $fieldSetForm->addChild("<i class=\"fa fa-phone\"></i> <b>Telefone 1</b> {$telefone1} <br>");
        $fieldSetForm->addChild("<i class=\"fa fa-phone\"></i> <b>Telefone 2</b> {$telefone2} <br>");
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

        $linkAlterar = new UILinkElement('javascript:void(0);', 'Alterar dados', 
			array('align' => 'center', 'style' => 'text-decoration:none;', 'alt' => 'Alterar dados'), 
			"onclick=\"ajax.init( 'includes/PfwController.php?app=pessoa&action=editarPerfil', 'viewers' );\"");

        $linkImagem = new UILinkElement('javascript:void(0);', 'Alterar Imagem', array('id' => 'altimagem'), 
        "onclick=\"ajax.init( 'includes/PfwController.php?app=pessoa&action=uploadFoto', 'viewers' );\"");

        $linkBar = new UIControlBarElement(array());
        $linkBar->addChild($linkAlterar);
        $linkBar->addChild($linkImagem);
        $fieldSetForm->addChild($linkBar);

        $fieldSetForm->addChild('<hr/>');
        $fieldSetForm->addChild('</div>');
        $fieldSetForm->show();
        
    }

    public static function montarFormulario(Pessoa $pessoa, $estados = null, $cidades = null) {
    
        $onSubmit = "onsubmit = \"return processarFormPessoa(this, 'pessoa', 'salvarPerfil');\"";
        $form = new PfwForm();
        $form->setFormHeader('post', '?', array('name' => 'frmPessoa', 'id' => 'frmPessoa',
            'class' => 'form-horizontal'), $onSubmit, "");

        static::criarControlesNoFormularios($form, $pessoa, $estados, $cidades);

        $form->addControlGroup(new PfwBtnConfirma());

        $form->showForm('Perfil');
        
    }

    public static function criarControlesNoFormularios(PfwForm &$form, Pessoa $pessoa = null, $estados = null, $cidades = null) {

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
        $telefone1 = isset($pessoa) ? $pessoa->getTelefone1() : null;
        $telefone2 = isset($pessoa) ? $pessoa->getTelefone2() : null;
        $email = isset($pessoa) ? $pessoa->getEmail() : null;
        //$senha = isset($pessoa) ? $pessoa->getSenha() : null;

        $form->addChild(new UIInputHiddenElement(array('name' => 'id_pessoa',
            'id' => 'id_pessoa', 'value' => $idPessoa)));
        $form->addChild(new UIInputHiddenElement(array('name' => 'documentoatual',
            'id' => 'documentoatual', 'value' => $documento)));
        /*$form->addChild(new UIInputHiddenElement(array('name' => 'senhaatual',
            'id' => 'senhaatual', 'value' => $senha)));*/

        $form->addChild(new PfwCampoDocumento($documento));

        $form->addControlGroup(new UILabelElement('Nome *', array('for' => 'nome', 'id' => 'lblNome', 'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'nome', 'id' => 'nome', 'class' => 'form-control',
            'maxlength' => '50', 'value' => $nome)));

        $form->addChild(new PfwCampoCEP($cep));

        $form->addControlGroup(new UILabelElement('Logradouro *', array('for' => 'logradouro', 'id' => 'lblLogradouro', 'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'logradouro', 'id' => 'logradouro', 'class' => 'form-control',
            'maxlength' => '50', 'value' => $logradouro)));

        $form->addControlGroup(new UILabelElement('Número', array('for' => 'numero', 'id' => 'lblNumero', 'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'numero', 'id' => 'numero', 'class' => 'form-control',
            'maxlength' => '50', 'value' => $numero)));

        $form->addControlGroup(new UILabelElement('Complemento', array('for' => 'complemento', 'id' => 'lblComplemento', 'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'complemento', 'id' => 'complemento', 'class' => 'form-control',
            'maxlength' => '50', 'value' => $complemento)));

        $form->addControlGroup(new UILabelElement('Bairro *', array('for' => 'bairro', 'id' => 'lblBairro', 'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'bairro', 'id' => 'bairro', 'class' => 'form-control',
            'maxlength' => '50', 'value' => $bairro)));

        $form->addChild(new PfwCampoEstadoCidade($estados, $estadoDaPessoa, $cidades, $cidadeDaPessoa));
        
        $form->addChild(new PfwCampoTelefone('Telefone 1', 'telefone1', $telefone1));
        
        $form->addChild(new PfwCampoTelefone('Telefone 2', 'telefone2', $telefone2));

        $form->addControlGroup(new UILabelElement('Email *', array('for' => 'email', 'id' => 'lblEmail', 'class' => 'control-label')), 
            new UIInputTextElement(array('name' => 'email', 'id' => 'Email', 'class' => 'form-control',
            'maxlength' => '100', 'value' => $email)));

        /*$form->addControlGroup(new UILabelElement('Senha *', array('for' => 'senha', 'id' => 'lblSenha', 'class' => 'control-label')), 
            new UIInputPasswordElement(array('name' => 'senha', 'id' => 'senha', 'class' => 'form-control',
            'maxlength' => '50')));

        $form->addChild("<p class=\"text-warning\">
                             <strong>AVISO: </strong>
                             Modifique esse campo para definir uma nova senha
                             </p>");*/
                             
    }

}