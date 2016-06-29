<?php
abstract class PfwMessageUtils
{
    static function showMessageInfo($msg) {
    
        static::showMessage('Informação!', $msg, 'alert alert-dismissable alert-info');
        
    }
    
    static function showMessageOK($msg) {
    
        static::showMessage('Sucesso!', $msg, 'alert alert-dismissable alert-success');
        
    }

    static function showMessageERROR($msg) {
    
        static::showMessage('Erro!', $msg, 'alert alert-dismissable alert-danger');
        
    }

    static function showMessageWARNING($msg) {
    
        static::showMessage('Aviso!', $msg, 'alert alert-dismissable alert-warning');
        
    }

    static private function showMessage($id, $msg, $class) {
        echo "<div class=\"{$class}\">";
        echo "<button type=\"button\" class=\"close\" data-dismiss=\"alert\">×</button>";
        echo "<strong>{$id} </strong>" . $msg;
        echo "</div>";        
    }
}