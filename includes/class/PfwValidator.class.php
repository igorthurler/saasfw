<?php

class PfwValidator
{

    var $errors; // A variable to store a list of error messages

    function validateIssetObject($theinput = null, $description = '') {
        
        if (isset($theinput)) {
            return true;
        }else{
            $this->errors[] = $description;
            return false;            
        }
        
    }
    
    // Validate something's been entered
    // NOTE: Only this method does nothing to prevent SQL injection
    // use with addslashes() command	
    function validateGeneral( $theinput, $description = '' )
    {
            if ( trim( $theinput ) != "" )
            {
                    return true;
            }else{
                    $this->errors[] = $description;
                    return false;
            }
    }

    // Validate text only
    // precisa modificar para permitir acento
    function validateTextOnly( $theinput, $description = '' )
    {
            $result = preg_match( "/[A-Za-z0-9\ ]+$/", $theinput );
            
            if ( $result )
            {
                    return true;
            }else{
                    $this->errors[] = $description;
                    return false;
            }
    }
    
    function validateTextMinMaxLength( $theinput, $min, $max, $description = '' ) {
        
        $size = strlen($theinput);
        
        if ($size < $min || $size > $max) {
            $this->errors[] = $description;
            return false;
        }else{
            return true;
        }
        
    }
    
    function validateTextMinLength( $theinput, $min, $description = '' ) {
        
        $size = strlen($theinput);
        
        if ($size < $min) {
            $this->errors[] = $description;
            return false;
        }else{
            return true;
        }
        
    }
    
    function validateTextMaxLength( $theinput, $max, $description = '' ) {
        
        $size = strlen($theinput);
        
        if ($size > $max) {
            $this->errors[] = $description;
            return false;
        }else{
            return true;
        }
        
    }    
    
    function validateTextOnlyNumbers( $theinput, $description = '' )
    {
            $result = preg_match( "^[0-9]+$^", $theinput );
            
            if ( $result )
            {
                    return true;
            }else{
                    $this->errors[] = $description;
                    return false;
            }
    }

    // Validate text only, no spaces allowed
    // Modificado original para permitir underline
    function validateTextOnlyNoSpaces( $theinput, $description = '' )
    {
            $result = preg_match( "/[A-Za-z0-9\_]+$/", $theinput );
            
            if ( $result )
            {
                    return true;
            }else{
                    $this->errors[] = $description;
                    return false;
            }
    }

    /**
     * Esta fucao é vinda do iMaster em seu tutorial sobre Ajax
     * direitos reservados a eles.
     *
     * Esta função tem o objetivo de evitar o SQL Injection
     * Consulte o Google [http://www.google.com.br/search?hl=pt-BR&q=sql+injection] para mais informações sobre o assunto.
     *	
     * @param string $data - dado a ser verificado contra SQL injection
     * @return string $data - dado formatado contra sql injection
     **/
    function formatData( $data )
    { 
            $data = strip_tags($data);
            $data = trim($data);
            $data = get_magic_quotes_gpc() == 0 ? addslashes($data) : $data;
            $data = preg_replace("@(--|\#|\*|;)@s", "", $data);
            return $data;
    }

    /**
     * Esta fucao e' vinda do iMaster em seu tutorial sobre Ajax
     * direitos reservados a eles.
     *
     * Esta função tem o objetivo de evitar o SQL Injection
     * Consulte o Google [http://www.google.com.br/search?hl=pt-BR&q=sql+injection] para mais informações sobre o assunto.
     * E trata os acentos que poderão conter nos dados enviados através da URL
     *	
     * @param string $data - dado a ser verificado contra SQL injection
     * @return string $data - dado formatado contra sql injection
     **/
    function formatDataAjax( $data )
    {
            $data = strip_tags($data);
            $data = trim($data);
            $data = get_magic_quotes_gpc() == 0 ? addslashes($data) : $data;
            $data = preg_replace("@(--|\#|\*|;)@s", "", $data);
            $data = urldecode($data);   // específico no caso do Ajax
            $data = utf8_decode($data); // específico no caso do Ajax
            return $data;
    }

    // Validate email address
    function validateEmail( $themail, $description = '' )
    {	   
            $result = preg_match("/^([[:alnum:]_.-]){3,}@([[:lower:][:digit:]_.-]{3,})(\.[[:lower:]]{2,3})(\.[[:lower:]]{2})?$/", $themail);
            if ( $result )
            {
                    return true;
            }else{
                    $this->errors[] = $description;
                    return false;
            }
    }

    // Validate numbers only
    function validateNumber( $theinput, $description = '' )
    {
            if ( is_numeric( $theinput ) )
            {
                    return true; // The value is numeric, return true
            }else{
                    $this->errors[] = $description; // Value not numeric! Add error description to list of errors
                    return false; // Return false
            }
    }

    // Validate date
    function validateDate( $thedate, $description = '' )
    {

            if ( strtotime( $thedate ) === -1 || $thedate == '' )
            {
                    $this->errors[] = $description;
                    return false;
            }else{
                    return true;
            }
    }

    // Return a string containing a list of errors found,
    // Seperated by a given deliminator
    function listErrors( $delim = ' ' )
    {
            return implode( $delim, $this->errors );
    }

    // Manually add something to the list of errors
    function addError( $description )
    {
            $this->errors[] = $description;
    }

    /*
    * Check whether any errors have been found (i.e. validation has returned false)
    * since the object was created
    *
    * @return bool result - True caso erros sejam encontrados
    */
    function foundErrors()
    {	
            return ( count( $this->errors ) > 0 );
    }	
	
}