<?php
$config_path = '../../includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
require_once UTILS_PATH . 'utilitarios.cfg.php';

include_once PFW_APPLICATION . 'application.cfg.php';

require_once( 'usuario_require.php' );

$sessao = new PfwSession();

$idUsuario = $sessao->getSessionValue($application['PFW_SESSION_USER']);

$driver = Utilitarios::retornarDriver();

$usuarioDAO = UsuarioFactory::criarUsuarioDAO($driver);
$usuario = UsuarioFactory::criarUsuario();
$usuario->setId($idUsuario);
$usuarioDAO->load($usuario);

$nome = isset($usuario) ? $usuario->getPrimeiroNome() : "";
$imagem = Utilitarios::retornarImagemDoUsuario($usuario);

$arr = array();
$arr[] = array(
    'nome' => $nome,
    'imagem' => $imagem
);
echo json_encode($arr);