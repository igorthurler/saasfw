<?php
// Carregar os dados iniciais para passar para o template. Buscar do banco de dados.
$config_path = '.' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
include_once UTILS_PATH . 'utils_require.php';   

define('USER_PATH', PFW_APPLICATION . 'usuario' . PFW_DS);
include_once USER_PATH . 'usuario_require.php';   

//Incluindo classe PfwCssMainMenu do template selecionado
require_once 'template/'.$application['PFW_TEMPLATE'].'/class/PwfCssMainForm.class.php';

include_once PFW_APPLICATION.'language/'.$application['PFW_DEFAULT_LANG'].'.php'; 

/*
Teste template
//$tpl = new Template('template/'.$application['PFW_TEMPLATE'].'/app_template.tpl');
//$tpl->show();
//exit;
*/
$sessao = new PfwSession();
// Se não existir usuário logado, exibir a tela de login
if (! $sessao->sessionValueExists($application['PFW_SESSION_USER'])) {
    $tpl = new Template('template/'.$application['PFW_TEMPLATE'].'/login_template.tpl');
    $tpl->TITULO = $application['PFW_APP_NAME'] . $lang['LOGIN_TITLE'];
    $tpl->FORM_LOGIN =  
        "<div class=\"login-panel panel panel-default\">
            <div class=\"panel-heading\">
                <h3 class=\"panel-title\">{$application['PFW_APP_NAME']}</h3>
            </div>
            <div class=\"panel-body\">
                <form class=\"form-signin\" id=\"formLogin\" method=\"post\" action=\"application/usuario/autenticacao.php\">        
                    <fieldset>        
                        <div class=\"form-group\">
                            <label for=\"identificador\">{$lang['LOGIN_IDENT']}</label>
                            <input type=\"text\" name=\"identificador\" class=\"form-control\"/>
                        </div>
                        <div class=\"form-group\">
                            <label for=\"senha\">{$lang['LOGIN_PASSW']}</label>
                            <input type=\"password\" name=\"senha\" class=\"form-control\"/>
                        </div>
                        <!--
                        <label class=\"checkbox\">
                            <input type=\"checkbox\" value=\"remember-me\"> {$lang['LOGIN_REMEMBER']} </input>
                        </label>
                        -->
                        <br/>
                        <button class=\"btn btn-info btn-block\" type=\"submit\">{$lang['LOGIN_SUBMIT']}</button>
                    </fieldset>
                </form>
            </div>
        </div>";
    $tpl->show();    	
    exit;
}

//Buscar os dados do usuário logado
$idUsuario = $sessao->getSessionValue($application['PFW_SESSION_USER']);

$usuario = UsuarioFactory::criarUsuario();
$usuario->setId($idUsuario);

$driver = Utilitarios::retornarDriver();
$driver->connect();

$usuarioDAO = UsuarioFactory::criarUsuarioDAO($driver);
$usuarioDAO->load($usuario);

$nome = $usuario->getPrimeiroNome();

$driver->disconnect();

//Carregando o template que será utilizado
$tpl = new Template('template/'.$application['PFW_TEMPLATE'].'/app_template.tpl');

$tpl->TITULO = $application['PFW_APP_NAME'];

if ($tpl->exists('FAV_ICON')) {
    $tpl->FAV_ICON = "<link rel=\"shortcut icon\" href=\"favicon.ico\" />
        <link rel=\"icon\" href=\"favicon.ico\" type=\"image/x-icon\" />";
}

// Carrega arquivos javascript da aplicação
$itens = glob('application/js/{*.js}', GLOB_BRACE);
if ($itens !== false) {
    foreach ($itens as $item) {
        $arquivosJS .= "<script type=\"text/javascript\" src=\"{$item}\"></script>";
    }
}

$tpl->METADATA = $arquivosJS;

$tpl->REDIRECIONAR = "index.php";
$tpl->LOGO_SISTEMA = "<span id=\"nomedosistema\">" . $application['PFW_APP_NAME'] . "</span>";

$tpl->USUARIO = "<span id=\"user_nome\">{$nome}</span>";

$pfwCssMainForm = new PfwCssMainForm();

if ($tpl->exists('IMG_USUARIO')) {
    $imgClass = $pfwCssMainForm->retornarCssImageClass();
    $img = Utilitarios::retornarImagemDoUsuario($usuario);
    $tpl->IMG_USUARIO = "<img id=\"user_imagem\" {$imgClass} src=\"{$img}\" class=\"img-rounded\" style=\"width:24px; height:24px;\"/>";
}			

$tpl->MENU_USUARIO = "<ul class=\"dropdown-menu dropdown-user\">
                      <li><a href=\"javascript:void(0);\" 
                     onclick=\"ajax.init( 'includes/PfwController.php?app=usuario&action=exibirPerfil&id_usuario={$idUsuario}', 
                        'viewers' );\"> <i class=\"fa fa-user\"></i> {$nome} </a></li>
                     <li class=\"divider\"></li>
                     <li><a href=\"application/usuario/logout.php\"><i class=\"fa fa-power-off\"></i> {$lang['MAIN_LOGOFF']}</a></li>
                     </ul>";

// Montando menu do sistema
$menu = new PfwMainMenu($pfwCssMainForm);

$tpl->MENU_PRINCIPAL = $menu->retornarMenu();
unset($menu);

$tpl->RODAPE = "{$application['PFW_APP_NAME']} {$application['PFW_APP_VERSION']}<br/>
		{$lang['MAIN_FOOTER']}: Igor Thurler<br/>
		desenvolvimento@igorthurler.com";
		
unset($sessao);        
        
$tpl->show();