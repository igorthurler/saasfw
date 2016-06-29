<?php
abstract class EnderecoFactory{

    static public function criarEstado() {
        return new Estado();
    }

    static public function criarCidade() {
        return new Cidade();
    }    
    
    static public function criarEnderecoDAO($driver) {
        return new EnderecoDAO($driver);                
    }   
    
}