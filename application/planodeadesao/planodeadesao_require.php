<?php   
include_once PFW_APPLICATION . 'application.cfg.php';

define('CANCELAMENTO_PATH', PFW_APPLICATION . 'cancelamento' . PFW_DS);   
require_once( CANCELAMENTO_PATH.'cancelamento_entity.class.php' );
require_once( CANCELAMENTO_PATH.'cancelamento_view.class.php' );

define('USUARIO_PATH', PFW_APPLICATION . 'usuario' . PFW_DS);
require_once( USUARIO_PATH.'usuario_entity.class.php' );   
require_once( USUARIO_PATH.'usuario_dao.class.php' );   
require_once( USUARIO_PATH.'usuario_factory.class.php' );   

define('POLITICA_DE_PRECO_PATH', PFW_APPLICATION . 'politicadepreco' . PFW_DS);
require_once( POLITICA_DE_PRECO_PATH .'politicadepreco_entity.class.php' );   

define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
include_once UTILS_PATH . 'utilitarios.cfg.php';
include_once UTILS_PATH . 'pfw_elements/PfwElements.inc.php';

define('MODULO_PATH', PFW_APPLICATION . 'modulo' . PFW_DS);
require_once( MODULO_PATH.'modulo_entity.class.php' );
require_once( MODULO_PATH.'modulo_dao.class.php' );
require_once( MODULO_PATH.'modulo_factory.class.php' );

require_once( 'planodeadesao_entity.class.php' );
require_once( 'planodeadesao_factory.class.php' );
require_once( 'planodeadesao_business.class.php' );
require_once( 'planodeadesao_dao.class.php' );
require_once( 'planodeadesao_view.class.php' );       