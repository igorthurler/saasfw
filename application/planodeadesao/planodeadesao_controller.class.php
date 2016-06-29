<?php
/* obtendo as configuraçes do banco de dados */
$config_path = '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

define('PLANODEADESAO_PATH', PFW_APPLICATION . 'planodeadesao' . PFW_DS);
require_once( PLANODEADESAO_PATH . 'planodeadesao_require.php' );

class PlanoDeAdesaoController {

    private $driver;
    private $dao;
    private $validador;
    private $moduloDAO;

    public function __construct() {
        $this->driver = Utilitarios::retornarDriver();
        $this->dao = PlanoDeAdesaoFactory::criarPlanoDeAdesaoDAO($this->driver);
        $this->validador = PlanoDeAdesaoFactory::criarPlanoDeAdesaoBusiness($this->dao);
        $this->moduloDAO = ModuloFactory::criarModuloDAO($this->driver);
    }

    public function salvar() {
        $planoDeAdesao = PlanoDeAdesaoFactory::criarPlanoDeAdesao();

        $estaInserindo = Utilitarios::estaInserindo(PfwRequest::get('id_planoadesao'));

        try {
            $this->dao->beginTransaction();

            if (!$estaInserindo) {
                $planoDeAdesao->setId(PfwRequest::get('id_planoadesao'));
                $this->dao->load($planoDeAdesao);
            }
            PlanoDeAdesaoFactory::atribuirValores($planoDeAdesao, $_POST);

            $msg = Utilitarios::estaInserindo(PfwRequest::get('id_planoadesao')) ?
                    "Plano de adesão cadastrado com sucesso" :
                    "Plano de adesão alterado com sucesso";
            $this->validador->Validar($planoDeAdesao);

            $this->dao->save($planoDeAdesao);
            $this->dao->commitTransaction();
            PfwMessageUtils::showMessageOK($msg);
            $this->listar();
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
			$modulos = $this->moduloDAO->buscarAtivos();
            PlanoDeAdesaoView::montarFormulario($planoDeAdesao, $modulos);
        }
    }
	
    public function desativar() {
        $planoDeAdesao = PlanoDeAdesaoFactory::criarPlanoDeAdesao();
        try {
            $this->dao->beginTransaction();

            $planoDeAdesao->setId(PfwRequest::get('id_registrocancelado'));
            $this->dao->load($planoDeAdesao);

            $this->validador->validarAoDesativar($planoDeAdesao);

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

            $planoDeAdesao->setCancelamento($cancelamento);

            $this->dao->save($planoDeAdesao);
            $this->dao->commitTransaction();
            PfwMessageUtils::showMessageOK("Plano de Adesão desativado com sucesso.");

            $this->listar();
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
            $this->listar();
        }
    }

    public function deletar() {
        try {
            $this->dao->beginTransaction();
            $planoDeAdesao = PlanoDeAdesaoFactory::criarPlanoDeAdesao();
            $planoDeAdesao->setId(PfwRequest::get('id_planoadesao'));
            $this->dao->load($planoDeAdesao);
            $this->validador->validarAoDeletar($planoDeAdesao);
            $this->dao->delete($planoDeAdesao);
            $this->dao->commitTransaction();
            PfwMessageUtils::showMessageOK("Plano de adesão deletado com sucesso.");
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
        }
        $this->listar();
    }

    public function visualizar() {
        $planoDeAdesao = PlanoDeAdesaoFactory::criarPlanoDeAdesao();
        $planoDeAdesao->setId($_GET['id_planoadesao']);
        $this->dao->load($planoDeAdesao);
        $modulos = $this->moduloDAO->buscarTodos();    
        PlanoDeAdesaoView::MontarFormulario($planoDeAdesao, $modulos, true);        
    }

    public function editar() {
        try {
            $planoDeAdesao = PlanoDeAdesaoFactory::criarPlanoDeAdesao();
            $planoDeAdesao->setId($_GET['id_planoadesao']);
            $this->dao->load($planoDeAdesao);
            $this->validador->validarPlanoDeAdesaoAtivo($planoDeAdesao);
            $modulos = $this->moduloDAO->buscarTodos();
            PlanoDeAdesaoView::MontarFormulario($planoDeAdesao, $modulos);
        } catch(Exception $e) {
            Utilitarios::exibirMensagemERRO($e->getMessage());
            $this->listar();
        }    
    }

    public function inserir() {
        try {
            $planoDeAdesao = PlanoDeAdesaoFactory::criarPlanoDeAdesao();
            $modulos = $this->moduloDAO->buscarAtivos();
            $this->validador->validarModulosAtivosNaInclusao($modulos);
            PlanoDeAdesaoView::montarFormulario($planoDeAdesao, $modulos);
        } catch(Exception $e) {
            Utilitarios::exibirMensagemERRO($e->getMessage());
            $this->listar();
        }      
    }

    public function listar() {
        $pagina = PfwRequest::isValid('pag') ? PfwRequest::get('pag') : 1;
        $inicio = ($pagina * PFW_PAGINATION_LIMIT) - PFW_PAGINATION_LIMIT;
        $planos = $this->dao->buscarPlanosDeAdesao($inicio, PFW_PAGINATION_LIMIT);
        PlanoDeAdesaoView::montarLista($planos);

        $total = $this->dao->totalDeRegistros();

        PlanoDeAdesaoView::exibirPaginacao($total, PFW_PAGINATION_LIMIT, $pagina);
    }

}