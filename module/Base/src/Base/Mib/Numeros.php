<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */

namespace Base\Mib;

class Numeros {

    public static function numCasas($intNum,$intCasa){
        $intNum = "000000000000$intNum";
        return substr($intNum,strlen($intNum)-$intCasa);
    }

    public static function arrIndice(& $arrMatriz,$strTexto,$strRetorno){
        foreach($arrMatriz as $strI=>$strV){
            if($strV==$strTexto){
                return $strI;
            }
        }
        return $strRetorno;
    }

    public static function txtPegNum($strT,$intT,$intI){
        $erLetra = "[^0-9\.,-]*";
        $strNum = ereg_replace($erLetra," ",$strT);
        $strNum = explode(" ",str_replace("/"," ",str_replace(" ","",str_replace("  ","/",$strNum))));
        if($intT!=0){
            return implode("",$strNum);
        }elseif($intI<0){
            return $strNum;
        }else{
            return $strNum[$intI];
        }
    }

    
    public static function pegCEP($strT){
        $erCEP = "/^[0-9]{2,}-[0-9]{2,}/";
        $encontrados = [];
        if(preg_match_all($erCEP, $strT, $encontrados)){
            return $encontrados[0][0];
        }
        return false;
    }
    
    public static function getVersao(){
        return self::$versao;
    }

    private static $versao = "1.0";
    
}

?>