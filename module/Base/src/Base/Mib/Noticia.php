<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */

namespace Base\Mib;

use Base\Mib\DataHora,
    Base\Mib\Imagem,
    Base\Mib\Text,
    Base\Mib\URL;


class Noticia {
    
    public static function listar($bdrecordset){
        if(count($bdrecordset)){//$this->paginator->getPages()->currentItemCount
            foreach($bdrecordset as $entity){
                if($entity=="error"){
                    echo "<div class=\"noticias pure-u-1\"><h3>No momento estamos com alguns problemas nesta seção. Atualize a página ou volte mais tarde.</h3></div>\n";
                }else{
                    $noticia = new Noticia($entity);
                    $data = $noticia->getData('U');
                    if(self::$data != $data){
                        self::$data = $data;
                        echo "<h4 class=\"data esquerda pure-u-1\"><span>".$noticia->getExtenso()."</span></h4>";
                    }
                    echo "<div class=\"noticias pure-u-1 pure-u-md-1-2 pure-u-lg-1-3\"><a href=\"".URL::getInstance()->getDir().$noticia->getURL()."/\"><span class=\"capa\" style=\"background-image: url(".URL::getInstance()->getDir()."imgtratadaq50peq/".$noticia->getCapa().");\"></span> <span class=\"hora\"><i class=\"fa fa-clock-o\" aria-hidden=\"true\"></i> <time datetime=\"" . $noticia->getData() . " " . $noticia->getHora('H:i') . "\"><em>" . $noticia->getHora() . " ></em></time></span> <span class=\"texto\">" . $noticia->getTituloResumido() ."</span></a></div>\n";
                }
            }
        }else{
            echo "<div class=\"noticias pure-u-1 pure-u-md-1-2 pure-u-lg-1-3\"><h3>Nenhuma notícia para esta data.</h3></div>\n";
        }
    }

    public static function listarDestaques($array){
        if(count($array)){// conta array diferente de 0
            $retorno="";
            foreach($array as $entity){
                $noticia = new Noticia($entity);
                $retorno .= "<div class=\"noticias pure-u-1 pure-u-md-1-3 pure-u-lg-1-4\">
                <a href=\"".URL::getInstance()->getRootPath().$noticia->getURL()."\">
                    <span class=\"capa\" style=\"background-image: url(".URL::getInstance()->getRootPath()."imgtratadaq50peq/".$noticia->getCapa().");\"></span>
                    <span class=\"texto\">".$noticia->getTitulo()."</span>
                </a>
            </div>";
            //$retorno .="<a href=\"".URL::getInstance()->getRootPath().$noticia->getURL()."\"><img alt=\"".$noticia->getTituloResumido()."\" src=\"".URL::getInstance()->getRootPath()."imgtratadaq50peq/".$noticia->getCapa()."\" data-description=\"".$noticia->getTitulo()."\" /></a>\n";
            }
            return $retorno;
        }
    }

    public function __construct(\Base\Entity\Noticia $noticia, $hashPattern = ""){
        $this->noticia = $noticia;
        $this->hashPattern = $hashPattern;
        $this->texto = $this->preparaTexto($this->noticia->getNoticia());
        $this->titulo = $this->preparaTitulo();
    }

    public function get(){
        return $this->noticia;
    }

    public function getURL(){
        $tituloResumido = $this->getTituloResumido();
        return "noticias/{$this->noticia->getId()}/".strtolower(Texto::tirCarEsp($tituloResumido));
    }

    public function getExtenso($formato = '%d de %B de %Y - %A'){
        return DataHora::dataExtenso($this->getData('U'),$formato);
    }

    public function getData($format="Y-m-d"){
        return $this->noticia->getDt()->format($format);
    }

    public function getHora($format="H:i:s"){
        return $this->noticia->getHr()->format($format);
    }

    public function getTitulo(){
        return $this->titulo;
    }

    public function getTituloResumido(){
        $txt = $this->titulo;
        $tamanho = self::$tamanho;
        $caracteres = strlen($txt);
        $titulo = substr($txt, 0, ($caracteres<$tamanho)? $caracteres : $tamanho) . (($caracteres<$tamanho)? "" : "...");
        if($titulo=="")
            $titulo = substr($txt, 0, $tamanho) . "...";
        return $titulo;
    }

    public function getTexto(){
        return $this->texto;
    }

    public function getTextoSemTitulo(){
        $txt = $this->getTexto();
        preg_match('/<br ?\/?>|<\/h1>/i',$txt, $br, PREG_OFFSET_CAPTURE);
        if(empty($br))
            $br = 0;
        else
            $br = $br[0][1];
        $texto = trim(preg_replace(
                [
                    '/<br\s*\/?>/i',
                    '/<\/?h1>/i'
                ],
                "",
                substr($txt, $br), 1));
        return $texto;
    }

    public function getCapa(){
        $imagens = self::getImagens(self::getPrefixo()."-".self::getId());
        
        if(count($imagens)>0){
            return $imagens[0];
        }
        return self::$imagensDir."semcapa.jpg";
    }

    public function getHashId(){
        return $this->hashid;
    }

    public function getPrefixo(){
        return $this->prefixo;
    }

    public function getSocialId(){
        return $this->socialid;
    }

    public function getId(){
        return $this->id;
    }

    public function getFacebook(){
        return $this->facebook;
    }

    public static function getImagens($noticiaPrefixoID){
        $imgsDir= self::$imagensDir;
        $imagens = Imagem::getImagens($imgsDir, "/^".$noticiaPrefixoID.".+\.(jpe?g|png|gif)$/");
        $imgs = [];
        foreach($imagens as $img){
                $imgs[] = htmlentities($img[0]);
        }
        return $imgs;
    }

    public static function ApagaImagens($noticiaPrefixoID){
        $imagens = self::getImagens($noticiaPrefixoID);
        foreach($imagens as $img){
            unlink(URL::getInstance()->getRoot().$img);
        }
    }

    public static function apagaImagensNaoUsadas(){
        $path = URL::getInstance()->getURI();
        if(
            strpos($path,"/admin/noticias/new")!==0 &&
            strpos($path,"/admin/noticias/delete")!==0 &&
            strpos($path,"/imagens/noticia")!==0 &&
            strpos($path,"/uploadendpoint")!==0
        ){
            if(!empty($_SESSION["Admin"]["Noticia"])){
                self::ApagaImagens($_SESSION['Admin']['noticiaprefixoid']);
                unset($_SESSION["Admin"]["Noticia"]);
            }
            if(!empty($_SESSION["Admin"]["noticiaprefixoid"]))
                unset($_SESSION['Admin']['noticiaprefixoid']);
        }
    }

    public static function getVersao(){
        return self::$versao;
    }

    private function geraHashId($prefixo="ilhapqt:ilhapqt_"){
        if(!empty($this->hashPattern))
            $prefixo = $this->hashPattern;
        return $prefixo.DataHora::ano().DataHora::mes().DataHora::dia().Texto::geraId(4);
    }

    private function preparaTexto($txt){
        if(empty($txt)){
            $txt = "{".$this->geraHashId()."}\n";
        }
        $arrMT = Texto::substituiERC($txt,'{((.+):([^}]{1,6}[^_]+)_([^}]+))}');
        if(!empty($arrMT)){
            if(strpos($arrMT[0][2],"ilhapqt")===0)
                $this->ilhapqt = true;
            elseif(strpos($arrMT[0][2],"fb")===0)
                $this->facebook = true;
            $this->hashid = $arrMT[0][1];
            $this->prefixo = $arrMT[0][2];
            $this->socialid = $arrMT[0][3];
            $this->id = $arrMT[0][4];
            $txt = trim(str_replace($arrMT[0][0], '', $txt));
            if(!$this->ilhapqt){
                $txt = htmlspecialchars($txt);
                $txt = str_replace("\n", "<br />", $txt);
                $txt = Texto::textoEmLink($txt);
            }
        }
        return $txt;
    }

    private function preparaTitulo(){
        $txt = $this->texto;
        preg_match('/<br ?\/?>|<\/h1>/i',$txt, $br, PREG_OFFSET_CAPTURE);
        if(empty($br))
            $br = 0;
        else
            $br = $br[0][1];
        $titulo = substr($txt, 0, $br);
        if($titulo=="")
            $titulo = "Sem Título";
        $titulo = preg_replace(
                [
                    '/<br\s*\/?>/i',
                    '/<\/?h1>/i'
                ],
                "",
                $titulo);
        return $titulo;
    }

    public static $imagensDir = 'img/fotos/noticias/';
    public static $tamanho = 120;
    private static $data = "";
    private $noticia;
    private  $hashPattern;
    private $texto = "";
    private $titulo = "";
    private $hashid = "";
    private $prefixo = "";
    private $socialid = "";
    private $id = "";
    private $facebook = false;
    private $ilhapqt = false;
    
    private static $versao = "1.5";
}

?>