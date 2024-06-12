<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */
 
 return array(
     'email' => array(
        'connection' => array(
            'params' => array(
                'host' => 'smtp',
                //'port' => '465',
                'port' => '587',
                'user' => 'usuario@email',
                'password' => "senha",
                'smtpauth' => true,
                //'smtpsecure' => 'ssl',
                'smtpsecure' => 'tls'
            )
        )
    )
 )
 
 
 ?>