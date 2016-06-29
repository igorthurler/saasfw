<?php

require_once 'validacao.class.php';

abstract class Utilitarios {

    static private $arraySimNao = array(0 => 'Não', 1 => 'Sim');
    static private $arraySexo = array('M' => 'Masculino', 'F' => 'Feminino');
    static private $imagens = array('IMG_FALSE' => 'pfw_check_false.png',
        'IMG_TRUE' => 'pfw_check_true.png', 'IMG_DESATIVAR' => 'pfw_desativar.png',
        'IMG_EDITAR' => 'pfw_editar.png', 'IMG_EXCLUIR' => 'pfw_excluir.png',
        'IMG_FINALIZAR' => 'pfw_finalizar.png', 'IMG_VISUALIZAR' => 'pfw_visualizar.png',
        'IMG_AVATAR' => 'pfw_avatar.png');
    static private $imagensPermitidas = array('.jpg', '.jpeg', '.png', '.gif');

    const EL_DATA = "^\d{1,2}/\d{1,2}/\d{4}$^";
    const EL_NUMERICO = "^[0-9]+$";
    const FORMAT_DMYY = "d/m/Y";
    const MASCARA_TEL1 = "(##)#####-####";
    const MASCARA_TEL2 = "(##)####-####";
    const MASCARA_CPF = "###.###.###-##";
    const MASCARA_CNPJ = "##.###.###/####-##";
    const MASCARA_CEP = "#####-###";
    const DEFAULT_IMAGE_SIZE = 2000000; //2MB

    static function arraySimNao() {
        return static::$arraySimNao;
    }

    static function arrayImagens() {
        return static::$imagens;
    }

    static function arraySexo() {
        return static::$arraySexo;
    }

    static function arrayImagensPermitidas() {
        return static::$imagensPermitidas;
    }

    // Retornar string com a data formatada DD/MM/YYYY. Se nenhuma data for passada, retorna string vazia.
    static function dataFormatada($data) {

        if ($data == null) {
            return "";
        }

        $dataValida = Utilitarios::expressaoValida(Utilitarios::EL_DATA, $data);

        if ($dataValida) {
            return $data;
        }

        return date(FORMAT_DMYY, strToTime($data));
    }

    static function expressaoValida($el, $valor) {
        return preg_match($el, $valor);
    }

    static function sqlFloat($valor) {

        $valor = str_replace($valor, '.', '');
        $valor = str_replace($valor, ',', '.');

        return $valor;
    }

    static function sqlData($valor) {

        $date = explode('/', $valor);
        $d = $date[0];
        $m = $date[1];
        $y = $date[2];

        return "'$y-$m-$d'";
    }

    static function adicionarMeses($data, $meses) {

        $date = explode('/', $data);
        $d = $date[0];
        $m = $date[1];
        $y = $date[2];

        return date(static::FORMAT_DMYY, mktime(0, 0, 0, ($m + $meses), $d, $y));
    }

    static function adicionarDias($data, $dias) {

        $date = explode('/', $data);
        $d = $date[0];
        $m = $date[1];
        $y = $date[2];

        return date(static::FORMAT_DMYY, mktime(0, 0, 0, $m, ($d + $dias), $y));
    }

    static function dataMenor($dataComparada, $dataReferencia) {

        $dataComp = explode('/', $dataComparada);
        $d1 = $dataComp[0];
        $m1 = $dataComp[1];
        $y1 = $dataComp[2];

        $dataRef = explode('/', $dataReferencia);
        $d2 = $dataRef[0];
        $m2 = $dataRef[1];
        $y2 = $dataRef[2];

        if ($y1 < $y2) {
            return true;
        } else {
            if ($y1 > $y2) {
                return false;
            } else {
                if ($m1 == $m2) {
                    if ($d1 < $d2) {
                        return true;
                    }
                } else {
                    if ($m1 < $m2) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    static function valorFormatado($valor, $casasDecimais) {

        if (!is_float($valor)) {
            return $valor;
        }

        return number_format($valor, $casasDecimais, ',', '.');
    }

    static function documentoFormatado($documento) {

        if (strlen($documento) != 11 && strlen($documento) != 14) {
            return $documento;
        }

        if (strlen($documento) == 11) {
            return self::retornarComMascara(static::MASCARA_CPF, $documento);
        }

        if (strlen($documento) == 14) {
            return self::retornarComMascara(static::MASCARA_CNPJ, $documento);
        }
    }

    static function CEPFormatado($cep) {
        return self::retornarComMascara(static::MASCARA_CEP, $cep);
    }

    static function retornarComMascara($mascara, $valor) {

        if (!isset($valor) || empty($valor)) {
            return "";
        }

        $valor = str_replace(" ", "", $valor);
        for ($i = 0; $i < strlen($valor); $i++) {
            $mascara[strpos($mascara, "#")] = $valor[$i];
        }

        return $mascara;
    }

    static function retornarTelefoneFormatado($telefone) {

        $tamanho = strlen($telefone);

        if ($tamanho == 11) {
            return self::retornarComMascara(self::MASCARA_TEL1, $telefone);
        } else {
            if ($tamanho == 10) {
                return self::retornarComMascara(self::MASCARA_TEL2, $telefone);
            }
        }

        return $telefone;
    }

    static function documentoValido($documento) {

        $validacao = new Validacao();

        $cpfValido = $validacao->validaCPF($documento);

        if ($cpfValido) {
            return true;
        }

        $cnpjValido = $validacao->validaCNPJ($documento);

        if ($cnpjValido) {
            return true;
        }

        return false;
    }

    static function retornarDriver($name = "mysql") {

        return PfwConnection::getDriver($name, DbDriver::MYSQLI);
        
    }

    static function exibirMensagemInfo($msg) {

        PfwMessageUtils::showMessageInfo($msg);
        
    }

    static function exibirMensagemOK($msg) {

        PfwMessageUtils::showMessageOK($msg);
        
    }

    static function exibirMensagemERRO($msg) {

        PfwMessageUtils::showMessageERROR($msg);
    }

    static function exibirMensagemAVISO($msg) {

        PfwMessageUtils::showMessageWARNING($msg);
    }

    static function buscarImagem($imagem) {

        return "<img src=\"img/{$imagem}\"></img>";
    }

    static function estaInserindo($id = null) {

        return empty($id);
    }

    static function listaValida(array $lista) {

        return isset($lista) && (count($lista) > 0);
    }

    /**
     * Função para gerar senhas aleatórias 
     * @author    Thiago Belem <contato@thiagobelem.net> 
     * @param integer $tamanho Tamanho da senha a ser gerada 
     * @param boolean $maiusculas Se terá letras maiúsculas 
     * @param boolean $numeros Se terá números 
     * @param boolean $simbolos Se terá símbolos 
     * @return string A senha gerada 
     */
    static function gerarSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false) {

        return PfwPassword::getPassword($tamanho, $maiusculas, $numeros, $simbolos);
    }

    static function retornarImagemDaPessoa(Pessoa $pessoa) {

        $imagem = $pessoa->getImagem();
        $img = (isset($imagem) && !empty($imagem)) ?
                'uploads/' . $pessoa->getEmail() . '/' . $imagem :
                'img/pfw_avatar.png';

        return $img;
    }

    static function retornarImagemDoUsuario(Usuario $usuario) {

        $imagem = $usuario->getImagem();
        $img = (isset($imagem) && !empty($imagem)) ?
                'uploads/' . $usuario->getEmail() . '/' . $imagem :
                'img/pfw_avatar.png';

        return $img;
    }    
    
}
