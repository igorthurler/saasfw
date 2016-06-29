<?php
/**
 * @Entity
 * @Table(name="PlanoDeAdesao")
 */
class PlanoDeAdesao {
    
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
     * @Column(name="descricao")
     */
    private $descricao;
    
    /**
     *
     * @var int 
     * @Column(name="duracao")
     */
    private $duracao;
    
    /**
     *
     * @var boolean
     * @Column(name="gratis")
     */
    private $gratis; 

    /**
     *
     * @var int 
     * @Column(name="quantUsuario")
     */
    private $quantUsuario;
	
    /**
     *
     * @var Cancelamento
     * @Column(name="cancelamento") 
     * @OneToOne(fetch=FetchType.LAZY, cascade=CascadeType.ALL)
     */
    private $cancelamento; 

    /**
     *
     * @var Collection 
     * @ManyToMany(targetEntity="Modulo",
     *      fetch=FetchType.LAZY,cascade=CascadeType.NONE)
     * @JoinTable(name="PlanoDeAdesaoModulo",joinColumns="planoDeAdesao",
     *      inverseJoinColumns="modulo")
     */
    private $modulos;      
    
    public function __construct() {
        $this->modulos = new Collection();
    }
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function setDescricao($descricao) {
        $this->descricao = $descricao;
    }

    public function getDuracao() {
        return $this->duracao;
    }

    public function setDuracao($duracao) {
        $this->duracao = $duracao;
    }

    public function isGratis() {
        return $this->gratis;
    }

    public function setGratis($gratis) {
        $this->gratis = $gratis;
    }

    public function getQuantUsuario() {
        return $this->quantUsuario;
    }

    public function setQuantUsuario($quantUsuario) {
        $this->quantUsuario = $quantUsuario;
    }	
	
    public function getCancelamento() {
        return $this->cancelamento;
    }

    public function setCancelamento(Cancelamento $cancelamento) {
        $this->cancelamento = $cancelamento;
    }
    
    public function getModulos() {               
        return $this->modulos;
    }

    public function setModulos(Collection $modulos) {                
        $this->modulos = $modulos;
    }
    
    public function isAtivo() {
        return ! isset($this->cancelamento);
    }
	
    public function moduloAssociado(Modulo $modulo) {

            $retorno = false;


            if (count($this->modulos) > 0) {
                $retorno = $this->modulos->contains($modulo);
            }

            return $retorno;

    }

    public function adicionarModulo(Modulo $modulo) {
        
        if (! $this->moduloAssociado($modulo)) {
            $this->modulos->add($modulo);
        }    
        
    }    
    
    public function equals(PlanoDeAdesao $object = null) {

		if (! isset($object)) {
				return false;
		}

		if ($object == $this) {
				return true;
		}

		return ($object->getId() == $this->id);
		
    }	

}