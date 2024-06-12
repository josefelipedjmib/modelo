<?php

setlocale(LC_ALL,"pt_BR", "pt_BR.iso-8859-1", "Portuguese_Brazil.1252","ptb","portuguese-brazil","bra", "portuguese");
date_default_timezone_set("America/Sao_Paulo");
$strMSPadrao = "index.php";

$path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    if (__FILE__ !== $path && is_file($path)) {
        return false;
    }

echo phpinfo();

?>