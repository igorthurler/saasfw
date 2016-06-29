<?php
/**
 * @Entity
 * @Table(name="Estado")
 */
class Estado {

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
     * @column(name="uf")
     */
    private $uf;
    
    /**
     *
     * @var string
     * @column(name="nome")
     */
    private $nome;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getUf() {
        return $this->uf;
    }

    public function setUf($uf) {
        $this->uf = $uf;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function equals(Estado $object = null) {
                        
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