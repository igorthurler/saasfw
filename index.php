<?php
include 'application/application.cfg.php';

// TODO: URL for www.site.com -> index.php da pasta application
//       URL for www.site.com/alias -> PFW_APP_USERFILE

$module = (isset($_GET['module'])) ? $_GET['module'] : '';

if ($module == '') {
	$module = 'index.php';
} 
else {
	if ($application['PFW_APP_USERFILE'] != '') {
		$module = $application['PFW_APP_USERFILE'];
	}
	else {
		echo 'Não existe página de usuário configurada.';
		exit;
	}
}

/* Carregando arquivos js/css do framework */
$arquivosJS = "<link rel=\"stylesheet\" href=\"css/pfw.css\">".
    "<link rel=\"stylesheet\" href=\"css/bootstrap.min.css\">".
    "<link rel=\"stylesheet\" href=\"css/tablesorter/style.css\">".
    "<link rel=\"stylesheet\" href=\"css/tablesorter/jquery.tablesorter.pager.css\">".
    "<script type=\"text/javascript\" src=\"js/pfwscripts/jquery-1.10.2.js\"></script>".
    "<script type=\"text/javascript\" src=\"js/head.load.min.js\"></script>".
    "<script type=\"text/javascript\" src=\"js/pfw_scripts.js\"></script>";
	
//Executa a página da aplicação.
include "application/{$module}";