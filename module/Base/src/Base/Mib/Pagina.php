<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */

namespace Base\Mib;

class Pagina {
    
    public static function htmlParaTextoPlano($strC){
        $strConteudo="";
        $strConteudo = str_replace("<hr>", "\n--------------------\n", $strC);
        $strConteudo = preg_replace("/<[\w\s\/=\"]+>/", "\n", $strConteudo);
        return $strConteudo;
    }
    
    public static function getVersao(){
        return self::$versao;
    }
    
    private static $versao = "1.0";
}