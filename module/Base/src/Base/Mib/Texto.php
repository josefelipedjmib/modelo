<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */

namespace Base\Mib;

class Texto{

	public static function adiciona($strTxt){
		echo $strTxt;
	}

	public static function txtMostraOculta($intData,$strTxt){
		if(date("Ymd")<=$intData){
			return $strTxt;
		}
	}

	public static function geraLetra($intNum){
		$str = "qwertyuiopasdfghjklzxcvbnm_1475963082-MNBVCXZLKJHGFDSAPOIUYTREWQ";
		if ($intNum==0 || $intNum>strlen($str)) {
			return substr($str,rand(0,strlen($str)-1),1);
		} else {
			return substr($str,$intNum-1,1);
		}
	}

	public static function geraId($intComp=1, $intId=0){
		$intId = $intId % 64;
		$str = self::geraLetra($intId);
		if(!is_integer($intComp)){
			$intComp = 0;
		}
		for($i=2; $i<=$intComp;$i++){
			$str.= self::geraLetra(0);
		}
		return $str;
	}

	public static function adiStr($strTxt, $intComp){
		return adiCortes(substr($strTxt,0,$intComp));
	}

	public static function adiCortes($strTxt){
		if(get_magic_quotes_gpc()){
			$strTxt = stripslashes($strTxt);
		}
		$strTxt = str_replace("\\","\\\\",$strTxt);
		$strTxt = str_replace("/","\\/",$strTxt);
		$strTxt = str_replace("'","\\'",$strTxt);
		$strTxt = str_replace("\"","\\\"",$strTxt);
		return $strTxt;
	}

	public static function tirCortes($strTxt){
		$strTxt = str_replace("\\\\","\\",$strTxt);
		$strTxt = str_replace("\\/","/",$strTxt);
		$strTxt = str_replace("\\'","'",$strTxt);
		$strTxt = str_replace("\\\"","\"",$strTxt);
		return $strTxt;
	}

	public static function tirCarEsp($string) {
		return preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'), array('', '-', ''), str_replace(self::$acentosSIM, self::$acentosNAO, $string));
	}

	public static function tirQuebras($strTexto){
		return str_replace("\n", "	", $strTexto);
	}

	public static function preparaUrl($strEnd){
		if(strpos(strtolower($strEnd), "http://")!==0){
			return "http://".urlencode($strEnd);
		}else{
			return "http://".urlencode(str_replace("http://", "", strtolower($strEnd)));
		}
	}

	public static function substituiERC($strT,$strP){
		preg_match_all("/$strP/",$strT,$mtC,PREG_SET_ORDER);
		return $mtC;
	}

//http://stackoverflow.com/questions/1188129/replace-urls-in-text-with-html-links
	public static function textoEmLink($txt){
		return preg_replace_callback("&\\b".self::$rexProtocol.self::$rexDomain.self::$rexPort.self::$rexPath.self::$rexQuery.self::$rexFragment."(?=[?.!,;:\"]?(\s|$))&i",
    "self::callback", $txt);
	}

	public static function strPegaValor($ini, $fim, $txt){
		$ini = (!is_integer($ini))
					?strpos($txt, $ini)+strlen($ini)
					:$ini;
		$fim = (!is_integer($fim))
					?(strrpos($txt, $fim)===false)
						?strlen($txt)
						:strrpos($txt, $fim)-strlen($txt)
					:$fim;
		return substr($txt, $ini, $fim);
	}
	
    public static function getVersao(){
        return self::$versao;
    } 

	private static function callback($match){
		// Prepend http:// if no protocol specified
		$completeUrl = $match[1] ? $match[0] : "http://{$match[0]}";

		return '<a href="' . htmlspecialchars($completeUrl) . '">'
			. htmlspecialchars($match[2] . $match[3] . $match[4]) . '</a>';
	}

	public static $acentosSIM = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'ð','¥','µ'); 
	public static $acentosNAO = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'o','y','u');
	public static $rexProtocol = "(https?://)?";
	public static $rexDomain   = '((?:[-a-z0-9]{1,63}\.)+[-a-z0-9]{2,63}|(?:[0-9]{1,3}\.){3}[0-9]{1,3})';
	public static $rexPort     = '(:[0-9]{1,5})?';
	public static $rexPath     = '(/[!$-/0-9:;=@_\':;!a-z\x7f-\xff]*?)?';
	public static $rexQuery    = '(\?[!$-/0-9:;=@_\':;!a-z\x7f-\xff]+?)?';
	public static $rexFragment = '(#[!$-/0-9:;=@_\':;!a-z\x7f-\xff]+?)?';
    
    private static $versao = "1.0";
}

?>