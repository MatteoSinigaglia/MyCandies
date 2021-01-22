<?php

// TODO remove paths_
require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';

use MyCandies\Controllers\ProductsManager;

if(!isset($_GET['name']))
    die('404: non è possibile accedere al server in questo momento, riprova fra qualche minuto');

$name = $_GET['name'];

$productManager = new ProductsManager();

// esegui e cattura l-output di prodotti_dashboard
ob_start();
include 'prodotti_dashboard.php';
$htmlPage = ob_get_clean();

$search = "<table";
$replace = "
            <form action=\"#\" method=\"post\">
            <table";
$htmlPage = str_replace($search, $replace, $htmlPage);
$search = "</table>";
$replace = "</table>
            </form>";
$htmlPage = str_replace($search, $replace, $htmlPage);

try {
    $product = $productManager->getProductByName($name);
} catch (Exception $e) {

}

$pattern = "/$name(.*?)<\/tr>/s";
$replace = "$name
            <input type=\"hidden\" name=\"modifyName\" value=\"{$name}\">
            </td>
            <td headers=\"price\" scope=\"row\">
                <input type=\"text\" value=\"{$product->getPrice()}\" id=\"modifyPrice\" name=\"modifyPrice\"/>
            </td>
            <td headers=\"quantity\" scope=\"row\">
                <input type=\"text\" value=\"{$product->getAvailability()}\" name=\"modifyAvailability\"/>
            </td>
            <td headers=\"actions\" scope=\"row\">
                <input type=\"button\" value=\"Salva\" name=\"modifyProduct\">
            </td>
        </tr>
    ";
$htmlPage = preg_replace($pattern, $replace, $htmlPage, 1);
echo $htmlPage;
