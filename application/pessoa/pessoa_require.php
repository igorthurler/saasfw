<?php
define('ENDERECO_PATH', PFW_APPLICATION . 'endereco' . PFW_DS);
require_once( ENDERECO_PATH.'endereco_dao.class.php' );
require_once( ENDERECO_PATH.'estado_entity.class.php' );
require_once( ENDERECO_PATH.'cidade_entity.class.php' );
require_once( ENDERECO_PATH.'endereco_factory.class.php' );

require_once 'permissao_entity.class.php';
require_once 'pessoa_business.class.php';
require_once 'pessoa_dao.class.php';
require_once 'pessoa_entity.class.php';
require_once 'pessoa_factory.class.php';
require_once 'pessoa_view.class.php';