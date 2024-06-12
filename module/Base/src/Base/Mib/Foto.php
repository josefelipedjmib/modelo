<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */

namespace Base\Mib;

use Base\Mib\Numeros,
	Base\Mib\Texto,
	Base\Mib\URL;

class Foto{

	public function __construct($fotoTotal, $fotoAlturaPequena, $mostragemPorSecao, $mostragemPorLinha, $fotoSigla, $fotoSecao, $tamanhos, $containerID, $layout = __DIR__."/fotolayout.php"){

		$strPaginaDir = URL::getInstance()->getDir();

		self::$total = $fotoTotal;
		self::$alturaPequena = $fotoAlturaPequena;
		self::$mostragem = $mostragemPorSecao;
		self::$linha = $mostragemPorLinha;
		self::$sigla = $fotoSigla;
		self::$tamanhos = $tamanhos;
		$this->secao = $fotoSecao;
		$this->secaoNome = $this->secao;
		$this->container = $containerID;
		$this->diretorio = $strPaginaDir . $this->diretorio . $this->secao . "/".self::$sigla;
		$this->fotoAmplia = $this->secaoNome."Amplia";
		$this->fotoMonta = $this->secaoNome."Secao";
		$this->verTodas = $this->secaoNome."Todas";

		$this->layout = str_replace("Sigla", self::$sigla, str_replace("coringa", $this->coringa, fread(fopen($layout, "r"), filesize($layout))));
	}

	public static function prepara($albumDiretorio){
		if(!is_dir($albumDiretorio)){
			return false;
		}
		
		$arquivos = scandir($albumDiretorio);
		natcasesort($arquivos);
		$imagens="";


		// // loop para pegar arquivos jpg ou jpeg

		foreach($arquivos as $arquivo){
			if(preg_match("/^.+\.jpe?g$/i",$arquivo)){
				list($largura,$altura)=getimagesize($albumDiretorio.$arquivo);
				$tamanhos[]="$largura,$altura";
			}
		}
		
		$fotoTotal = count($tamanhos);

		natcasesort($tamanhos);
		$tamanhosQuantidade = array_count_values($tamanhos);
		asort($tamanhosQuantidade);
		asort($tamanhos);
		$tamanhosNumerados = "";
		foreach($tamanhosQuantidade as $k=>$v){
			$numeros="";
			if($v != end($tamanhosQuantidade)){
				$numerosArray = array_keys($tamanhos,$k);
				asort($numerosArray);
				foreach($numerosArray as $vv){
					$n1=$vv+1;
					$numeros.="-$n1";
				}
				$n1=0;
				$n2=0;
				$numeros.="-0";
				$numerosArray = explode("-",$numeros);
				array_shift($numerosArray);
				$numeros="";
				foreach($numerosArray as $vv){
					if(is_numeric($vv)&&$n1!=0){
						if($n1+1==$vv&&$n2==0){
							$n2=$n1;
							$numeros.="({$n2}a";
						}elseif($n1+1==$vv){// verificar
						}elseif($n2!=0){
							$numeros.="$n1)";
							$n2=0;
						}else{
							$numeros.="-$n1";
						}
					}
					$n1=$vv;
				}
				$numeros.=",$k,";
			}else{
				$numeros.=$fotoTotal;
				$numeros.=",$k";
			}
			$tamanhosNumerados.=$numeros;
		}

		self::$total = $fotoTotal;
		self::$tamanhos = explode(",", $tamanhosNumerados);

		for($i=0; $i<count(self::$tamanhos); $i+=3){
			$imagemOriginalLargura = (int) self::$tamanhos[$i+1];
			$imagemOriginalAltura = (int) self::$tamanhos[$i+2];
			self::$tamanhos[$i+1] = ceil($imagemOriginalLargura * self::$alturaGrande / $imagemOriginalAltura);
			self::$tamanhos[$i+2] = self::$alturaGrande;
			if(self::$tamanhos[$i+1]>self::$larguraGrande){
				self::$tamanhos[$i+1] = self::$larguraGrande;
				self::$tamanhos[$i+2] = ceil($imagemOriginalAltura * self::$larguraGrande / $imagemOriginalLargura);
			}
		}

		return true;
	}

	public function monta($intNum, $intTipo=0){
		$intNum = (int) $intNum;
		if($intTipo==0){

			if(self::$mostragem > self::$total){
				self::$mostragem = self::$total;
			}
			
			$this->secaoTotal = (int) (self::$total / self::$mostragem);
			if(self::$total % self::$mostragem>0){
				$this->secaoTotal+=1;
			}

			($intNum=="")
				?$this->secaoNumero=1
				:($intNum<1||$intNum>$this->secaoTotal)
					?$this->secaoNumero=1
					:$this->secaoNumero=$intNum;

			$this->num = (int) ($this->secaoNumero*self::$mostragem - self::$mostragem+1);
			($this->secaoTotal>$this->secaoNumero)
				?$this->mini($this->num, $this->secaoNumero * self::$mostragem)
				:$this->mini($this->num, self::$total);
		}else{

			$this->amplia($intNum);

		}

	}

	public function fotoConfigura($intNum){
		if($intNum<1||$intNum>self::$total||$intNum==""){
			$intNum=1;
		}
		$achou = 0;
		$i=0;
		while($i<count(self::$tamanhos) && $achou!=1){
			if(strpos(self::$tamanhos[$i], "-")>-1 || strpos(self::$tamanhos[$i], "(") >-1){
				$arrT = str_replace("-", ",", self::$tamanhos[$i]);
				$arrT = str_replace("(", ",-", $arrT);
				$arrT = str_replace("a",",",$arrT);
				$arrT = str_replace(")","",$arrT);
				$arrT = explode(",", $arrT);
				$j=1;
				while($j<count($arrT) && $achou!=1){
					if($arrT[$j]>0){
						if($arrT[$j]==$intNum){
							self::$larguraGrande = self::$tamanhos[$i+1];
							self::$alturaGrande = self::$tamanhos[$i+2];
							$achou=1;
						}
					}else{
						if(($arrT[$j]*-1)<=$intNum&&$intNum<=$arrT[$j+1]){
							self::$larguraGrande = self::$tamanhos[$i+1];
							self::$alturaGrande = self::$tamanhos[$i+2];
							$achou=1;
						}
						$j++;
					}
					$j++;
				}
			}elseif(is_numeric(self::$tamanhos[$i])){
				if(self::$tamanhos[$i]>= $intNum){
					self::$larguraGrande = self::$tamanhos[$i+1];
					self::$alturaGrande = self::$tamanhos[$i+2];
				}
			}
			self::$larguraPequena = round(self::$larguraGrande * self::$alturaPequena / self::$alturaGrande);
			$this->diretorioGrandes = $this->diretorio . "n". $intNum.".jpg";
			$this->diretorioPequenas = $this->diretorio . "pn" . $intNum.".jpg";
			$this->titulo = (isset($this->titulos[$intNum]))
								?$this->titulos[$intNum]
								:$this->titulos[0];
			$this->autor = (isset($this->autor[$intNum]))
								?$this->autores[$intNum]
								:$this->autores[0];
			$i+=3;
		}
	}

	public function mini($intIni, $intFim){
		$intNum = $intIni;
		$fotosMiniaturas = Texto::strPegaValor("<miniaturas>","</miniaturas>",$this->layout);

		$layout = "<div id=\"".$this->container."\">\n" . Texto::strPegaValor("<miniaturas-secao>", "<miniaturas-secao-".$this->coringa.">", $fotosMiniaturas);

		for($i=1; $i<=$this->secaoTotal;$i++){
			if($i==$this->secaoNumero){
				$layout .= "\n".str_replace("<!-- texto-secao-numero-selecionada -->", Numeros::numCasas($i, $this->numCasas), Texto::strPegaValor("<miniaturas-secao-selecionada>", "</miniaturas-secao-selecionada>", $fotosMiniaturas));
			}else{
				$layout .= "\n".str_replace("<!-- texto-secao-numero-nao-selecionada -->", Numeros::numCasas($i, $this->numCasas), Texto::strPegaValor("<miniaturas-secao-nao-selecionada>", "</miniaturas-secao-nao-selecionada>", $fotosMiniaturas));
			}
		}

		$layout .= "\n".Texto::strPegaValor("</miniaturas-secao-nao-selecionada>", "</miniaturas-secao>", $fotosMiniaturas);
		$layout .= "\n".Texto::strPegaValor("</miniaturas-secao>", "<miniaturas-".$this->coringa.">", $fotosMiniaturas)."\n";

		while($intNum<=$intFim){
			if($intNum%self::$linha==1){
				$layout .= Texto::strPegaValor("<miniaturas-foto-linha-inicio>", "</miniaturas-foto-linha-inicio>", $fotosMiniaturas)."\n";
			}
			$layout .= Texto::strPegaValor("<miniaturas-foto>", "</miniaturas-foto>", $fotosMiniaturas)."\n";
			$this->fotoConfigura($intNum);
			$layout = str_replace("<!-- miniatura-foto-largura-pequena -->", self::$larguraPequena, $layout);
			$layout = str_replace("<!-- miniatura-foto-altura-pequena -->", self::$alturaPequena, $layout);
			$layout = str_replace("<!-- miniatura-foto-diretorio-pequena -->", $this->diretorioPequenas, $layout);
			$layout = str_replace("<!-- ampliada-foto-diretorio-grande -->", $this->diretorioGrandes, $layout);
			$layout = str_replace("<!-- miniatura-foto-numero -->", $intNum, $layout);

			if($intNum%self::$linha==0||$intNum==$intFim){
				$layout .= Texto::strPegaValor("<miniaturas-foto-linha-final>", "</miniaturas-foto-linha-final>", $fotosMiniaturas)."\n";
			}
			$intNum++;
		}
		
		$layout .= Texto::strPegaValor("</miniaturas-foto-linha-final>", "</miniaturas>", $fotosMiniaturas)."\n</div>";

		$layout = str_replace("<!-- miniatura-foto-secao-nome -->", $this->secaoNome, $layout);
		$layout = str_replace("<!-- miniatura-foto-amplia -->", $this->fotoAmplia, $layout);
		$layout = str_replace("<!-- texto-secao-nome -->", $this->secaoNome, $layout);
		$layout = str_replace("<!-- texto-secao-foto-monta -->", $this->fotoMonta, $layout);
		$layout = str_replace("\\/", "/", $layout);
		echo $layout;
	}

	public function amplia($intNum){
		$this->num = $intNum;
		$this->secaoNumero = ((int) (($intNum-1)/self::$mostragem+1));

		$fotosAmpliadas = "<ampliadas>".Texto::strPegaValor("<ampliadas>", "</ampliadas>", $this->layout);

		$layout = "<div id=\"".$this->container."\">\n" . Texto::strPegaValor("<ampliadas>", "<ampliadas-anterior>", $fotosAmpliadas);
		$layout .= ($intNum>1)
						?Texto::strPegaValor("<ampliadas-anterior>", "</ampliadas-anterior>", $fotosAmpliadas)
						:" &nbsp; ";
		$layout .= Texto::strPegaValor("</ampliadas-anterior>", "<ampliadas-proxima>", $fotosAmpliadas);
		$layout .= ($intNum<self::$total)
						?Texto::strPegaValor("<ampliadas-proxima>", "</ampliadas-proxima>", $fotosAmpliadas)
						:" &nbsp; ";
		$this->fotoConfigura($intNum);
		$layout .= Texto::strPegaValor("</ampliadas-proxima>", "</ampliadas>", $fotosAmpliadas)."\n</div>";

		$layout = str_replace("<!-- secao-numero -->", Numeros::numCasas($this->secaoNumero, $this->numCasas), $layout);
		$layout = str_replace("<!-- ampliada-foto-numero -->", $this->num, $layout);
		$layout = str_replace("<!-- ampliada-foto-secao-nome -->", $this->secaoNome, $layout);
		$layout = str_replace("<!-- ver-todas -->", $this->verTodas, $layout);
		$layout = str_replace("<!-- ampliada-foto-amplia -->", $this->fotoAmplia, $layout);
		$layout = str_replace("<!-- ampliada-foto-controle-numero-anterior -->", $intNum-1, $layout);
		$layout = str_replace("<!-- ampliada-foto-controle-numero-proximo -->", $intNum+1, $layout);
		$layout = str_replace("<!-- ampliada-foto-titulo -->", $this->titulo, $layout);
		$layout = str_replace("<!-- ampliada-foto-autor -->", $this->autor, $layout);
		$layout = str_replace("<!-- ampliada-foto-largura-grande -->", self::$larguraGrande, $layout);
		$layout = str_replace("<!-- ampliada-foto-altura-grande -->", self::$alturaGrande, $layout);
		$layout = str_replace("<!-- ampliada-foto-diretorio-grande -->", $this->diretorioGrandes, $layout);
		$layout = str_replace("\\/", "/", $layout);

		echo $layout;
	}
	
    public static function getVersao(){
        return self::$versao;
    }

	public static $mostragem = 20;
	public static $linha = 5;
	public static $sigla = "modelo";
	public static $larguraPequena = 90;
	public static $alturaPequena = 60;
	public static $larguraGrande = 600;
	public static $alturaGrande = 450;
	public static $total;
	public static $tamanhos;
	private $secao;
	private $container;
	private $imagens=null;
	private $secaoNumero=1;
	private $secaoTotal=1;
	private $secaoNome;
	private $num;
	private $coringa = "<!--djmibfotos-->";
	private $numCasas = 2;
	private $diretorio="img/fotos/";
	private $diretorioPequenas;
	private $diretorioGrandes;
	private $layout;
	private $fotoAmplia;
	private $fotoMonta;
	private $verTodas;
	private $titulo;
	private $titulos;
	private $autor;
	private $autores;
	private static $versao = "1.1";



}


?>