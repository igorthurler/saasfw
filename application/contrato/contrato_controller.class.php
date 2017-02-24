<?php

$config_path = '..' . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR;
require_once($config_path . 'basic_require.php');

require_once( 'contrato_require.php' );

class ContratoController {

    private $driver;
    private $dao;
    private $validador;
    private $configAdmin;
    private $contratoEmail;
    private $application;

    public function __construct() {

        $this->driver = Utilitarios::retornarDriver();
        $this->dao = ContratoFactory::criarContratoDAO($this->driver);
        $this->validador = ContratoFactory::criarContratoBusiness($this->dao);
        $configAdminDAO = ConfigAdminFactory::criarConfigAdminDAO($this->driver);
        $this->configAdmin = $configAdminDAO->buscarConfiguracaoAdministrativa();
        $this->contratoEmail = ContratoFactory::criarContratoEmail();
        $this->application = $GLOBALS['application'];
		
    }

    public function salvar() {

        $contrato = ContratoFactory::criarContrato();

        try {

            $this->dao->beginTransaction();

            if (!Utilitarios::estaInserindo(PfwRequest::get('id_contrato'))) {
                $contrato->setId(PfwRequest::get('id_contrato'));
                $this->dao->load($contrato);
            }

            ContratoFactory::atribuirValores($contrato, $_POST);

            $this->validador->validar($contrato);
                   
            $this->dao->save($contrato);

            if (Utilitarios::estaInserindo(PfwRequest::get('id_contrato'))) {
                $this->dao->load($contrato);
            }            
                       
            $this->dao->commitTransaction();

            PfwMessageUtils::showMessageOK("Contrato cadastrado com sucesso");
            
            $envioDeEmailHabilitado = !$this->application['PFW_SEND_MAIL'];

            if ($envioDeEmailHabilitado && Utilitarios::estaInserindo(PfwRequest::get('id_contrato'))) {
                $this->contratoEmail->setEmailEnvio($this->application['PFW_DEFAULT_MAIL']);
                $this->contratoEmail->notificarCriacaoDoContrato($contrato);                
            }
                        
            $this->listar();
            
        } catch (Exception $e) {
        
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
            ContratoView::montarFormulario($contrato);
            
        }
		
    }

    public function desativar() {

        $contrato = ContratoFactory::criarContrato();

        try {
            $this->dao->beginTransaction();

            $contrato->setId(PfwRequest::get('id_registrocancelado'));
            $this->dao->load($contrato);

            $this->validador->validarAoCancelar($contrato);

            $admin = UsuarioFactory::criarUsuario();
            $admin->setId(PfwRequest::get('id_responsavel'));
            $adminDAO = UsuarioFactory::criarUsuarioDAO($this->driver);
            $adminDAO->load($admin);

            $data = date(Utilitarios::FORMAT_DMYY);
            $motivo = PfwRequest::get('motivo');

            $cancelamento = new Cancelamento();
            $cancelamento->setData($data);
            $cancelamento->setMotivo($motivo);
            $cancelamento->setResponsavel($admin);

            /*$contrato->setCancelamento($cancelamento);

            if (!$contrato->gratuito()) {
                $contrato->cancelarPagamentosNaoQuitados($cancelamento);
            }*/
			
			$contrato->cancelar($cancelamento);

            $this->dao->save($contrato);

            $this->dao->commitTransaction();

            PfwMessageUtils::showMessageOK("Contrato cancelado com sucesso");

            $envioDeEmailHabilitado = !$this->application['PFW_SEND_MAIL'];

            if ($envioDeEmailHabilitado) {
                $this->contratoEmail->setEmailEnvio($this->application['PFW_DEFAULT_MAIL']);
                $this->contratoEmail->notificarDesativacaoDoContrato($contrato);
            }
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
        }

        $this->listar();
    }

    public function inserir() {

        try {
            $contrato = ContratoFactory::criarContrato();
            $this->validador->validarParaInsercao();
            ContratoView::montarFormulario($contrato);
        } catch (Exception $e) {
            PfwMessageUtils::showMessageERROR($e->getMessage());
            $this->listar();
        }
    }

    public function finalizar() {

        try {
            $contrato = ContratoFactory::criarContrato();

            $contrato->setId(PfwRequest::get('id'));

            $this->dao->beginTransaction();

            $this->dao->load($contrato);

            $this->validador->validarAoFinalizar($contrato);

            $contrato->setDataDeFinalizacao(date(Utilitarios::FORMAT_DMYY));

            $this->dao->save($contrato);

            $this->dao->commitTransaction();

            PfwMessageUtils::showMessageOK("Contrato finalizado com sucesso.");

            $envioDeEmailHabilitado = !$this->application['PFW_SEND_MAIL'];

            if ($envioDeEmailHabilitado) {
                $this->contratoEmail->setEmailEnvio($this->application['PFW_DEFAULT_MAIL']);
                $this->contratoEmail->notificarFinalizacaoDoContrato($contrato);
            }
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
        }

        $this->listar();
    }

    public function visualizar() {

        $contrato = ContratoFactory::criarContrato();
        $contrato->setId(PfwRequest::get('id_contrato'));
        $this->dao->load($contrato);
        ContratoView::exibirDetalhes($contrato);
    }

    public function finalizarTodos() {

        try {
            $dataDoDia = date(PfwDateUtils::FORMAT_DMYY);

            $contratos = $this->dao->buscarContratosParaFinalizar($dataDoDia);

            if (count($contratos) == 0) {

                PfwMessageUtils::showMessageWARNING('Não existem contratos para finalizar.');
            } else {

                $this->dao->beginTransaction();

                foreach ($contratos as $contrato) {
                    $contrato->setDataDeFinalizacao($dataDoDia);
                    $this->dao->save($contrato);

                    $envioDeEmailHabilitado = !$this->application['PFW_SEND_MAIL'];

                    if ($envioDeEmailHabilitado) {
                        $this->contratoEmail->setEmailEnvio($this->application['PFW_DEFAULT_MAIL']);
                        $this->contratoEmail->notificarFinalizacaoDoContrato($contrato);
                    }
                }

                $this->dao->commitTransaction();

                PfwMessageUtils::showMessageOK("Contratos finalizados com sucesso.");
            }
        } catch (Exception $e) {
            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
        }

        $this->listar();
		
    }

    public function emitirCobrancaDePagamentos() {

        try {

            $envioDeEmailHabilitado = !$this->application['PFW_SEND_MAIL'];
            $enviaEmailCobranca = $this->configAdmin->isEnviaEmailDeCobrancaParaPagamentosEmAtraso();
            $executaProcesso = $envioDeEmailHabilitado && $enviaEmailCobranca;

            if ($executaProcesso) {
                $diasParaCobranca = $this->configAdmin->getDiasParaEnvioDaCobrancaDePagamentosPendentes();

                $dataInicial = date(Utilitarios::FORMAT_DMYY);
                $dataFinal = Utilitarios::adicionarDias($dataInicial, $diasParaCobranca);

                $this->contratoEmail->setEmailEnvio($this->application['PFW_DEFAULT_MAIL']);

                $pagamentoDAO = PagamentoFactory::criarPagamentoDAO($this->driver);

                // TODO: Retornar os pagamentos que serão cobrados em um único select
                $pagamentosPendentes = $pagamentoDAO->buscarPagamentosPendentesNoPeriodo($dataInicial, $dataFinal);
                foreach ($pagamentosPendentes as $pagamentoPendente) {
                    $this->contratoEmail->notificarCobrancaDePagamento($pagamentoPendente);
                }
                $pagamentosEmAtraso = $pagamentoDAO->buscarPagamentosEmAtraso($dataInicial);
                foreach ($pagamentosEmAtraso as $pagamentoEmAtraso) {
                    $this->contratoEmail->notificarCobrancaDePagamento($pagamentoEmAtraso);
                }
                /*----------------------------------------------------------------------*/

                PfwMessageUtils::showMessageOK("Cobrança de pagamentos enviadas com sucesso.");
            } else {
                PfwMessageUtils::showMessageWARNING('Verifique se o envio de email está habilitado e o envio de email para cobrança.');
            }
			
        } catch (Exception $e) {

            $this->dao->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
			
        }

        $this->listar();
		
    }

    public function exibirPagamentosEmAtraso() {

        $data = date(PfwDateUtils::FORMAT_DMYY);
        $pagamentoDAO = PagamentoFactory::criarPagamentoDAO($this->driver);
        $pagamentos = $pagamentoDAO->buscarPagamentosEmAtraso($data);
        if (count($pagamentos) == 0) {
            PfwMessageUtils::showMessageWARNING("Nenhum pagamento em atraso.");
        } else {
            $tblPagamentos = ContratoView::retornarTabelaDePagamentos($pagamentos, true, true);
            $tblPagamentos->show();
        }
		
    }

    public function confirmarPagamento() {

        $pagamentoDAO = PagamentoFactory::criarPagamentoDAO($this->driver);

        try {

            $pagamento = PagamentoFactory::criarPagamento();
            $pagamento->setId(PfwRequest::get('id_pagamento'));

            $pagamentoDAO->beginTransaction();

            $pagamentoDAO->load($pagamento);

            $pagamentoBusiness = PagamentoFactory::criarPagamentoBusiness();

            $pagamentoBusiness->validar($pagamento);

            $pagamento->setDataDePagamento(date(Utilitarios::FORMAT_DMYY));
            $pagamento->setDataDeConfirmacao(date(Utilitarios::FORMAT_DMYY));

            $pagamentoDAO->save($pagamento);

            $contrato = $pagamento->getContrato();

            if ($pagamento->ehPrimeiroPagamentoDoContrato()) {
                $contrato->setDataDeAtivacao(date(Utilitarios::FORMAT_DMYY));
                $this->dao->save($contrato);
            }

            $pagamentoDAO->commitTransaction();

            PfwMessageUtils::showMessageOK("Pagamento confirmado com sucesso.");

            $envioDeEmailHabilitado = !$this->application['PFW_SEND_MAIL'];

            if ($envioDeEmailHabilitado) {
                $this->contratoEmail->setEmailEnvio($this->application['PFW_DEFAULT_MAIL']);
                $this->contratoEmail->notificarConfirmacaoDoPagamento($pagamento);
            }
        } catch (Exception $e) {

            $pagamentoDAO->rollbackTransaction();
            PfwMessageUtils::showMessageERROR($e->getMessage());
        }

        $this->listar();
		
    }

    public function listar() {

        $pagina = PfwRequest::isValid('pag') ? PfwRequest::get('pag') : 1;
        $inicio = ($pagina * PFW_PAGINATION_LIMIT) - PFW_PAGINATION_LIMIT;
        $contratos = $this->dao->buscarContratos($inicio, PFW_PAGINATION_LIMIT);
        ContratoView::montarLista($contratos);
        $total = $this->dao->totalDeRegistros();
        ContratoView::exibirPaginacao($total, PFW_PAGINATION_LIMIT, $pagina);
		
    }

    public function buscarDados() {

        $documento = PfwRequest::isValid('documento') ? PfwRequest::get('documento') : '';

        $arr = array();

        if ($documento != '') {

            $pessoaDAO = PessoaFactory::criarPessoaDAO($this->driver);

            $dados = $pessoaDAO->buscarDadosPessoaContrato($documento);

            $d = $dados[0];
            if (isset($d)) {
                $arr[] = array('id_pessoa' => $d['id_pessoa'],
                    'id_contratante' => $d['id_contratante'],
                    'documento' => $d['documento'],                    
                    'nome' => utf8_encode($d['nome']),
                    'cep' => $d['cep'],
                    'logradouro' => $d['logradouro'],
                    'numero' => $d['numero'],
                    'complemento' => $d['complemento'],
                    'bairro' => $d['bairro'],
                    'estado' => $d['estado'],
                    'cidade' => $d['cidade'],
                    'email' => $d['email']);
            }
        }

        echo json_encode($arr);
		
    }

}