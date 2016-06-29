<?php
class PagamentoBusiness {
    
    public function validar(Pagamento $pagamento) {
        
        if ($pagamento->status() == StatusDoPagamento::PAGO) {
            throw new Exception("Não é possível confirmar um pagamento que já foi pago.");
        }        
        
        if ($pagamento->getCancelamento() != null) {
            throw new Exception("Não é possível cancelar um pagamento cancelado.");
        }        
    }
}