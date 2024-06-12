<?php
/**
 * Dj Mib - José Felipe (http://www.djmib.net/)
 * PQT.com.br (www.pqt.com.br)
 * @link      http://www.djmib.net - para contatos com o Administrador
 * @copyright CopyLeft (c) 2004-2016 DJ Mib - José Felipe (http://www.djmib.net)
 * @license   https://creativecommons.org/licenses/by-sa/4.0/deed.pt_BR Atribuição-Compartilha Igual 4.0 Internacional 
 */
 
 return array(
     'doctrine' => array(
         'connection' => array(
             'orm_default' => array(
                 'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                 'params' => array(
                     //Banco MySQL
                     'host' => 'localhost',
                     'port' => '3306',
                     'user' => 'root',
                     'password' => "senha",
                     'dbname' => 'geral',
                     'driverOptions' => array(
                         PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
                     )
                 )
             )
         )
     )
 )
 
 
 ?>