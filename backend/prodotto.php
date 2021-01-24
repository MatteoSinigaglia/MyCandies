<?php

// TODO rimuovere import paths index
require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';
require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'Exceptions'.DS.'DBException.php';

use MyCandies\Controllers\ProductsManager;
use DB\Exceptions\DBException;

$productId = $_GET['id'];

$productsManager = new ProductsManager();
$prodotto = array();
try {
    $prodotto = $productsManager->getSingleProduct($productId);
    $prodotto = $prodotto + ['id' => $productId];
} catch (DBException $e) {
    // TODO pagina 404
    ob_start();
    require_once 'listaProdotti.php';
    echo ob_get_clean();
    die();
}

$htmlPage = file_get_contents(VIEW_PATH . DS . "prodotto.html");
$htmlPage = str_replace("<nomeprodotto />", $prodotto['name'], $htmlPage);
$htmlPage = str_replace("<immagineprodotto />",
    '<img src="'.$prodotto['image'].'" alt="Immagine del prodotto '.$prodotto['name'].'"/>', $htmlPage);
$htmlPage = str_replace("<descrizioneprodotto />", $prodotto['description'], $htmlPage);
$htmlPage = str_replace("<prezzo />", $prodotto['price'], $htmlPage);
$htmlPage = str_replace("<categoria />", $prodotto['category'], $htmlPage);
$htmlPage = str_replace("<principioattivo />", $prodotto['activeprinciple'], $htmlPage);
$htmlPage = str_replace("<percentualeprincipioattivo />", $prodotto['activeprinciplepercentage'] . '%', $htmlPage);
$htmlPage = str_replace("<effetti />", $prodotto['effects'], $htmlPage);
$htmlPage = str_replace("<effetticollaterali />", $prodotto['sideeffects'], $htmlPage);

require_once MODEL_PATH.DS.'lib'.DS.'functions.php';
$htmlPage = str_replace('_product_data', http_build_query(array_slice_assoc($prodotto, ['id', 'name', 'price'])), $htmlPage);

echo $htmlPage;


