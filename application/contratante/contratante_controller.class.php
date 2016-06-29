<?php

/* obtendo as configura�es do banco de dados */
$config_path = '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

define('CONTRATANTE_PATH', PFW_APPLICATION . 'contratante' . PFW_DS);
require_once( CONTRATANTE_PATH . 'contratante_require.php' );

class ContratanteController {

    private $driver;
    private $dao;
    private $validador;
    private $enderecoDAO;
    private $estados;
    private $application;

    public function __construct() {
	
        $this->driver = Utilitarios::retornarDriver();
        $this->dao = ContratanteFactory::criarContratanteDAO($this->driver);
        $this->validador = ContratanteFactory::criarContratanteBusiness($this->dao);
        $this->enderecoDAO = EnderecoFactory::criarEnderecoDAO($this->driver);
        $this->estados = $this->enderecoDAO->buscarEstados();
        $this->application = $GLOBALS['application'];
		
    }

    public function inserir() {
	
        try {
        
     		$contratante = ContratanteFactory::criarContratante();
            ContratanteView::montarFormulario($contratante, $this->estados);
			
        } catch (Exception $e) {
		
            PfwMessageUtils::showMessageERROR($e->getMessage());
            $this->listar();
			
        }
		
    }

    public function visualizar() {
	
        try {
		
            $contratante = ContratanteFactory::criarContratante();
            $contratante->setId(PfwRequest::get('id_contratante'));
            $this->dao->load($contratante);
            
			ContratanteView::exibirPerfil($contratante, false);
			
        } catch (Exception $e) {
		
            PfwMessageUtils::showMessageERROR($e->getMessage());
            $this->listar();
			
        }
    }

    public function editar() {
	
        try {
		
            $contratante = ContratanteFactory::criarContratante();
            $contratante->setId(PfwRequest::get('id_contratante'));
            $this->dao->load($contratante);
            $cidades = null;
			
            if ($contratante->getPessoa() != null) {
                $estado = $contratante->getPessoa()->getEstado();
                $cidades = $this->enderecoDAO->buscarCidades($estado);
            }
			
            ContratanteView::montarFormulario($contratante, $this->estados, $cidades);
			
        } catch (Exception $e) {
		
            PfwMessageUtils::showMessageERROR($e->getMessage());
            $this->listar();
			
        }
		
    }

    public function deletar() {
	
        try {
		
            $this->dao->beginTransaction();
            $contratante = ContratanteFactory::criarContratante();
            $contratante->setId(PfwRequest::get('id_contratante'));			
            $this->dao->load($contratante);
            $this->validador->validarAoExcluir($contratante);
            $this->dao->delete($contratante);
            $this->dao->commitTransaction();
            
			PfwMessageUtils::showMessageOK("Contratante deletado com sucesso.");
			
        } catch (Exception $e) {
		
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
			
        }
		
        $this->listar();
		
    }

    public function salvar() {
	
        $contratante = ContratanteFactory::criarContratante();
		
        try {
		
            if (!Utilitarios::estaInserindo(PfwRequest::get('id_contratante'))) {
                $contratante->setId(PfwRequest::get('id_contratante'));
                $this->dao->load($contratante);
            }
			
            ContratanteFactory::atribuirValores($contratante, $_POST);

            $this->dao->beginTransaction();
            $this->validador->Validar($contratante);
            
			$msg = Utilitarios::estaInserindo(PfwRequest::get('id_contratante')) ?
                    "Contratante cadastrado com sucesso" :
                    "Contratante alterado com sucesso";
            
			$this->dao->save($contratante);
            $this->dao->commitTransaction();
            
			PfwMessageUtils::showMessageOK($msg);
            
			$this->listar();
			
        } catch (Exception $e) {
		
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
            $estado = $contratante->getPessoa()->getEstado();
            $cidades = $this->enderecoDAO->buscarCidades($estado);
            ContratanteView::montarFormulario($contratante, $this->estados, $cidades);
			
        }
    }

    public function exibirContrato() {
	
        $sessao = new PfwSession();
        $idContratante = $sessao->getSessionValue($this->application['PFW_SESSION_CONT']);
        
		$contratante = ContratanteFactory::criarContratante();
        $contratante->setId($idContratante);
        
		$this->dao->load($contratante);
        
		$contratoDAO = ContratoFactory::criarContratoDAO($this->driver);
        $contratoAtivo = $contratoDAO->retornarContratoAtivo($contratante);
        
		ContratoView::exibirDetalhes($contratoAtivo, true);
		
    }

    public function listar() {
	
        $pagina = PfwRequest::isValid('pag') ? PfwRequest::get('pag') : 1;
        $inicio = ($pagina * PFW_PAGINATION_LIMIT) - PFW_PAGINATION_LIMIT;
        $contratantes = $this->dao->buscarContratantes($inicio, PFW_PAGINATION_LIMIT);
        ContratanteView::montarLista($contratantes);

        $total = $this->dao->totalDeRegistros();

        ContratanteView::exibirPaginacao($total, PFW_PAGINATION_LIMIT, $pagina);
		
    }

}