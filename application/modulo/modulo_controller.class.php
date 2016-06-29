<?php
/* obtendo as configuraçes do banco de dados */
$config_path = '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

define('MODULO_PATH', PFW_APPLICATION . 'modulo' . PFW_DS);

/* inicializando objetos base */
require_once( MODULO_PATH . 'modulo_require.php' );

class ModuloController {
    
    private $driver;
    private $dao;
    private $validador;
    
    public function __construct() {
        $this->driver = Utilitarios::retornarDriver();
        $this->dao = ModuloFactory::criarModuloDAO($this->driver);
        $this->validador = ModuloFactory::criarModuloBusiness($this->dao);
    }   
    
    public function salvar() {        
        $modulo = ModuloFactory::criarModulo();                        
        try {                        
            $idModulo = PfwRequest::get('id_modulo');
			
			$this->dao->beginTransaction();       
			
            if (! Utilitarios::estaInserindo($idModulo)) {        
                $modulo->setId($idModulo);
                $this->dao->load($modulo);        
            }
            ModuloFactory::atribuirValores($modulo, $_POST);                                       

            $msg = Utilitarios::estaInserindo($idModulo) ?
                "Módulo cadastrado com sucesso." :
                "Módulo alterado com sucesso.";                
            $this->validador->Validar($modulo);                
            
            $this->dao->save($modulo);        
            $this->dao->commitTransaction();        
            Utilitarios::exibirMensagemOK($msg);            
            $this->listar();                                 
        } catch (Exception $e) {          
            $this->dao->rollbackTransaction();
            Utilitarios::exibirMensagemErro($e->getMessage());  
            ModuloView::montarFormulario($modulo, false);
        }
    }
    
    public function desativar() {
        $modulo = ModuloFactory::criarModulo();                        
        try {                        
            $this->dao->beginTransaction();                     
            $modulo->setId(PfwRequest::get('id_registrocancelado'));
            $this->dao->load($modulo);

            $this->validador->validarAoDesativar($modulo);        

            $usuario = UsuarioFactory::criarusuario();
            $usuario->setId(PfwRequest::get('id_responsavel'));
            $usuarioDAO = usuarioFactory::criarusuarioDAO($this->driver);
            $usuarioDAO->load($usuario);

            $data = date(Utilitarios::FORMAT_DMYY);
            $motivo = PfwRequest::get('motivo');

            $cancelamento = new Cancelamento();
            $cancelamento->setData($data);
            $cancelamento->setMotivo($motivo);
            $cancelamento->setResponsavel($usuario);

            $modulo->setCancelamento($cancelamento);                
            
            $this->dao->save($modulo);        
            $this->dao->commitTransaction();        
            Utilitarios::exibirMensagemOK("Módulo desativado com sucesso.");            
            $this->listar();                                 
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            Utilitarios::exibirMensagemErro($e->getMessage());  
            ModuloView::montarFormulario($modulo, false);
        }
    }
    
    public function deletar() {
        try {
            $this->dao->beginTransaction();
            $modulo = ModuloFactory::criarModulo();
            $modulo->setId(PfwRequest::get('id_modulo'));
            $this->dao->load($modulo);
            $this->validador->ValidarAoDeletar($modulo);
            $this->dao->delete($modulo);
            $this->dao->commitTransaction();	
            Utilitarios::exibirMensagemOK("Módulo deletado com sucesso.");			                        
        }
        catch(Exception $e) {
            $this->dao->rollbackTransaction();				
            Utilitarios::exibirMensagemErro($e->getMessage());            
        }        
        $this->listar();
    }
    
    public function visualizar() {
        $modulo = ModuloFactory::criarModulo();
        $modulo->setId(PfwRequest::get('id_modulo'));    
        $this->dao->load($modulo);
        ModuloView::montarFormulario($modulo, true);
    }

    public function editar() {
        try {
            $modulo = ModuloFactory::criarModulo();
            $modulo->setId(PfwRequest::get('id_modulo'));    
            $this->dao->load($modulo);
            $this->validador->validarAoEditar($modulo);
            ModuloView::montarFormulario($modulo, false); 
        }
        catch(Exception $e) {        
            Utilitarios::exibirMensagemErro($e->getMessage());
            $this->listar();     
        }
    }
    
    public function inserir() {
        $modulo = ModuloFactory::criarModulo();
        ModuloView::montarFormulario($modulo, false);
    }
    
    public function listar() {
        $pagina = PfwRequest::get('pag') != '' ? PfwRequest::get('pag') : 1;	
        $limite = PFW_PAGINATION_LIMIT;
        $inicio = ($pagina * $limite) - $limite;
        $modulos = $this->dao->buscarModulos($inicio, $limite);        
        ModuloView::montarLista($modulos);
        
        $total = $this->dao->totalDeRegistros();
        
        ModuloView::exibirPaginacao($total, $limite, $pagina);
    }
}