<?php 
define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
include_once UTILS_PATH . 'utilitarios.cfg.php';
include_once UTILS_PATH . 'pfw_elements/PfwElements.inc.php';

define('ENUMERADOS_PATH', PFW_APPLICATION . 'enumerados' . PFW_DS);
require_once( ENUMERADOS_PATH.'tipodepagamento.enum.php' );   
require_once( ENUMERADOS_PATH.'formadepagamento.enum.php' );   

define('USUARIO_PATH', PFW_APPLICATION . 'usuario' . PFW_DS);
require_once( USUARIO_PATH.'usuario_dao.class.php');
require_once( USUARIO_PATH.'usuario_entity.class.php');
require_once( USUARIO_PATH.'usuario_factory.class.php');

define('CANCELAMENTO_PATH', PFW_APPLICATION . 'cancelamento' . PFW_DS);   
require_once( CANCELAMENTO_PATH.'cancelamento_entity.class.php' );

define('MODULO_PATH', PFW_APPLICATION . 'modulo' . PFW_DS);
require_once( MODULO_PATH.'modulo_entity.class.php' );

define('PLANODEADESAO_PATH', PFW_APPLICATION . 'planodeadesao' . PFW_DS);      
require_once PLANODEADESAO_PATH . 'planodeadesao_entity.class.php';   

define('POLITICADEPRECO_PATH', PFW_APPLICATION . 'politicadepreco' . PFW_DS);
require_once( POLITICADEPRECO_PATH.'politicadepreco_entity.class.php' );      
require_once( POLITICADEPRECO_PATH.'politicadepreco_dao.class.php' );      
require_once( POLITICADEPRECO_PATH.'politicadepreco_factory.class.php' );      

require_once( 'configadmin_business.class.php' );
require_once( 'configadmin_dao.class.php' );
require_once( 'configadmin_view.class.php' );
require_once( 'configadmin_entity.class.php' );
require_once( 'configadmin_factory.class.php' );