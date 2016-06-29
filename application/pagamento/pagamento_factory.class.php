<?php
abstract class PagamentoFactory {

    static public function criarPagamento() {
        return new Pagamento();
    }

    static public function criarPagamentoBusiness() {
        return new PagamentoBusiness();
    }    
    
    static public function criarPagamentoDAO($driver) {
        return new PagamentoDAO($driver);
    }
    
}