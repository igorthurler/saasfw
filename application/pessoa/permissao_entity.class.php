<?php
/**
 * @Entity
 * @Table(name="PessoaPermissao")
 */
class Permissao {

	/**
	 * @var Pessoa
	 * @Column(name="pessoa") 
         * @Id(strategy=GenerationType.NONE)
	 * @ManyToOne(fetch=FetchType.LAZY, cascade=CascadeType.NONE)         
	 */
	private $pessoa;

	/**
	 * @var string
	 * @Column(name="permissao") 
         * @Id(strategy=GenerationType.NONE)
	 */	
	private $permissao;        
        
        public function getPessoa() {
            return $this->pessoa;
        }

        public function setPessoa(Pessoa $pessoa) {
            $this->pessoa = $pessoa;
        }

        public function getPermissao() {
            return $this->permissao;
        }

        public function setPermissao($permissao) {
            $this->permissao = $permissao;
        }

}
?>