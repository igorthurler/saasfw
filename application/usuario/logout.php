<?php
$config_path = DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once("../..".$config_path . 'basic_require.php');
require_once(PFW_CLASS_PATH . 'PfwSession.class.php');

$module = PfwRequest::get('module');

$sessao = new PfwSession();
$sessao->close();
unset($sessao);
header("Location: ../../{$module}");