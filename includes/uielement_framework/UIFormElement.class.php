<?php
class UIFormElement extends UIElement {

    /**
     * Armazena formulario juto a seus campos, botoes e valores
     * @var string
     * */
    protected $formulario = '';    
    
    /**
     * Define o cabeçalho do form
     *
     * @param string $method - Deixe em branco para POST
     * @param string $action - Arquivo que processara este formulario
     * @param array $properties - Propriedades do formulário
     * @param string $something - variavel a disposicao do programador
     * @param string $enctype - define o enctype do form
     *
     * */
    function setFormHeader($method = 'POST', $action = '?',  
            $properties = array(), $something = '', $enctype = '') {
        
        parent::init($properties, $something);
        
        if (!isset($method))
            $method = "POST";

        $this->formulario = "<form action='{$action}' method='{$method}' {$this->getProperties()} enctype='{$enctype}'>\n";

    }    

    /**
     * Gera a saída HTML para o objeto
     */
    public function toHTML() {
        $value = $this->formulario;
        $value .= $this->getAdded();
        $value .= "\n" . "</form>";        
        return $value;        
    }
}