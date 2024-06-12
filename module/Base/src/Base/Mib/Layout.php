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

class Layout{

	public static function menuMonta($arr=''){
		if(empty($arr)){
			// asort(self::$menu);
			$arr = self::$menu;
		}
		foreach($arr as $v){
			echo "\n<li";
			if(!empty($v["class"])){
				echo " class=\"".$v["class"]."\"";
			}
			echo ">\n";
			if(!empty($v["url"])){
				?>  <a href="<?= (URL::validaURLExterna($v['url']))? $v['url'] : self::$basePath.$v['url']; ?>"><?= $v['texto']; ?></a><?php
			}else{
				if(strpos($v["texto"],"Base".DIRECTORY_SEPARATOR."Mib")!==false){
				echo str_replace("strPaginaDir/", self::$basePath, file_get_contents($v['texto']));
				}else{
				?>  <?= $v['texto']; ?><?php
				}
			}
			if($v['filha']!==false){
				echo "\n    <ul";
				if(!empty($v["filhaclass"])){
					echo " class=\"".$v["filhaclass"]."\"";
				}
				echo ">";
				self::menuMonta($v["filha"]);
				echo "\n    </ul>\n";
			}
			echo "</li>";
		}
	}

	public static function menuSocial($root){
		?>
		
			<h3>Entre usando seu provedor</h3>
			<ul class="listahorizontal">
				<li><a href="<?= $root; ?>facebook/" class="pure-button pure-button-primary"><i class="fa fa-facebook"></i> Facebook</a></li>
				<li><a href="<?= $root; ?>google/" class="pure-button pure-button-primary"><i class="fa fa-google"></i> Google</a></li>
				<li><a href="<?= $root; ?>twitter/" class="pure-button pure-button-primary"><i class="fa fa-twitter"></i> Twitter</a></li>
				<!-- <li><a href="<?= $root; ?>linkedin/" class="pure-button pure-button-primary"><i class="fa fa-linkedin"></i> Linkedin</a></li>
				<li><a href="<?= $root; ?>yahoo/" class="pure-button pure-button-primary"><i class="fa fa-yahoo"></i> Yahoo!</a></li>
				<li><a href="<?= $root; ?>windowslive/" class="pure-button pure-button-primary"><i class="fa fa-windows"></i> Microsoft</a></li> -->
			</ul>
		<?php
	}

	public static function breadCrumb(){
		$urlAtual = URL::getInstance()->getURI(1);
		$urls = explode("/",$urlAtual);
		$links = [];
		foreach($urls as $k=>$url){
			if(!empty($url)){
				$urlPosicaoFinal = strpos($urlAtual, $url)+strlen($url);
				$urlLink = substr($urlAtual,0,$urlPosicaoFinal);
				if(substr($urlAtual,$urlPosicaoFinal,1)=="/")
					$urlLink.="/";
				if($urlLink==$urlAtual||$url=="pagina")
					$urlLink = "#";
				$links[] = [$url,$urlLink];
			}
		}
		?>
		<nav>
			<ul class="breadcrumb flat semnumero">
				<?php
					foreach($links as $links){
							echo "<li><a href=\"{$links[1]}\">".ucfirst($links[0])."</a></li>\n";
					}
				?>
			</ul>
		</nav>
		<?php
	}

    public static function getVersao(){
        return self::$versao;
    }

	public static $basePath = "";
	public static $menu = [
		[
			'url' => "./",
			'texto' => 'Início',
			'class' => '',
			'filha' => false,
			'filhaclass' => ''
		],
	];

    private static $versao = "1.0";
}

?>