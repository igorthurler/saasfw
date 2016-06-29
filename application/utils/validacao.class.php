<?php
/**********************************************************************************************************
  Data de criação : 18/08/2009
  Autor           : Daniel Flores Bastos
  Proposta        : Validar CPF e CNPJ. Em caso de erro
                    retorna em qual digito verificador ocorreu
                    o erro.
************************************************************************************************************/
  class Validacao
  {
  
    public  $erroCPF    = '';
    public  $erroCNPJ   = '';

    private $pArray_cpf = array(10, 9, 8, 7, 6, 5, 4, 3, 2);
    private $sArray_cpf = array(11, 10, 9, 8, 7, 6, 5, 4, 3, 2);
    private $pArray_cnpj = array(5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2);
    private $sArray_cpj = array(6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2);
       
    function validaCPF($cpf)
    {
        
        if (strlen($cpf) != 11) {
            return false;
        }  
      
        //$cpf = str_pad(ereg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);
        // Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
        if (strlen($cpf) != 11 || $cpf == '00000000000' || 
                $cpf == '11111111111' || $cpf == '22222222222' || 
                $cpf == '33333333333' || $cpf == '44444444444' || 
                $cpf == '55555555555' || $cpf == '66666666666' || 
                $cpf == '77777777777' || $cpf == '88888888888' || 
                $cpf == '99999999999') {
                return false;
        } else {
                // Calcula os números para verificar se o CPF é verdadeiro        
                for ($t = 9; $t < 11; $t++) {
                        for ($d = 0, $c = 0; $c < $t; $c++) {
                                $d += $cpf{$c} * (($t + 1) - $c);
                        }
                        $d = ((10 * $d) % 11) % 10;
                        if ($cpf{$c} != $d) {
                                return false;
                        }
                }
                return true;   
        }

    }
    
    function validaCNPJ($valor)
    {
        
      if (strlen($valor) != 14) {
          return false;
      }  
      $somador_cnpj = 0;
      //$pArray_cnpj = array(5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2);

      for($i = 0; $i < (strlen($valor) - 2); $i++)
      {
        $somador_cnpj = $somador_cnpj + ($valor[$i] * $this->pArray_cnpj[$i]);
      }

      $auxiliar = $somador_cnpj % 11;
      $p_digito_verificador = 11 - $auxiliar;
      
      if($p_digito_verificador < 2)
        $p_digito_verificador = 0;

      if($p_digito_verificador == $valor[12])
      {

        //$sArray_cpj = array(6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2);
        $somador_cnpj = 0;

        for($i = 0; $i < (strlen($valor) - 1); $i++)
        {
          $somador_cnpj = $somador_cnpj + ($valor[$i] * $this->sArray_cpj[$i]);
        }

        $auxiliar = $somador_cnpj % 11;
        $s_digito_verificador = 11 - $auxiliar;
        
        if($s_digito_verificador < 2)
          $s_digito_verificador = 0;

        if($s_digito_verificador == $valor[13])
        {
          return true;
        }
        else
        {
          $this->erroCNPJ = 2; // Erro no segundo digito verificador
          return false;
        }
      }
      else
      {
          $this->erroCNPJ = 1; // Erro no primeiro digito verificador
          return false;
      }
    }
  }
?>