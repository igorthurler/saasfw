<?php
$config_path = '../..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
include_once UTILS_PATH . 'utils_require.php';

define('USUARIO_PATH', PFW_APPLICATION . 'usuario' . PFW_DS);
require_once USUARIO_PATH . 'usuario_require.php';

define('UPLOAD_PATH', '../../uploads' . PFW_DS);

if (isset($_FILES['imagemperfil'])) {
    // 1. Recuperar nome temporário do arquivo.
    $tmp = $_FILES['imagemperfil']['tmp_name'];
    
    // 2- recuperar extensões validas
    $extensoesValidas = Utilitarios::arrayImagensPermitidas();
    
    // 3- recuperar tamanho do arquivo
    $tamanhoDoArquivo = $_FILES['imagemperfil']['size'];
    
    // 4- verificar se o arquivo é maior que o tamanho padrão
    if ($tamanhoDoArquivo > Utilitarios::DEFAULT_IMAGE_SIZE) {
        die('O arquivo deve ter no máximo ' . Utilitarios::DEFAULT_IMAGE_SIZE);
    }
    
    // 5- verificar se a extensão é valida
    $nomeDoArquivo = $_FILES['imagemperfil']['name'];
    $extesaoDoArquivo = strrchr($nomeDoArquivo, '.');
    if (!in_array($extesaoDoArquivo, $extensoesValidas)) {
        die('Extensão do arquivo inválida');
    }    
    
    // 6. Recuperar dados do usuarioistrador passado pelo parametro id
    $idUsuario = PfwRequest::get('id_usuario');
    $usuario = UsuarioFactory::criarUsuario();
    $usuario->setId($idUsuario);
    $driver = Utilitarios::retornarDriver();
    $usuarioDAO = UsuarioFactory::criarUsuarioDAO($driver);
    $usuarioDAO->load($usuario);
    $identificador = $usuario->getEmail();
    
    // 7. Verificar se existe uma pasta para a pessoa dentro da pasta upload.
    $diretorio = UPLOAD_PATH . $identificador;
    if (! is_dir($diretorio)) {
        // 7.1. Se não existir, criar pasta com o identificador da pessoa
        mkdir(UPLOAD_PATH . $identificador);
    }    
    
    // 8. Definir nome para a imagem de upload ex. md5([nomedaimagem]fulano@gmail.com).[extensão da imagem]
    $novaImagem = md5('perfil_' . $nomeDoArquivo . $identificador) . $extesaoDoArquivo;
    
    // 9. Executar o upload da imagem.   
    $novoArquivo = UPLOAD_PATH . $identificador . PFW_DS . $novaImagem;
    if (move_uploaded_file($tmp, $novoArquivo)) {
        $imagemAtual = $usuario->getImagem();    
        $usuario->setImagem($novaImagem);
        $usuarioDAO->save($usuario);        
        // 9.1. Apagar imagem antiga, se existir
        unlink(UPLOAD_PATH . $identificador . PFW_DS . $imagemAtual);        
    }
}