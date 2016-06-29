<?php
// paths do sistema
define('PFW_DS', DIRECTORY_SEPARATOR);
define('PFW_DIR', 'saasfw' . PFW_DS );
define('PFW_ROOT_PATH', $_SERVER['DOCUMENT_ROOT'] . PFW_DS . PFW_DIR);
define('PFW_APPLICATION', PFW_ROOT_PATH . 'application' . PFW_DS);
define('PFW_CSS', PFW_ROOT_PATH . 'css' . PFW_DS);
define('PFW_IMG', PFW_ROOT_PATH . 'img' . PFW_DS);
define('PFW_INC', PFW_ROOT_PATH . 'includes' . PFW_DS);
define('PFW_CLASS_PATH', PFW_INC . 'class' . PFW_DS);
define('PFW_ENGINE_PATH', PFW_INC . PFW_DS . 'engine' . PFW_DS);
define('PFW_ELEMENT_PATH', PFW_INC . PFW_DS . 'uielement_framework' . PFW_DS);
define('PFW_NUSOAP_PATH', PFW_INC . PFW_DS . 'nusoap' . PFW_DS);
define('PFW_JS', PFW_ROOT_PATH . 'js' . PFW_DS);