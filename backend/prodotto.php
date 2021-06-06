<?php

require_once '..' . DIRECTORY_SEPARATOR . 'paths.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'ProductsManager.php';
require_once MYCANDIES_PATH.DS.'Controllers'.DS.'Authentication.php';
require_once MODEL_PATH.DS.'classes'.DS.'DB'.DS.'Exceptions'.DS.'DBException.php';

use MyCandies\Controllers\ProductsManager;
use MyCandies\Controllers\Authentication;
use DB\Exceptions\DBException;

$auth = new Authentication();

$productId = $_GET['id'];

$productsManager = new ProductsManager();
$prodotto = array();
try {
    $prodotto = $productsManager->getSingleProduct($productId);
    $prodotto = $prodotto + ['id' => $productId];
} catch (DBException | Exception $e) {
    http_response_code(404);
    include(MODEL_PATH . DS . 'error404.php');
    die();
}

$htmlPage = file_get_contents(VIEW_PATH . DS . "prodotto.html");

if  ($auth->isLoggedIn()) {
	$htmlPage = str_replace('<a_auth_state />', '<a href="logout.php" id="loginButton" class="fa fa-sign-out buttons"><span xml:lang="en"> Logout</span></a>', $htmlPage);
} else
	$htmlPage = str_replace('<a_auth_state />', '<a href="./formCliente.php" id="loginButton" class="fa fa-sign-in buttons"><span> Accedi</span></a>', $htmlPage);

//  Menu setup
$htmlPage = str_replace('<dashboard />', ($auth->isAdmin()
	?
	'<li><a href="../backend/inserisciProdotto.php">Gestione</a></li>'
	:
	''), $htmlPage);

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
$htmlPage = str_replace('_product_data', http_build_query(array_slice_assoc($prodotto, ['id'])), $htmlPage);

echo $htmlPage;
