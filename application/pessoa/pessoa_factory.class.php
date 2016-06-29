<?php
abstract class PessoaFactory {

    static public function atribuirValores(Pessoa &$pessoa, $dados) {        
        if (isset($dados)) {
            
            $idPessoa = isset($dados['id_pessoa']) && ($dados['id_pessoa'] != "") ? $dados['id_pessoa'] : null;             
            $documento = isset($dados['documento']) ? $dados['documento'] : null;
            $nome = isset($dados['nome']) ? $dados['nome'] : null;
            $cep = isset($dados['cep']) ? $dados['cep'] : null;
            $logradouro = isset($dados['logradouro']) ? $dados['logradouro'] : null;
            $numero = isset($dados['numero']) && ($dados['numero'] != "") ? $dados['numero'] : null;
            $complemento = isset($dados['complemento']) && ($dados['complemento'] != "") ? $dados['complemento'] : null;
            $bairro = isset($dados['bairro']) ? $dados['bairro'] : null;
            $telefone1 = isset($dados['telefone1']) && ($dados['telefone1'] != "") ? $dados['telefone1'] : null; 
            $telefone2 = isset($dados['telefone2']) && ($dados['telefone2'] != "") ? $dados['telefone2'] : null; 
            $email = isset($dados['email']) && !empty($dados['email']) ? $dados['email'] : null; 
            //$senha = isset($dados['senha']) && !empty($dados['senha']) ? md5($dados['senha']) : null; 
            //$senhaatual = isset($dados['senhaatual']) && !empty($dados['senhaatual']) ? $dados['senhaatual'] : null;             
                            
            $pessoa->setId($idPessoa);
            $pessoa->setDocumento($documento);
            $pessoa->setNome($nome);
            $pessoa->setCep($cep);
            $pessoa->setLogradouro($logradouro);
            $pessoa->setNumero($numero);
            $pessoa->setComplemento($complemento);
            $pessoa->setBairro($bairro);            
            
            $driver = DAOFactory::getDAO()->getDriver();            
            
            $enderecoDAO = EnderecoFactory::criarEnderecoDAO($driver);

            if (isset($dados['estado']) && $dados['estado'] != '') {			
                $estado = EnderecoFactory::criarEstado();
                $estado->setId($dados['estado']);
                $enderecoDAO->load($estado);                
                $pessoa->setEstado($estado);            
            }

            if (isset($dados['cidade']) && $dados['cidade'] != '') {				
                $cidade = EnderecoFactory::criarCidade();
                $cidade->setId($dados['cidade']);
                $enderecoDAO->load($cidade);                
                $pessoa->setCidade($cidade);            
            }

            $pessoa->setTelefone1($telefone1);
            $pessoa->setTelefone2($telefone2);
            $pessoa->setEmail($email);
                
            /*if (Utilitarios::estaInserindo($idPessoa)) {			
                $senha = $pessoa->getSenha();
                if (! isset($senha) || $senha == '') {
                    $senhaProvisoria = Utilitarios::gerarSenha(5);
                    $senhaMd5 = md5($senhaProvisoria);
                    $pessoa->setSenhaDescriptografada($senhaProvisoria);
                    $pessoa->setSenha($senhaMd5);					
                }
            } else {
                $mudouSenha = isset($senha) && ($senhaatual != $senha);
                if ($mudouSenha) {
                        $pessoa->setSenha($senha);                                   
                }
            }*/
			
        }
        
    }
    
    static public function criarPessoa() {
        return new Pessoa();
    }
    
    static public function criarPessoaDAO($driver) {
        return new PessoaDAO($driver);
    }

    static public function criarPessoaBusiness() {
        return new PessoaBusiness();
    }    
}