<?php    
include_once PFW_APPLICATION . 'application.cfg.php';

define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
include_once UTILS_PATH . 'utilitarios.cfg.php';
include_once UTILS_PATH . 'pfw_elements/PfwElements.inc.php';

require_once(PFW_CLASS_PATH . 'PfwSession.class.php');

define('PESSOA_PATH', PFW_APPLICATION . 'pessoa' . PFW_DS);
require_once( PESSOA_PATH.'pessoa_require.php' );   

define('CONTRATO_PATH', PFW_APPLICATION . 'contrato' . PFW_DS);
require_once( CONTRATO_PATH.'contrato_entity.class.php' );   
require_once( CONTRATO_PATH.'contrato_factory.class.php' );   
require_once( CONTRATO_PATH.'contrato_dao.class.php' );   
require_once( CONTRATO_PATH.'contrato_view.class.php' );   

define('PAGAMENTO_PATH', PFW_APPLICATION . 'pagamento' . PFW_DS);      
require_once( PAGAMENTO_PATH.'pagamento_entity.class.php' );      
require_once( PAGAMENTO_PATH.'pagamento_dao.class.php' );      
require_once( PAGAMENTO_PATH.'pagamento_factory.class.php' );      

define('CANCELAMENTO_PATH', PFW_APPLICATION . 'cancelamento' . PFW_DS);   
require_once( CANCELAMENTO_PATH.'cancelamento_entity.class.php' );

define('PLANODEADESAO_PATH', PFW_APPLICATION . 'planodeadesao' . PFW_DS);      
require_once PLANODEADESAO_PATH . 'planodeadesao_entity.class.php';   

define('ENUMERADOS_PATH', PFW_APPLICATION . 'enumerados' . PFW_DS);
require_once( ENUMERADOS_PATH.'tipodepagamento.enum.php' );   
require_once( ENUMERADOS_PATH.'formadepagamento.enum.php' );   
require_once( ENUMERADOS_PATH.'statusdocontrato.enum.php' );   
require_once( ENUMERADOS_PATH.'statusdopagamento.enum.php' );   

define('POLITICADEPRECO_PATH', PFW_APPLICATION . 'politicadepreco' . PFW_DS);
require_once( POLITICADEPRECO_PATH.'politicadepreco_entity.class.php' );      

require_once( 'contratante_entity.class.php' );
require_once( 'contratante_factory.class.php' );
require_once( 'contratante_business.class.php' );
require_once( 'contratante_dao.class.php' );
require_once( 'contratante_view.class.php' );         