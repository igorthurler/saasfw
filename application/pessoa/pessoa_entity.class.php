<?php
/**
 * @Entity
 * @Table(name="Pessoa")
 */
class Pessoa {

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
     * @Column(name="documento") 
     */    
    private $documento;    
    
    /**
     *
     * @var string
     * @Column(name="nome") 
     */
    private $nome;    

    /**
     *
     * @var string
     * @Column(name="logradouro") 
     */    
    private $logradouro;

    /**
     *
     * @var string
     * @Column(name="numero") 
     */    
    private $numero;
    
    /**
     *
     * @var string
     * @Column(name="complemento") 
     */    
    private $complemento;
        
    /**
     *
     * @var string
     * @Column(name="bairro") 
     */    
    private $bairro;
    
    /**
     *
     * @var Cidade
     * @OneToOne(targetEntity="Cidade", fetch=FetchType.LAZY, cascade=CascadeType.NONE)
     * @Column(name="cidade") 
     */    
    private $cidade;
    
    /**
     *
     * @var Estado
     * @OneToOne(targetEntity="Estado", fetch=FetchType.LAZY, cascade=CascadeType.NONE)
     * @Column(name="estado") 
     */        
    private $estado;
    
    /**
     *
     * @var string
     * @Column(name="cep") 
     */    
    private $cep;
    
    /**
     *
     * @var string
     * @Column(name="email") 
     */    
    private $email;
    
    /**
     *
     * @var string
     * @Column(name="imagem") 
     */    
    private $imagem;
    
    /**
     *
     * @var string
     * @Column(name="telefone1") 
     */    
    private $telefone1;
    
    /**
     *
     * @var string
     * @Column(name="telefone2") 
     */    
    private $telefone2;
	
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getDocumento() {
        return $this->documento;
    }

    public function setDocumento($documento) {
        $this->documento = $documento;
    }

    public function getNome() {
        return $this->nome;
    }

    public function setNome($nome) {
        $this->nome = $nome;
    }

    public function getLogradouro() {
        return $this->logradouro;
    }

    public function setLogradouro($logradouro) {
        $this->logradouro = $logradouro;
    }

    public function getNumero() {
        return $this->numero;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }

    public function getComplemento() {
        return $this->complemento;
    }

    public function setComplemento($complemento) {
        $this->complemento = $complemento;
    }

    public function getBairro() {
        return $this->bairro;
    }

    public function setBairro($bairro) {
        $this->bairro = $bairro;
    }

    public function getCidade() {
        return $this->cidade;
    }

    public function setCidade(Cidade $cidade) {
        $this->cidade = $cidade;
    }

    public function getEstado() {
        return $this->estado;
    }

    public function setEstado(Estado $estado) {
        $this->estado = $estado;
    }

    public function getCep() {
        return $this->cep;
    }

    public function setCep($cep) {
        $this->cep = $cep;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getImagem() {
        return $this->imagem;
    }

    public function setImagem($imagem) {
        $this->imagem = $imagem;
    }

    public function getTelefone1() {
        return $this->telefone1;
    }

    public function setTelefone1($telefone1) {
        $this->telefone1 = $telefone1;
    }

    public function getTelefone2() {
        return $this->telefone2;
    }

    public function setTelefone2($telefone2) {
        $this->telefone2 = $telefone2;
    }

    public function getPrimeiroNome() {
            
        $arrayNome = explode(" ",$this->getNome());

        return $arrayNome[0];    
            
    }
	
    public function equals(Pessoa $object) {
        
        if (! isset($object)) {
            return false;
        }

        if ($object == $this) {
            return true;
        }

        return ($object->getDocumento() == $this->documento);        
        
    }
    
}