<?php    
include_once PFW_APPLICATION . 'application.cfg.php';

define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
include_once UTILS_PATH . 'utilitarios.cfg.php';
include_once UTILS_PATH . 'pfw_elements/PfwElements.inc.php';

define('PESSOA_PATH', PFW_APPLICATION . 'pessoa' . PFW_DS);   
require_once( PESSOA_PATH.'pessoa_require.php' );      

define('CLIENTE_PATH', PFW_APPLICATION . 'contratante' . PFW_DS);   
require_once( CLIENTE_PATH.'contratante_entity.class.php' );   

define('CONTRATO_PATH', PFW_APPLICATION . 'contrato' . PFW_DS);   
require_once( CONTRATO_PATH.'contrato_entity.class.php' );      

define('CANCELAMENTO_PATH', PFW_APPLICATION . 'cancelamento' . PFW_DS);   
require_once( CANCELAMENTO_PATH.'cancelamento_entity.class.php' );
require_once( CANCELAMENTO_PATH.'cancelamento_view.class.php' );

define('PLANODEADESAO_PATH', PFW_APPLICATION . 'planodeadesao' . PFW_DS);      
require_once PLANODEADESAO_PATH . 'planodeadesao_entity.class.php';   
require_once PLANODEADESAO_PATH . 'planodeadesao_dao.class.php';   
require_once PLANODEADESAO_PATH . 'planodeadesao_factory.class.php';   

define('MODULO_PATH', PFW_APPLICATION . 'modulo' . PFW_DS);
require_once( MODULO_PATH.'modulo_entity.class.php' );

define('USUARIO_PATH', PFW_APPLICATION . 'usuario' . PFW_DS);
require_once( USUARIO_PATH.'usuario_entity.class.php' );   

require_once( 'politicadepreco_entity.class.php' );
require_once( 'politicadepreco_business.class.php' );
require_once( 'politicadepreco_dao.class.php' );
require_once( 'politicadepreco_view.class.php' );     
require_once( 'politicadepreco_factory.class.php' );