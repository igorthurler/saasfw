<?php

abstract class ContratoFactory {

    static public function criarContrato() {
        $contrato = new Contrato();
        $contrato->setCodigo(0);
        return $contrato;
    }

    //TODO: Refatorar assim que possível, está uma zona
    static public function atribuirValores(Contrato &$contrato, $dados) {

        if (isset($dados)) {

            $id = isset($dados['id_contrato']) && ($dados['id_contrato'] != "") ? $dados['id_contrato'] : null;
            $contrato->setId($id);

            $driver = DAOFactory::getDAO()->getDriver();

            if (isset($dados['documento']) && ($dados['documento'] != "")) {
                $contratanteDAO = ContratanteFactory::criarContratanteDAO($driver);
                $contratante = $contratanteDAO->buscarPeloDocumento($dados['documento']);
                if ($contratante != null) {
                    $dados['id_contratante'] = $contratante->getId();
                } else {
                    $contratante = ContratanteFactory::criarContratante();
                }
                ContratanteFactory::atribuirValores($contratante, $dados);
                $contrato->setContratante($contratante);
            }

            if (isset($dados['politicadepreco'])) {
                $politicaDePreco = ($contrato->getPoliticaDePreco() != null) ? 
                    $contrato->getPoliticaDePreco() : 
                    PoliticaDePrecoFactory::criarPoliticaDePreco();
                $politicaDePreco->setId($dados['politicadepreco']);
                $politicaDePrecoDAO = PoliticaDePrecoFactory::criarPoliticaDePrecoDAO($driver);
                $politicaDePrecoDAO->load($politicaDePreco);
                $contrato->setPoliticaDePreco($politicaDePreco);
            }

            if (isset($dados['formadepagamento'])) {
                $formaDePagamento = new FormaDePagamento(FormaDePagamento::getFormaDePagamento($dados['formadepagamento']));
                $contrato->setFormaDePagamento($formaDePagamento);
            }

            if (isset($dados['tipodepagamento'])) {
                $tipoDePagamento = new TipoDePagamento(TipoDePagamento::getTipoDePagamento($dados['tipodepagamento']));
                $contrato->setTipoDePagamento($tipoDePagamento);
            }

            $dataDeCriacao = date(Utilitarios::FORMAT_DMYY);
            $contrato->setDataDeCriacao($dataDeCriacao);

            if (Utilitarios::estaInserindo($id)) {
                static::criarPagamentosDoContrato($contrato);
                if ($contrato->gratuito()) {
                    $contrato->setDataDeAtivacao($dataDeCriacao);
                }
            }
        }
    }

    static private function criarPagamentosDoContrato(Contrato $contrato) {

        $driver = DAOFactory::getDAO()->getDriver();
        $configAdminDAO = ConfigAdminFactory::criarConfigAdminDAO($driver);
        $configAdmin = $configAdminDAO->buscarConfiguracaoAdministrativa();
        $diasDeTolerancia = $configAdmin->getDiasDeToleranciaParaPagamento();
        $dataDeCriacao = $contrato->getDataDeCriacao();
        $dataDoPrimeiroPagamento = Utilitarios::adicionarDias($dataDeCriacao, $diasDeTolerancia);
        $geraPagamento = new GeraPagamentoContrato($contrato);
        $geraPagamento->gerarPagamentos($dataDoPrimeiroPagamento);
        
    }

    static public function criarContratoDAO($driver) {
        return new ContratoDAO($driver);
    }

    static public function criarContratoBusiness(ContratoDAO $dao) {
        return new ContratoBusiness($dao);
    }

    static public function criarContratoEmail() {
        return new ContratoEmail();
    }
	
}