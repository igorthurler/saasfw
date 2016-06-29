<?php
class PagamentoBusiness {
    
    public function validar(Pagamento $pagamento) {
        
        if ($pagamento->status() == StatusDoPagamento::PAGO) {
            throw new Exception("N�o � poss�vel confirmar um pagamento que j� foi pago.");
        }        
        
        if ($pagamento->getCancelamento() != null) {
            throw new Exception("N�o � poss�vel cancelar um pagamento cancelado.");
        }        
    }
}