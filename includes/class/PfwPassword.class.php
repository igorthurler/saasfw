<?php

abstract class PfwPassword
{
    static function getPassword($size = 8, $uppercase = true, 
            $numbers = true, $symbols = false) {

        $lmin = 'abcdefghijklmnopqrstuvwxyz'; 
        $lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
        $num = '1234567890'; 
        $simb = '!@#$%*-';
        $retorno = ''; 
        $caracteres = '';
        $caracteres .= $lmin;
        
        if ($uppercase) $caracteres .= $lmai; 
        if ($numbers) $caracteres .= $num; 
        if ($symbols) $caracteres .= $simb;
        $len = strlen($caracteres);
        
        for ($n = 1; $n <= $size; $n++) { 
            $rand = mt_rand(1, $len); 
            $retorno .= $caracteres[$rand-1]; 
        }
        
        return $retorno;         
        
    }
}