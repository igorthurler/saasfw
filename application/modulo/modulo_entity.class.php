<?php
/**
 * @Entity
 * @Table(name="Modulo")
 */
class Modulo {
    
    /**
     *
     * @var int
     * @Id(strategy=GenerationType.AUTO) 
     * @Column(name="id")
     */
    private $id;

    /**
     *
     * @var string 
     * @Column(name="identificador") 
     */    
    private $identificador;
    
    /**
     *
     * @var string 
     * @Column(name="descricao") 
     */
    private $descricao;
        
    /**
     *
     * @var Cancelamento
     * @Column(name="cancelamento") 
     * @OneToOne(fetch=FetchType.LAZY, cascade=CascadeType.ALL)
     */
    private $cancelamento;                
    
    public function getId() {
        return $this->id;        
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function getDescricao() {
        return $this->descricao;
    }

    public function getIdentificador() {
        return $this->identificador;        
    }

    public function setIdentificador($identificador) {
        $this->identificador = $identificador;
    }
    
    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }
    
    public function getCancelamento() {
        return $this->cancelamento;
    }
    
    public function setCancelamento(Cancelamento $cancelamento) {
        $this->cancelamento = $cancelamento;
    }
        
    public function isAtivo() {
        return ! isset($this->cancelamento);
    }
    
    public function equals(Modulo $object) {

            if (! isset($object)) {
                    return false;
            }

            if ($object == $this) {
                    return true;
            }

            return ($object->getId() == $this->id);
    }    
}
?>