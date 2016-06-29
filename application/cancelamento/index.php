<?php
/* obtendo as configuraçes do banco de dados */
$config_path = '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

define('CANCELAMENTO_PATH', PFW_APPLICATION . 'cancelamento' . PFW_DS);
require_once( CANCELAMENTO_PATH.'cancelamento_view.class.php' );   

require_once(PFW_CLASS_PATH . 'PfwSession.class.php');

define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
include_once UTILS_PATH . 'utilitarios.cfg.php';
include_once UTILS_PATH . 'pfw_elements/PfwElements.inc.php';

define('USUARIO_PATH', PFW_APPLICATION . 'usuario' . PFW_DS);
require_once( USUARIO_PATH.'usuario_require.php' );   

include_once PFW_APPLICATION . 'application.cfg.php';

$application = $GLOBALS['application'];

// Buscar usuario logado na sessão
$sessao = new PfwSession();
if (! $sessao->sessionValueExists($application['PFW_SESSION_USER'])) {
    die("Não existe nenhum usuário configurado na sessão");
}                

$driver = Utilitarios::retornarDriver();
$driver->connect();

// Buscar dados do usuário
$idUsuario = $sessao->getSessionValue($application['PFW_SESSION_USER']);
$usuario = UsuarioFactory::criarUsuario();
$usuario->setId($idUsuario);
$usuarioDAO = UsuarioFactory::criarUsuarioDAO($driver);
$usuarioDAO->load($usuario);

CancelamentoView::mostrarForm($_GET, $usuario);

$driver->disconnect();