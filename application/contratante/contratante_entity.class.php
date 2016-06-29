<?php

/**
 * @Entity
 * @Table(name="Contratante")
 */
class Contratante {
    
    /**
     *
     * @var int
     * @Id(strategy=GenerationType.AUTO) 
     * @Column(name="id")
     */
    private $id;

    /**
     *
     * @var Pessoa
     * @OneToOne(targetEntity="Pessoa", fetch=FetchType.FETCH, cascade=CascadeType.SAVE)
     * @Column(name="pessoa") 
     */    
    private $pessoa;
    
    /**
     *
     * @var date
     * @Column(name="dataDeCadastro") 
     */    
    private $dataDeCadastro;

    /**
     *
     * @var string
     * @Column(name="alias") 
     */    	
	private $alias;
	
    /**
     *
     * @var string
     * @Column(name="site") 
     */    
    private $site;
        
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getPessoa() {
        return $this->pessoa;
    }

    public function setPessoa(Pessoa $pessoa) {
        $this->pessoa = $pessoa;
    }
        
    public function getDataDeCadastro() {
        return $this->dataDeCadastro;
    }

    public function setDataDeCadastro($dataDeCadastro) {
        $this->dataDeCadastro = $dataDeCadastro;
    }

    public function getAlias() {
        return $this->alias;
    }

    public function setAlias($alias) {
        $this->alias = $alias;
    }	
	
    public function getSite() {
        return $this->site;
    }

    public function setSite($site) {
        $this->site = $site;
    }        
    
    public function equals(Contratante $object) {

            if (! isset($object)) {
                    return false;
            }

            if ($object == $this) {
                    return true;
            }

            return ($object->getId() == $this->id);
    }            
    
}