<?php
/* obtendo as configuraçes do banco de dados */
$config_path = '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

include_once PFW_APPLICATION . 'application.cfg.php';

define('UTILS_PATH', PFW_APPLICATION . 'utils' . PFW_DS);
include_once UTILS_PATH . 'utils_require.php';

require_once( 'usuario_require.php' );

class UsuarioController {

    private $driver;
    private $dao;
    private $validador;
    private $application;

    public function __construct() {
    
        $this->driver = Utilitarios::retornarDriver();
        $this->dao = UsuarioFactory::criarUsuarioDAO($this->driver);
        $this->validador = UsuarioFactory::criarUsuarioBusiness($this->dao);
        $this->application = $GLOBALS['application'];
        
    }
    
    public function listar() {
    
        $pagina = PfwRequest::isValid('pag') ? PfwRequest::get('pag') : 1;
        $inicio = ($pagina * PFW_PAGINATION_LIMIT) - PFW_PAGINATION_LIMIT;
        $usuarios = $this->dao->buscarUsuarios($inicio, PFW_PAGINATION_LIMIT);
        UsuarioView::montarLista($usuarios);

        $total = $this->dao->totalDeRegistros();

        UsuarioView::exibirPaginacao($total, PFW_PAGINATION_LIMIT, $pagina);
        
    }
    
    public function inserir() {
    
        try {
            $usuario = UsuarioFactory::criarUsuario();
            UsuarioView::montarFormulario($usuario);
        } catch (Exception $e) {
            PfwMessageUtils::showMessageERROR($e->getMessage());
            $this->listar();
        }
        
    }
    
    public function salvar() {
    
        $usuario = UsuarioFactory::criarUsuario();
        try {
            if (!Utilitarios::estaInserindo(PfwRequest::get('id_usuario'))) {
                $usuario->setId(PfwRequest::get('id_usuario'));
                $this->dao->load($usuario);
            }

            UsuarioFactory::atribuirValores($usuario, $_POST);

            $msg = Utilitarios::estaInserindo(PfwRequest::get('id_usuario')) ?
                    "Usuario cadastrado com sucesso." :
                    "Usuario alterado com sucesso.";            
            
            $this->dao->beginTransaction();
            $this->validador->validar($usuario);
            $this->dao->save($usuario);
            $this->dao->commitTransaction();
            PfwMessageUtils::showMessageOK($msg);
            $this->listar();
            
            $envioDeEmailHabilitado = !$this->application['PFW_SEND_MAIL'];
            if ($envioDeEmailHabilitado && Utilitarios::estaInserindo(PfwRequest::get('id_usuario'))) {
                $email = new UsuarioEmail();
                $email->setEmailEnvio($this->application['PFW_DEFAULT_MAIL']);
                $email->notificarCriacao($usuario);                
            }            
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
            UsuarioView::montarFormulario($usuario);
        }
        
    }    
   
    public function visualizar() {
    
        try {
            $usuario = UsuarioFactory::criarUsuario();
            $usuario->setId(PfwRequest::get('id_usuario'));
            $this->dao->load($usuario);
            UsuarioView::exibirPerfil($usuario, false);
        } catch (Exception $e) {
            PfwMessageUtils::showMessageERROR($e->getMessage());
            $this->listar();
        }
        
    }

    public function editar() {
    
        try {
            $usuario = UsuarioFactory::criarUsuario();
            $usuario->setId(PfwRequest::get('id_usuario'));
            $this->dao->load($usuario);
            UsuarioView::montarFormulario($usuario);
        } catch (Exception $e) {
            PfwMessageUtils::showMessageERROR($e->getMessage());
            $this->listar();
        }
        
    }

    public function desativar() {
    
        $usuario = UsuarioFactory::criarUsuario();
        try {
            $this->dao->beginTransaction();            
            
            $usuario->setId(PfwRequest::get('id_usuario'));
            $this->dao->load($usuario);            
            
            // Não permitir ao Usuario logado desativar seus próprios dados
            $sessao = new PfwSession();
            $idUsuarioLogado = $sessao->getSessionValue($this->application['PFW_SESSION_USER']);
            unset($sessao);            
            
            if ($idUsuarioLogado == $usuario->getId()) {
                throw new Exception('Não é possível desativar os dados do usuario logado.');
            }            
            
            $this->validador->validarAoDesativar($usuario);
           
            $usuario->setDataDeDesativacao(date(Utilitarios::FORMAT_DMYY));
            
            $this->dao->save($usuario);
            $this->dao->commitTransaction();
            PfwMessageUtils::showMessageOK("Usuário desativado com sucesso.");                        
            $this->listar();            
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
            $this->listar();
        }  
        
    }
    
    public function excluir() {
    
        try {
            $sessao = new PfwSession();
            $idUsuarioLogado = $sessao->getSessionValue($this->application['PFW_SESSION_USER']);
            unset($sessao);		
		
            $this->dao->beginTransaction();

            $usuario = UsuarioFactory::criarUsuario();
            $usuario->setId(PfwRequest::get('id_usuario'));
            $this->dao->load($usuario);

            // Não permitir ao Usuario logado excluir seus próprios dados           
            if ($idUsuarioLogado == $usuario->getId()) {
                throw new Exception('Não é possível excluir os dados do usuario logado.');
            }            

            $this->dao->delete($usuario);
            
            $this->dao->commitTransaction();
            PfwMessageUtils::showMessageOK("Usuario deletado com sucesso.");
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
        }
        $this->listar();
        
    }
    
    public function exibirPerfil() {
            
        try {     
            $idUsuarioLogado = PfwRequest::get('id_usuario');
            
            $usuario = UsuarioFactory::criarUsuario();
            $usuario->setId($idUsuarioLogado);
            
            $this->dao->beginTransaction();            
            $this->dao->load($usuario);            
            $this->dao->commitTransaction();

            UsuarioView::exibirPerfil($usuario, true);
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
        }
        
    }    
   
    public function alterarPerfil() {
    
        try {
            $usuario = UsuarioFactory::criarUsuario();
            $usuario->setId(PfwRequest::get('id_usuario'));
            $this->dao->load($usuario);
            UsuarioView::montarFormularioEdicaoPerfil($usuario);           
        } catch (Exception $e) {
            PfwMessageUtils::showMessageERROR($e->getMessage());
            $this->listar();
        }
        
    }
    
    public function salvarPerfil() {
    
        $usuario = UsuarioFactory::criarUsuario();
        try {
            $usuario->setId(PfwRequest::get('id_usuario'));
            $this->dao->load($usuario);

            UsuarioFactory::atribuirValores($usuario, $_POST);

            $this->dao->beginTransaction();
            
            $this->validador->validar($usuario);
            $this->dao->save($usuario);
            
            $this->dao->commitTransaction();
            
            PfwMessageUtils::showMessageOK('Dados alterados com sucesso.');
            
            UsuarioView::exibirPerfil($usuario, true);
            
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
            UsuarioView::montarFormularioEdicaoPerfil($usuario);
        }
        
    }
    
    public function exibirFormUploadFoto() {
    
        $idUsuario = PfwRequest::get('id_usuario');
        UsuarioView::montarFormUpload("application/usuario/upload_imagem_perfil.php?id_usuario={$idUsuario}",
            "includes/PfwController.php?app=usuario&action=exibirPerfil&id_usuario={$idUsuario}");
            
    }  
        
}