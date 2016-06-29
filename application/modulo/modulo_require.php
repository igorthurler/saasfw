<?php 
require_once(PFW_INC . 'basic_require.php');

include_once PFW_APPLICATION . 'application.cfg.php';

define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
require_once UTILS_PATH . 'utilitarios.cfg.php';
include_once UTILS_PATH . 'pfw_elements/PfwElements.inc.php';

define('CANCELAMENTO_PATH', PFW_APPLICATION . 'cancelamento' . PFW_DS);
require_once( CANCELAMENTO_PATH. 'cancelamento_entity.class.php' );   
require_once( CANCELAMENTO_PATH. 'cancelamento_view.class.php' );   

define('USUARIO_PATH', PFW_APPLICATION . 'usuario' . PFW_DS);
require_once( USUARIO_PATH.'usuario_entity.class.php' );   
require_once( USUARIO_PATH.'usuario_dao.class.php' );   
require_once( USUARIO_PATH.'usuario_factory.class.php' );   

define('PESSOA_PATH', PFW_APPLICATION . 'pessoa' . PFW_DS);
require_once( PESSOA_PATH.'pessoa_require.php' );   

define('PLANO_DE_ADESAO_PATH', PFW_APPLICATION . 'planodeadesao' . PFW_DS);
require_once( PLANO_DE_ADESAO_PATH . 'planodeadesao_entity.class.php' );         

require_once( 'modulo_entity.class.php' );   
require_once( 'modulo_factory.class.php' );   
require_once( 'modulo_business.class.php' );
require_once( 'modulo_dao.class.php' );
require_once( 'modulo_view.class.php' );    