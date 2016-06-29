<?php
$config_path = '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
include_once UTILS_PATH . 'utilitarios.cfg.php';
include_once UTILS_PATH . 'pfw_elements/PfwElements.inc.php';

define('PESSOA_PATH', PFW_APPLICATION . 'pessoa' . PFW_DS);
require_once PESSOA_PATH . 'pessoa_require.php';

include_once PFW_APPLICATION . 'application.cfg.php';

if ($action == 'buscar') {
       	
    if (PfwRequest::isValid('documento')) {
        buscarPeloDocumento();
    }
    
    if (PfwRequest::isValid('estado')) {
        buscarCidades();
    }
}

if ($action == 'perfil') {
    //echo 'Exibir perfil do usuário logado.';
    $application = $GLOBALS['application'];
    $sessao = new PfwSession();
    $idUsuario = $sessao->getSessionValue($application['PFW_SESSION_USER']);
    $usuario = PessoaFactory::criarPessoa();
    $usuario->setId($idUsuario);
    $pessoaDAO = PessoaFactory::criarPessoaDAO(Utilitarios::retornarDriver());
    $pessoaDAO->load($usuario);
    PessoaView::exibirPerfil($usuario);
}

if ($action == 'editarPerfil') {
    $application = $GLOBALS['application'];
    $sessao = new PfwSession();
    $idUsuario = $sessao->getSessionValue($application['PFW_SESSION_USER']);
    $usuario = PessoaFactory::criarPessoa();
    $usuario->setId($idUsuario);
    $driver = Utilitarios::retornarDriver();
    $pessoaDAO = PessoaFactory::criarPessoaDAO($driver);
    $pessoaDAO->load($usuario);
    if ($usuario != null) {        
        $estado = $usuario->getEstado();
        $enderecoDAO = EnderecoFactory::criarEnderecoDAO($driver);
        $estados = $enderecoDAO->buscarEstados();
        $cidades = $enderecoDAO->buscarCidades($estado);        
        PessoaView::montarFormulario($usuario, $estados, $cidades);
    }    
}

if ($action == 'salvarPerfil') {
    $pessoaDAO = PessoaFactory::criarPessoaDAO(Utilitarios::retornarDriver());
    $usuario = PessoaFactory::criarPessoa();    
    $pessoaBusiness = PessoaFactory::criarPessoaBusiness();
    try {        
		$idPessoa = PfwRequest::get('id_pessoa');
	
        if (! Utilitarios::estaInserindo($idPessoa)) {
            $usuario->setId($idPessoa);
            $pessoaDAO->load($usuario);        
        }

        PessoaFactory::atribuirValores($usuario, $_POST);                                           

        $pessoaDAO->beginTransaction();
        $pessoaBusiness->validarPessoa($usuario);
        $pessoaDAO->save($usuario);        
        $pessoaDAO->commitTransaction();        
        Utilitarios::exibirMensagemOK('Perfil atualizado com sucesso.');    
        PessoaView::exibirPerfil($usuario);    
    } catch (Exception $e) {
        $pessoaDAO->rollbackTransaction();
        Utilitarios::exibirMensagemERRO($e->getMessage());
    }   
}

if ($action == 'uploadFoto') {
    $sessao = new PfwSession();
    $application = $GLOBALS['application'];
    $idUsuario = $sessao->getSessionValue($application['PFW_SESSION_USER']);
    PessoaView::montarFormUpload("application/pessoa/upload_imagem_perfil.php?id_pessoa={$idUsuario}",
        "includes/PfwController.php?app=pessoa&action=perfil");
}

function buscarPeloDocumento() {    
    $driver = Utilitarios::retornarDriver();
    $driver->connect();

    $dao = new PessoaDAO($driver);

    $pessoa = $dao->buscarPeloDocumento($_GET['documento']);
    $arr = array();                

    if (isset($pessoa)) {                   
        $arr[] = array('id_pessoa' => $pessoa->getId(),
        'documento' => $pessoa->getDocumento(),
        'nome' => utf8_encode($pessoa->getNome()),
        'cep' => $pessoa->getCep(),
        'logradouro' => $pessoa->getLogradouro(),
        'numero' => $pessoa->getNumero(),
        'complemento' => $pessoa->getComplemento(),
        'bairro' => $pessoa->getBairro(),
        'estado' => $pessoa->getEstado()->getId(),
        'cidade' => $pessoa->getCidade()->getId(),
        'telefone1' => $pessoa->getTelefone1(),
        'telefone2' => $pessoa->getTelefone2(),
        'senha' => $pessoa->getSenha(),
        'email' => $pessoa->getEmail());
    }        

    // Para conseguiu recuperar os dados no js, devemos utilizar o json_encode para que a instrução eval funcione.
    // Tem que utilizar o json_encoder nos campos texto senão no js não recupera o valor com caracteres especiais
    echo json_encode($arr);        
}

function buscarCidades() {    
    $driver = Utilitarios::retornarDriver();
    $enderecoDAO = EnderecoFactory::criarEnderecoDAO($driver);

    $estado = $enderecoDAO->buscarEstado(PfwRequest::get('estado'));

    $cidades = $enderecoDAO->buscarCidades($estado);

    $arr = array();        

    foreach ($cidades as $cidade) {

        $arr[] = array(
          'id' => $cidade->getId(),
          'nome' => $cidade->getNome()
        );

    }

    echo json_encode($arr);       
}