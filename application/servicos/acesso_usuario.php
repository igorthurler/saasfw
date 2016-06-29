<?php
$config_path = '../..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

define('USUARIO_PATH', PFW_APPLICATION . 'usuario' . PFW_DS);
require_once( USUARIO_PATH . 'usuario_require.php' );

define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
include_once UTILS_PATH . 'utilitarios.cfg.php';

$server = new soap_server;

// iniciando suporte ao WSDL
$server->configureWSDL('server.acesso_usuario','urn:server.acesso_usuario');
$server->wsdl->schemaTargetNamespace = 'urn:server.acesso_usuario';

// registra o método
$server->register('acesso_usuario',
array('identificador'=>'xsd:string','senha'=>'xsd:string'),
array('return'=>'xsd:string'),
'urn:server.acesso_usuario',
'urn:server.acesso_usuario#acesso_usuario',
'rpc',
'encoded',
'Retorna "ERRO" se o usuário não for autenticado.
Retorna os dados do usuário se o usuário for autenticado (ID|NOME|EMAIL)');

function acesso_usuario($identificador, $senha) {    
    $driver = Utilitarios::retornarDriver();
    $dao = UsuarioFactory::criarUsuarioDAO($driver);
    $usuario = $dao->retornarAutenticado($identificador, $senha);
    if (! isset($usuario)) {
        return 'ERRO';
    } else {
      if (! $usuario->isAtivo()) {
        return 'ERRO';
      } else {
        return $usuario->toString();
      }  
    }
}

$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
$server->service($HTTP_RAW_POST_DATA);