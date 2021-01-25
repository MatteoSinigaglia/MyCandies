<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths_index.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';
require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'Exceptions'.DS.'DBException.php';
require_once __DIR__.'/classes/MyCandies/Controllers/Authentication.php';

use MyCandies\Controllers\ProductsManager;
use DB\Exceptions\DBException;
use MyCandies\Controllers\Authentication;

$auth = new Authentication();

$htmlPage = file_get_contents(VIEW_PATH . DS . "prodotto.html");
//($auth->isLoggedIn()? '<a href="logout.php" id="loginButton" class="buttons">Logout</a>' : '<a href="formCliente.php" id="loginButton" class="buttons">Accedi</a>')
if  ($auth->isLoggedIn()) {
    $htmlPage = str_replace('<a_auth_state />', '<a href="logout.php" id="loginButton" class="fa fa-sign-out buttons"><span> Logout</span></a>', $htmlPage);
} else
$htmlPage = str_replace('<a_auth_state />', '<a href="./formCliente.php" id="loginButton" class="fa fa-sign-in buttons"><span> Accedi</span></a>', $htmlPage);
$productId = $_GET['id'];

$productsManager = new ProductsManager();
$prodotto = array();
try {
    $prodotto = $productsManager->getSingleProduct($productId);
    $prodotto = $prodotto + ['id' => $productId];
} catch (DBException $e) {
    ob_start();
    require_once 'listaProdotti.php';
    echo ob_get_clean();
    die();
}

$htmlPage = str_replace("<nomeprodotto />", $prodotto['name'], $htmlPage);
$htmlPage = str_replace("<immagineprodotto />",
    '<img src="'.$prodotto['image'].'" alt="Immagine del prodotto '.$prodotto['name'].'"/>', $htmlPage);
$htmlPage = str_replace("<descrizioneprodotto />", $prodotto['description'], $htmlPage);
$htmlPage = str_replace("<prezzo />", $prodotto['price'], $htmlPage);
$htmlPage = str_replace("<categoria />", $prodotto['category'], $htmlPage);
$htmlPage = str_replace("<principioattivo />", $prodotto['activeprinciple'], $htmlPage);
$htmlPage = str_replace("<percentualeprincipioattivo />", $prodotto['activeprinciplepercentage'] . ' %', $htmlPage);
$htmlPage = str_replace("<effetti />", $prodotto['effects'], $htmlPage);
$htmlPage = str_replace("<effetticollaterali />", $prodotto['sideeffects'], $htmlPage);

require_once MODEL_PATH.DS.'lib'.DS.'functions.php';
$htmlPage = str_replace('_product_data', http_build_query(array_slice_assoc($prodotto, ['id', 'name', 'price'])), $htmlPage);

echo $htmlPage;


