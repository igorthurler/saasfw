<?php 
include_once PFW_APPLICATION . 'application.cfg.php';

define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
include_once UTILS_PATH . 'utilitarios.cfg.php';
include_once UTILS_PATH . 'pfw_elements/PfwElements.inc.php';
include_once UTILS_PATH . 'envia_email.class.php';

define('PESSOA_PATH', PFW_APPLICATION . 'pessoa' . PFW_DS);
require_once( PESSOA_PATH.'pessoa_require.php' );   

define('CONTRATANTE_PATH', PFW_APPLICATION . 'contratante' . PFW_DS);
require_once CONTRATANTE_PATH . 'contratante_entity.class.php';   
require_once CONTRATANTE_PATH . 'contratante_factory.class.php';   
require_once CONTRATANTE_PATH . 'contratante_dao.class.php';   
require_once CONTRATANTE_PATH . 'contratante_business.class.php';   

define('USUARIO_PATH', PFW_APPLICATION . 'usuario' . PFW_DS);
require_once( USUARIO_PATH.'usuario_dao.class.php');
require_once( USUARIO_PATH.'usuario_entity.class.php');
require_once( USUARIO_PATH.'usuario_factory.class.php');

define('CANCELAMENTO_PATH', PFW_APPLICATION . 'cancelamento' . PFW_DS);   
require_once( CANCELAMENTO_PATH.'cancelamento_entity.class.php' );
require_once( CANCELAMENTO_PATH.'cancelamento_view.class.php' );

define('PLANODEADESAO_PATH', PFW_APPLICATION . 'planodeadesao' . PFW_DS);      
require_once PLANODEADESAO_PATH . 'planodeadesao_entity.class.php';   
require_once PLANODEADESAO_PATH . 'planodeadesao_dao.class.php';   
require_once PLANODEADESAO_PATH . 'planodeadesao_factory.class.php';   

define('MODULO_PATH', PFW_APPLICATION . 'modulo' . PFW_DS);
require_once( MODULO_PATH.'modulo_entity.class.php' );

define('ENUMERADOS_PATH', PFW_APPLICATION . 'enumerados' . PFW_DS);
require_once( ENUMERADOS_PATH.'tipodepagamento.enum.php' );   
require_once( ENUMERADOS_PATH.'formadepagamento.enum.php' );   
require_once( ENUMERADOS_PATH.'statusdocontrato.enum.php' );   
require_once( ENUMERADOS_PATH.'statusdopagamento.enum.php' );   
require_once( ENUMERADOS_PATH.'tipopermissao.enum.php' );   

define('POLITICADEPRECO_PATH', PFW_APPLICATION . 'politicadepreco' . PFW_DS);
require_once( POLITICADEPRECO_PATH.'politicadepreco_entity.class.php' );      
require_once( POLITICADEPRECO_PATH.'politicadepreco_dao.class.php' );      
require_once( POLITICADEPRECO_PATH.'politicadepreco_factory.class.php' );      

define('CONFIG_ADMIN_PATH', PFW_APPLICATION . 'configadmin' . PFW_DS);      
require_once( CONFIG_ADMIN_PATH.'configadmin_entity.class.php' );      
require_once( CONFIG_ADMIN_PATH.'configadmin_dao.class.php' );      
require_once( CONFIG_ADMIN_PATH.'configadmin_factory.class.php' );      

define('PAGAMENTO_PATH', PFW_APPLICATION . 'pagamento' . PFW_DS);      
require_once( PAGAMENTO_PATH.'pagamento_entity.class.php' );      
require_once( PAGAMENTO_PATH.'pagamento_dao.class.php' );      
require_once( PAGAMENTO_PATH.'pagamento_business.class.php' );      
require_once( PAGAMENTO_PATH.'pagamento_factory.class.php' );      
require_once( PAGAMENTO_PATH.'gerapagamento_interface.php' );      

require_once( 'contrato_entity.class.php' );
require_once( 'contrato_dao.class.php' );
require_once( 'contrato_business.class.php' );
require_once( 'contrato_factory.class.php' );
require_once( 'contrato_email.class.php' );
require_once( 'contrato_view.class.php' );
require_once( 'gerapagamento_contrato.class.php' );    