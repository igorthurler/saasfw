<?php
$config_path = DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once("../..".$config_path . 'basic_require.php');

include_once PFW_APPLICATION . 'application.cfg.php';

include_once PFW_APPLICATION.'language/'.$application['PFW_DEFAULT_LANG'].'.php'; 

define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
include_once UTILS_PATH . 'utils_require.php';

require_once 'usuario_require.php';

$module = PfwRequest::get('module');

$sessao = new PfwSession();

$driver = Utilitarios::retornarDriver();
$driver->connect();    

$identificador = PfwRequest::get('identificador');
$senha = PfwRequest::get('senha');

$possuiIdentificador = ! empty($identificador);
$possuiSenha = ! empty($senha);

if (! $possuiIdentificador || ! $possuiSenha) {
    echo $lang['AUTH_ERROR_FIELDS'];
} else {
  $usuarioDAO = UsuarioFactory::criarUsuarioDAO($driver);  
  $usuario = $usuarioDAO->retornarAutenticado($identificador, md5($senha));
  $driver->disconnect();  
  if ($usuario != null) {
      if (! $usuario->isAtivo()) {
          echo $lang['AUTH_ERROR_ACTIVE_USER'];
      } else {
        $application = $GLOBALS['application'];
        $sessao->setSessionValue($application['PFW_SESSION_USER'], $usuario->getId());
        header("Location: ../../{$module}");
      }
  } else {
      echo $lang['AUTH_ERROR_USER_NOT_FOUND'];
  }      
}            

$driver->disconnect();