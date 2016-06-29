<?php
/**
 * @Entity
 * @Table(name="PoliticaDePreco")
 */
class PoliticaDePreco {

    /**
     *
     * @var int
     * @Id(strategy=GenerationType.AUTO) 
     * @Column(name="id")
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
     * @var float
     * @Column(name="valor") 
     */    
    private $valor;        
    
    /**
     *
     * @var PlanoDeAdesao
     * @OneToOne(targetEntity="PlanoDeAdesao", fetch=FetchType.FETCH, cascade=CascadeType.NONE)
     * @Column(name="planoDeAdesao") 
     */
    private $planoDeAdesao;  
    
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

    public function getValor() {
        return $this->valor;
    }

    public function setValor($valor) {
        $this->valor = $valor;
    }
        
    public function getPlanoDeAdesao() {
        return $this->planoDeAdesao;
    }

    public function setPlanoDeAdesao(PlanoDeAdesao $planoDeAdesao) {
        $this->planoDeAdesao = $planoDeAdesao;
    }
    
    public function valorTotal() {
    
        $valorTotal = ($this->planoDeAdesao != null) ? $this->planoDeAdesao->getDuracao() : 0;
        return $valorTotal;
    
    }
    
	public function isGratis() {
	
		if ($this->planoDeAdesao == null) {
			return false;
		} else {
			return $this->planoDeAdesao->isGratis();
		}		
		
	}
	
    public function equals(PoliticaDePreco $object = null) {

		if (! isset($object)) {
				return false;
		}

		if ($object == $this) {
				return true;
		}

		return ($object->getId() == $this->id);
			
    }        
    
}