<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';
require_once LIB_PATH . DS . 'productListDashboard.php';
require_once LIB_PATH . DS . 'functions.php';

use MyCandies\Controllers\ProductsManager;

[
    'DOM' => $htmlPage,
    'productList' => $productList,
] = initProductList();

$htmlPage = insertProductRow($productList, $htmlPage, true);

$name = $_GET['name'];
$productManager = new ProductsManager();

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

$pattern = "/(<modify_$name \/>)(.*?)(<\/tr>)/is";
$replace = "
         <tr>
            <td scope=\"row\" title=\"Nome\">
                {$name}
                <input type=\"hidden\" name=\"modifyName\" value=\"{$name}\" />
            </td>
            <td scope=\"row\" title=\"Prezzo\">
                <input type=\"text\" value=\"{$product->getPrice()}\" id=\"modifyPrice\" name=\"modifyPrice\"/>
                <error_price />
            </td>
            <td scope=\"row\" title=\"Quantità\">
                <input type=\"text\" value=\"{$product->getAvailability()}\" name=\"modifyAvailability\"/>
                <error_availability />
            </td>
            <td scope=\"row\" title=\"Azioni\">
                <input type=\"submit\" value=\"Salva\" id =\"modifyProduct\" name=\"modifyProduct\" />
                <input type=\"submit\" value=\"Elimina prodotto\" id =\"deleteProduct\" name=\"deleteProduct\" /> 
                <error_overall />
            </td>
        </tr>";

$htmlPage = preg_replace($pattern, $replace, $htmlPage, 1);
$htmlPage = noModifyTag($htmlPage);
$htmlPage = noFormErrors($htmlPage);
echo $htmlPage;
