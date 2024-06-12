<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */

namespace Base\Mib;

class XML {
    
    public function __construct($xmlArquivo){
        if(file_exists($xmlArquivo)){
            $this->xmlArquivo = simplexml_load_file($xmlArquivo);
        }
    }
    
    public function conteudo(){
        if($this->xmlArquivo!==false){
            return $this->xmlArquivo->conteudo[0];   
        }else{
            return false;
        }
    }
    
    public function conteudoTratado($urlContexto){

        $arrP;
        $intA;
        $strC = $this->xmlArquivo->conteudo[0];
        $strConteudo = "";

        $strConteudo = str_replace("strModeloPadrao", "index.php", $strC);
        $strConteudo = str_replace("strPaginaURL", $urlContexto->getURL(), $strConteudo);
        $strConteudo = str_replace("strPaginaDir/", $urlContexto->getDir(), $strConteudo);

        return $strConteudo;

    }
    
    public static function getVersao(){
        return self::$versao;
    }
    
    private $xmlArquivo = false;
    private static $versao = "1.0";
}

?>