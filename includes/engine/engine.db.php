<?php
define('ENGINE_PATH', realpath(dirname(__FILE__).'/../'));
set_include_path('.'
.PATH_SEPARATOR.ENGINE_PATH
.PATH_SEPARATOR.get_include_path()
);
$imported_paths = array();
function import($classPath) {
    global $imported_paths;
    if (isset($imported_paths[$classPath])) return;
    //Verificar se o caminho ja foi totalmente incluso
    $vClassPath = explode(".",$classPath);
    if (sizeof($vClassPath) > 1) {
        for ($i = 2; $i < count($vClassPath)  ; $i++) {
            $currentPath = $vClassPath;
            array_splice($currentPath, $i);
            $currentPath[count($currentPath)] = "*";
            $allClassPath = implode(".", $currentPath);
            if (isset($imported_paths[$allClassPath])) return;
        }
    }

    $path = str_replace(".","/",$classPath);
    if (strstr($path,'*')) {
        $dir = str_replace("*","",$path);
        $incPath = explode(PATH_SEPARATOR, get_include_path());
        foreach ($incPath as $base) {
            $target = rtrim($base, '\\/') . "/" . $dir;
            if (is_dir($target)) {
                if ($dh = opendir($target)) {
                    while (($file = readdir($dh)) !== false) {
                        if (is_dir($target.$file)) {
                            if (($file != ".") && ($file != "..")) {
                                //echo $file.":".filetype($target.$file)."<br>\n";
                                import(str_replace("/",".",$dir.$file).".*");
                            }
                        }
                        if (stristr($file,"~")) continue;
                        if (stristr($file,".php")) {
                            //echo $dir.$file."<br>";
                            require_once($dir.$file);
                        }
                    }
                    closedir($dh);
                }
            }
        }
    } else {
        if (require_once($path.'.php')) {
            $arr = explode("/",$path);
            $file = array_pop($arr);
            $arr = null;
        } else {
            throw new Exception("Erro carregando arquivo. [Caminho: ".$path.".php]");
        }
    }
    $imported_paths[$classPath] = true;
}

function engineErrorHandler($errno, $errstr, $errfile, $errline) {
    if ( E_RECOVERABLE_ERROR===$errno ) {
        echo "Exception 'catched' pelo Engine Error Handler\n";
        //Erro de propriedade que nao aceita nulo
        $pattern = "/Argument 1 passed to (?P<class>([\w\d]+))\:\:(?P<method>([\w\d_]*))\(\) [\w\d\s]*\, null given/";
        preg_match_all($pattern,$errstr,$matches);
        if (count($matches['class'])>0) {
            trigger_error("O metodo ".$matches['class'][0].".".$matches['method'][0]." nao aceita null como parametro. Considere utilizar \$parametro = null.", E_USER_NOTICE);
            return true;
        }

        throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
        // return true;
    }
    return false;
}
set_error_handler('engineErrorHandler');

import('engine.reflection.*');
import('engine.exceptions.*');
import('engine.db.*');
import('engine.core.*');
import('engine.types.*');
?>