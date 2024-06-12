<?php
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
// Constantes e Variaveis Globais
$contadorInicio = microtime(true);
define('REQUEST_MICROTIME', $contadorInicio);
define('DOCUMENTO_RAIZ', getcwd());
// header("Content-Type: text/html; charset=utf-8",true);
setlocale(LC_ALL,"pt_BR", "pt_BR.iso-8859-1", "Portuguese_Brazil.1252","ptb","portuguese-brazil","bra", "portuguese");
date_default_timezone_set("America/Sao_Paulo");
$strModeloPadrao = "index.php";

chdir(dirname(__DIR__));

// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();
