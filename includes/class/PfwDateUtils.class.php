<?php
abstract class PfwDateUtils
{
    const FORMAT_DMYY = "d/m/Y";
    const EL_DATA = "^\d{1,2}/\d{1,2}/\d{4}$^";
    
    static function dataFormatada($data) {

        if ($data == null) {
            return "";
        }
        
        $dataValida = preg_match(EL_DATA, $data);

        if ($dataValida) {
            return $data;
        }

        return date(FORMAT_DMYY, strToTime($data));

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
    
}