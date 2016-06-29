<?php
/**
 * @Entity
 * @Table(name="Cidade")
 */
class Cidade {

    /**
     *
     * @var int
     * @Id(strategy=GenerationType.AUTO) 
     * @Column(name="id")
     */    
    private $id;
    
    /**
     *
     * @var int
     * @Column(name="codigo")
     */    
    private $codigo;

    /**
     *
     * @var string
     * @Column(name="nome")
     */
    private $nome;
    
    /**
     * @var Estado
     * @OneToOne(targetEntity="Estado",fetch=FetchType.LAZY, cascade=CascadeType.NONE)
     * @Column(name="estado")
     */		
    private $estado;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getCodigo() {
        return $this->codigo;
    }

    public function setCodigo($codigo) {
        $this->codigo = $codigo;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado(Estado $estado) {
        $this->estado = $estado;
    }
        
    public function equals(Cidade $object = null) {
        
        if (! isset($object)) {
                return false;
        }

        if ($object == $this) {
                return true;
        }

        return ($object->getCodigo() == $this->codigo);                        
        
    }
    
}
?>