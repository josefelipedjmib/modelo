<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */

namespace Base\Credenciais;

use Base\Mib\URL;

class SocialHybridAuth {
    
    public static function getConfig($callbackURL){
		if(empty($callbackURL)){
			$host = URL::getInstance()->getHost();
			$scheme = URL::getInstance()->getScheme();
			if($host!="localhost")
				$scheme = "https";
			$callbackURL = $scheme."://".$host."/social";
		}
        return array(
			"base_url" => $callbackURL,
			"callback" => $callbackURL,
			"providers" => array(
				// openid providers
				"OpenID" => array(
					"enabled" => true,
					"openid_identifier" => "https://me.yahoo.com/"
				),
				"Yahoo" => array(
					"enabled" => true,
					"keys" => array("id" => "kkkkkkkkkkkkkkkkkk", "secret" => "sssssssssssssssssssss"),
					"scope" => 'sdpp-w',
					"redirect_uri" => "https://dominio/social/yahoo"
				),
				"Google" => array(
					"enabled" => true,
					"keys" => array("id" => "kkkkkkkkkkkkkkkkkkkkkkkkkkkkkkkk", "secret" => "ssssssssssssssssssss")
				),
				"Facebook" => array(
					"enabled" => true,
					"keys" => array("id" => "kkkkkkkkkkkkkkkkkkk", "secret" => "sssssssssssssssss"),
					"trustForwarded" => false,
					"scope" => "email",
				),
				"Twitter" => array(
					"enabled" => true,
					"keys" => array("key" => "kkkkkkkkkkkkkkkkkk", "secret" => "ssssssssssssssssss"),
					"includeEmail" => true
				),
				"WindowsLive" => array(
					"enabled" => true,
					"keys" => array("id" => "kkkkkkkkkkkkkkkkkkkkkkk", "secret" => "sssssssssssssssssssssss"),
					"response_type" => "code",
					"redirect_uri" => "https://dominio/social/windowslive"
				),
				"LinkedIn" => array(
					"enabled" => true,
					"keys" => array("key" => "kkkkkkkkkkkkkkkkkkkk", "secret" => "ssssssssssssssssssss")
				),
			),
			// If you want to enable logging, set 'debug_mode' to true.
			// You can also set it to
			// - "error" To log only error messages. Useful in production
			// - "info" To log info and error messages (ignore debug messages)
			"debug_mode" => false,
			// Path to file writable by the web server. Required if 'debug_mode' is not false
			"debug_file" => "",
        );
    }
    
    public static function getVersao(){
        return self::$versao;
    }   
    
    private $versao = "1.0";
}