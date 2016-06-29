<?php

/**
 * @Entity
 * @Inheritance(type=InheritanceType.TABLE_PER_CLASS)
 * @Table(name="Usuario")
 */
class Usuario {

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
     * @Column(name="nome") 
     */
    private $nome;

    /**
     *
     * @var string
     * @Column(name="email") 
     */
    private $email;

    /**
     *
     * @var string
     * @Column(name="senha") 
     */
    private $senha;

    /**
     *
     * @var string
     * @Transient
     */
    private $senhaDescriptografada;

    /**
     *
     * @var string
     * @Column(name="imagem") 
     */
    private $imagem;
    
    /**
     *
     * @var date
     * @Column(name="dataDeCadastro") 
     */
    private $dataDeCadastro;

    /**
     *
     * @var date
     * @Column(name="dataDeDesativacao") 
     */
    private $dataDeDesativacao; 
    
    public function __construct() {
        $this->perfis = new Collection();
    }	
	
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getSenha() {
        return $this->senha;
    }

    public function setSenha($senha) {
        $this->senha = $senha;
    }

    public function getSenhaDescriptografada() {
        return $this->senhaDescriptografada;
    }

    public function setSenhaDescriptografada($senhaDescriptografada) {
        $this->senhaDescriptografada = $senhaDescriptografada;
    }

    public function getImagem() {
        return $this->imagem;
    }

    public function setImagem($imagem) {
        $this->imagem = $imagem;
    }
      
   public function getDataDeCadastro() {
        return $this->dataDeCadastro;
    }

    public function setDataDeCadastro($dataDeCadastro) {
        $this->dataDeCadastro = $dataDeCadastro;
    }

    public function getDataDeDesativacao() {
        return $this->dataDeDesativacao;
    }

    public function setDataDeDesativacao($dataDeDesativacao) {
        $this->dataDeDesativacao = $dataDeDesativacao;
    }
	
    public function isAtivo() {
        return $this->dataDeDesativacao == null;
    }      
      
    public function getPrimeiroNome() {

        $arrayNome = explode(" ", $this->getNome());

        return $arrayNome[0];
        
    }
 
    public function equals(Usuario $object) {

        if (! isset($object)) {
            return false;
        }

        if ($object == $this) {
            return true;
        }

        return ($object->getEmail() == $this->email);
        
    }

}