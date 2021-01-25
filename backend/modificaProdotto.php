<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';

use MyCandies\Controllers\ProductsManager;

$name = $_GET['name'];
$productManager = new ProductsManager();

// esegui e cattura l-output di prodotti_dashboard
ob_start();
include 'prodotti_dashboard.php';
$htmlPage = ob_get_clean();

$search = "<table";
$replace = "
            <form action=\"../backend/saveProductChanges.php\" method=\"post\">
            <fieldset>
            <table";
$htmlPage = str_replace($search, $replace, $htmlPage);
$search = "</table>";
$replace = "</table>
            </fieldset>
            </form>";
$htmlPage = str_replace($search, $replace, $htmlPage);

try {
    $product = $productManager->getProductByName($name);
} catch (Exception $e) {

}

$pattern = "/(<$name \/>)(.*?)(<\/tr>)/s";
$replace = "
            <td>
                $name
                <input type=\"hidden\" name=\"modifyName\" value=\"{$name}\" />
            </td>
            <td headers=\"price\" scope=\"row\">
                <input type=\"text\" value=\"{$product->getPrice()}\" id=\"modifyPrice\" name=\"modifyPrice\"/>
                <errPrice />
            </td>
            <td headers=\"quantity\" scope=\"row\">
                <input type=\"text\" value=\"{$product->getAvailability()}\" name=\"modifyAvailability\"/>
                <errAvailability />
            </td>
            <td headers=\"actions\" scope=\"row\">
                <input type=\"submit\" value=\"Salva\" id =\"modifyProduct\" name=\"modifyProduct\" />
                <input type=\"submit\" value=\"Elimina prodotto\" id =\"deleteProduct\" name=\"deleteProduct\" /> 
                <messages />
            </td>
        </tr>";
$htmlPage = preg_replace($pattern, $replace, $htmlPage, 1);
echo $htmlPage;
