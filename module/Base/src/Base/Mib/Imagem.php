<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */

namespace Base\Mib;

use Base\Mib\URL;

class Imagem {
    
    public static function getImagens($dir, $ereg){
        $arquivos = scandir(URL::getInstance()->getRoot().$dir);

        array_splice($arquivos,0,2);
        natcasesort($arquivos);
        $imagens = [];

       foreach($arquivos as $imgnome){
            if(preg_match($ereg,$imgnome)){
                
                list($imgTW,$imgTH)=getimagesize(URL::getInstance()->getRoot().$dir.$imgnome);
                $intLargura = $imgTW;
                $intAltura = $imgTH;
                $imagens[]=[
                        $dir.htmlentities($imgnome),
                        $intLargura,
                        $intAltura
                    ];
            }
        }

        return $imagens;
    }

    public static function getBaseName($img){
        return self::getInfo($img);
    }

    public static function getExtension($img){
        return self::getInfo($img, 'extension');
    }

    public static function getUUID($img){
        $uuid = self::getInfo($img,'filename');
        $uuid = explode("-", $uuid, 2);
        $uuid = substr($uuid[1], 0, -5);
        return $uuid;
    }
    
    public static function getVersao(){
        return self::$versao;
    }

    private static function getInfo($img, $infokey='basename'){
        $info = pathinfo($img);
        return $info[$infokey];
    }
    
    private static $versao = "1.2";
}

?>