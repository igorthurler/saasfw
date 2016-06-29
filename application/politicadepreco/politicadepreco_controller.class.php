<?php
$config_path = '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

define('POLITICADEPRECO_PATH', PFW_APPLICATION . 'politicadepreco' . PFW_DS);
require_once( POLITICADEPRECO_PATH . 'politicadepreco_require.php' );

class PoliticaDePrecoController {

    private $driver;
    private $dao;
    private $validador;
    private $planos;

    public function __construct() {
    
        $this->driver = Utilitarios::retornarDriver();
        $this->dao = PoliticadePrecoFactory::criarPoliticadePrecoDAO($this->driver);
        $this->validador = PoliticadePrecoFactory::criarPoliticadePrecoBusiness($this->dao);        
        
    }

    private function definirPlanosDeAdesao($estaInserindo) {
        $planoDeAdesaoDAO = PlanoDeAdesaoFactory::criarPlanoDeAdesaoDAO($this->driver);
        if($estaInserindo) {
            $this->planos = $planoDeAdesaoDAO->buscarPlanosDeAdesaoAtivos();
        } else {
            $this->planos = $planoDeAdesaoDAO->buscarTodos();
        }
    }
    
    public function listar() {
        $pagina = PfwRequest::isValid('pag') ? PfwRequest::get('pag') : 1;
        $inicio = ($pagina * PFW_PAGINATION_LIMIT) - PFW_PAGINATION_LIMIT;
        $politicas = $this->dao->listar($inicio, PFW_PAGINATION_LIMIT);
        PoliticaDePrecoView::montarLista($politicas);

        $total = $this->dao->totalDeRegistros();

        PoliticaDePrecoView::exibirPaginacao($total, PFW_PAGINATION_LIMIT, $pagina);
    }	
	
    public function inserir() {
        try {            
            $this->definirPlanosDeAdesao(true);
            if (count($this->planos) == 0) {
                throw new Exception('Não é possível cadastrar uma política de preço sem a existênica de plano de adesão ativo.');
            }
            $politicaDePreco = PoliticaDePrecoFactory::criarPoliticaDePreco();
            PoliticaDePrecoView::montarFormulario($politicaDePreco, $this->planos);
        } catch (Exception $e) {
            PfwMessageUtils::showMessageERROR($e->getMessage());
            $this->listar();
        }
    }	

    public function visualizar() {
        $politicaDePreco = PoliticaDePrecoFactory::criarPoliticaDePreco();
        $politicaDePreco->setId(PfwRequest::get('id_politicapreco'));
        $this->dao->load($politicaDePreco);
        $this->definirPlanosDeAdesao(false);
        PoliticaDePrecoView::montarFormulario($politicaDePreco, $this->planos, true);        
    }

    public function editar() {
        try {
            $politicaDePreco = PoliticaDePrecoFactory::criarPoliticaDePreco();
            $politicaDePreco->setId(PfwRequest::get('id_politicapreco'));
            $this->dao->load($politicaDePreco);
            $this->validador->validarParaAlteracao($politicaDePreco);
            $this->definirPlanosDeAdesao(false);
            PoliticaDePrecoView::montarFormulario($politicaDePreco, $this->planos);
        } catch (Exception $e) {
            PfwMessageUtils::showMessageERROR($e->getMessage());
            $this->listar();
        }
    }

    public function deletar() {
        try {
            $this->dao->beginTransaction();
            $politicaDePreco = PoliticaDePrecoFactory::criarPoliticaDePreco();
            $politicaDePreco->setId(PfwRequest::get('id_politicapreco'));
            $this->dao->load($politicaDePreco);
            $this->validador->validarAoExcluir($politicaDePreco);
            $this->dao->delete($politicaDePreco);
            $this->dao->commitTransaction();
            PfwMessageUtils::showMessageOK("Política de preço deletada com sucesso.");
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
        }
        $this->listar();
    }
	
    public function salvar() {

        $politicaDePreco = PoliticaDePrecoFactory::criarPoliticaDePreco();

        $estaInserindo = Utilitarios::estaInserindo(PfwRequest::get('id_politicapreco'));
        
        try {
            if (! $estaInserindo) {
                $politicaDePreco->setId(PfwRequest::get('id_politicapreco'));
                $this->dao->load($politicaDePreco);
            }
            PoliticaDePrecoFactory::atribuirValores($politicaDePreco, $_POST);

            $this->dao->beginTransaction();
            $msg = $estaInserindo ?
                    "Política de preço cadastrada com sucesso" :
                    "Política de preço alterada com sucesso";
            $this->validador->Validar($politicaDePreco);
            $this->dao->save($politicaDePreco);
            $this->dao->commitTransaction();
            PfwMessageUtils::showMessageOK($msg);
            $this->listar();
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());                                  
            $this->definirPlanosDeAdesao($estaInserindo);
            PoliticaDePrecoView::montarFormulario($politicaDePreco, $this->planos);
        }
        
    }

}