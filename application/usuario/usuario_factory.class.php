<?php

abstract class UsuarioFactory {

    static public function atribuirValores(Usuario &$usuario, $dados) {
            
		$idUsuario = PfwRequest::isValid('id_usuario') ? PfwRequest::get('id_usuario') : null;
		$nome = PfwRequest::isValid('nome') ? PfwRequest::get('nome') : null;
		$email = PfwRequest::isValid('email') ? PfwRequest::get('email') : null;
		$senha = PfwRequest::isValid('senha') ? PfwRequest::get('senha') : null;
		$senhaAtual = PfwRequest::isValid('senhaAtual') ? PfwRequest::get('senhaAtual') : null;
		
		$usuario->setId($idUsuario);
		$usuario->setNome($nome);
		$usuario->setEmail($email);
		
		if (Utilitarios::estaInserindo($idUsuario)) {                
			$dataDeCadastro = date(Utilitarios::FORMAT_DMYY);
			$usuario->setDataDeCadastro($dataDeCadastro);
			if ($senha == null) {
				$senha = Utilitarios::gerarSenha(5);
			}                
			$usuario->setSenhaDescriptografada($senha);
			$usuario->setSenha(md5($senha));                
		} else {
			$mudouSenha = isset($senha) && ($senhaAtual != md5($senha));
			if ($mudouSenha) {
				$usuario->setSenha(md5($senha));
				$usuario->setSenhaDescriptografada($senha);
			}
		}
        
    }

    static public function criarUsuario() {
        return new Usuario();
    }

    static public function criarUsuarioDAO($driver) {
        return new UsuarioDAO($driver);
    }

    static public function criarUsuarioBusiness(UsuarioDAO $dao) {
        return new UsuarioBusiness($dao);
    }
    
}