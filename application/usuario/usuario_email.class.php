<?php
class UsuarioEmail extends EnviaEmail {
		
    public function notificarCriacao(Usuario $usuario) {       
        
        $nome = $usuario->getNome();
        $email = $usuario->getEmail();		
        $senha = $usuario->getSenhaDescriptografada();
	        
        $mensagem = "<h1>Cadastro de usuários.</h1>";
        $mensagem .= "<p>";
        $mensagem .= "Prezado(a) Sr.(a) $nome,<br/><br/>";
        $mensagem .= "Seus dados foram cadastrados com sucesso.<br/>";
        $mensagem .= "Usuario: $email<br/>";        
        $mensagem .= "<strong>Senha Provisória: $senha</strong><br/>";
        $mensagem .= "</p><br/>";
        $mensagem .= "<hr/>";
        $mensagem .= "<br/>"+
        $mensagem .= "Este é um e-mail automático disparado pelo sistema.";
        
        $this->enviarEmail($emailDoContratante, "Cadastro de usuários", $mensagem);        
        
    }
	
}