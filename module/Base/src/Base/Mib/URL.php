<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */

namespace Base\Mib;

class URL {

    public static function getInstance(){
        if ( is_null( self::$instance ) ){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public static function getUltimaParte($url){
        $arr = explode("/",$url);
        if(empty($arr[count($arr)-1])){
            return $arr[count($arr)-2];
        }else{
            return $arr[count($arr)-1];
        }
    }

    public static function validaURLExterna($url){
        return !empty(parse_url($url, PHP_URL_HOST));
    }
    
    public function __construct(){

        $this->uri = parse_url($this->getURL());
        $this->scheme = $this->uri['scheme'];
        $this->host = $this->uri['host'];
        $this->base = sprintf('%s://%s', $this->scheme, $this->host);
        
        $paginaURL = str_replace($this->subpasta."/","",$this->getTratada());
        $paginaDir = "";
        if($paginaURL == $this->subpasta){
            $paginaURL = "";
            $paginaDir = $this->subpasta."/";
        }
        $arrPaginaURL = explode("/",$paginaURL);
        $j = count($arrPaginaURL);

        for($i=1;$i<$j;$i++){
            $paginaDir .= "../";
        }
        
        if(substr($paginaURL,(strlen($paginaURL)-1)) == "/"){
            $paginaURL = substr($paginaURL,0,(strlen($paginaURL)-1));
        }
        
        if($paginaURL!=""){
            $this->pagina = $paginaURL;   
        }

        $this->dir = $paginaDir;
    }
    
    public function getTratada(){
        
        preg_match("/\/?([^?]+).*/i", $_SERVER['REQUEST_URI'], $encontrados);
        
        
        if($encontrados[1]=="/"){
            return "";
        }
        
        return $encontrados[1];
    }

    public function getRoot(){
        $root = explode(DIRECTORY_SEPARATOR, $_SERVER["DOCUMENT_ROOT"]);
        if($root[count($root)-1]!=""){
            $root = $root[count($root)-1];
        }else{
            $root = $root[count($root)-2];
        }
        $root.= $this->getRootPath();
        if(!is_dir($root)){
            if(is_dir(DOCUMENTO_RAIZ)){
                // Constante definida na index da raiz
                $root = DOCUMENTO_RAIZ."/";
            }else{
                $root = "./";
            }
        }
        return $root;
    }

    public function getRootPath(){
        $root = "/";
        if(strpos($this->getTratada(),$this->subpasta)===0){
            $root.= $this->subpasta."/";
        }
        return $root;
    }

    public function detectaScheme(){
        return (!empty($_SERVER['HTTPS'])&&$_SERVER['HTTPS']!='off')?'https':'http';
    }
    
    public function getURL(){
        return $this->detectaScheme().'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    }

    public function getURI($tratada=false){
        if($tratada){
            return str_replace($this->subpasta."/","", $_SERVER['REQUEST_URI']);
        }
        return $_SERVER['REQUEST_URI'];
    }

    public function getBase(){
        return $this->base;
    }

    public function getHost(){
        return $this->host;
    }

    public function getScheme(){
        return $this->scheme;
    }
    
    public function getPagina(){
        return str_replace("/","-",$this->pagina);
    }
    
    public function getDir(){
        return $this->dir;
    } 

    public function getHostName(){
        // getHostByName(getHostName()) . " - ".
        return getHostName();
    }

    public function getIP(){
        return join(".",$this->getIPTratado());
    }

    public function getIPTratado(){
        $ipPartes = explode(".",$_SERVER['REMOTE_ADDR']);
        if(count($ipPartes)!=4){
            $ipPartes = explode(".",getHostByName($this->getHostName()));
        }
        return $ipPartes;
    }
    
    public static function getVersao(){
        return self::$versao;
    }
    
    private $dir = "";
    private $pagina = "principal";
    private $subpasta = "modelo";
    
    private $uri;
    private $scheme;
    private $host;
    private $base;
    private static $instance;
    
    private static $versao = "2.0";
}

?>