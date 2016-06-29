<?php
$config_path = '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

include 'configadmin_require.php';

class ConfigAdminController {
    
    private $driver;
    private $dao;
    private $validador;    
    
    public function __construct() {
	
        $this->driver = Utilitarios::retornarDriver();
        $this->dao = ConfigAdminFactory::criarConfigAdminDAO($this->driver);
        $this->validador = ConfigAdminFactory::criarConfigAdminBusiness();
		
    }       
    
    public function visualizar() {
	
        $total = $this->dao->totalDeRegistros();    
    
        if ($total == 0) {
            $this->inserir();
        } else {
            $this->editar();
        }        
		
    }
    
    private function editar() {
	
        $configAdmin = $this->dao->buscarConfiguracaoAdministrativa();   
        ConfigAdminView::montarFormulario($configAdmin);              
		
    }
    
    private function inserir() {
	
	    $configAdmin = ConfigAdminFactory::criarConfigAdmin();        
        ConfigAdminView::montarFormulario($configAdmin);
		
    }
    
    public function salvar() {

     	$configAdmin = ConfigAdminFactory::criarConfigAdmin();                
     
        try {

			$id = PfwRequest::get('id_configadmin');
			
            if (! Utilitarios::estaInserindo($id)) {        
                $configAdmin->setId($id);
                $this->dao->load($configAdmin);        
            }

            ConfigAdminFactory::atribuirValores($configAdmin, $_POST);

            $msg = Utilitarios::estaInserindo($id) ?
                "Configura��es administrativas cadastradas com sucesso." :
                "Configura��es administrativas alteradas com sucesso.";        
            
			$this->dao->beginTransaction();        
            $this->validador->validar($configAdmin);							
            $this->dao->save($configAdmin);
            $this->dao->commitTransaction();        
            
			PfwMessageUtils::showMessageOK($msg);
            $this->editar();
			
        } catch (Exception $e) {
		
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
            ConfigAdminView::montarFormulario($configAdmin);
			
        }       
		
    }

	// Se a pol�tica de pre�o for gratuita, buscar a forma e o tipo de pagamento que est�o definidas na configura��o.
	public function buscarFormaPgtoGratis() {
			
		$idPolitica = PfwRequest::get('politica');
				
		$politicaDAO = PoliticaDePrecoFactory::criarPoliticaDePrecoDAO($this->driver);
		$politica = PoliticaDePrecoFactory::criarPoliticaDePreco();
		$politica->setId($idPolitica);
		$politicaDAO->load($politica);
		
		$formaPgto = 0;
		$tipoPgto = 0;
		
		$arr = array();
		
		if ($politica->isGratis()) {
		  $configAdmin = $this->dao->buscarConfiguracaoAdministrativa();   
		  $formaPgtoGratis = $configAdmin->getFormaPgtoGratis();		
		  if (isset($formaPgtoGratis)) {
			$formaPgto = $formaPgtoGratis->ordinal();
		  }
		  $tipoPgtoGratis = $configAdmin->getTipoPgtoGratis();		
		  if (isset($tipoPgtoGratis)) {
			$tipoPgto = $tipoPgtoGratis->ordinal();
		  }		  
		}
		
		$arr[] = array('formadepagamento' => $formaPgto,
					   'tipodepagamento' => $tipoPgto);
		
		echo json_encode($arr);
			
	}
	
}