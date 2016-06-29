<?php

/**
 * @Entity
 * @Table(name="Cancelamento")
 */
class Cancelamento {

    /**
     *
     * @var int
     * @Id(strategy=GenerationType.AUTO) 
     */
    private $id;
    
    /**
     *
     * @var date 
     * @Column(name="data") 
     */
    private $data;
    
    /**
     *
     * @var Usuario
     * @Column(name="responsavel") 
     * @OneToOne(fetch=FetchType.LAZY)
     */
    private $responsavel;
    
    /**
     *
     * @var string 
     * @Column(name="motivo") 
     */
    private $motivo;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($data) {
        $this->data = $data;
    }
        
    public function getResponsavel() {
        return $this->responsavel;
    }

    public function setResponsavel(Usuario $responsavel) {
        $this->responsavel = $responsavel;
    }

    public function getMotivo() {
        return $this->motivo;
    }

    public function setMotivo($motivo) {
        $this->motivo = $motivo;
    }
	
	public function equals(Cancelamento $object) {
		
            if (! isset($object)) {
                    return false;
            }

            if ($object == $this) {
                    return true;
            }

            return ($object->getId() == $this->id);
            
	}		
    
}