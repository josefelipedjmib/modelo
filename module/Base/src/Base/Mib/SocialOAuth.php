<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */

namespace Base\Mib;

use Hybridauth\Hybridauth;
use \Exception;

class SocialOAuth{

    public static function conecta($rede, $urlCallback='', $config=''){
        try{
            if(empty($config)){
                $config = \Base\Credenciais\SocialHybridAuth::getConfig($urlCallback);
            }

            self::$autenticacao = new Hybridauth($config);
            $provedores = self::$autenticacao->getProviders();
            $provedorIndice = array_search(strtolower($rede), array_map('strtolower', $provedores));

            if($provedorIndice!==false){
                self::$provedor = self::$autenticacao->authenticate($provedores[$provedorIndice]);
            }
            if(is_object(self::$provedor)){
                $usuario = self::$provedor->getUserProfile();

                $email = trim($usuario->emailVerified);
                if(empty($email)){
                    $email = trim($usuario->email);
                    if(empty($email))
                        self::$erro = true;
                }
                if(!self::$erro){
                    self::$email = $email;
                    $nome = trim($usuario->displayName);
                    if(empty($nome)){
                        $nome = trim($usuario->firstName . " " . $usuario->lastName);
                        
                        if(empty($nome)){
                            $nome = explode("@", $email);
                            $nome = trim($nome[0]);
                        }
                    }
                    self::$nome = $nome;

                    self::$id = $usuario->identifier;
                    self::$foto = $usuario->photoURL;
                    self::$perfil = $usuario->profileURL;
                    
                    self::$provedor->disconnect();
                }

            }else{
                self::$erro = true;
            }
        }
        catch(Exception $e){
                self::$erro = true;
        }
        return !self::$erro;
    }

    public static function getFaceAlbums(){
        echo "<pre>";
        var_dump(self::$provedor->apiRequest('me/albums?limit=1000'));
        exit;
    }

    public static function getErro(){
        return self::$erro;
    }

    public static function getAutenticacao(){
        return self::$autenticacao;
    }

    public static function getProvedor(){
        return self::$provedor;
    }

    public static function getEmail(){
        return self::$email;
    }

    public static function getNome(){
        return self::$nome;
    }

    public static function getId(){
        return self::$id;
    }

    public static function getFoto(){
        return self::$foto;
    }

    public static function getPerfil(){
        return self::$perfil;
    }

    public static function getVersao(){
        return self::$versao;
    }

    private static $erro = false;
    private static $autenticacao;
    private static $provedor;
    private static $email;
    private static $nome;
    private static $id;
    private static $foto;
    private static $perfil;
    private static $versao = "1.0";

}

?>